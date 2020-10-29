/**
 * @param {string} myPath  filename
 * @param {string} type  'file' for creating file, 'folder' for creating folder, (default is file)
 * @description adds a file if the extension is valid, returns success message 200 if created, else error 400.
 */
function makeFile(myPath, type = 'file') {
    var arrayPath = myPath.split('/');
    var data = {
        filename: arrayPath[arrayPath.length - 1],
        path: myPath
    }
    fetch('src/server/files.php?makeFile', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.text()).then(res => console.log(res));
}


/**
 * @description gets all file paths recursively starting on root
 * @returns array with paths
 */
function getAllPaths() {
    fetch('src/server/files.php?getAllPaths').then(res => res.json()).then(function (res) {
        console.log(res);
    });
}

/**
 * @description gets all file paths recursively starting on root
 * @returns  success if successfully uploaded, else error
 */

function uploadFile(data) {
    return fetch('src/server/upload.php', {
            method: 'post',
            body: data
        })
        .then(res => res.text());
}