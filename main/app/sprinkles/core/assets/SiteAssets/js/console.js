/**
 * File for some things logged in console // TODO: Create Error/Success logger functions so we don't need to write the whole log things several times
 */

console.log("%s%c%s%c%s%c%s%c%s%c%s%c",
    " ____   _____     _     __  __ \n" +
    "| __ ) | ____|   / \\   |  \\/  |\n" +
    "|  _ \\ |  _|    / _ \\  | |\\/| |\n" +
    "| |_) || |___  / ___ \\ | |  | |\n" +
    "|____/ |_____|/_/   \\_\\|_|  |_|\n",
    "whitespace: pre;",
    "Hello, world! Thanks for checking our code.\nAs this project is completely open source it would be an better option to look here:\n",
    "color: blue;", "https://github.com/marvinborner/BEAM-Messenger\n\n",
    "", "...or even clone the entire repo with:\n",
    "border-radius: 100px; padding: 2px; background-color: black; color: white;", "git clone https://github.com/marvinborner/BEAM-Messenger.git\n",
    "", "\nWe are also thankful for every contributor we have, so if you have any idea, fix or anything else - feel free to create a pull request.",
    "font-family: monospace;");

function playGame() {
    var a, b, d, e, f, g, h, k, l, m, n, p, q, r;
    h = "<0>-----------------------<0>--------------";
    f = [0, 20];
    p = [];
    n = !1;
    m = d = 0;
    q = 1;
    e = function (c) {
        var s;
        s = p[c[0]].substr(c[1] + 1, 1);
        p[c[0]] = p[c[0]].substr(0, c[1]) + "X" + p[c[0]].substr(c[1] + 1);
        return s
    };
    b = function (c) {
        return /[<0>]/.test(c)
    };
    k = function () {
        n = !0;
        return setTimeout(function () {
            alert("Oops, you got squashed.\n\nTry again");
            n = !1;
            f = [0, 20];
            return q++
        }, 60)
    };
    r = function () {
        n = !0;
        setTimeout(function () {
            alert("CONGRATULATIONS! You made it! Nice.\n\nIt took you " + q + " tries, " + m + " moves, and " + d + " draw cycles");
            n = !1;
            m = d = 0;
            q = 1;
            return f = [0, 20]
        }, 60)
    };
    g = null;
    l = {
        38: function () {
            if (f[0] < p.length - 1) return f[0]++
        }, 40: function () {
            if (0 < f[0]) return f[0]--
        }, 37: function () {
            if (0 < f[1]) return f[1]--
        }, 39: function () {
            if (f[1] < h.length - 1) return f[1]++
        }
    };
    document.onkeydown = function (c) {
        g = c.keyCode;
        return m++
    };
    a = setInterval(function () {
        var c;
        if (!n) if (d++, l[g] && (l[g](), g = null), 27 === g) clearInterval(a); else if (h = h.substr(-1) + h.substr(0, h.length - 1), p = ["ooooooooooooooooooooooooooooooooooooooooooo", h, h.split("").reverse().join(""), h.substr(-11) + h.substr(0, h.length - 11), h.split("").reverse().join("").substr(-11) + h.split("").reverse().join("").substr(0, h.length - 11), "ooooooooooooooooooooooooooooooooooooooooooo"], c = e(f), console.clear(), console.log("\n\nYou found a secret game!\nTake a break from coding and play it! :)\n\nYour Goal: Use the arrow keys to move the 'X' across the street and avoid the cars - just ignore the rest of the screen.\nPress 'Esc' to stop the game.\n\n(Note, if your cursor is in the console you'll need to click on the page outside the console so the arrow keys will work)\n\n\n"), console.log(p[5]), console.log(p[4]), console.log(p[3]), console.log(p[2]), console.log(p[1]), console.log(p[0]), console.log("\n\nMade by @jschomay"), f[0] >= p.length - 1 && r(), b(c)) return k()
    }, 60);
}