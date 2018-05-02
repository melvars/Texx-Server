
/*! OpenPGP.js v3.0.9 - 2018-04-30 - this is LGPL licensed code, see LICENSE/our website https://openpgpjs.org/ for more information. */
!function e(n, r, t) {
    function o(a, f) {
        if (!r[a]) {
            if (!n[a]) {
                var u = "function" == typeof require && require;
                if (!f && u) return u(a, !0);
                if (i) return i(a, !0);
                var c = new Error("Cannot find module '" + a + "'");
                throw c.code = "MODULE_NOT_FOUND", c
            }
            var s = r[a] = {exports: {}};
            n[a][0].call(s.exports, function (e) {
                var r = n[a][1][e];
                return o(r || e)
            }, s, s.exports, e, n, r, t)
        }
        return r[a].exports
    }

    for (var i = "function" == typeof require && require, a = 0; a < t.length; a++) o(t[a]);
    return o
}({
    1: [function (e, n, r) {
        self.window = self, importScripts("encryption.js");
        var t = window.openpgp, o = [], i = 6e4;

        function a(e) {
            self.postMessage(e, t.util.getTransferables(e.data))
        }

        t.crypto.random.randomBuffer.init(i, function () {
            return o.length || self.postMessage({event: "request-seed", amount: i}), new Promise(function (e, n) {
                o.push(e)
            })
        }), self.onmessage = function (e) {
            var n = e.data || {};
            switch (n.event) {
                case"configure":
                    !function (e) {
                        for (var n in e) t.config[n] = e[n]
                    }(n.config);
                    break;
                case"seed-random":
                    !function (e) {
                        e instanceof Uint8Array || (e = new Uint8Array(e));
                        t.crypto.random.randomBuffer.set(e)
                    }(n.buf);
                    var r = o;
                    o = [];
                    for (var i = 0; i < r.length; i++) r[i]();
                    break;
                default:
                    !function (e, n, r) {
                        if ("function" != typeof t[n]) return void a({
                            id: e,
                            event: "method-return",
                            err: "Unknown Worker Event"
                        });
                        r = t.packet.clone.parseClonedPackets(r, n), t[n](r).then(function (n) {
                            a({id: e, event: "method-return", data: t.packet.clone.clonePackets(n)})
                        }).catch(function (n) {
                            a({id: e, event: "method-return", err: n.message, stack: n.stack})
                        })
                    }(n.id, n.event, n.options || {})
            }
        }
    }, {}]
}, {}, [1]);