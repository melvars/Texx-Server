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

// $.ajax({
//     type: "POST",
//     url: "keys/public/1",
//     data: {
//         "key": "test_key_2"
//     },
//     cache: false,
//     success: (results) => {
//         console.log(results);
//     }
// });



var socket = io('http://127.0.0.1:8890', {
    transports: ['websocket']
});
socket.on('message', (data) => {
    data = JSON.parse(data);
    $("#messages").append("<p>" + data.user + " : " + data.message + "</p>");
});

$('input.send').click((e) => {
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
            "message": message
        },
        cache: false
    });
}