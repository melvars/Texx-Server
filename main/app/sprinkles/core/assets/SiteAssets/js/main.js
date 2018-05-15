var MainTabWindows = $(".MainTabWindows");
var NavbarIconWrap = $(".NavbarIconWrap");
var Navbar = $(".Navbar");
var NavbarLine = $(".NavbarLine");
var UserSearchBar = $("#UserSearchBar");
var SearchResults = $(".SearchResults");
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
function triggerErrorPopup() {
    swal({
        title: 'Error!',
        text: 'Do you want to continue?',
        footer: '<a>Why do I have this problem?</a>',
        type: 'error',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    });
}

/**
 * ENCRYPTION
 */
/*var openpgp = window.openpgp;
var options, EncryptedText, DecryptedText, PrivateKey, PassPhrase, PrivateKeyObj;
var PublicKey = [];
openpgp.initWorker({path: '/assets-raw/core/assets/SiteAssets/js/openpgp.worker.js'});

$.ajax({
    type: 'GET',
    url: site.uri.public + '/api/users/u/' + current_username + '/publickey',
    dataType : "json",
    success: function (response) {
        if (response.user_id === current_user_id) {
            PublicKey[current_username] = response.PublicKey;
            PrivateKey = localStorage.getItem("PrivateKey");
            PassPhrase = localStorage.getItem("🔒")
        }
    }
});

function EncryptMessage(Message, Username) {
    if (!Username in PublicKey) {
        $.ajax({
            type: 'GET',
            url: site.uri.public + '/api/users/u/' + Username + '/publickey',
            dataType : "json",
            success: function (response) {
                if (response.user_id === current_user_id) {
                    PublicKey[Username] = response.PublicKey;
                }
            }
        });
    }
    options = {
        data: Message,
        publicKeys: openpgp.key.readArmored(PublicKey[Username]).keys
    };
    openpgp.encrypt(options).then(function (EncryptedText) {
        EncryptedText = EncryptedText.data;
    });
}

function DecryptMessage(EncryptedText) {
    PrivateKeyObj = openpgp.key.readArmored(PrivateKey).keys[0];
    PrivateKeyObj.decrypt(PassPhrase);
    options = {
        message: openpgp.message.readArmored(EncryptedText),
        privateKeys: [PrivateKeyObj]
    };

    openpgp.decrypt(options).then(function (DecryptedText) {
        DecryptedText = DecryptedText.data;
    });
}*/

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
    //currentSlide.children().attr("data-src", (currentSlide.children().attr("data-src").split('.svg')[0].replace('Activated', '') + ".svg"));
    //nextSlide.children().attr("data-caching-key", nextSlide.children().attr("data-src").split('.svg')[0].split('/').pop() + "Activated_nav_cached");
    //nextSlide.children().attr("data-src", nextSlide.children().attr("data-src").split('.svg')[0] + "Activated.svg");
    //cachedNavbarIcons.refresh();
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

                alerts.ufAlerts().ufAlerts('fetch');

                SearchResults.append("<img class='Avatar' src='" + answer.avatar + "'/><div class='UsersFullName'>" + answer.full_name + "</div>");
                //$(".SearchResults .Avatar").imageCaching(); // refresh
            },
            error: function () {
                console.log("%c[SEARCH LOGGER] User " + RequestedUser + " was not found!", "color: red");

                alerts.ufAlerts().ufAlerts('fetch');
            }
        });
});