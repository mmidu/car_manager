import json

sorted_chain = {
	"sort" : {
		"index": {"order": "desc"}
	}		    
}

last_by_plate = {
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

last_by_plate_owner = {
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
				},
				{
					"nested" : {
						"path" : "transaction.owner",
						"query" : {
							"bool" : {
								"must" : [
									{ "match" : {"transaction.owner.fiscal_code" : "__fiscal_code__"} }
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