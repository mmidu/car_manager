import uuid
from flask import request, jsonify, render_template
from app.ledger.ledger import Ledger
from app.ledger.contract import validate_contract
from app import app

ledger = Ledger(name='transactions')

@app.route('/api', methods=['GET'])
def home():
	return "v1.0", 201


@app.route('/init', methods=['PUT'])
def init_ledger():
	ledger.init()
	return 'ok', 201


@app.route('/add_transaction', methods=['POST'])
def add_transaction():
	request_data = request.get_json()

	status, message = validate_contract(request_data)
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

@app.route('/check_chain', methods=['GET'])
def check_chain():
	valid = ledger.check_chain_validity()
	return jsonify({
		"valid": valid
	})

@app.route('/car/<plate>', methods=['GET'])
def transaction_by_plate(plate):

	elem = next((elem["transaction"] for elem in reversed(ledger.chain) if elem["transaction"] and elem["transaction"]["car"]["license_plate"] == plate), -1)

	if elem == -1:
		return 'No cars with the selected plate.', 404
	return elem, 201
