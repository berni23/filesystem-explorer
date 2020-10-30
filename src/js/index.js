/* show messages to the user*/



$(document).ready(function () {


    var currentPath = "."; // current part starting from root

    $("#formUpload").submit(function (e) {
        e.preventDefault();

        var data = new FormData();

        var files = document.getElementById('uploadFile').files;
        data.append('file', files[0]);
        console.log(data);
        uploadFile(data).then(res => {

            message(res[1], res[0]);
            console.log(res);

        });
    })


    var rootFolder = $("#rootFolder");
    rootFolder.click(function (event) {
        var elem = event.target;
        if (elem.tagName.toLowerCase() == "span" && elem !== event.currentTarget) {
            var type = elem.classList.contains("folder") ? "folder" : "file";
            if (type == "file") {
                alert("File accessed");
            }
            if (type == "folder") {
                var isexpanded = elem.dataset.isexpanded == "true";
                if (isexpanded) {
                    elem.classList.remove("fa-folder-o");
                    elem.classList.add("fa-folder");
                } else {
                    elem.classList.remove("fa-folder");
                    elem.classList.add("fa-folder-o");
                }
                elem.dataset.isexpanded = !isexpanded;

                var toggleelems = [].slice.call(elem.parentElement.children);
                var classnames = "file,foldercontainer,noitems".split(",");

                toggleelems.forEach(function (element) {
                    if (classnames.some(function (val) {
                            return element.classList.contains(val);
                        }))
                        element.style.display = isexpanded ? "none" : "block";
                });
            }
        }
    });

    //getTreePaths().then(res => console.log(res));

    getAllPaths().then(res => console.log(res));



    /***FOLDER HIERARCHY***/


    function populateFolderStructure(file) {

        if (file.extension == 'folder') {

            var folder = $('<div class = "foldercontainer"></div>');
            var folderIcon = $(` <span class="folder fa-folder-o" data-isexpanded="true">${file.name}</span>`);


            folder.append(folderIcon);

            folder.data("path", file.path);
            // folder.data("ext", file.extension);

            if (!file.parentPath) rootFolder.append(folder);

            else {


                var parent = $(`.folder[data-path=${ file.parentPath}]`);
            }

        }
    }
})