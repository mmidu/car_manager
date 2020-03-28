import json

transactions = json.dumps({
	"properties": {
		"index": {"type": "integer"},
		"transaction": {
			"type": "nested",
			"properties": {
				"car": {
					"type": "nested",
					"properties": {
						"license_plate": {"type": "text"},
						"model": {"type": "text"},
						"manufacturer": {"type": "text"},
						"year": {"type": "date"},
						"engine_displacement": {"type": "integer"},
						"horse_power": {"type": "integer"}
					}
				},
				"owner": {
					"type": "nested",
					"properties": {
						"fiscal_code": {"type": "text"},
						"first_name": {"type": "text"},
						"last_name": {"type": "text"},
						"birth_date": {"type": "date"},
						"address": {"type": "text"}
					}
				}
			}			
		},
		"timestamp": {"type": "date"},
		"previous_hash": {"type": "text"},
		"nonce": {"type": "integer"},
		"hash": {"type": "text"}
	}
})

unconfirmed_transactions = json.dumps({
	"properties": {
		"car": {
			"type": "nested",
			"properties": {
				"license_plate": {"type": "text"},
				"model": {"type": "text"},
				"manufacturer": {"type": "text"},
				"year": {"type": "date"},
				"engine_displacement": {"type": "integer"},
				"horse_power": {"type": "integer"}
			}
		},
		"owner": {
			"type": "nested",
			"properties": {
				"fiscal_code": {"type": "text"},
				"first_name": {"type": "text"},
				"last_name": {"type": "text"},
				"birth_date": {"type": "date"},
				"address": {"type": "text"}
			}
		},
		"timestamp": {"type": "date"},
		"uuid": {"type": "text"}
	}
})

users = json.dumps({
	"properties": {
		"fiscal_code": {"type": "text"},
		"password": {"type": "text"},
		"data": {
			"type": "nested",
			"properties": {
				"first_name": {"type": "text"},
				"last_name": {"type": "text"},
				"birth_date": {"type": "date"},
				"address": {"type": "text"}
			}
		}
	}
})