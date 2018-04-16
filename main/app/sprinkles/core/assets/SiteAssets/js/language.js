function Translate() {
    //initialization
    this.init = function (lng) {
        this.attribute = 'data-lang';
        this.lng = lng;
    };
    //translate
    this.process = function () {
        var _self = this;
        var xrhFile = new XMLHttpRequest();
        //load content data
        xrhFile.open("GET", "assets/languages/json/Translations.json", true);
        xrhFile.onreadystatechange = function () {
            if (xrhFile.readyState === 4) {
                if (xrhFile.status === 200 || xrhFile.status === 0) {
                    var LngObject = JSON.parse(xrhFile.responseText);
                    var allDom = document.getElementsByTagName("*");
                    for (var i = 0; i < allDom.length; i++) {
                        var elem = allDom[i];
                        var key = elem.getAttribute(_self.attribute);

                        if (key != null) {
                            //console.log("Language initialized with language pack: " + _self.lng);
                            elem.innerHTML = LngObject[_self.lng][key];
                        }
                    }
                }
            }
        };
        xrhFile.send();
    }
}

$(document).ready(function () {
    initiateLanguage();
});
