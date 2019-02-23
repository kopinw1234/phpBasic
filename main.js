var file = document.getElementById("file");
var csvFile = document.getElementById("csvFile");
file.onchange = function(){
    if(file.files.length > 0)
    {

      document.getElementById('filename').innerHTML = file.files[0].name;

    }
};
csvFile.onchange = function(){
    if(csvFile.files.length > 0)
    {

      document.getElementById('csvfilename').innerHTML = csvFile.files[0].name;

    }
};