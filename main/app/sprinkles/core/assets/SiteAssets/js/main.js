var MainTabWindows = $(".MainTabWindows");
var NavbarIconWrap = $(".NavbarIconWrap");
var Navbar = $(".Navbar");
var NavbarLine = $(".NavbarLine");
var UserSearchBar = $("#UserSearchBar");
var SearchResults = $(".SearchResults");
var alerts = $("#alerts-page");
var ExploreData = $("#ExploreData");

/**********
 OLD BROWSER
 *********/
var isIE = /*@cc_on!@*/false || !!document.documentMode;
var isEdge = !isIE && !!window.StyleMedia;
if (isIE || isEdge) {
    alert("Sorry, your browser is currently not supported. " +
        "Please update to a newer browser if you are facing any kind of issues. " +
        "If you are a developer, you can help us supporting this browser on github.com/marvinborner/BEAM-Messenger/") // PLEASE DO IT ACTUALLY
}

/******
 NAVBAR
 *****/
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

/********
 SWIPEABLE
 *******/
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

/*****
 SEARCH
 ****/
UserSearchBar.keyup(function () {
    var RequestedUser = UserSearchBar.val();
    $.ajax({
        url: site.uri.public + "/api/users/u/" + RequestedUser,
        success: function (answer) {
            console.log("[SEARCH LOGGER] User " + RequestedUser + " was (finally) found! :)");
            var GifUrls = ["https://media.giphy.com/media/xUPGcg01dIAot4zyZG/giphy.gif", "https://media.giphy.com/media/IS9LfP9oSLdcY/giphy.gif", "https://media.giphy.com/media/5wWf7H0WTquIU1DFY4g/giphy.gif"];
            var RandomGif = Math.floor((Math.random() * GifUrls.length));
            var RandomGifUrl = GifUrls[RandomGif];
            console.image(RandomGifUrl, 0.5);

            alerts.ufAlerts().ufAlerts('fetch');

            SearchResults.empty();
            SearchResults.append("<img class='avatar' src='" + answer.avatar + "'/><div class='full_name'>" + answer.full_name + "</div>");
        },
        error: function () {
            console.log("[SEARCH LOGGER] 404s are not a bug - they're a feature!");
            console.log("[SEARCH LOGGER] " + RequestedUser + " not found...");

            alerts.ufAlerts().ufAlerts('fetch');

            SearchResults.empty();
        }
    });
});