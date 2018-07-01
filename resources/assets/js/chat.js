// var openpgp = window.openpgp;

// var options = {
//     userIds: [{ name:'Marvin Borner', email:'test@test.de' }], // multiple user IDs
//     numBits: 4096,                                            // RSA key size
//     passphrase: 'cool password of private key'
// };

// openpgp.generateKey(options).then(function(key) {
//     var privateKey = key.privateKeyArmored; // '-----BEGIN PGP PRIVATE KEY BLOCK ... '
//     var publicKey = key.publicKeyArmored;   // '-----BEGIN PGP PUBLIC KEY BLOCK ... '

//     console.log(publicKey);
//     console.log(privateKey);
// });



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
    $('input.message').val("");
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