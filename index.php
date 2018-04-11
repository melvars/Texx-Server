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

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" media="screen">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"
          media="screen">
    <link rel="stylesheet" href="assets/css/main.css" media="screen">

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
                    <span class="HeaderCaption">Feed</span>
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
                    <span class="HeaderCaption">Explore</span>
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
                    <span class="HeaderCaption">Chat</span>
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
                    <span class="HeaderCaption">Friends</span>
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
                    <span class="HeaderCaption">Personal</span>
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
</div>

<script src="https://code.jquery.com/jquery-latest.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="assets/js/chat.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>