function makeFile(myPath) {

    var arrayPath = myPath.split('/');

    var data = {
        filename: arrayPath[arrayPath.length - 1],
        path: myPath
    }

    fetch('src/SERVER/files.php?makeFile', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.text()).then(res => console.log(res));
}


makeFile('file.txt');