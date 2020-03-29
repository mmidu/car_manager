import json

sorted_chain = {
	"sort" : {
		"index": {"order": "desc"}
	}		    
}

plate_last_transaction = {
	"query": {
		"bool": {
			"must": [
				{
					"nested" : {
						"path" : "transaction.car",
						"query" : {
							"bool" : {
								"must" : [
									{ "match" : {"transaction.car.license_plate" : "__plate__"} }
								]
							}
						}
					}
				}
			]
		}
	},
	"sort" : {
		"index": {"order": "desc"}
	},
	"size" : 1			    
}