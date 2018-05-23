/**
 * ENCRYPTION
 */
var ReceiversUsername, ReceiversId;
var openpgp = window.openpgp;
var options, EncryptedMessage, DecryptedMessage;
var PublicKey = [];
openpgp.initWorker({path: '/assets-raw/core/assets/SiteAssets/js/openpgp.worker.js'});
var privKeyObj = openpgp.key.readArmored(localStorage.getItem("PrivateKey").replace(/\r/, "")).keys[0];
privKeyObj.decrypt(localStorage.getItem("🔒"));

/**
 * GLOBAL DECLARATIONS
 */
var LastMessage, Username;


/**
 * GENERAL CHAT
 */
function InitializeChatServer() {
    var ChatTextInput = $("#ChatTextInput");
    var SubscribeTextInput = $("#SubscribeTextInput");
    var ChatMessages = $("#ChatMessages");
    var TypingIndicatorAnimationElement = "<div class='spinner'><div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div></div>";

    var WebSocketConnectTimerStart = performance.now(); // START CONNECTION EXECUTION TIMER
    const ChatSocket = new WebSocket('wss://marvinborner.ddnss.de:1337');
    ChatSocket.onerror = function () {
        setTimeout(function () {
            console.log("%c[CHATSOCKET LOGGER] Connection failed. Trying again...", "color: red");
            InitializeChatServer();
        }, 5000);
    };
    ChatSocket.onopen = function () {
        // CONNECTION SUCCESSFUL!
        var WebSocketConnectTimerEnd = performance.now(); // END CONNECTION EXECUTION TIMER
        console.log("%c[CHATSOCKET LOGGER] Chat connection established! (Took " + +(WebSocketConnectTimerEnd - WebSocketConnectTimerStart) + " milliseconds)", "color: darkorange");
        // START VERIFICATION
        ChatSocket.send(JSON.stringify({
            ClientMessageType: "Verify",
            Cookie: document.cookie,
            UserID: current_user_id
        }));
        console.log("%c[CHATSOCKET LOGGER] Started chat verification process...", "color: gray");
        // GOT MESSAGE
        ChatSocket.onmessage = function (e) {
            // DECLARATIONS
            var TypingIndicatorMessage = $(".TypingIndicatorMessage").parent();
            var MessageObject = JSON.parse(e.data);
            var Message = MessageObject.Message; // ENCRYPTED MESSAGE (NOT ENCRYPTED ON SERVER MESSAGES)
            var Fullname = MessageObject.Fullname;
            var Avatar = MessageObject.Avatar;
            var GroupName = MessageObject.GroupName;
            var State = MessageObject.State;
            var ServerMessage = MessageObject.ServerMessage;
            var WasHimself = MessageObject.WasHimself;
            var ServerMessageType = MessageObject.ServerMessageType;
            var Granted = MessageObject.Granted;
            var Success = MessageObject.Success;

            // GLOBAL OVERWRITES
            LastMessage = $(".MessageWrapper.Normal:last .ChatMessage");
            Username = MessageObject.Username;

            // GET OWN PUBLIC KEY FIRST
            if (!(current_username in PublicKey)) {
                $.ajax({
                    type: 'GET',
                    url: site.uri.public + '/api/users/u/' + current_username + '/publickey',
                    dataType: "json",
                    success: function (response) {
                        PublicKey[current_username] = response.PublicKey;
                        console.log("%c[ENCRYPTION LOGGER]\nPublickey of " + current_username + ": \n\n" + PublicKey[current_username].substr(96).slice(0, -35), "font-family: monospace; white-space: pre; display: inline-block; border-radius: 10px; padding: 5px; color: #20c20e; background-color: black;")
                    }
                });
            }

            // GET PUBLIC KEY IF NOT ALREADY DID
            if (!(ReceiversUsername in PublicKey) && ReceiversUsername !== null && ReceiversUsername !== undefined) {
                $.ajax({
                    type: 'GET',
                    url: site.uri.public + '/api/users/u/' + ReceiversUsername + '/publickey',
                    dataType: "json",
                    success: function (response) {
                        PublicKey[ReceiversUsername] = response.PublicKey;
                        console.log("%c[ENCRYPTION LOGGER]\nPublickey of " + ReceiversUsername + ": \n\n" + PublicKey[ReceiversUsername].substr(96).slice(0, -35), "font-family: monospace; white-space: pre; display: inline-block; border-radius: 10px; padding: 5px; color: #20c20e; background-color: black;")
                    }
                });
            }

            if (!ServerMessage) { // NO SERVER MESSAGE -> SENT BY USER
                // DECRYPT MESSAGE
                options = {
                    message: openpgp.message.readArmored("-----BEGIN PGP MESSAGE-----\r\nVersion: OpenPGP.js v3.0.9\r\nComment: https://openpgpjs.org\r\n\r\n" + Message + "\r\n\-----END PGP MESSAGE-----\r\n"), // FORMAT MESSAGE
                    publicKeys: openpgp.key.readArmored(PublicKey[Username]).keys, // FOR VERIFICATION
                    privateKeys: [privKeyObj]
                };
                openpgp.decrypt(options).then(function (plaintext) {
                    plaintext ? console.log("%c[ENCRYPTION LOGGER] Decrypting succeeded!", "font-family: monospace; white-space: pre; display: inline-block; border-radius: 10px; padding: 2px; color: #20c20e; background-color: black;") : console.log("%c[ENCRYPTION LOGGER] Decrypting failed!", "font-family: monospace; white-space: pre; display: inline-block; border-radius: 10px; padding: 2px; color: red; background-color: black;");
                    DecryptedMessage = plaintext.data;
                    if (WasHimself) { // -> MESSAGE WAS FROM HIMSELF -> Don't write to chat, as its done directly (on enter function at the bottom, for performance)
                        console.log("%c[CHATSOCKET LOGGER] Message sending succeeded!", "color: darkorange");
                    } else if (!WasHimself) { // -> MESSAGE WAS FROM OTHER USER -> decrypt
                        console.log("%c[CHATSOCKET LOGGER] You received a message!", "color: darkorange");
                        NotifySound.play();
                        Push.create(Fullname, { // CREATE NOTIFICATION
                            body: DecryptedMessage,
                            icon: Avatar,
                            timeout: 5000,
                            onClick: function () {
                                window.focus();
                                this.close();
                            }
                        });
                        if (!LastMessage.hasClass("MessageReceived")) { // CHECK IF PREVIOUS MESSAGE WAS FROM OTHER USER TOO -> IF NOT, CREATE NEW 'ALONE' MESSAGE
                            ChatMessages.append("<div class='MessageWrapper Normal'><div class='ChatMessage MessageReceived AloneMessage animated fadeInLeft'>" + DecryptedMessage + "</div></div>");
                        } else if (LastMessage.hasClass("MessageReceived")) { // IF PREVIOUS MESSAGE WAS FROM OTHER USER TOO -> CREATE WITH CORRESPONDING CLASSES FOR DESIGN
                            ChatMessages.append("<div class='MessageWrapper Normal'><div class='ChatMessage MessageReceived BottomMessage animated fadeInLeft'>" + DecryptedMessage + "</div></div>");
                            if (LastMessage.hasClass("AloneMessage")) {
                                LastMessage.removeClass("AloneMessage");
                                LastMessage.addClass("TopMessage");
                            } else if (LastMessage.hasClass("BottomMessage")) {
                                LastMessage.removeClass("BottomMessage");
                                LastMessage.addClass("MiddleMessage");
                            }
                        }
                    }
                });

                // CONVERT LINKS TO LINKS
                $('.ChatMessage').linkify({
                    target: "_blank"
                });
            } else if (ServerMessage) { // SERVER MESSAGE
                if (ServerMessageType === "GroupJoin") { // TYPE: USER JOINED A GROUP
                    if (WasHimself) { // HIMSELF JOINED A GROUP -> NOTIFY
                        ChatMessages.empty(); // -> EMPTY MESSAGES ON NEW GROUP JOIN
                        ChatMessages.append("<br><div class='MessageWrapper'><div class='ServerChatMessage'>" + GroupName + "</span></div></div><br>");
                        ReplaceServerMessage("YouGroupJoin"); // FOR TRANSLATION
                        console.log("%c[CHATSOCKET LOGGER] You joined the group " + GroupName + "!", "color: darkorange");
                    } else if (!WasHimself) { // OTHER USER JOINED A GROUP -> NOTIFY
                        ChatMessages.append("<br><div class='MessageWrapper'><div class='ServerChatMessage'>" + Username + "</span></div></div><br>");
                        ReplaceServerMessage("UserGroupJoin"); // FOR TRANSLATION
                        console.log("%c[CHATSOCKET LOGGER] " + Username + " joined the group!", "color: darkorange");
                    }
                } else if (ServerMessageType === "UserDisconnect") { // TYPE: USER DISCONNECTED -> NOTIFY
                    ChatMessages.append("<br><div class='MessageWrapper'><div class='ServerChatMessage'>" + Username + "</span></div></div><br>");
                    ReplaceServerMessage("UserDisconnect"); // FOR TRANSLATION
                    console.log("%c[CHATSOCKET LOGGER] " + Username + " disconnected from server!", "color: darkorange");
                } else if (ServerMessageType === "TypingState") { // TYPE: SOMEBODY'S TYPING STATE CHANGED!
                    if (State) { // IF 'SOMEBODY' STARTED TYPING
                        if (WasHimself) { // IDENTIFY 'SOMEBODY' -> WAS HIMSELF -> NOT THAT IMPORTANT (USER KNOWS WHEN HE STARTS TYPING?)
                            console.log("%c[CHAT TYPING LOGGER] You started typing!", "color: gray");
                        } else if (!WasHimself) { // IDENTIFY 'SOMEBODY' -> WAS OTHER USER -> SHOW TYPING ANIMATION ON RECEIVER'S SIDE
                            ChatMessages.append("<div class='MessageWrapper'><div class='ChatMessage TypingIndicatorMessage AloneMessage'>" + TypingIndicatorAnimationElement + "</div></div>");
                            console.log("%c[CHAT TYPING LOGGER] " + Username + " started typing!", "color: gray");
                        }
                    } else if (!State) { // IF 'SOMEBODY' STOPPED TYPING
                        if (WasHimself) { // IDENTIFY 'SOMEBODY' -> WAS HIMSELF -> NOT THAT IMPORTANT (USER KNOWS WHEN HE STOPS TYPING?)
                            console.log("%c[CHAT TYPING LOGGER] You stopped typing!", "color: gray");
                        } else if (!WasHimself) { // IDENTIFY 'SOMEBODY' -> WAS OTHER USER -> REMOVE TYPING ANIMATION
                            //TypingIndicatorMessage.fadeOut("fast");
                            TypingIndicatorMessage.remove();
                            console.log("%c[CHAT TYPING LOGGER] " + Username + " stopped typing!", "color: gray");
                        }
                    }
                } else if (ServerMessageType === "Verify") { // TYPE: SERVER CHECKED ACCESS -- MOSTLY HANDLED IN BACKEND
                    if (Granted) {
                        console.log("%c[CHATSOCKET LOGGER] Chat access granted!", "color: green");
                    } else if (!Granted) {
                        triggerErrorPopup("ChatNotAllowed");
                        console.log("%c[CHATSOCKET LOGGER] Chat access denied!", "color: red");
                    }
                } else if (ServerMessageType === "SetReceiver") { // TYPE: SERVER CHECKED ACCESS -- MOSTLY HANDLED IN BACKEND
                    if (Success) {
                        console.log("%c[CHATSOCKET LOGGER] Setting receiver succeeded!", "color: green");
                        $(".SelectReceiver").hide();
                        $(".SelectedReceiver > *").addClass("animated slideInRight");
                        $(".ChatTab .headerWrap .header .HeaderCaption").text(ReceiversUsername);
                        $(".SelectedReceiver").show();
                    } else if (!Success) {
                        console.log("%c[CHATSOCKET LOGGER] Setting receiver failed!", "color: red");
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
            if (!isTyping) {
                sendTypingState(true);
                isTyping = true;
            }
        }

        function sendStopTyping() {
            if (isTyping) {
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
            if (ChatSocket.readyState === 1) {
                if (e.keyCode === 13 && SubscribeTextInput.val().length > 0) {
                    subscribe(SubscribeTextInput.val());
                }
            } else {
                NotConnectedAnymore();
            }
        });

        function subscribe(channel) {
            //ChatSocket.send(JSON.stringify({ClientMessageType: "Subscribe", Channel: channel}));
            SubscribeTextInput.hide();
            ChatTextInput.show();
        }

        // SEND MESSAGE FROM INPUT FIELD
        ChatTextInput.keyup(function (e) {
            if (ChatSocket.readyState === 1) {
                if (e.keyCode === 13 && ChatTextInput.val().length > 0) {
                    const ChatTextInputText = ChatTextInput.val();
                    ChatTextInput.val("");
                    var LastMessage = $(".MessageWrapper.Normal:last .ChatMessage");
                    if (!LastMessage.hasClass("MessageSent")) { // CHECK IF PREVIOUS MESSAGE WAS FROM HIMSELF TOO -> IF NOT, CREATE NEW 'ALONE' MESSAGE
                        ChatMessages.append("<div class='MessageWrapper Normal'><div class='ChatMessage MessageSent AloneMessage animated fadeInRight'>" + ChatTextInputText + "</div></div>");
                    } else if (LastMessage.hasClass("MessageSent")) { // IF PREVIOUS MESSAGE WAS FROM HIMSELF TOO -> CREATE WITH CORRESPONDING CLASSES FOR DESIGN
                        ChatMessages.append("<div class='MessageWrapper Normal'><div class='ChatMessage MessageSent BottomMessage animated fadeInRight'>" + ChatTextInputText + "</div></div>");
                        if (LastMessage.hasClass("AloneMessage")) {
                            LastMessage.removeClass("AloneMessage");
                            LastMessage.addClass("TopMessage");
                        } else if (LastMessage.hasClass("BottomMessage")) {
                            LastMessage.removeClass("BottomMessage");
                            LastMessage.addClass("MiddleMessage");
                        }
                    }

                    // USER USUALLY STOPS TYPING ON SENDING -> CHANGE STATE TO FALSE
                    sendTypingState(false);
                    isTyping = false;
                    clearTimeout(typingTimer);

                    // ENCRYPT AND SEND MESSAGE WITH OWN PUBLIC KEY
                    options = {
                        data: ChatTextInputText,
                        publicKeys: openpgp.key.readArmored(PublicKey[current_username]).keys,
                        privateKeys: [privKeyObj] // FOR SIGNING
                    };
                    openpgp.encrypt(options).then(function (Encrypted) {
                        EncryptedMessage = Encrypted.data.substr(91).slice(0, -29); // SLICING FOR DATABASE SAVING (LESS DATA)
                        console.log("%c[ENCRYPTION LOGGER]\nEncrypted message for sender: \n\n" + EncryptedMessage, "font-family: monospace; white-space: pre; display: inline-block; border-radius: 10px; padding: 5px; color: #20c20e; background-color: black;");

                        ChatSocket.send(JSON.stringify({
                            ClientMessageType: "ChatMessage",
                            EncryptedWithKeyOfUsername: current_username,
                            Message: EncryptedMessage
                        }));
                    });

                    // ENCRYPT AND SEND MESSAGE WITH RECEIVERS PUBLIC KEY
                    options = {
                        data: ChatTextInputText,
                        publicKeys: openpgp.key.readArmored(PublicKey[ReceiversUsername]).keys,
                        privateKeys: [privKeyObj] // FOR SIGNING
                    };
                    openpgp.encrypt(options).then(function (Encrypted) {
                        EncryptedMessage = Encrypted.data.substr(91).slice(0, -29); // SLICING FOR DATABASE SAVING (LESS DATA)
                        console.log("%c[ENCRYPTION LOGGER]\nEncrypted message for receiver: \n\n" + EncryptedMessage, "font-family: monospace; white-space: pre; display: inline-block; border-radius: 10px; padding: 5px; color: #20c20e; background-color: black;");

                        ChatSocket.send(JSON.stringify({
                            ClientMessageType: "ChatMessage",
                            EncryptedWithKeyOfUsername: ReceiversUsername,
                            Message: EncryptedMessage
                        }));
                    });
                }
            } else {
                NotConnectedAnymore();
            }
        });

        function NotConnectedAnymore() {
            console.log("%c[CHATSOCKET LOGGER] Not connected to Websocket anymore! Trying to connect again...", "color: red");
            InitializeChatServer();
        }

        // SET RECEIVER
        $(document).on("click", ".ReceiverSelector", function () {
            ReceiversUsername = $(this).attr("data-username");
            ReceiversId = $(this).attr("data-id");
            ChatSocket.send(JSON.stringify({
                ClientMessageType: "SetReceiver",
                ReceiversId: ReceiversId,
                ReceiversUsername: ReceiversUsername
            }));
        });
    };
}

InitializeChatServer();