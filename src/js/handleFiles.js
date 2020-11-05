/**
 * @param {string} myPath  filename
 * @param {string} type  'file' for creating file, 'folder' for creating folder, (default is file)
 * @description adds a file if the extension is valid, returns success message 200 if created, else error 400.
 */


function makeFile(myPath, name, myType = "file") {
    var data = {
        filename: name,
        path: myPath,
        type: myType,
    }
    return fetch('src/server/files.php?makeFile', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.text());
}

/**
 * @description gets all file paths recursively starting on root
 * @returns array with paths, unidimensional array
 */
function getAllPaths() {
    return fetch('src/server/files.php?getAllPaths').then(res => res.text());
}

/**
 * @description gets all file paths recursively starting on root
 * @returns   a promise, with a mssage success if successfully uploaded, else error
 */

function uploadFile(data, location) {
    return fetch('src/server/upload.php?location=' + location, {
            method: 'post',
            body: data
        })
        .then(res => res.text());
}

/**
 * @description gets the folder content given a right folder path
 * @param {string} folderPath the folder path
 * @returns {promise}a promise, outputing  in the first place a file object with the current folder, 
 * and in the second place an array of objects type File.
 */
function getFolderContent(folderPath) {
    return fetch('src/server/files.php?folderContent=' + folderPath).then(res => res.text())
}

/**
 * @description gets all the files or folders which name contains the input search provided
 * @param {string} search string used to find related file names
 * @returns {promise} a promise, outputing an array of objects type File when finished.
 */
function searchFiles(search) {
    return fetch('src/server/files.php?inputSearch=' + search).then(res => res.text());
}
/**
 * @description gets a files or folders specified
 * @param {string} path path of the desired file
 * @returns {promise} a promise, outputing an array created with the File class (php)
 */
function getFile(path) {
    return fetch('src/server/files.php?getFile=' + path).then(res => res.text());
}


/**
 * @description moves a file from path1 to path2
 * @param {string} path1 origin path of the file to be moved
 * @param {string} path2 end path of the file to be moved
 * @returns {promise} an array  with  the object moved and a status message
 */
function move(path1, path2) {
    var data = {
        origin: path1,
        end: path2
    }
    return fetch('src/server/files.php?move', {
        method: 'POST',
        body: JSON.stringify(data)
    }).then(res => res.text());
}

function deleteFile(path) {
    return fetch('src/server/files.php?delete=' + path).then(res => res.text());
}


function editFile(path, newName) {
    var data = {
        path: path,
        newname: newName
    }
    return fetch('src/server/files.php?edit', {
        method: 'POST',
        body: JSON.stringify(data)
    }).then(res => res.text());
}