/* show messages to the user*/

;

$(document).ready(function () {


    var currentPath = ""; // current part starting from root
    var pathLabel = $('.path-label');

    $("#formUpload").submit(function (e) {
        e.preventDefault();

        var data = new FormData();

        var files = document.getElementById('uploadFile').files;
        data.append('file', files[0]);
        console.log(data);
        uploadFile(data).then(res => {

            console.log(res);
            res = JSON.parse(res);

            console.log(res);
            message(res["message"], res["status"]);
            console.log(res);

        });
    })


    /*** FOLDER TREE **/

    var rootFolder = $("#rootFolder");
    var rootContainer = $("#rootFolder>.foldercontainer")
    rootFolder.click(function (event) {
        var elem = event.target;
        if (elem.tagName.toLowerCase() == "span" && elem !== event.currentTarget) {
            var type = elem.classList.contains("folder") ? "folder" : "file";
            if (type == "file") {
                if ($(elem).data('parentPath')) pathLabel.text('root/' + $(elem).data('parentPath'));
                else pathLabel.text('root/');
                currentPath = $(elem).data('parentPath');

            }
            if (type == "folder") {

                if ($(elem).data('path')) pathLabel.text('root/' + $(elem).data('path'));
                else pathLabel.text('root/');
                currentPath = $(elem).data('path');

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



    function populateFile(file) {

        console.log(file);
        if (file.extension == 'folder') {

            var folder = $('<div class = "foldercontainer"></div>');
            var folderIcon = $(`<span class="folder fa-folder" data-isexpanded="true">${file.name}</span>`);
            folderIcon.attr("data-ext", file.extension);
            folderIcon.attr("data-path", file.path);
            folderIcon.attr("data-parentPath", file.parentPath);
            folder.append(folderIcon);

            if (file.path.split('/').length <= 1) rootContainer.append(folder);

            else {

                console.log('folder');
                var parent = $(`.folder[data-path="${file.parentPath}"]`);
                parent.after(folder);
            }

        } else {

            var newFile = $(`<span class ="file"><img class = "ext-icon" src ="assets/file_extensions/${file.extension?file.extension:'file'}.svg">${file.name}</span>`);
            newFile.attr("data-path", file.path);
            newFile.attr("data-ext", file.extension);
            newFile.attr("data-parentPath", file.parentPath);

            if (file.path.split('/').length <= 1) rootContainer.append(newFile);

            else {


                console.log('file');
                var parent = $(`.folder[data-path="${file.parentPath}"]`);
                parent.after(newFile);
            }
        }

    }
})