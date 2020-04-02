from hashlib import sha256
import json
import time
import redis

r = redis.Redis(host='cm_redis', port=6379, db=0)

milli_time = lambda: int(round(time.time() * 1000))

class Ledger:
	def __init__(self, name='ledger',difficulty=2):
		self.difficulty = difficulty
		self.name = name

	@property
	def last_block(self):
		return self.chain[-1]

	@property
	def chain(self):
		data = r.zrange(self.name, 0, -1)
		return [json.loads(elem.decode()) for elem in data]
		
	def init(self):
		r.flushdb()
		self.create_genesis_block()

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
		r.zadd(self.name, {json.dumps(block): block["index"]})

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
		 
		for block in self.chain:

			block_hash = block['hash']
			del block["hash"]

			if not self.is_valid_proof(block, block_hash) or \
					previous_hash != block['previous_hash']:
				result = False
				break

			block['hash'], previous_hash = block_hash, block_hash

		return result
	
	# def transaction_by_plate(self, plate):
	# 	query = re.sub('__plate__', plate, json.dumps(last_by_plate))
	# 	data = es.search(index=self.name, body=query)['hits']['hits']
	# 	lst = list(map(lambda x: x['_source'], data))
	# 	if not lst:
	# 		return None
	# 	return lst[0]

	# def transaction_by_plate_owner(self, plate, fiscal_code):
	# 	query = re.sub('__plate__', plate, json.dumps(last_by_plate_owner))
	# 	query = re.sub('__fiscal_code__', fiscal_code, query)
	# 	data = es.search(index=self.name, body=query)['hits']['hits']
	# 	lst = list(map(lambda x: x['_source'], data))
	# 	if not lst:
	# 		return None
	# 	return lst[0]
