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

def validate_contract(input):
	for group in required_fields:
		if input.get(group) is None:
			return False, "Invalid request data. {} field group required.".format(group)
		for field in required_fields[group]:
			if input.get(group).get(field) is None:
				return False, "Invalid request data. {}.{} field required.".format(group, field)
	return True, "Validation ok"