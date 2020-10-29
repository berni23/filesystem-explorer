/* show messages to the user*/
var infoWindow = $(".info-window");



function makeFile(myPath) {

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



//getAllPaths();

function getAllPaths() {

    fetch('src/server/files.php?getAllPaths').then(res => res.json()).then(function (res) {

        console.log(res);

    });

}

// utils


function message(msg, tag = false) {

    if (tag) infoWindow.addClass(tag);

    infoWindow.text(msg);
    infoWindow.addClass("show-info");
    infoWindow.removeClass("hidden");
    setTimeout(function () {
        infoWindow.removeClass("show-info");
        setTimeout(() => {

            infoWindow.addClass("hidden");
            if (tag) infoWindow.removeClass(tag);
        }, 1000);
    }, 1500);
}