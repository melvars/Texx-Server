/**
 * GENERAL CHAT
 */
function InitializeChatServer() {
    var ChatTextInput = $("#ChatTextInput");
    var SubscribeTextInput = $("#SubscribeTextInput");
    var ChatMessages = $("#ChatMessages");
    var TypingIndicatorAnimationElement = "<div class='spinner'><div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div></div>";

    var ChatSocket = new WebSocket('wss://marvinborner.ddnss.de:1337');
    ChatSocket.onerror = function () {
        setTimeout(function () {
            console.log("%c[CHATSOCKET LOGGER] Connection failed. Trying again...", "color: red");
            InitializeChatServer();
        }, 5000);
    };
    ChatSocket.onopen = function () {
        // CONNECTION SUCCESSFUL!
        console.log("%c[CHATSOCKET LOGGER] Chat connection established!", "color: darkorange");
        // START VERIFICATION
        ChatSocket.send(JSON.stringify({
            ClientMessageType: "Verify",
            Cookie: document.cookie,
            UserID: current_user_id
        }));
        console.log("%c[CHATSOCKET LOGGER] Started chat verification process...", "color: grey");
        // GOT MESSAGE
        ChatSocket.onmessage = function (e) {
            // DECLARATIONS
            var TypingIndicatorMessage = $(".TypingIndicatorMessage").parent();
            var LastMessage = $(".MessageWrapper.Normal:last .ChatMessage");
            var MessageObject = JSON.parse(e.data);
            var Message = MessageObject.Message;
            var Username = MessageObject.Username;
            var Fullname = MessageObject.Fullname;
            var Avatar = MessageObject.Avatar;
            var GroupName = MessageObject.GroupName;
            var State = MessageObject.State;
            var ServerMessage = MessageObject.ServerMessage;
            var WasHimself = MessageObject.WasHimself;
            var ServerMessageType = MessageObject.ServerMessageType;
            var Granted = MessageObject.Granted;

            if (ServerMessage === false) { // NO SERVER MESSAGE -> SENT BY USER
                if (WasHimself === true) { // -> MESSAGE WAS FROM HIMSELF
                    console.log("%c[CHATSOCKET LOGGER] You sent a message!", "color: darkorange");
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
                    console.log("%c[CHATSOCKET LOGGER] You received a message!", "color: darkorange");
                    NotifySound.play();
                    Push.create(Fullname, { // CREATE NOTIFICATION
                        body: Message,
                        icon: Avatar,
                        timeout: 5000,
                        onClick: function () {
                            window.focus();
                            this.close();
                        }
                    });
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
                        console.log("%c[CHATSOCKET LOGGER] You joined the group " + GroupName + "!", "color: darkorange");
                    } else if (WasHimself === false) { // OTHER USER JOINED A GROUP -> NOTIFY
                        ChatMessages.append("<br><div class='MessageWrapper'><div class='ServerChatMessage'>" + Username + "</span></div></div><br>");
                        ReplaceServerMessage("UserGroupJoin"); // FOR TRANSLATION
                        console.log("%c[CHATSOCKET LOGGER] " + Username + " joined the group!", "color: darkorange");
                    }
                } else if (ServerMessageType === "UserDisconnect") { // TYPE: USER DISCONNECTED -> NOTIFY
                    ChatMessages.append("<br><div class='MessageWrapper'><div class='ServerChatMessage'>" + Username + "</span></div></div><br>");
                    ReplaceServerMessage("UserDisconnect"); // FOR TRANSLATION
                    console.log("%c[CHATSOCKET LOGGER] " + Username + " disconnected from server!", "color: darkorange");
                } else if (ServerMessageType === "TypingState") { // TYPE: SOMEBODY'S TYPING STATE CHANGED!
                    if (State === true) { // IF 'SOMEBODY' STARTED TYPING
                        if (WasHimself === true) { // IDENTIFY 'SOMEBODY' -> WAS HIMSELF -> NOT THAT IMPORTANT (USER KNOWS WHEN HE STARTS TYPING?)
                            console.log("%c[CHAT TYPING LOGGER] You started typing!", "color: grey");
                        } else if (WasHimself === false) { // IDENTIFY 'SOMEBODY' -> WAS OTHER USER -> SHOW TYPING ANIMATION ON RECEIVER'S SIDE
                            ChatMessages.append("<div class='MessageWrapper'><div class='ChatMessage TypingIndicatorMessage AloneMessage'>" + TypingIndicatorAnimationElement + "</div></div>");
                            console.log("%c[CHAT TYPING LOGGER] " + Username + " started typing!", "color: grey");
                        }
                    } else if (State === false) { // IF 'SOMEBODY' STOPPED TYPING
                        if (WasHimself === true) { // IDENTIFY 'SOMEBODY' -> WAS HIMSELF -> NOT THAT IMPORTANT (USER KNOWS WHEN HE STOPS TYPING?)
                            console.log("%c[CHAT TYPING LOGGER] You stopped typing!", "color: grey");
                        } else if (WasHimself === false) { // IDENTIFY 'SOMEBODY' -> WAS OTHER USER -> REMOVE TYPING ANIMATION
                            //TypingIndicatorMessage.fadeOut("fast");
                            TypingIndicatorMessage.remove();
                            console.log("%c[CHAT TYPING LOGGER] " + Username + " stopped typing!", "color: grey");
                        }
                    }
                } else if (ServerMessageType === "Verify") { // TYPE: SERVER CHECKED ACCESS -- MOSTLY HANDLED IN BACKEND
                    if (Granted === true) {
                        console.log("%c[CHATSOCKET LOGGER] Chat access granted!", "color: green");
                    } else if (Granted === false) {
                        console.log("%c[CHATSOCKET LOGGER] Chat access denied!", "color: red");
                    }
                }
            }
            // SCROLL TO BOTTOM ON NEW MESSAGE OF ANY KIND
            if ((ChatMessages.scrollTop() + ChatMessages.innerHeight() < ChatMessages[0].scrollHeight)) {
                ChatMessages.animate({scrollTop: document.querySelector("#ChatMessages").scrollHeight});
            }
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
                // USER USUALLY STOPS TYPING ON SENDING -> CHANGE STATE TO FALSE
                sendTypingState(false);
                isTyping = false;
                clearTimeout(typingTimer);

                ChatSocket.send(JSON.stringify({
                    ClientMessageType: "ChatMessage",
                    MessageType: "Private",
                    Message: ChatTextInput.val()
                }));
                ChatTextInput.val("");
                ChatTextInput.val("");
            }
        });
    };
}

InitializeChatServer();