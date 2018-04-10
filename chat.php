<input title="MessageInput" id="MessageInput" type="text" placeholder="Message"/>
<input title="SubscribeInput" id="SubscribeInput" type="text" placeholder="Subscribe"/>
<div id="Response"></div>

<script src="https://code.jquery.com/jquery-latest.min.js"></script>
<script>
    function subscribe(channel) {
        conn.send(JSON.stringify({command: "subscribe", channel: channel}));
        $("#Response").empty();
    }

    function sendMessage(msg) {
        conn.send(JSON.stringify({command: "message", message: msg}));
        $("#MessageInput").val("");
    }

    var conn = new WebSocket('wss://marvinborner.ddnss.de:1337');
    conn.onopen = function () {
        console.log("Connection established!");
    };
    conn.onmessage = function (e) {
        document.getElementById("Response").innerHTML += e.data + "<br>";
    };
    $('#MessageInput').keyup(function (e) {
        if (e.keyCode === 13) {
            sendMessage($('#MessageInput').val());
            $('#MessageInput').val("");
        }
    });
    $('#SubscribeInput').keyup(function (e) {
        if (e.keyCode === 13) {
            subscribe($('#SubscribeInput').val());
        }
    });
</script>