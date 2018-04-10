/******
 GENERAL
 ******/

/*****
 NAVBAR
 *****/
var $el, leftPos, newWidth,
    $mainNav = $(".Navbar");
$mainNav.append("<span class='NavbarLine'></span>");
var $magicLine = $(".NavbarLine");
$magicLine
    .css("left", $(".ActiveTab").position().left)
    .data("origLeft", $magicLine.position().left)
    .data("origWidth", $magicLine.width());
$(".NavbarIconWrap").on("click", function () {
    $(".NavbarIconWrap").removeClass("ActiveTab");
    $(this).addClass("ActiveTab");
    var index = $(this).attr('id');
    $('.MainTabWindows').slick('slickGoTo',index);
    //$('.MainTabWindows').flickity().flickity('select', index);

    $el = $(this);
    leftPos = $el.position().left;
    $magicLine.stop().animate({
        left: leftPos,
        width: newWidth,
    }, 300);
});

/*******
FLICKITY
*******/
$('.MainTabWindows').slick({
    initialSlide: 2,
    mobileFirst: true,
    nextArrow: "",
    prevArrow: "",
    infinite: false,
    zIndex: 500
});

$('.MainTabWindows').on('beforeChange', function(event, slick, currentSlide, nextSlide){
    //console.log(nextSlide);
    $(".NavbarIconWrap").removeClass("ActiveTab");
    $el = $("#" + nextSlide);
    $el.addClass("ActiveTab");
    leftPos = $el.position().left;
    $magicLine.stop().animate({
        left: leftPos,
        width: newWidth
    }, 300);
});

/*
$('.MainTabWindows').flickity({
    cellAlign: 'left',
    prevNextButtons: false,
    pageDots: false,
    friction: 0.3,
    dragThreshold: ($("body").width() * 0.5),
    initialIndex: 2,
    wrapAround: true,
    maxSwipeWidth: 0,
    on: {
        change: function (index) {
            $(".NavbarIconWrap").removeClass("ActiveTab");
            $el = $("#" + index);
            $el.addClass("ActiveTab");
            leftPos = $el.position().left;
            $magicLine.stop().animate({
                left: leftPos,
                width: newWidth
            }, 300);
        },
        dragStart: function () {
            $(".ActiveTab").css({ transform: 'scale(1.05)' });
        },
        dragEnd: function () {
            $(".NavbarIconWrap").css({ transform: 'scale(1.0)' });
        }/*,
        scroll: function (event, progress) {
            var TotalWidth = $("body").width();
            console.log(progress / 10);
            leftPos = ((progress / 1000) * TotalWidth + 'px');
            $magicLine.stop().animate({
                left: leftPos,
                width: newWidth
            });
        }*
    }
});
*/