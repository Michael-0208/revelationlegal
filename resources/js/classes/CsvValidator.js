function CsvValidator(rules) {
    this.rules = rules;
}

CsvValidator.prototype.validate = function(csv) {

    let errors = [];
    let row = null;
    let rule = null;
    let regex = null;
    let val = null;

    for(let i = 0; i < csv.length; i++){
        row = csv[i];

        if(row.length != this.rules.length){
            errors.push({ row: i, col: 0,  error: "Incorrect column count", msg: `Found wrong number of columns on row ${i}. Expected columns ${this.rules.length}. Actual columns ${row.length}.` });
        }

        for(let j = 0; j < this.rules.length; j++){
            rule = this.rules[j];

            switch(rule.regex.toUpperCase()) {
                case "INTEGER":
                    val = parseInt(row[j]);
                    if(isNaN(val))
                        errors.push({ row: i, col: j,  error: "Mismtached Data Type", msg: `Value at row ${i} : column ${j} did not pass validation ${rule.name}. Integer is expected.` });
                    break;
                case "DECIMAL":
                    val = parseFloat(row[j]);
                    if(isNaN(val))
                        errors.push({ row: i, col: j,  error: "Mismtached Data Type", msg: `Value at row ${i} : column ${j} did not pass validation ${rule.name}. Decimal is expected.` });
                    break;
                case "EMAIL":
                    val = (new RegExp("^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$")).test(row[j]);
                    if(!val)
                        errors.push({ row: i, col: j,  error: "Mismtached Data Type", msg: `Value at row ${i} : column ${j} did not pass validation ${rule.name}. Email Address is expected.` });
                    break;
                case "STRING":
                    if(!row[j])
                        errors.push({ row: i, col: j,  error: "Mismtached Data Type", msg: `Value at row ${i} : column ${j} did not pass validation rule ${rule.name}. String value is expected.` });
                    break;
            }

        }
    }

    return errors;

}
