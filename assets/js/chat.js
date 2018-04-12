/************
 GENERATE KEYS
 ************/
if (localStorage.getItem('KeysGenerated') === null || localStorage.getItem('KeysGenerated') !== "true") {
    // GENERATE -- LATER ON LOGIN!
    var EncryptionPhrase = "PASSWORD 123"; // THE USERS PASSWORD
    var RSABitLength = 1024;
    var PrivateKeyString = cryptico.generateRSAKey(EncryptionPhrase, RSABitLength);
    var PublicKeyString = cryptico.publicKeyString(PrivateKeyString);
    // SAVE TO DATABASE
    $.ajax({
        type: "POST",
        url: "assets/php/SavePublicKey.php",
        data: {
            UserID: "1", // TEMPORARY
            PublicKeyString: PublicKeyString
        },
        async: true,
        error: function () {
            console.error("Error while saving public key to database!");
        },
        success: function () {
            localStorage.setItem('KeysGenerated', "true");
        }
    });
}


/******
 GENERAL
 ******/

var ChatTextInput = $("#ChatTextInput");
var SubscribeTextInput = $("#SubscribeTextInput");
var ChatResponses = $("#ChatResponses");

var WebSocket = new WebSocket('wss://marvinborner.ddnss.de:1337');
WebSocket.onopen = function () {
    console.log("Chat connection established!");
};
WebSocket.onmessage = function (e) {
    var MessageObject = JSON.parse(e.data);
    if (MessageObject.ServerMessage === false) {
        ChatResponses.append(MessageObject.Username + " - " + MessageObject.Message + "<br>");
    } else if (MessageObject.ServerMessage === true) {
        if (MessageObject.ServerMessageType === "GroupJoin") {
            if (MessageObject.WasHimself === false) {
                ChatResponses.append(MessageObject.Username + " joined the group. <br>");
            } else if (MessageObject.WasHimself === true) {
                ChatResponses.empty();
                ChatResponses.append("You joined the group " + MessageObject.GroupName + ".<br>");
            }
        } else if (MessageObject.ServerMessageType === "UserDisconnect") {
            ChatResponses.append(MessageObject.Username + " disconnected from the Server.");
        }
    }
};

ChatTextInput.keyup(function (e) {
    if (e.keyCode === 13) {
        sendMessage(ChatTextInput.val());
        ChatTextInput.val("");
    }
});

SubscribeTextInput.keyup(function (e) {
    if (e.keyCode === 13) {
        subscribe(SubscribeTextInput.val());
    }
});

function subscribe(channel) {
    WebSocket.send(JSON.stringify({ClientMessageType: "Subscribe", Channel: channel}));
    SubscribeTextInput.hide();
    ChatTextInput.show();
}

function sendMessage(msg) {
    WebSocket.send(JSON.stringify({ClientMessageType: "Message", Message: msg}));
    ChatTextInput.val("");
}
