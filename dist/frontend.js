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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./component/frontend.ts");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./component/block/list/frontend.ts":
/*!******************************************!*\
  !*** ./component/block/list/frontend.ts ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
var shared_1 = __webpack_require__(/*! ./shared */ "./component/block/list/shared.ts");
shared_1.loadRecordings("wp-block-avorg-block-list");


/***/ }),

/***/ "./component/block/list/shared.ts":
/*!****************************************!*\
  !*** ./component/block/list/shared.ts ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
var recordingList_1 = __webpack_require__(/*! ../../molecule/recordingList */ "./component/molecule/recordingList/index.ts");
exports.loadRecordings = function (className) {
    var elements = document.querySelectorAll("." + className);
    elements.forEach(function (el) {
        var list = el.getAttribute('data-type') || '', url = "/api/presentation/" + list;
        fetch(url).then(function (response) {
            return response.json();
        }).then(function (response) {
            el.innerHTML = recordingList_1.default(response);
        });
    });
};


/***/ }),

/***/ "./component/block/placeholder/frontend.ts":
/*!*************************************************!*\
  !*** ./component/block/placeholder/frontend.ts ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var AvorgPlaceholder;
(function (AvorgPlaceholder) {
    var loadContent = function (className) {
        var elements = document.querySelectorAll("." + className);
        if (!elements)
            return;
        elements.forEach(function (el) {
            var identifier = el.getAttribute('data-id'), media_id = avorg.recordings ? avorg.recordings[0].id : '', url = "/wp-json/avorg/v1/placeholder-content/" + identifier + "/" + media_id;
            fetch(url).then(function (response) {
                return response.json();
            }).then(function (response) {
                console.log("matches for " + identifier, response);
                if (typeof response !== 'undefined' && response.length > 0) {
                    var i = Math.floor(Math.random() * response.length);
                    el.innerHTML = response[i].post_content;
                }
                else {
                    console.warn("No content found for placeholder ID '" + identifier + "'");
                    el.innerHTML = '';
                }
            });
        });
    };
    loadContent('wp-block-avorg-block-placeholder');
})(AvorgPlaceholder || (AvorgPlaceholder = {}));


/***/ }),

/***/ "./component/frontend.ts":
/*!*******************************!*\
  !*** ./component/frontend.ts ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
__webpack_require__(/*! ./block/list/frontend */ "./component/block/list/frontend.ts");
__webpack_require__(/*! ./block/placeholder/frontend */ "./component/block/placeholder/frontend.ts");
var text = "FRONTEND.TS BUNDLE";
console.log(text);


/***/ }),

/***/ "./component/molecule/mediaObject/index.ts":
/*!*************************************************!*\
  !*** ./component/molecule/mediaObject/index.ts ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
var molecule_mediaObject = function (title, titleUrl, secondLine, imgUrl, imgAlt) {
    var image = imgUrl ? "<img class=\"avorg-molecule-mediaObject__image\" src=\"" + imgUrl + "\" alt=\"" + imgAlt + "\" />" : '', titleLinkStart = titleUrl ? "<a href=\"" + titleUrl + "\">" : '', titleLinkEnd = titleUrl ? '</a>' : '';
    return "<li class=\"avorg-molecule-mediaObject\">\n    " + image + "\n    <div class=\"avorg-molecule-mediaObject__text\">\n        " + titleLinkStart + "\n        <h4 class=\"avorg-molecule-mediaObject__title\">" + title + "</h4>\n        " + titleLinkEnd + "\n        " + secondLine + "\n    </div>\n</li>";
};
exports.default = molecule_mediaObject;


/***/ }),

/***/ "./component/molecule/recordingList/index.ts":
/*!***************************************************!*\
  !*** ./component/molecule/recordingList/index.ts ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
var mediaObject_1 = __webpack_require__(/*! ../mediaObject */ "./component/molecule/mediaObject/index.ts");
var itemTemplate = function (recording) {
    var imageUrl = recording.presenters[0] ? recording.presenters[0].photo : null;
    var imageAlt = recording.presenters[0] ?
        recording.presenters[0].name.first + " " + recording.presenters[0].name.last + " " + recording.presenters[0].name.suffix : null;
    return mediaObject_1.default(recording.title, recording.url, recording.presentersString, imageUrl, imageAlt);
};
var molecule_recordingList = function (recordings) {
    return recordings.map(itemTemplate).join("");
};
exports.default = molecule_recordingList;


/***/ })

/******/ });
//# sourceMappingURL=frontend.js.map