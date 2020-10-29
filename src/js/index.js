/* show messages to the user*/



$(document).ready(function () {

    var currentPath = "."; // current part starting from root

    $("#formUpload").submit(function (e) {
        e.preventDefault();

        var data = new FormData();
        var files = document.getElementById('uploadFile').files;
        console.log(files)
        data.append('file', files[0]);
        console.log(data);
        uploadFile(data).then(res => console.log(res));


    })

    //getTreePaths().then(res => console.log(res));


    getAllPaths().then(res => console.log(res));

    getTreePaths().then(res => console.log(res))
});