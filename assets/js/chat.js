var conn = new WebSocket('wss://marvinborner.ddnss.de:1337');
conn.onopen = function () {
    console.log("Chat connection established!");
};

conn.onmessage = function (e) {
    document.getElementById("ChatResponses").innerHTML += e.data + "<br>";
};

$('#ChatTextInput').keyup(function (e) {
    if (e.keyCode === 13) {
        sendMessage($('#ChatTextInput').val());
        $('#ChatTextInput').val("");
    }
});

$('#SubscribeTextInput').keyup(function (e) {
    if (e.keyCode === 13) {
        subscribe($('#SubscribeTextInput').val());
    }
});

function subscribe(channel) {
    conn.send(JSON.stringify({command: "subscribe", channel: channel}));
    $("#SubscribeTextInput").hide();
    $("#ChatTextInput").show();
    $("#ChatResponses").empty();
}

function sendMessage(msg) {
    conn.send(JSON.stringify({command: "message", message: msg}));
    $("#ChatTextInput").val("");
}
