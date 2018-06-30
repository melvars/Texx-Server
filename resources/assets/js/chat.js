var socket = io('http://127.0.0.1:8890', {
    transports: ['websocket']
});
socket.on('message', function (data) {
    data = JSON.parse(data);
    $("#messages").append("<p>" + data.user + " : " + data.message + "</p>");
});

$('input.send').click(function (e) {
    e.preventDefault();
    sendMessage();
});

function sendMessage() {
    var message = $('input.message').val();
    $.ajax({
        type: "POST",
        url: "sendMessage",
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "message": message
        },
        cache: false,
        success: function (results) {}
    });
}