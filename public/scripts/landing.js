/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
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
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

"use strict";
'use strict';
var STICKY_FIXED_OFFSET = 40;
var stickyEl = $('#value-proposition');
var featuresEl = $('#features');
var hasSticky = false;
var styckyOffsets = {
    min: 0,
    max: 0
};
function setSticky() {
    hasSticky = !stickyEl.parent().next().children('img').first().is(':hidden');
}
function setStickyOffsets() {
    styckyOffsets.min = stickyEl.offset().top - STICKY_FIXED_OFFSET;
    styckyOffsets.max = featuresEl.offset().top - stickyEl.height() - STICKY_FIXED_OFFSET * 2;
}
function setStickyState(state) {
    stickyEl
        .removeAttr('style');
    switch (state) {
        case 1:
            stickyEl
                .removeClass('sticky');
            break;
        case 2:
            stickyEl
                .addClass('sticky')
                .css({
                width: stickyEl.parent().width(),
                top: STICKY_FIXED_OFFSET
            });
            break;
        case 3:
            stickyEl
                .addClass('sticky')
                .css({
                top: 'auto',
                bottom: STICKY_FIXED_OFFSET,
                position: 'absolute',
                width: stickyEl.parent().width()
            });
            break;
    }
}
$(window)
    .on('resize', function () {
    setStickyOffsets();
    setSticky();
})
    .on('scroll', function (e) {
    if (!hasSticky) {
        return;
    }
    var pageScrollTop = $(e.target).scrollTop();
    if (pageScrollTop > styckyOffsets.max) {
        setStickyState(3);
    }
    else if (pageScrollTop > styckyOffsets.min) {
        setStickyState(2);
    }
    else {
        setStickyState(1);
    }
});
setStickyOffsets();
setSticky();


/***/ }
/******/ ]);
//# sourceMappingURL=landing.js.map