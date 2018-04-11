<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>



<script src="assets/js/encryption.js"></script>
<script>
    // The passphrase used to repeatably generate this RSA key.
    var PassPhrase = randomString(16);
    // The length of the RSA key, in bits.
    var Bits = 1024;
    var MattsRSAkey = cryptico.generateRSAKey(PassPhrase, Bits);
    var MattsPublicKeyString = cryptico.publicKeyString(MattsRSAkey);

    var PlainText = "Hello Bro";
    var EncryptionResult = cryptico.encrypt(PlainText, MattsPublicKeyString);
    console.log(EncryptionResult.cipher);

    function randomString(len, charSet) {
        charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var randomString = '';
        for (var i = 0; i < len; i++) {
            var randomPoz = Math.floor(Math.random() * charSet.length);
            randomString += charSet.substring(randomPoz,randomPoz+1);
        }
        return randomString;
    }

</script>
</body>
</html>