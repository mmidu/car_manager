from hashlib import sha256
import json
import time
import redis
import uuid
from flask import Flask, request
import requests


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


class Blockchain:
	def __init__(self, difficulty=2, auto_mine=True):
		self.difficulty = difficulty
		self.auto_mine = auto_mine
		self.create_genesis_block()
		#r.set('chain', '[]') # EMPTIES THE BLOCKCHAIN AT STARTUP

	def create_genesis_block(self):
		if not self.chain:
			genesis_block = Block(0, [], 0, "0")
			genesis_block.hash = genesis_block.compute_hash()
			self.add_to_chain(genesis_block)

	@property
	def last_block(self):
		lb = self.chain[-1]
		block = Block(lb['index'], lb['transaction'], lb['timestamp'], lb['previous_hash'], lb['nonce'])
		block.hash = lb['hash']
		return block

	@property
	def unconfirmed_transactions(self):
		if r.get('unconfirmed_transactions') is None:
			unconfirmed_transactions = '[]'
		else:
			unconfirmed_transactions = r.get('unconfirmed_transactions')
		return json.loads(unconfirmed_transactions)

	@property
	def chain(self):
		if r.get('chain') is None:
			chain = '[]'
		else:
			chain = r.get('chain')
		return json.loads(chain)

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
		unconfirmed_transactions = self.unconfirmed_transactions
		unconfirmed_transactions.append(transaction)
		r.set('unconfirmed_transactions', json.dumps(unconfirmed_transactions))

	def add_to_chain(self, block):
		chain = self.chain
		chain.append(block.__dict__)
		r.set('chain', json.dumps(chain))

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

		unconfirmed_transactions = self.unconfirmed_transactions

		new_block = Block(index=last_block.index + 1,
						  transaction=unconfirmed_transactions.pop(0),
						  timestamp=time.time(),
						  previous_hash=last_block.hash)

		r.set('unconfirmed_transactions', json.dumps(unconfirmed_transactions))

		proof = self.proof_of_work(new_block)

		self.add_block(new_block, proof)

		return last_block.index + 1
	


app = Flask(__name__)

r = redis.Redis(host='bc_redis', port=6379, db=0)

blockchain = Blockchain(auto_mine=True)

@app.route('/', methods=['GET'])
def home():
	return "api 1.0", 201

@app.route('/get', methods=['GET'])
def get_document():
	key = request.args.get('key', None)
	return r.get(key), 201


@app.route('/new_transaction', methods=['POST'])
def new_transaction():
	tx_data = request.get_json()

	required_fields = ["old_owner", "new_owner", "car"]

	for field in required_fields:
		if not tx_data.get(field):
			return "Invalid transaction data", 404

	tx_data["timestamp"] = time.time()
	tx_data["uuid"] = uuid.uuid1()

	blockchain.add_new_transaction(tx_data)

	return "Success", 201


@app.route('/chain', methods=['GET'])
def get_chain():
	chain_data = []
	for block in blockchain.chain:
		chain_data.append(block)
	return json.dumps({
		"length": len(chain_data),
		"chain": chain_data
	})


@app.route('/mine', methods=['GET'])
def mine_unconfirmed_transactions():
	result = blockchain.mine()
	if not result:
		return "No transactions to mine."
	return "Block #{} is mined.".format(result)


@app.route('/pending_tx')
def get_pending_tx():
	return json.dumps(blockchain.unconfirmed_transactions)


@app.route('/new_transaction_dev', methods=['POST'])
def new_transaction_dev():

	tx_data = request.get_json()

	for i in range(10):
		required_fields = ["old_owner", "new_owner", "car"]

		for field in required_fields:
			if not tx_data.get(field):
				return "Invalid transaction data", 404

		tx_data["timestamp"] = time.time()

		dt = {
			'old_owner': tx_data['old_owner'] + '_' + str(i),	
			'new_owner': tx_data['new_owner'] + '_' + str(i),
			'car': tx_data['car'] + '_' + str(i),
			'timestamp': tx_data['timestamp'],
		}
		blockchain.add_new_transaction(dt)
		if blockchain.auto_mine:
			blockchain.mine()

	return "Success", 201