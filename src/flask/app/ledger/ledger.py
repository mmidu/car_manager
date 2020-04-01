from hashlib import sha256
import json
import time
import re
from app.database.queries import *
from app.database.migrations import transactions
from elasticsearch import Elasticsearch

es = Elasticsearch('http://cm_elastic')

milli_time = lambda: int(round(time.time() * 1000))

class Ledger:
	def __init__(self, name='ledger',difficulty=2):
		self.difficulty = difficulty
		self.name = name

	@property
	def last_block(self):
		return self.chain[0]

	@property
	def chain(self):
		data = es.search(index=self.name, body=sorted_chain)['hits']['hits']
		return list(map(lambda x: x['_source'], data))
		
	def init(self):
		self.init_elastic()
		self.create_genesis_block()

	def init_elastic(self):
		es.indices.delete(index=self.name, ignore=[400, 404])
		es.indices.create(index=self.name)
		es.indices.put_mapping(index=self.name, body=transactions)

	def create_genesis_block(self):
		if not self.chain:
			genesis_block = {
				"index": 0,
				"transaction": [],
				"timestamp": milli_time(),
				"previous_hash": "0",
			}
			genesis_block['hash'] = self.compute_hash(genesis_block)
			self.add_to_chain(genesis_block)

	def compute_hash(self, block):
		block_string = json.dumps(block, sort_keys=True)
		return sha256(block_string.encode()).hexdigest()

	def add_block(self, block, proof):
		previous_hash = self.last_block['hash']

		if previous_hash != block['previous_hash']:
			return False

		if not self.is_valid_proof(block, proof):
			return False

		block['hash'] = proof
		self.add_to_chain(block)
		return True

	def proof_of_work(self, block):
		block['nonce'] = 0

		computed_hash = self.compute_hash(block)
		while not computed_hash.startswith('0' * self.difficulty):
			block['nonce'] += 1
			computed_hash = self.compute_hash(block)

		return computed_hash

	def add_to_chain(self, block):
		es.index(index=self.name, doc_type='_create', id=block['hash'], body=block, refresh=True)

	def is_valid_proof(self, block, block_hash):
		return block['index'] != 0 and (block_hash.startswith('0' * self.difficulty) and
				block_hash == self.compute_hash(block)) or block_hash == self.compute_hash(block)

	def add_direct(self, transaction):
		if not self.check_chain_validity():
			return 'error: tampered chain'

		last_block = self.last_block

		new_block = {
			"index": last_block['index'] + 1,
			"transaction": transaction,
			"timestamp": milli_time(),
			"previous_hash": last_block['hash']
		}

		proof = self.proof_of_work(new_block)

		return self.add_block(new_block, proof) and 'ok' or 'ko'

	def check_chain_validity(self):
		result = True
		previous_hash = "0"

		reverse_chain = self.chain
		reverse_chain.reverse()
		 
		for block in reverse_chain:

			block_hash = block['hash']
			del block["hash"]

			if not self.is_valid_proof(block, block_hash) or \
					previous_hash != block['previous_hash']:
				result = False
				break

			block['hash'], previous_hash = block_hash, block_hash

		return result
	
	def transaction_by_plate(self, plate):
		query = re.sub('__plate__', plate, json.dumps(last_by_plate))
		data = es.search(index=self.name, body=query)['hits']['hits']
		lst = list(map(lambda x: x['_source'], data))
		if not lst:
			return None
		return lst[0]

	def transaction_by_plate_owner(self, plate, fiscal_code):
		query = re.sub('__plate__', plate, json.dumps(last_by_plate_owner))
		query = re.sub('__fiscal_code__', fiscal_code, query)
		data = es.search(index=self.name, body=query)['hits']['hits']
		lst = list(map(lambda x: x['_source'], data))
		if not lst:
			return None
		return lst[0]
