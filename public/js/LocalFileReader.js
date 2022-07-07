function LocalFileReader(file){
    this.file = file;
}

LocalFileReader.prototype.readFile = async function() {

    return new Promise(function(resolve, reject){
        const fr = new FileReader();
        fr.onload = function() { resolve(fr.result) };
        fr.onerror = function(e) { console.log(e); alert(e); reject("Something went wrong loading file"); };
        fr.readAsText(this.file);
    }.bind(this));
}
