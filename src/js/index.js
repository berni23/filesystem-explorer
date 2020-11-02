$(document).ready(function () {

    var currentPath = ""; // current part starting from root
    var pathLabel = $('.path-label');
    var tbody = $(".folder-content tbody");

    //**INITIALIZE **//

    initialize();

    // Populates the HTML and displays root folder content

    function initialize() {
        getAllPaths().then(function (res) {
            console.log(res);
            var folderStructure = JSON.parse(res);
            folderStructure.forEach((file) => {
                populateFile(file);
            })
        });

        displayFolderContent(currentPath);
    }

    // upload file on input uploaded

    $("#uploadFile").change(function (e) {
        console.log('event triggered');
        e.preventDefault();
        var data = new FormData();
        var files = document.getElementById('uploadFile').files;
        data.append('file', files[0]);
        console.log(currentPath);
        uploadFile(data, currentPath).then(res => {
            console.log(res);
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

            console.log(res);
            res = JSON.parse(res);

            tbody.empty();
            res.forEach(function (file) {

                tbody.append(displayInTable(file));
            })

        })
    })

    //*** FOLDER TREE ***//

    var rootFolder = $("#rootFolder");
    var rootContainer = $("#rootFolder>.foldercontainer")
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
                    console.log("path:", path);
                    displayFolderContent(path);

                }

            } else if (type == "folder") {
                if ($(elem).data('path')) pathLabel.text('root/' + $(elem).data('path'));
                else pathLabel.text('root/');

                var path = $(elem).data('path');

                if (currentPath != path) {
                    currentPath = path;

                    console.log("path:", path);
                    displayFolderContent(path);

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
            folderIcon.attr("data-ext", file.extension);
            folderIcon.attr("data-path", file.path);
            folderIcon.attr("data-parentPath", file.parentPath);
            folder.append(folderIcon);

            if (file.path.split('/').length <= 1) rootContainer.append(folder);
            else {
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
                var parent = $(`.folder[data-path="${file.parentPath}"]`);
                parent.after(newFile);
            }
        }
    }

    function displayFolderContent(path) {

        tbody.empty();
        getFolderContent(path).then(function (res) {

            console.log(res);
            res = JSON.parse(res);
            res.forEach(function (file) {
                console.log(file);
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
})