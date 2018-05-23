var MainTabWindows = $(".MainTabWindows");
var NavbarIconWrap = $(".NavbarIconWrap");
var Navbar = $(".Navbar");
var NavbarLine = $(".NavbarLine");
var UserSearchBar = $("#UserSearchBar");
var SearchResults = $(".SearchResults");
var SelectReceiver = $(".SelectReceiver");
var FriendList = $(".FriendList");
var alerts = $("#alerts-page");
var ExploreData = $("#ExploreData");


/**
 * CACHE IMAGES
 * @type {*|jQueryImageCaching|jQuery}
 */
//var cachedNavbarIcons = $(".NavbarIconWrap img").imageCaching();
//var cashedAvatarIcons = $("img.Avatar").imageCaching();

/**
 * POPUPS
 */
function triggerErrorPopup(ErrorCode) {
    var ErrorMessage = "Unknown Error occurred!", ErrorInformationSite = "", AlsoLogInConsole = true; // WILL BE REWRITTEN (EXCEPT SOMETHING CRAZY HAPPENS)
    switch (ErrorCode) {
        case "ChatNotAllowed":
            AlsoLogInConsole = false;
            ErrorMessage = "Sorry, it seems like your account is not allowed to use our chat feature.";
            break;
    }
    if (AlsoLogInConsole) console.error("Error: " + ErrorMessage);
    swal({
        title: 'Error!',
        text: ErrorMessage,
        footer: '<a href="' + ErrorInformationSite + '">Why do I have this problem?</a>',
        type: 'error'
    });
}

/**
 * OLD BROWSER
 * @type {boolean}
 */
var isIE = /*@cc_on!@*/false || !!document.documentMode;
var isEdge = !isIE && !!window.StyleMedia;
if (isIE || isEdge) {
    alert("Sorry, your browser is currently not supported. " +
        "Please update to a newer browser if you are facing any kind of issues. " +
        "If you are a developer, you can help us supporting this browser on github.com/marvinborner/BEAM-Messenger/") // PLEASE DO IT ACTUALLY
}

/**
 * NAVBAR
 */
var $el, leftPos, newWidth;
NavbarLine
    .css("left", $(".ActiveTab").position().left)
    .data("origLeft", NavbarLine.position().left)
    .data("origWidth", NavbarLine.width());
NavbarIconWrap.on("click", function () {
    NavbarIconWrap.removeClass("ActiveTab");
    var index = $(this).attr('id');
    MainTabWindows.slick('slickGoTo', index);
    $el = $(this);
    leftPos = $el.position().left;
    NavbarLine.stop().animate({
        left: leftPos,
        width: newWidth
    }, 300);
});

window.addEventListener("load", function () {
    setTimeout(function () {
        // This hides the address bar:
        window.scrollTo(0, 1);
    }, 0);
});

/**
 * SWIPEABLE TABS
 */
MainTabWindows.slick({
    initialSlide: 2,
    mobileFirst: true,
    nextArrow: "",
    prevArrow: "",
    infinite: false,
    zIndex: 500
});

MainTabWindows.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
    currentSlide = $("#" + currentSlide);
    nextSlide = $("#" + nextSlide);

    currentSlide.children().attr("src", (currentSlide.children().attr("src").split('.svg')[0].replace('Activated', '') + ".svg"));
    nextSlide.children().attr("src", nextSlide.children().attr("src").split('.svg')[0] + "Activated.svg");
    $el = nextSlide;
    $el.addClass("ActiveTab");
    leftPos = $el.position().left;
    NavbarLine.stop().animate({
        left: leftPos,
        width: newWidth
    }, 300);
});

/**
 * SEARCH
 */
UserSearchBar.keyup(function () {
    SearchResults.empty();
    var RequestedUser = UserSearchBar.val();
    if (RequestedUser !== " " && RequestedUser !== "")
        $.ajax({
            url: site.uri.public + "/api/users/u/" + RequestedUser,
            success: function (answer) {
                console.log("%c[SEARCH LOGGER] User " + RequestedUser + " was found!", "color: green");
                //var GifUrls = ["https://media.giphy.com/media/xUPGcg01dIAot4zyZG/giphy.gif", "https://media.giphy.com/media/IS9LfP9oSLdcY/giphy.gif", "https://media.giphy.com/media/5wWf7H0WTquIU1DFY4g/giphy.gif"];
                //var RandomGif = Math.floor((Math.random() * GifUrls.length));
                //var RandomGifUrl = GifUrls[RandomGif];
                //console.image(RandomGifUrl, 0.5);

                SearchResults.append("<img class='Avatar' src='" + answer.avatar + "'/><div class='UsersFullName'>" + answer.full_name + "</div>");
            },
            error: function () {
                console.log("%c[SEARCH LOGGER] User " + RequestedUser + " was not found!", "color: red");

                alerts.ufAlerts().ufAlerts('fetch');
            }
        });
});

/**
 * SEVERAL API REQUESTS/REFRESHES
 */
// CHAT RECEIVERS -- more in chat.js
$(document).ready(function () {
    $.ajax({
        url: site.uri.public + "/api/users/u/" + current_username + "/friends",
        success: function (receivers) {
            receivers.forEach(function (receiversInfo) {
                SelectReceiver.append("<div class='ReceiverSelector' data-username='" + receiversInfo.username + "' data-id='" + receiversInfo.id + "'><img class='Avatar' src='" + receiversInfo.avatar + "'/><div class='UsersFullName'>" + receiversInfo.full_name + "</div></div>");
                FriendList.append("<img class='Avatar' src='" + receiversInfo.avatar + "'><a class='FriendName' href='" + site.uri.public + "/users/u/" + receiversInfo.username + "'>" + receiversInfo.full_name + "</a><br>");
            })
        },
        error: function () {
            console.log("%c[FRIENDLY LOGGER] No friends were found! :(", "color: red");

            alerts.ufAlerts().ufAlerts('fetch');
        }
    });
});
