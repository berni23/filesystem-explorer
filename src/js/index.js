/* show messages to the user*/

;

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
    var rootContainer = $("#rootFolder>.foldercontainer")
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
                    elem.classList.remove("fa-folder");
                    elem.classList.add("fa-folder-o");
                } else {
                    elem.classList.remove("fa-folder-o");
                    elem.classList.add("fa-folder");
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

    getAllPaths().then(function (res) {

        console.log(res);
        var folderStructure = JSON.parse(res);
        folderStructure.forEach((file) => {

            populateFile(file);

        })

    });


    /***FOLDER HIERARCHY***/

    function populateFile(file) {

        if (file.extension == 'folder') {

            var folder = $('<div class = "foldercontainer"></div>');
            var folderIcon = $(`<span class="folder fa-folder" data-isexpanded="true">${file.name}</span>`);

            folderIcon.attr("data-path", file.path ? file.path : 'null');
            folderIcon.attr("data-ext", file.extension);
            folder.append(folderIcon);

            if (!file.path.length) rootContainer.append(folder);

            else {
                var parent = $(`.folder[data-path=${file.parentPath ? file.parentPath : 'null'}]`);
                parent.after(folder);
            }

        } else {




            var newFile = $(`<span class ="file" ><img class = "ext-icon" src ="assets/file_extensions/${file.extension?file.extension:'file'}.svg">${file.name}</span>`);
            newFile.attr("data-path", file.path);
            newFile.attr("data-ext", file.extension);

            if (!file.parentPath.length) rootContainer.append(newFile);

            else {

                parent = $(`.folder[data-path=${file.parentPath ? file.parentPath : 'null'}]`);
                parent.after(newFile);
            }
        }

    }
})