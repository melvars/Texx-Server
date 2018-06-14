const MainTabWindows = $(".MainTabWindows");
const FeedTabWindow = $(".FeedTabWindow");
const NavbarIconWrap = $(".NavbarIconWrap");
const Navbar = $(".Navbar");
const NavbarLine = $(".NavbarLine");
const UserSearchBar = $("#UserSearchBar");
const SearchResults = $(".SearchResults");
const SelectReceiver = $(".SelectReceiver");
const SelectedReceiver = $(".SelectedReceiver");
const FriendList = $(".FriendList");
const alerts = $("#alerts-page");
const ExploreData = $("#ExploreData");


/**
 * To get HTML for user selector
 *
 * @param User
 * @returns {string}
 * @constructor
 */
function GetUserSelectorHTML(User) {
    return "<div class='UserSelector' data-username='" + User.username + "' data-user-id='" + User.id + "'><img class='Avatar' src='" + User.avatar + "'/><div class='UsersFullName'>" + User.full_name + "</div></div><hr class='ShorterLine'>"
}


/**
 * CACHE IMAGES
 * @type {*|jQueryImageCaching|jQuery}
 */
//var cachedNavbarIcons = $(".NavbarIconWrap img").imageCaching();
//var cashedAvatarIcons = $("img.Avatar").imageCaching();

/**
 * ERROR/SUCCESS POPUPS
 */
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

function triggerErrorPopup(ErrorCode) {
    let ErrorMessage = "Unknown Error occurred!", ErrorInformationSite = "", AlsoLogInConsole = true; // WILL BE REWRITTEN (EXCEPT SOMETHING CRAZY HAPPENS)
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
let isIE = /*@cc_on!@*/false || !!document.documentMode;
const isEdge = !isIE && !!window.StyleMedia;
if (isIE || isEdge) {
    alert("Sorry, your browser is currently not supported. " +
        "Please update to a newer browser if you are facing any kind of issues. " +
        "If you are a developer, you can help us supporting this browser on github.com/marvinborner/BEAM-Messenger/") // PLEASE DO IT ACTUALLY
}

/**
 * NAVBAR
 */
let $el, leftPos, newWidth;
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

    // NAVBAR LINE
    leftPos = $el.position().left;
    NavbarLine.stop().animate({
        left: leftPos,
        width: newWidth
    }, 300);
});

/**
 * TOP NAVBAR BUTTONS LOGIC
 */
$("#ImageUploadButton").on("click", function () {
    swal({
        title: 'Choose an image to upload!',
        html: "<form id='ImageUploadForm'>" +
        "<input formenctype='multipart/form-data' type='file' name='image' />" +
        "<input formenctype='multipart/form-data' type='submit' value='Upload!' />" +
        "<input type='hidden' name='" + site.csrf.keys.name + "' value='" + site.csrf.name + "' />" +
        "<input type='hidden' name='" + site.csrf.keys.value + "' value='" + site.csrf.value + "' />" +
        "</form>"
    });

    $(".swal2-confirm").text("Close");

    $("#ImageUploadForm")
        .submit(function (e) {
            $.ajax({
                url: '/api/posts/image',
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false
            });
            e.preventDefault();
        });
});

/**
 * SEARCH
 */
UserSearchBar.keypress(function () {
    const RequestedUser = UserSearchBar.val();
    if (RequestedUser !== " " && RequestedUser !== "") {
        $.ajax({
            url: site.uri.public + "/api/search/user/" + RequestedUser,
            success: function (UserArray) {
                SearchResults.empty();
                UserArray.forEach(function (User) {
                    console.log("%c[SEARCH LOGGER] User " + RequestedUser + " was found!", "color: green");
                    //var GifUrls = ["https://media.giphy.com/media/xUPGcg01dIAot4zyZG/giphy.gif", "https://media.giphy.com/media/IS9LfP9oSLdcY/giphy.gif", "https://media.giphy.com/media/5wWf7H0WTquIU1DFY4g/giphy.gif"];
                    //var RandomGif = Math.floor((Math.random() * GifUrls.length));
                    //var RandomGifUrl = GifUrls[RandomGif];
                    //console.image(RandomGifUrl, 0.5);
                    SearchResults.append(GetUserSelectorHTML(User));
                })
            },
            error: function () {
                SearchResults.empty();
                console.log("%c[SEARCH LOGGER] User " + RequestedUser + " was not found!", "color: red");

                alerts.ufAlerts().ufAlerts('fetch');
            }
        });
    } else {
        SearchResults.empty();
    }
});

/**
 * SEVERAL API REQUESTS/REFRESHES
 */
$(document).ready(function () {
    $.ajax({ // CHAT RECEIVERS -- more in chat.js
        url: site.uri.public + "/api/users/u/" + current_username + "/friends",
        success: function (receivers) {
            receivers.forEach(function (User) {
                SelectReceiver.append(GetUserSelectorHTML(User));
                SelectedReceiver.prepend("<div style='display: none;' id='ChatMessages' class='ChatMessages' data-username='" + User.username + "'></div>");

                FriendList.append(GetUserSelectorHTML(User));
            })
        },
        error: function () {
            console.log("%c[FRIENDLY LOGGER] No friends were found! :(", "color: red");

            alerts.ufAlerts().ufAlerts('fetch');
        }
    });
    $.ajax({ // INITIALIZE IMAGE FEED
        url: site.uri.public + "/api/feed/" + current_username,
        success: function (images) {
            images.forEach(function (imageInfo) {
                FeedTabWindow.append("" +
                    "<div data-image-id='" + imageInfo.image_id + "' class='FeedImageWrapper'>" +
                    "<div data-username='" + imageInfo.username + "' data-id='" + imageInfo.user_id + "' class='UploaderInfo'>" +
                    "<img class='UploaderAvatar' src='" + imageInfo.avatar + "'>" +
                    "<div class='UploaderName'>" + imageInfo.full_name + "</div>" +
                    "</div>" +
                    "<img class='FeedImage' src='" + imageInfo.image_url + "'>" +
                    "</div>" +
                    "<hr>");
            });

            /**
             * USER PROFILE PAGE SHOW/RENDER -- needs to be initialized after ajax load
             */
            $("div:not(.SelectReceiver) > [data-username]").on("click", function () {
                console.log(1);
                $(".main > *").hide(); // TODO: Improve -- maybe move out of success ajax
                $(".main").prepend(GetProfilePageHTML($(this).attr("data-username")));
            });

        },
        error: function () {
            console.log("%c[FEED LOGGER] No images in feed!", "color: red");

            alerts.ufAlerts().ufAlerts('fetch');
        }
    });
});