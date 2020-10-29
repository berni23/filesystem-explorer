/**

 * @description gets the prefix path so the search always starts on root
 */

function getPrefixPath() {
    var prefixPath = '../../root';
}

/**

 * @param {string} msg message to be displayed
    @param {string} tag (optional), tag to be added to the message.possible tags: success, error
 * @description  displays a user-friendly message during 1.5 seconds
 */


function message(msg, tag = false) {

    var infoWindow = $(".info-window");
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