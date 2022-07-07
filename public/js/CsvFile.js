// a very basic utility for representing a csv file as an array of rows
function CsvFile () {
    this.csv = null;
    this.rows = [];
}

CsvFile.prototype.loadCsv = function (csv) {
    this.csv = csv;
}

CsvFile.prototype.parseCsv = function() {
    if(this.csv){

        const rows = this.csv.split(/\r*\n|\r/);

        // Really simple solution to a rather complex problem - should allow us to get by for now
        // Might be better to send the data to the server for validation and then show the results, since PHP is pretty good with handling CSV
        for(row of rows){

            if(!row.length)
                continue;

            // replace in commas that are inside quotes with an empty space so we don't break the parsing
            // pretty simple / naive solution but quick and easy and should do the trick
            let inQuotes = false;
            row = row.split('').map(function(ch){
                if(ch == '"' && !inQuotes)
                    inQuotes = true
                else if(ch == '"' && inQuotes)
                    inQuotes = false;
                else if(ch == ',' && inQuotes)
                    ch = ' ';
                return ch;
            }).join('');

            this.rows.push([...row.split(',')]);
        }

    } else {
        throw new Error('No csv to parse');
    }
}


CsvFile.prototype.validateCsv = function(csvValidator) {
    return csvValidator.validate(this.rows);
}
