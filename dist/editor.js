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
/******/ 	return __webpack_require__(__webpack_require__.s = "./component/editor.ts");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./component/block/list/index.tsx":
/*!****************************************!*\
  !*** ./component/block/list/index.tsx ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var AvorgBlockList;
(function (AvorgBlockList) {
    window.wp.blocks.registerBlockType('avorg/block-list', {
        title: 'Recordings List',
        icon: 'playlist-audio',
        category: 'widgets',
        attributes: {
            type: {
                type: 'string',
            }
        },
        edit: function (props) {
            var type = props.attributes.type, setAttributes = props.setAttributes;
            var InspectorControls = window.wp.editor.InspectorControls;
            var _a = window.wp.components, PanelBody = _a.PanelBody, PanelRow = _a.PanelRow, SelectControl = _a.SelectControl;
            return wp.element.createElement("p", { className: props.className },
                wp.element.createElement(InspectorControls, null,
                    wp.element.createElement(PanelBody, { title: "List Type" },
                        wp.element.createElement(PanelRow, null,
                            wp.element.createElement(SelectControl, { label: "List Type", value: type, options: [
                                    { value: 'recent', label: 'Recent' },
                                    { value: 'featured', label: 'Featured' },
                                    { value: 'popular', label: 'Popular' }
                                ], onChange: function (type) {
                                    setAttributes({ type: type });
                                } })))),
                "Recordings List: ",
                type);
        },
        save: function () { return null; }
    });
})(AvorgBlockList || (AvorgBlockList = {}));


/***/ }),

/***/ "./component/block/placeholder/index.tsx":
/*!***********************************************!*\
  !*** ./component/block/placeholder/index.tsx ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
var AvorgPlaceholder;
(function (AvorgPlaceholder) {
    window.wp.blocks.registerBlockType('avorg/block-placeholder', {
        title: 'Placeholder',
        icon: 'location',
        category: 'widgets',
        attributes: {
            id: {
                type: 'string'
            },
        },
        edit: function (props) {
            var id = props.attributes.id, isSelected = props.isSelected, setAttributes = props.setAttributes, className = props.className;
            var TextControl = window.wp.components.TextControl;
            fetch('/wp-json/avorg/v1/placeholder-ids').then(function (response) {
                return response.json();
            }).then(function (response) {
                var el = document.querySelector('#avorg_placeholder_suggestions');
                if (typeof response !== 'undefined' && response.length > 0 && el) {
                    el.innerHTML = response.map(function (s) {
                        return "<option value=\"" + s + "\" />";
                    }).join('');
                }
            });
            var form = wp.element.createElement("form", { onSubmit: function (event) { return event.preventDefault(); } },
                wp.element.createElement(TextControl, { placeholder: 'Placeholder Identifier', value: id, list: 'avorg_placeholder_suggestions', onChange: function (id) { return setAttributes({ id: id }); } }),
                wp.element.createElement("datalist", { id: 'avorg_placeholder_suggestions' },
                    wp.element.createElement("option", { value: 'Something' })));
            return wp.element.createElement("div", { className: className }, isSelected ? form : "Placeholder: " + id);
        },
        save: function () { return null; }
    });
})(AvorgPlaceholder || (AvorgPlaceholder = {}));


/***/ }),

/***/ "./component/block/relatedSermons/index.tsx":
/*!**************************************************!*\
  !*** ./component/block/relatedSermons/index.tsx ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var AvorgRelatedSermons;
(function (AvorgRelatedSermons) {
    window.wp.blocks.registerBlockType('avorg/block-relatedsermons', {
        title: 'Related Sermons',
        icon: 'excerpt-view',
        category: 'widgets',
        edit: function (props) {
            return wp.element.createElement("p", { className: props.className }, "Related Sermons");
        },
        save: function () { return null; }
    });
})(AvorgRelatedSermons || (AvorgRelatedSermons = {}));


/***/ }),

/***/ "./component/block/rss/index.tsx":
/*!***************************************!*\
  !*** ./component/block/rss/index.tsx ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
var AvorgBlockRss;
(function (AvorgBlockRss) {
    var blockStyle = {};
    var createLink = function (url) {
        return wp.element.createElement("a", { href: url, target: '_blank', rel: 'noopener noreferrer' },
            wp.element.createElement("svg", { "aria-hidden": "true", role: "img", focusable: "false", className: "dashicon dashicons-rss", xmlns: "http://www.w3.org/2000/svg", width: "20", height: "20", viewBox: "0 0 20 20" },
                wp.element.createElement("path", { d: "M14.92 18H18C18 9.32 10.82 2.25 2 2.25v3.02c7.12 0 12.92 5.71 12.92 12.73zm-5.44 0h3.08C12.56 12.27 7.82 7.6 2 7.6v3.02c2 0 3.87.77 5.29 2.16C8.7 14.17 9.48 16.03 9.48 18zm-5.35-.02c1.17 0 2.13-.93 2.13-2.09 0-1.15-.96-2.09-2.13-2.09-1.18 0-2.13.94-2.13 2.09 0 1.16.95 2.09 2.13 2.09z" })));
    };
    window.wp.blocks.registerBlockType('avorg/block-rss', {
        title: 'RSS Link',
        icon: 'rss',
        category: 'widgets',
        attributes: {
            url: {
                type: 'string',
                source: 'attribute',
                attribute: 'href',
                selector: 'a',
            },
        },
        edit: function (props) {
            var url = props.attributes.url, isSelected = props.isSelected, setAttributes = props.setAttributes;
            var URLInput = window.wp.editor.URLInput;
            var form = wp.element.createElement("form", { onSubmit: function (event) { return event.preventDefault(); } },
                wp.element.createElement(URLInput, { placeholder: 'RSS Url', value: url, onChange: function (url) { return setAttributes({ url: url }); } }));
            return wp.element.createElement("div", { style: blockStyle, className: props.className }, isSelected ? form : createLink(url));
        },
        save: function (props) {
            var url = props.attributes.url;
            return wp.element.createElement("div", { style: blockStyle }, createLink(url));
        },
    });
})(AvorgBlockRss || (AvorgBlockRss = {}));


/***/ }),

/***/ "./component/editor.ts":
/*!*****************************!*\
  !*** ./component/editor.ts ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
__webpack_require__(/*! ./block/list */ "./component/block/list/index.tsx");
__webpack_require__(/*! ./block/placeholder */ "./component/block/placeholder/index.tsx");
__webpack_require__(/*! ./block/relatedSermons */ "./component/block/relatedSermons/index.tsx");
__webpack_require__(/*! ./block/rss */ "./component/block/rss/index.tsx");
var editor_message = "EDITOR.TS BUNDLE";
console.log(editor_message);


/***/ })

/******/ });
//# sourceMappingURL=editor.js.map