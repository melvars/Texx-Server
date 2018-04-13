<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#0B1D33">
    <meta name="msapplication-navbutton-color" content="#0B1D33">
    <meta name="apple-mobile-web-app-status-bar-style" content="#0B1D33">

    <style>
        <?php include "assets/php/stylesheet.php"; ?>
    </style>

    <title>Social Network</title>
</head>
<body>

<div class="main">
    <div class="MainTabWindows">
        <div class="carousel-cell FeedTab">
            <div class="headerWrap">
                <div class="header">
                    <span class="LeftButtonHeader"><img draggable="false" ondragstart="return false;"
                                                        src="assets/icons/BurgerMenuShort.svg"></span>
                    <span class="HeaderCaption"><span data-lang='Feed'></span></span>
                    <span class="RightButtonHeader"><i class="fas fa-bell"></i></span>
                </div>
                <hr>
            </div>
        </div>
        <div class="carousel-cell ExploreTab">
            <div class="headerWrap">
                <div class="header">
                    <span class="LeftButtonHeader"><img draggable="false" ondragstart="return false;"
                                                        src="assets/icons/BurgerMenuShort.svg"></span>
                    <span class="HeaderCaption"><span data-lang='Explore'></span></span>
                    <span class="RightButtonHeader"><i class="fas fa-bell"></i></span>
                </div>
                <hr>
            </div>
        </div>
        <div class="carousel-cell">
            <div class="headerWrap">
                <div class="header">
                    <span class="LeftButtonHeader"><img draggable="false" ondragstart="return false;"
                                                        src="assets/icons/BurgerMenuShort.svg"></span>
                    <span class="HeaderCaption"><span data-lang='Chat'></span></span>
                    <span class="RightButtonHeader"><i class="fas fa-bell"></i></span>
                </div>
                <hr>
            </div>
            <div class="MainInTab">
                <div class="ChatWindow">
                    <div id="ChatResponses" class="ChatResponses"></div>
                    <input title="ChatTextInput" id="ChatTextInput" class="ChatInput" type="text"/>
                    <input title="SubscribeTextInput" id="SubscribeTextInput" class="ChatInput" type="text"/>
                </div>
            </div>
        </div>
        <div class="carousel-cell">
            <div class="headerWrap">
                <div class="header">
                    <span class="LeftButtonHeader"><img draggable="false" ondragstart="return false;"
                                                        src="assets/icons/BurgerMenuShort.svg"></span>
                    <span class="HeaderCaption"><span data-lang='Friends'></span></span>
                    <span class="RightButtonHeader"><i class="fas fa-bell"></i></span>
                </div>
                <hr>
            </div>
        </div>
        <div class="carousel-cell">
            <div class="headerWrap">
                <div class="header">
                    <span class="LeftButtonHeader"><img draggable="false" ondragstart="return false;"
                                                        src="assets/icons/BurgerMenuShort.svg"></span>
                    <span class="HeaderCaption"><span data-lang='Personal'></span></span>
                    <span class="RightButtonHeader"><i class="fas fa-bell"></i></span>
                </div>
                <hr>
            </div>
        </div>
    </div>

    <div class="Navbar">
         <span id="0" class="NavbarIconWrap">
             <img draggable="false" ondragstart="return false;" src="assets/icons/FriendFeedOutline.svg">
        </span>
        <span id="1" class="NavbarIconWrap">
            <img draggable="false" ondragstart="return false;" src="assets/icons/ExploreGlobeOutline.svg">
        </span>
        <span id="2" class="NavbarIconWrap ActiveTab">
            <img draggable="false" ondragstart="return false;" src="assets/icons/MessageBubbleOutline.svg">
        </span>
        <span id="3" class="NavbarIconWrap">
            <img draggable="false" ondragstart="return false;" src="assets/icons/UserGroupOutline.svg">
         </span>
        <span id="4" class="NavbarIconWrap">
            <img draggable="false" ondragstart="return false;" src="assets/icons/UserOutline.svg">
        </span>
    </div>
    <span class='NavbarLine'></span>
</div>

<script>
    <?php include "assets/php/scripts.php"; ?>
</script>

<script>
    function initiateLanguage() {
        var translate = new Translate();
        var currentLng = 'de'; // <- PHP IP LOCATION
        translate.init(currentLng);
        translate.process();
    }
</script>

</body>
</html>