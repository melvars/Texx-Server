$('form[keygen]').submit((event) => {
    event.preventDefault();

    $('button[type="submit"]').attr("disabled", true).html('Loading...');

    var openpgp = window.openpgp;

    var options = {
        userIds: [{
            email: $("input#email").val()
        }],
        numBits: 4096,
        passphrase: $("input#password").val()
    };

    openpgp.generateKey(options).then((key) => {
        var privateKey = key.privateKeyArmored;
        var publicKey = key.publicKeyArmored;

        localStorage.setItem("privkey", privateKey);

        var now = new Date();
        var time = now.getTime();
        time += 3600 * 1000;
        now.setTime(time);
        document.cookie = "publickey=" + encodeURI(publicKey.substr(96).slice(0, -35)) + "; expires=" + now.toUTCString() + ";";

        $('form[keygen]').unbind('submit').submit();
    });
});