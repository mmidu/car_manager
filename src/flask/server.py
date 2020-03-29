import uuid
from flask import Flask, request, jsonify
from ledger import Blockchain, milli_time

app = Flask(__name__)

blockchain = Blockchain(name='transactions')


@app.route('/', methods=['GET'])
def home():
	return "v1.3", 201

@app.route('/init', methods=['PUT'])
def init_ledger():
	blockchain.init()
	return 'ok', 201

@app.route('/new_transaction', methods=['POST'])
def new_transaction():
	request_data = request.get_json()

	required_fields = {
		"car": [
			"license_plate",
			"manufacturer",
			"year",
			"engine_displacement",
			"horse_power",
		],
		"owner": [
			"fiscal_code",
			"first_name",
			"last_name",
			"birth_date",
			"address",
		]
	}
	
	for group in required_fields:
		if request_data.get(group) is None:
			return "Invalid request data. {} field group required.".format(group), 404
		for field in required_fields[group]:
			if request_data.get(group).get(field) is None:
				return "Invalid request data. {}.{} field required.".format(group, field), 404


	request_data["timestamp"] = milli_time()
	request_data["uuid"] = str(uuid.uuid1())

	blockchain.add_new_transaction(request_data)

	return "Success", 201


@app.route('/chain', methods=['GET'])
def get_chain():
	chain_data = blockchain.chain
	return jsonify({
		"_size": len(chain_data),
		"chain": chain_data
	})

@app.route('/mine', methods=['GET'])
def mine_unconfirmed_transactions():
	result = blockchain.mine()
	if not result:
		return "No transactions to mine."
	return "Transaction #{} is mined.".format(result)


@app.route('/unconfirmed_transactions')
def get_unconfirmed_transactions():
	ut = blockchain.unconfirmed_transactions
	return jsonify({
		"_size": len(ut),
		"unconfirmed_transactions": ut
	})
