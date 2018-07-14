/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 65);
/******/ })
/************************************************************************/
/******/ ({

/***/ 65:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(66);


/***/ }),

/***/ 66:
/***/ (function(module, exports) {

eval("$('form[keygen]').submit(function (event) {\n    event.preventDefault();\n\n    $('button[type=\"submit\"]').attr(\"disabled\", true).html('Loading...');\n\n    var openpgp = window.openpgp;\n\n    var options = {\n        userIds: [{\n            email: $(\"input#email\").val()\n        }],\n        numBits: 4096,\n        passphrase: $(\"input#password\").val()\n    };\n\n    openpgp.generateKey(options).then(function (key) {\n        var privateKey = key.privateKeyArmored;\n        var publicKey = key.publicKeyArmored;\n\n        localStorage.setItem(\"privkey\", privateKey);\n\n        var now = new Date();\n        var time = now.getTime();\n        time += 3600 * 1000;\n        now.setTime(time);\n        document.cookie = \"publickey=\" + encodeURI(publicKey.substr(96).slice(0, -35)) + \"; expires=\" + now.toUTCString() + \";\";\n\n        $('form[keygen]').unbind('submit').submit();\n    });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvYXNzZXRzL2pzL2luaXRpYWxfa2V5X2dlbi5qcz8xMDVlIl0sIm5hbWVzIjpbIiQiLCJzdWJtaXQiLCJldmVudCIsInByZXZlbnREZWZhdWx0IiwiYXR0ciIsImh0bWwiLCJvcGVucGdwIiwid2luZG93Iiwib3B0aW9ucyIsInVzZXJJZHMiLCJlbWFpbCIsInZhbCIsIm51bUJpdHMiLCJwYXNzcGhyYXNlIiwiZ2VuZXJhdGVLZXkiLCJ0aGVuIiwia2V5IiwicHJpdmF0ZUtleSIsInByaXZhdGVLZXlBcm1vcmVkIiwicHVibGljS2V5IiwicHVibGljS2V5QXJtb3JlZCIsImxvY2FsU3RvcmFnZSIsInNldEl0ZW0iLCJub3ciLCJEYXRlIiwidGltZSIsImdldFRpbWUiLCJzZXRUaW1lIiwiZG9jdW1lbnQiLCJjb29raWUiLCJlbmNvZGVVUkkiLCJzdWJzdHIiLCJzbGljZSIsInRvVVRDU3RyaW5nIiwidW5iaW5kIl0sIm1hcHBpbmdzIjoiQUFBQUEsRUFBRSxjQUFGLEVBQWtCQyxNQUFsQixDQUF5QixVQUFDQyxLQUFELEVBQVc7QUFDaENBLFVBQU1DLGNBQU47O0FBRUFILE1BQUUsdUJBQUYsRUFBMkJJLElBQTNCLENBQWdDLFVBQWhDLEVBQTRDLElBQTVDLEVBQWtEQyxJQUFsRCxDQUF1RCxZQUF2RDs7QUFFQSxRQUFJQyxVQUFVQyxPQUFPRCxPQUFyQjs7QUFFQSxRQUFJRSxVQUFVO0FBQ1ZDLGlCQUFTLENBQUM7QUFDTkMsbUJBQU9WLEVBQUUsYUFBRixFQUFpQlcsR0FBakI7QUFERCxTQUFELENBREM7QUFJVkMsaUJBQVMsSUFKQztBQUtWQyxvQkFBWWIsRUFBRSxnQkFBRixFQUFvQlcsR0FBcEI7QUFMRixLQUFkOztBQVFBTCxZQUFRUSxXQUFSLENBQW9CTixPQUFwQixFQUE2Qk8sSUFBN0IsQ0FBa0MsVUFBQ0MsR0FBRCxFQUFTO0FBQ3ZDLFlBQUlDLGFBQWFELElBQUlFLGlCQUFyQjtBQUNBLFlBQUlDLFlBQVlILElBQUlJLGdCQUFwQjs7QUFFQUMscUJBQWFDLE9BQWIsQ0FBcUIsU0FBckIsRUFBZ0NMLFVBQWhDOztBQUVBLFlBQUlNLE1BQU0sSUFBSUMsSUFBSixFQUFWO0FBQ0EsWUFBSUMsT0FBT0YsSUFBSUcsT0FBSixFQUFYO0FBQ0FELGdCQUFRLE9BQU8sSUFBZjtBQUNBRixZQUFJSSxPQUFKLENBQVlGLElBQVo7QUFDQUcsaUJBQVNDLE1BQVQsR0FBa0IsZUFBZUMsVUFBVVgsVUFBVVksTUFBVixDQUFpQixFQUFqQixFQUFxQkMsS0FBckIsQ0FBMkIsQ0FBM0IsRUFBOEIsQ0FBQyxFQUEvQixDQUFWLENBQWYsR0FBK0QsWUFBL0QsR0FBOEVULElBQUlVLFdBQUosRUFBOUUsR0FBa0csR0FBcEg7O0FBRUFqQyxVQUFFLGNBQUYsRUFBa0JrQyxNQUFsQixDQUF5QixRQUF6QixFQUFtQ2pDLE1BQW5DO0FBQ0gsS0FiRDtBQWNILENBN0JEIiwiZmlsZSI6IjY2LmpzIiwic291cmNlc0NvbnRlbnQiOlsiJCgnZm9ybVtrZXlnZW5dJykuc3VibWl0KChldmVudCkgPT4ge1xuICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cbiAgICAkKCdidXR0b25bdHlwZT1cInN1Ym1pdFwiXScpLmF0dHIoXCJkaXNhYmxlZFwiLCB0cnVlKS5odG1sKCdMb2FkaW5nLi4uJyk7XG5cbiAgICB2YXIgb3BlbnBncCA9IHdpbmRvdy5vcGVucGdwO1xuXG4gICAgdmFyIG9wdGlvbnMgPSB7XG4gICAgICAgIHVzZXJJZHM6IFt7XG4gICAgICAgICAgICBlbWFpbDogJChcImlucHV0I2VtYWlsXCIpLnZhbCgpXG4gICAgICAgIH1dLFxuICAgICAgICBudW1CaXRzOiA0MDk2LFxuICAgICAgICBwYXNzcGhyYXNlOiAkKFwiaW5wdXQjcGFzc3dvcmRcIikudmFsKClcbiAgICB9O1xuXG4gICAgb3BlbnBncC5nZW5lcmF0ZUtleShvcHRpb25zKS50aGVuKChrZXkpID0+IHtcbiAgICAgICAgdmFyIHByaXZhdGVLZXkgPSBrZXkucHJpdmF0ZUtleUFybW9yZWQ7XG4gICAgICAgIHZhciBwdWJsaWNLZXkgPSBrZXkucHVibGljS2V5QXJtb3JlZDtcblxuICAgICAgICBsb2NhbFN0b3JhZ2Uuc2V0SXRlbShcInByaXZrZXlcIiwgcHJpdmF0ZUtleSk7XG5cbiAgICAgICAgdmFyIG5vdyA9IG5ldyBEYXRlKCk7XG4gICAgICAgIHZhciB0aW1lID0gbm93LmdldFRpbWUoKTtcbiAgICAgICAgdGltZSArPSAzNjAwICogMTAwMDtcbiAgICAgICAgbm93LnNldFRpbWUodGltZSk7XG4gICAgICAgIGRvY3VtZW50LmNvb2tpZSA9IFwicHVibGlja2V5PVwiICsgZW5jb2RlVVJJKHB1YmxpY0tleS5zdWJzdHIoOTYpLnNsaWNlKDAsIC0zNSkpICsgXCI7IGV4cGlyZXM9XCIgKyBub3cudG9VVENTdHJpbmcoKSArIFwiO1wiO1xuXG4gICAgICAgICQoJ2Zvcm1ba2V5Z2VuXScpLnVuYmluZCgnc3VibWl0Jykuc3VibWl0KCk7XG4gICAgfSk7XG59KTtcblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9yZXNvdXJjZXMvYXNzZXRzL2pzL2luaXRpYWxfa2V5X2dlbi5qcyJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///66\n");

/***/ })

/******/ });