from hashlib import sha256
import json
import time
import uuid
from flask import Flask, request
import requests
from queries import sorted_chain, sorted_unconfirmed
from migrations import transactions, unconfirmed_transactions
from elasticsearch import Elasticsearch

es = Elasticsearch('http://cm_elastic')

app = Flask(__name__)

milli_time = lambda: int(round(time.time() * 1000))

class Block: 
	def __init__(self, index, transaction, timestamp, previous_hash, nonce=0):
		self.index = index
		self.transaction = transaction
		self.timestamp = timestamp
		self.previous_hash = previous_hash
		self.nonce = nonce

	def compute_hash(self):
		block_string = json.dumps(self.__dict__, sort_keys=True)
		return sha256(block_string.encode()).hexdigest()

	@property
	def json(self):
		return self.__dict__


class Blockchain:
	def __init__(self, name='ledger',difficulty=2, auto_mine=True):
		self.difficulty = difficulty
		self.auto_mine = auto_mine
		self.name = name
		self.init_elastic()
		self.create_genesis_block()

	def init_elastic(self):
		es.indices.delete(index='unconfirmed_{}'.format(self.name), ignore=[400, 404])
		es.indices.create(index='unconfirmed_{}'.format(self.name))
		es.indices.put_mapping(index='unconfirmed_{}'.format(self.name), body=unconfirmed_transactions)
		es.indices.delete(index=self.name, ignore=[400, 404])
		es.indices.create(index=self.name)
		es.indices.put_mapping(index=self.name, body=transactions)

	def create_genesis_block(self):
		if not self.chain:
			genesis_block = Block(0, [], 0, "0")
			genesis_block.hash = genesis_block.compute_hash()
			self.add_to_chain(genesis_block)

	@property
	def last_block(self):
		lb = self.chain[0]
		block = Block(lb['index'], lb['transaction'], lb['timestamp'], lb['previous_hash'], lb['nonce'])
		block.hash = lb['hash']
		return block

	@property
	def unconfirmed_transactions(self):
		data = es.search(index='unconfirmed_{}'.format(self.name), body=sorted_unconfirmed)['hits']['hits']
		return list(map(lambda x: x['_source'], data))

	@property
	def chain(self):
		data = es.search(index=self.name, body=sorted_chain)['hits']['hits']
		return list(map(lambda x: x['_source'], data))

	def add_block(self, block, proof):
		previous_hash = self.last_block.hash

		if previous_hash != block.previous_hash:
			return False

		if not self.is_valid_proof(block, proof):
			return False

		block.hash = proof
		self.add_to_chain(block)
		return True

	def proof_of_work(self, block):
		block.nonce = 0

		computed_hash = block.compute_hash()
		while not computed_hash.startswith('0' * self.difficulty):
			block.nonce += 1
			computed_hash = block.compute_hash()

		return computed_hash

	def add_new_transaction(self, transaction):
		es.index(index='unconfirmed_{}'.format(self.name), doc_type='_create', id=transaction["uuid"], body=json.dumps(transaction))

	def add_to_chain(self, block):
		es.index(index=self.name, doc_type='_create', id=block.hash, body=block.json)

	def is_valid_proof(self, block, block_hash):
		return (block_hash.startswith('0' * self.difficulty) and
				block_hash == block.compute_hash())

	def check_chain_validity(self, chain):
		result = True
		previous_hash = "0"

		for block in chain:
			block_hash = block.hash
			delattr(block, "hash")

			if not self.is_valid_proof(block, block_hash) or \
					previous_hash != block.previous_hash:
				result = False
				break

			block.hash, previous_hash = block_hash, block_hash

		return result

	def mine(self):
		if not self.unconfirmed_transactions:
			return False

		last_block = self.last_block

		new_block = Block(index=last_block.index + 1,
						  transaction=self.unconfirmed_transactions[0],
						  timestamp=milli_time(),
						  previous_hash=last_block.hash)

		es.delete(index='unconfirmed_{}'.format(self.name), id=self.unconfirmed_transactions[0]['uuid'])
		proof = self.proof_of_work(new_block)

		self.add_block(new_block, proof)

		return last_block.index + 1


blockchain = Blockchain(auto_mine=True, name='transactions')


def _finditem(obj, key):
    if key in obj: return obj[key]
    for k, v in obj.items():
        if isinstance(v,dict):
            item = _finditem(v, key)
            if item is not None:
                return item


@app.route('/', methods=['GET'])
def home():
	return "api 1.0", 201

@app.route('/new_transaction', methods=['POST'])
def new_transaction():
	tx_data = request.get_json()

	required_fields = [
		"license_plate",
		"manufacturer",
		"year",
		"engine_displacement",
		"horse_power",
		"fiscal_code",
		"first_name",
		"last_name",
		"birth_date",
		"address"
	]

	for field in required_fields:
		if not _finditem(tx_data, field):
			return "Invalid transaction data", 404

	tx_data["timestamp"] = milli_time()
	tx_data["uuid"] = str(uuid.uuid1())

	blockchain.add_new_transaction(tx_data)

	return "Success", 201


@app.route('/chain', methods=['GET'])
def get_chain():
	chain_data = blockchain.chain
	return json.dumps({
		"length": len(chain_data),
		"chain": chain_data
	})

@app.route('/mine', methods=['GET'])
def mine_unconfirmed_transactions():
	result = blockchain.mine()
	return str(result)
	if not result:
		return "No transactions to mine."
	return "Block #{} is mined.".format(result)


@app.route('/pending_tx')
def get_pending_tx():
	return json.dumps(blockchain.unconfirmed_transactions)
