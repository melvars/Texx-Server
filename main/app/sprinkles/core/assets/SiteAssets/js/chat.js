 /******
 GENERAL
 ******/
function InitializeChatServer() {
    var ChatTextInput = $("#ChatTextInput");
    var SubscribeTextInput = $("#SubscribeTextInput");
    var ChatMessages = $("#ChatMessages");
    var TypingIndicatorAnimationElement = "<div class='spinner'><div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div></div>";

    var ChatSocket = new WebSocket('wss://marvinborner.ddnss.de:1337');
    ChatSocket.onerror = function () {
        setTimeout(function () {
            console.log("[WEBSOCKET LOGGER] Connection failed. Trying again...");
            InitializeChatServer();
        }, 5000);
    };
    ChatSocket.onopen = function () {
        ChatSocket.send(JSON.stringify({ClientMessageType: "Verify", Cookie: document.cookie}));
        // CONNECTION SUCCESSFUL!
        console.log("[WEBSOCKET LOGGER] Chat connection established!");
        // GOT MESSAGE
        ChatSocket.onmessage = function (e) {
            console.log("[WEBSOCKET LOGGER] Received a message from server!");
            // DECLARATIONS
            var TypingIndicatorMessage = $(".TypingIndicatorMessage").parent();
            var LastMessage = $(".MessageWrapper.Normal:last .ChatMessage");
            var MessageObject = JSON.parse(e.data);
            var Message = MessageObject.Message;
            var Username = MessageObject.Username;
            var GroupName = MessageObject.GroupName;
            var State = MessageObject.State;
            var ServerMessage = MessageObject.ServerMessage;
            var WasHimself = MessageObject.WasHimself;
            var ServerMessageType = MessageObject.ServerMessageType;

            if (ServerMessage === false) { // NO SERVER MESSAGE -> SENT BY USER
                if (WasHimself === true) { // -> MESSAGE WAS FROM HIMSELF
                    if (!LastMessage.hasClass("MessageSent")) { // CHECK IF PREVIOUS MESSAGE WAS FROM HIMSELF TOO -> IF NOT, CREATE NEW 'ALONE' MESSAGE
                        ChatMessages.append("<div class='MessageWrapper Normal'><div class='ChatMessage MessageSent AloneMessage animated fadeInRight'>" + Message + "</div></div>");
                    } else if (LastMessage.hasClass("MessageSent")) { // IF PREVIOUS MESSAGE WAS FROM HIMSELF TOO -> CREATE WITH CORRESPONDING CLASSES FOR DESIGN
                        ChatMessages.append("<div class='MessageWrapper Normal'><div class='ChatMessage MessageSent BottomMessage animated fadeInRight'>" + Message + "</div></div>");
                        if (LastMessage.hasClass("AloneMessage")) {
                            LastMessage.removeClass("AloneMessage");
                            LastMessage.addClass("TopMessage");
                        } else if (LastMessage.hasClass("BottomMessage")) {
                            LastMessage.removeClass("BottomMessage");
                            LastMessage.addClass("MiddleMessage");
                        }
                    }
                } else if (WasHimself === false) { // -> MESSAGE WAS FROM OTHER USER
                    if (!LastMessage.hasClass("MessageReceived")) { // CHECK IF PREVIOUS MESSAGE WAS FROM OTHER USER TOO -> IF NOT, CREATE NEW 'ALONE' MESSAGE
                        ChatMessages.append("<div class='MessageWrapper Normal'><div class='ChatMessage MessageReceived AloneMessage animated fadeInLeft'>" + Message + "</div></div>");
                    } else if (LastMessage.hasClass("MessageReceived")) { // IF PREVIOUS MESSAGE WAS FROM OTHER USER TOO -> CREATE WITH CORRESPONDING CLASSES FOR DESIGN
                        ChatMessages.append("<div class='MessageWrapper Normal'><div class='ChatMessage MessageReceived BottomMessage animated fadeInLeft'>" + Message + "</div></div>");
                        if (LastMessage.hasClass("AloneMessage")) {
                            LastMessage.removeClass("AloneMessage");
                            LastMessage.addClass("TopMessage");
                        } else if (LastMessage.hasClass("BottomMessage")) {
                            LastMessage.removeClass("BottomMessage");
                            LastMessage.addClass("MiddleMessage");
                        }
                    }
                }
                // CONVERT LINKS TO LINKS
                $('.MessageReceived').linkify({
                    target: "_blank"
                });
            } else if (ServerMessage === true) { // SERVER MESSAGE
                if (ServerMessageType === "GroupJoin") { // TYPE: USER JOINED A GROUP
                    if (WasHimself === true) { // HIMSELF JOINED A GROUP -> NOTIFY
                        ChatMessages.empty(); // -> EMPTY MESSAGES ON NEW GROUP JOIN
                        ChatMessages.append("<br><div class='MessageWrapper'><div class='ServerChatMessage'>" + GroupName + "</span></div></div><br>");
                        ReplaceServerMessage("YouGroupJoin"); // FOR TRANSLATION
                    } else if (WasHimself === false) { // OTHER USER JOINED A GROUP -> NOTIFY
                        ChatMessages.append("<br><div class='MessageWrapper'><div class='ServerChatMessage'>" + Username + "</span></div></div><br>");
                        ReplaceServerMessage("UserGroupJoin"); // FOR TRANSLATION
                    }
                } else if (ServerMessageType === "UserDisconnect") { // TYPE: USER DISCONNECTED -> NOTIFY
                    ChatMessages.append("<br><div class='MessageWrapper'><div class='ServerChatMessage'>" + Username + "</span></div></div><br>");
                    ReplaceServerMessage("UserDisconnect"); // FOR TRANSLATION
                } else if (ServerMessageType === "TypingState") { // SOMEBODY'S TYPING STATE CHANGED!
                    if (State === true) { // IF 'SOMEBODY' STARTED TYPING
                        if (WasHimself === true) { // IDENTIFY 'SOMEBODY' -> WAS HIMSELF -> NOT THAT IMPORTANT (USER KNOWS WHEN HE STARTS TYPING?)
                            // NOTHING
                        } else if (WasHimself === false) { // IDENTIFY 'SOMEBODY' -> WAS OTHER USER -> SHOW TYPING ANIMATION ON RECEIVER'S SIDE
                            ChatMessages.append("<div class='MessageWrapper'><div class='ChatMessage TypingIndicatorMessage AloneMessage'>" + TypingIndicatorAnimationElement + "</div></div>");
                            console.log("[SERVER REPORT] " + Username + " STARTED TYPING");
                        }
                    } else if (State === false) { // IF 'SOMEBODY' STOPPED TYPING
                        if (WasHimself === true) { // IDENTIFY 'SOMEBODY' -> WAS HIMSELF -> NOT THAT IMPORTANT (USER KNOWS WHEN HE STOPS TYPING?)
                            // NOTHING
                        } else if (WasHimself === false) { // IDENTIFY 'SOMEBODY' -> WAS OTHER USER -> REMOVE TYPING ANIMATION
                            //TypingIndicatorMessage.fadeOut("fast");
                            TypingIndicatorMessage.remove();
                            console.log("[SERVER REPORT] " + Username + " STOPPED TYPING");
                        }
                    }
                }
            }
            // SCROLL TO BOTTOM ON NEW MESSAGE OF ANY KIND
            ChatMessages.animate({scrollTop: document.querySelector("#ChatMessages").scrollHeight}, "slow");
        };


        // TYPING RECOGNITION
        var typingTimer;
        var isTyping = false;

        ChatTextInput.keydown(function () {
            sendStartTyping();
            clearTimeout(typingTimer);
        });

        ChatTextInput.keyup(function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function () {
                sendStopTyping()
            }, 2500)
        });

        function sendStartTyping() {
            if (isTyping === false) {
                sendTypingState(true);
                isTyping = true;
            }
        }

        function sendStopTyping() {
            if (isTyping === true) {
                sendTypingState(false);
                isTyping = false;
            }
        }

        function sendTypingState(state) { // SEND STATE TO CHAT SERVER
            ChatSocket.send(JSON.stringify({ClientMessageType: "TypingState", State: state}));
        }

        $(window).unload(function () {
            sendStopTyping(); // USER STOPS TYPING ON PAGE CLOSE ETC
        });

        // SUBSCRIBE TO CHAT
        SubscribeTextInput.keyup(function (e) {
            if (e.keyCode === 13 && SubscribeTextInput.val().length > 0) {
                subscribe(SubscribeTextInput.val());
            }
        });

        function subscribe(channel) {
            ChatSocket.send(JSON.stringify({ClientMessageType: "Subscribe", Channel: channel}));
            SubscribeTextInput.hide();
            ChatTextInput.show();
        }

        // SEND MESSAGE FROM INPUT FIELD
        ChatTextInput.keyup(function (e) {
            if (e.keyCode === 13 && ChatTextInput.val().length > 0) {
                ChatSocket.send(JSON.stringify({ClientMessageType: "Message", Message: ChatTextInput.val()}));
                ChatTextInput.val("");
                ChatTextInput.val("");

                // USER USUALLY STOPS TYPING ON SENDING -> CHANGE STATE TO FALSE
                sendTypingState(false);
                isTyping = false;
                clearTimeout(typingTimer);
            }
        });
    };
}

InitializeChatServer();