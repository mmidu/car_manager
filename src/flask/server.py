import uuid
from flask import Flask, request, jsonify
from ledger import Ledger, milli_time
from contract import validate

app = Flask(__name__)

ledger = Ledger(name='transactions')


@app.route('/', methods=['GET'])
def home():
	return "v1.0", 201


@app.route('/init', methods=['PUT'])
def init_ledger():
	ledger.init()
	return 'ok', 201


@app.route('/add_transaction', methods=['POST'])
def add_transaction():
	request_data = request.get_json()

	status, message = validate(request_data)
	if not status:
		return message, 404

	return ledger.add_direct(request_data)


@app.route('/chain', methods=['GET'])
def get_chain():
	chain_data = ledger.chain
	return jsonify({
		"_size": len(chain_data),
		"chain": chain_data
	})
