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
    var PassPhrase = "The Moon is a Harsh Mistress.";

    // The length of the RSA key, in bits.
    var Bits = 1024;

    var MattsRSAkey = cryptico.generateRSAKey(PassPhrase, Bits);
    var MattsPublicKeyString = cryptico.publicKeyString(MattsRSAkey);

    var PlainText = "Matt, I need you to help me with my Starcraft strategy.";
    var EncryptionResult = cryptico.encrypt(PlainText, MattsPublicKeyString);

    console.log(EncryptionResult.cipher);
</script>
</body>
</html>