$(document).ready(function () {
    var currentPath = ""; // current part starting from root
    var currentFile = ""; // path of the current file being displayed
    var pathLabel = $('.path-label');
    var folderContent = $(".folder-content");
    var tbody = $(".folder-content tbody");
    var rootFolder = $("#rootFolder");
    var rootContainer = $("#rootFolder>.foldercontainer")
    var rootIcon = $("#rootIcon");
    var fileInfo = $(".fileInfo");
    var inputSearch = $('#input-search');
    var newFileModal = $("#newFile-modal");
    var newFolderModal = $("#newFolder-modal");
    var newFileBtn = $('#newFileBtn');
    var fileOptions = $('.fileOptions');


    //**INITIALIZE **//

    initialize();
    // Populates the HTML and displays root folder content

    function initialize() {
        getAllPaths().then(function (res) {
            displayFile(currentPath);
            JSON.parse(res).forEach((file) => populateFile(file));
        });
        displayFolderContent(currentPath, true);
    }

    $('#moveFileBtn').click(function () {
        var newPath = $('#input-movepath').val();
        move(currentFile, newPath).then(res => fileMoved(JSON.parse(res)));
    });

    $('#deleteFileBtn').click(function () {
        deleteFile(currentFile).then(res => {

            console.log(res);
            fileMoved(JSON.parse(res));

        });
    });


    //create file

    newFileBtn.click(function () {
        var name = $('#input-createFile').val().replace(/ /g, "_"); // input of file or folder name
        var validate = validateName(name);
        if (validate[0]) {
            makeFile(currentPath, name, newFileBtn.data('file')).then(function (res) {
                console.log(res);
                res = JSON.parse(res);
                message(res[1]["message"], res[1]["status"]);
                populateFile(res[0]);
                tbody.append(displayInTable(res[0]));
            });
        } else message(validate[1], 400);
    })

    function validateName(name) {
        var message = 'names cannot include "." or "/"';
        if (name.includes('/') || name.includes('.')) return [false, message];
        else return [true, ''];

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
        var searchVal = inputSearch.val();
        pathLabel.addClass('searching');
        folderContent.removeClass('noResults');
        pathLabel.text(`Searching for files containing '${searchVal}'...`);
        searchFiles(searchVal).then(res => {
            res = JSON.parse(res);
            tbody.empty();
            res.forEach((file) => tbody.append(displayInTable(file)));
            if (res.length) pathLabel.text(`${res.length} results found`);
            else {
                pathLabel.text('OMG  :(  your search did not produce any results')
                folderContent.addClass('noResults');
            };
        })
    })




    $('#btn-edit').click(() => $('#input-editFile').attr('placeholder', currentFile.split('/').pop()));
    $('#editFileBtn').click(function () {

        console.log('button clicked');
        var newName = $('#input-editFile').val().replace(/ /g, "_");
        if (newName.length == 0) message("new name can't be blank", 400);



        else {
            var validate = validateName(newName);
            if (validate[0]) {

                $('#close-edit').click();
                editFile(currentFile, newName).then((res) => fileMoved(JSON.parse(res)));
            } else {
                message(validate[1], 400);
                $('#input-editFile').val('');
            }
        }
    });

    tbody.click(function (event) {
        var path = $(event.target).closest("[data-path]").data('path');
        if (path) displayFile(path);

    })

    //*** FOLDER TREE ***//

    rootFolder.click(function (event) {
        pathLabel.removeClass('searching');
        folderContent.removeClass('noResults');
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
                if (currentFile != $(elem).data('path')) displayFile($(elem).data('path'));

            } else if (type == "folder") {
                if ($(elem).data('path')) pathLabel.text('root/' + $(elem).data('path'));
                else pathLabel.text('root/');
                var path = $(elem).data('path');
                if (currentPath != path) {
                    currentPath = path;
                    displayFolderContent(path, elem.name, type);
                }
                if (currentFile != path) displayFile(path);

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

    // modals

    newFileModal.click(() => {
        $('#hiddenModal').click()
        $('#dropdown-create').click();
        $('.modal-title').text('file name');
        newFileBtn.data('file', 'file');
    });

    newFolderModal.click(() => {
        $('#hiddenModal').click()
        $('#dropdown-create').click();
        $('.modal-title').text('folder name');
        newFileBtn.data('file', 'folder');
    })

    // ***POPULATE HTML*** //

    function populateFile(file) {
        if (file.extension == 'folder') {
            var folder = $('<div class = "foldercontainer"></div>');
            var folderIcon = $(`<span class = "folder fa-folder" data-isexpanded= "true"> ${file.name}</span>`);
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
        var tr = $(`<tr class = "container" data-path="${file.path}">`);
        var name = $(`<td><div style = "margin-left:50px"><span><img class = "ext-icon" src ="assets/file_extensions/${file.extension?file.extension:'file'}.svg"> ${file.name}</span></div></td>`);
        var size = $(`<td class="text-center"> ${file.size}</td>`);
        var modified = $(`<td class = "modified text-center">${file.modified}</td>`);
        tr.append(name, size, modified);
        return tr;
    }

    function populateFileInfo(file) {
        fileInfo.empty();
        currentFile = file.path;

        console.log(file);
        if (file.path == 'root' && file.parentPath == '') fileOptions.addClass('hidden');
        else fileOptions.removeClass('hidden');
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


    function fileMoved(res) {
        message(res[1]['message'], res[1]['status']);
        if ((res[1]['status']) == 200) {
            currentPath = res[0].parentPath;
            currentFile = res[0].parentPath;
            rootContainer.children().not(':first').remove();
            initialize()
        }
    }



})