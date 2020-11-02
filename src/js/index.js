$(document).ready(function () {

    var currentPath = ""; // current part starting from root
    var currentFile = "";
    var pathLabel = $('.path-label');
    var tbody = $(".folder-content tbody");
    var rootFolder = $("#rootFolder");
    var rootContainer = $("#rootFolder>.foldercontainer")
    var rootIcon = $("#rootIcon");
    var fileInfo = $(".fileInfo");

    //**INITIALIZE **//

    initialize();

    // Populates the HTML and displays root folder content

    function initialize() {
        getAllPaths().then(function (res) {
            var folderStructure = JSON.parse(res);
            displayFile(currentPath);
            folderStructure.forEach((file) => {
                populateFile(file);
            })
        });

        displayFolderContent(currentPath, true);
    }

    // upload file on input uploaded

    $("#uploadFile").change(function (e) {
        e.preventDefault();
        var data = new FormData();
        var files = document.getElementById('uploadFile').files;
        data.append('file', files[0]);
        uploadFile(data, currentPath).then(res => {
            res = JSON.parse(res);
            message(res[1]["message"], res[1]["status"]);
            if (res[1]["status"] == 200) {
                populateFile(res[0]);
                if (res[0].parentPath == currentPath) tbody.append(displayInTable(res[0]));
            }
        });
    })

    $('#search-btn').click(function () {
        searchFiles($('#input-search').val()).then(res => {
            res = JSON.parse(res);
            tbody.empty();
            res.forEach(function (file) {
                tbody.append(displayInTable(file));
            })
        })
    })

    //*** FOLDER TREE ***//

    rootFolder.click(function (event) {
        var elem = event.target;
        if (elem.tagName.toLowerCase() == "img") elem = event.target.parentNode;
        if (elem.tagName.toLowerCase() == "span" && elem !== event.currentTarget) {
            var type = elem.classList.contains("folder") ? "folder" : "file";
            if (type == "file") {
                var path = $(elem).data('parentpath');
                if (path) pathLabel.text('root/' + path);
                else pathLabel.text('root/');
                if (currentPath != path) {
                    currentPath = path;
                    displayFolderContent(path);
                }

                if (currentFile != $(elem).data('path')) {
                    currentFile = $(elem).data('ppath');
                    displayFile($(elem).data('path'));
                }

            } else if (type == "folder") {
                if ($(elem).data('path')) pathLabel.text('root/' + $(elem).data('path'));
                else pathLabel.text('root/');
                var path = $(elem).data('path');

                if (currentPath != path) {
                    currentPath = path;
                    displayFolderContent(path, elem.name, type);
                }

                if (currentFile != path) {
                    currentFile = path;
                    displayFile(path);
                }

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

    // ***POPULATE HTML*** //

    function populateFile(file) {
        if (file.extension == 'folder') {
            var folder = $('<div class = "foldercontainer"></div>');
            var folderIcon = $(`<span class="folder fa-folder" data-isexpanded="true">${file.name}</span>`);
            // folderIcon.attr("data-ext", file.extension);
            folderIcon.attr("data-path", file.path);
            folderIcon.attr("data-parentPath", file.parentPath);
            folder.append(folderIcon);

            if (file.path.split('/').length <= 1) folder.insertAfter(rootIcon);
            else {
                var parent = $(`.folder[data-path="${file.parentPath}"]`);
                folder.insertAfter(parent);
            }

        } else {

            var newFile = $(`<span class ="file"><img class = "ext-icon" src ="assets/file_extensions/${file.extension?file.extension:'file'}.svg">${file.name}</span>`);
            newFile.attr("data-path", file.path);
            //  newFile.attr("data-ext", file.extension);
            newFile.attr("data-parentPath", file.parentPath);
            if (file.path.split('/').length <= 1) rootContainer.append(newFile);
            else {
                var parent = $(`.folder[data-path="${file.parentPath}"]`).parent();
                parent.append(newFile);
            }
        }
    }

    function displayFolderContent(path) {
        tbody.empty();
        getFolderContent(path).then(function (res) {
            res = JSON.parse(res);
            res.forEach(function (file) {
                var tr = displayInTable(file);
                tbody.append(tr);

            })
        });
    }

    function displayInTable(file) {
        var tr = $('<tr class = "container">');
        var name = $(`<td><span><img class = "ext-icon" src ="assets/file_extensions/${file.extension}.svg"> ${file.name}</span></td>`);
        var size = $(`<td class="text-center"> ${file.size}</td>`);
        var modified = $(`<td class = "modified text-center" >${file.modified}</td>`);
        tr.append(name, size, modified);
        return tr;
    }

    function populateFileInfo(file) {
        fileInfo.empty();
        var name = `<p><h2><img class = "ext-icon-big" src ="assets/file_extensions/${file.extension?file.extension:'file'}.svg">&nbsp;&nbsp;${file.name}</h2></p>`;
        var size = $(`<p> Size&nbsp;&nbsp;<span>${file.size}</span></p>`);
        var lastM = $(`<p> Last modified:&nbsp; &nbsp; <span>${file.modified}</span></p>`);
        var create = $(`<p>Creation date:&nbsp;&nbsp;<span>${file.creationDate}</span ></span ></p>`);
        var location = $(`<p>Location:&nbsp;&nbsp; <span>root/${file.parentPath}</span</p>`)
        fileInfo.append(name, size, lastM, create, location);

    }

    function displayFile(path) {
        getFile(path).then(res => {
            populateFileInfo(JSON.parse(res));

        });

    }
})