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
/******/ 	return __webpack_require__(__webpack_require__.s = 8);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(process, global) {var require;/*** IMPORTS FROM imports-loader ***/
(function() {

/*!
 * @overview es6-promise - a tiny implementation of Promises/A+.
 * @copyright Copyright (c) 2014 Yehuda Katz, Tom Dale, Stefan Penner and contributors (Conversion to ES6 API by Jake Archibald)
 * @license   Licensed under MIT license
 *            See https://raw.githubusercontent.com/stefanpenner/es6-promise/master/LICENSE
 * @version   4.1.0
 */

(function (global, factory) {
     true ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global.ES6Promise = factory());
}(this, (function () { 'use strict';

function objectOrFunction(x) {
  return typeof x === 'function' || typeof x === 'object' && x !== null;
}

function isFunction(x) {
  return typeof x === 'function';
}

var _isArray = undefined;
if (!Array.isArray) {
  _isArray = function (x) {
    return Object.prototype.toString.call(x) === '[object Array]';
  };
} else {
  _isArray = Array.isArray;
}

var isArray = _isArray;

var len = 0;
var vertxNext = undefined;
var customSchedulerFn = undefined;

var asap = function asap(callback, arg) {
  queue[len] = callback;
  queue[len + 1] = arg;
  len += 2;
  if (len === 2) {
    // If len is 2, that means that we need to schedule an async flush.
    // If additional callbacks are queued before the queue is flushed, they
    // will be processed by this flush that we are scheduling.
    if (customSchedulerFn) {
      customSchedulerFn(flush);
    } else {
      scheduleFlush();
    }
  }
};

function setScheduler(scheduleFn) {
  customSchedulerFn = scheduleFn;
}

function setAsap(asapFn) {
  asap = asapFn;
}

var browserWindow = typeof window !== 'undefined' ? window : undefined;
var browserGlobal = browserWindow || {};
var BrowserMutationObserver = browserGlobal.MutationObserver || browserGlobal.WebKitMutationObserver;
var isNode = typeof self === 'undefined' && typeof process !== 'undefined' && ({}).toString.call(process) === '[object process]';

// test for web worker but not in IE10
var isWorker = typeof Uint8ClampedArray !== 'undefined' && typeof importScripts !== 'undefined' && typeof MessageChannel !== 'undefined';

// node
function useNextTick() {
  // node version 0.10.x displays a deprecation warning when nextTick is used recursively
  // see https://github.com/cujojs/when/issues/410 for details
  return function () {
    return process.nextTick(flush);
  };
}

// vertx
function useVertxTimer() {
  if (typeof vertxNext !== 'undefined') {
    return function () {
      vertxNext(flush);
    };
  }

  return useSetTimeout();
}

function useMutationObserver() {
  var iterations = 0;
  var observer = new BrowserMutationObserver(flush);
  var node = document.createTextNode('');
  observer.observe(node, { characterData: true });

  return function () {
    node.data = iterations = ++iterations % 2;
  };
}

// web worker
function useMessageChannel() {
  var channel = new MessageChannel();
  channel.port1.onmessage = flush;
  return function () {
    return channel.port2.postMessage(0);
  };
}

function useSetTimeout() {
  // Store setTimeout reference so es6-promise will be unaffected by
  // other code modifying setTimeout (like sinon.useFakeTimers())
  var globalSetTimeout = setTimeout;
  return function () {
    return globalSetTimeout(flush, 1);
  };
}

var queue = new Array(1000);
function flush() {
  for (var i = 0; i < len; i += 2) {
    var callback = queue[i];
    var arg = queue[i + 1];

    callback(arg);

    queue[i] = undefined;
    queue[i + 1] = undefined;
  }

  len = 0;
}

function attemptVertx() {
  try {
    var r = require;
    var vertx = __webpack_require__(17);
    vertxNext = vertx.runOnLoop || vertx.runOnContext;
    return useVertxTimer();
  } catch (e) {
    return useSetTimeout();
  }
}

var scheduleFlush = undefined;
// Decide what async method to use to triggering processing of queued callbacks:
if (isNode) {
  scheduleFlush = useNextTick();
} else if (BrowserMutationObserver) {
  scheduleFlush = useMutationObserver();
} else if (isWorker) {
  scheduleFlush = useMessageChannel();
} else if (browserWindow === undefined && "function" === 'function') {
  scheduleFlush = attemptVertx();
} else {
  scheduleFlush = useSetTimeout();
}

function then(onFulfillment, onRejection) {
  var _arguments = arguments;

  var parent = this;

  var child = new this.constructor(noop);

  if (child[PROMISE_ID] === undefined) {
    makePromise(child);
  }

  var _state = parent._state;

  if (_state) {
    (function () {
      var callback = _arguments[_state - 1];
      asap(function () {
        return invokeCallback(_state, child, callback, parent._result);
      });
    })();
  } else {
    subscribe(parent, child, onFulfillment, onRejection);
  }

  return child;
}

/**
  `Promise.resolve` returns a promise that will become resolved with the
  passed `value`. It is shorthand for the following:

  ```javascript
  let promise = new Promise(function(resolve, reject){
    resolve(1);
  });

  promise.then(function(value){
    // value === 1
  });
  ```

  Instead of writing the above, your code now simply becomes the following:

  ```javascript
  let promise = Promise.resolve(1);

  promise.then(function(value){
    // value === 1
  });
  ```

  @method resolve
  @static
  @param {Any} value value that the returned promise will be resolved with
  Useful for tooling.
  @return {Promise} a promise that will become fulfilled with the given
  `value`
*/
function resolve(object) {
  /*jshint validthis:true */
  var Constructor = this;

  if (object && typeof object === 'object' && object.constructor === Constructor) {
    return object;
  }

  var promise = new Constructor(noop);
  _resolve(promise, object);
  return promise;
}

var PROMISE_ID = Math.random().toString(36).substring(16);

function noop() {}

var PENDING = void 0;
var FULFILLED = 1;
var REJECTED = 2;

var GET_THEN_ERROR = new ErrorObject();

function selfFulfillment() {
  return new TypeError("You cannot resolve a promise with itself");
}

function cannotReturnOwn() {
  return new TypeError('A promises callback cannot return that same promise.');
}

function getThen(promise) {
  try {
    return promise.then;
  } catch (error) {
    GET_THEN_ERROR.error = error;
    return GET_THEN_ERROR;
  }
}

function tryThen(then, value, fulfillmentHandler, rejectionHandler) {
  try {
    then.call(value, fulfillmentHandler, rejectionHandler);
  } catch (e) {
    return e;
  }
}

function handleForeignThenable(promise, thenable, then) {
  asap(function (promise) {
    var sealed = false;
    var error = tryThen(then, thenable, function (value) {
      if (sealed) {
        return;
      }
      sealed = true;
      if (thenable !== value) {
        _resolve(promise, value);
      } else {
        fulfill(promise, value);
      }
    }, function (reason) {
      if (sealed) {
        return;
      }
      sealed = true;

      _reject(promise, reason);
    }, 'Settle: ' + (promise._label || ' unknown promise'));

    if (!sealed && error) {
      sealed = true;
      _reject(promise, error);
    }
  }, promise);
}

function handleOwnThenable(promise, thenable) {
  if (thenable._state === FULFILLED) {
    fulfill(promise, thenable._result);
  } else if (thenable._state === REJECTED) {
    _reject(promise, thenable._result);
  } else {
    subscribe(thenable, undefined, function (value) {
      return _resolve(promise, value);
    }, function (reason) {
      return _reject(promise, reason);
    });
  }
}

function handleMaybeThenable(promise, maybeThenable, then$$) {
  if (maybeThenable.constructor === promise.constructor && then$$ === then && maybeThenable.constructor.resolve === resolve) {
    handleOwnThenable(promise, maybeThenable);
  } else {
    if (then$$ === GET_THEN_ERROR) {
      _reject(promise, GET_THEN_ERROR.error);
      GET_THEN_ERROR.error = null;
    } else if (then$$ === undefined) {
      fulfill(promise, maybeThenable);
    } else if (isFunction(then$$)) {
      handleForeignThenable(promise, maybeThenable, then$$);
    } else {
      fulfill(promise, maybeThenable);
    }
  }
}

function _resolve(promise, value) {
  if (promise === value) {
    _reject(promise, selfFulfillment());
  } else if (objectOrFunction(value)) {
    handleMaybeThenable(promise, value, getThen(value));
  } else {
    fulfill(promise, value);
  }
}

function publishRejection(promise) {
  if (promise._onerror) {
    promise._onerror(promise._result);
  }

  publish(promise);
}

function fulfill(promise, value) {
  if (promise._state !== PENDING) {
    return;
  }

  promise._result = value;
  promise._state = FULFILLED;

  if (promise._subscribers.length !== 0) {
    asap(publish, promise);
  }
}

function _reject(promise, reason) {
  if (promise._state !== PENDING) {
    return;
  }
  promise._state = REJECTED;
  promise._result = reason;

  asap(publishRejection, promise);
}

function subscribe(parent, child, onFulfillment, onRejection) {
  var _subscribers = parent._subscribers;
  var length = _subscribers.length;

  parent._onerror = null;

  _subscribers[length] = child;
  _subscribers[length + FULFILLED] = onFulfillment;
  _subscribers[length + REJECTED] = onRejection;

  if (length === 0 && parent._state) {
    asap(publish, parent);
  }
}

function publish(promise) {
  var subscribers = promise._subscribers;
  var settled = promise._state;

  if (subscribers.length === 0) {
    return;
  }

  var child = undefined,
      callback = undefined,
      detail = promise._result;

  for (var i = 0; i < subscribers.length; i += 3) {
    child = subscribers[i];
    callback = subscribers[i + settled];

    if (child) {
      invokeCallback(settled, child, callback, detail);
    } else {
      callback(detail);
    }
  }

  promise._subscribers.length = 0;
}

function ErrorObject() {
  this.error = null;
}

var TRY_CATCH_ERROR = new ErrorObject();

function tryCatch(callback, detail) {
  try {
    return callback(detail);
  } catch (e) {
    TRY_CATCH_ERROR.error = e;
    return TRY_CATCH_ERROR;
  }
}

function invokeCallback(settled, promise, callback, detail) {
  var hasCallback = isFunction(callback),
      value = undefined,
      error = undefined,
      succeeded = undefined,
      failed = undefined;

  if (hasCallback) {
    value = tryCatch(callback, detail);

    if (value === TRY_CATCH_ERROR) {
      failed = true;
      error = value.error;
      value.error = null;
    } else {
      succeeded = true;
    }

    if (promise === value) {
      _reject(promise, cannotReturnOwn());
      return;
    }
  } else {
    value = detail;
    succeeded = true;
  }

  if (promise._state !== PENDING) {
    // noop
  } else if (hasCallback && succeeded) {
      _resolve(promise, value);
    } else if (failed) {
      _reject(promise, error);
    } else if (settled === FULFILLED) {
      fulfill(promise, value);
    } else if (settled === REJECTED) {
      _reject(promise, value);
    }
}

function initializePromise(promise, resolver) {
  try {
    resolver(function resolvePromise(value) {
      _resolve(promise, value);
    }, function rejectPromise(reason) {
      _reject(promise, reason);
    });
  } catch (e) {
    _reject(promise, e);
  }
}

var id = 0;
function nextId() {
  return id++;
}

function makePromise(promise) {
  promise[PROMISE_ID] = id++;
  promise._state = undefined;
  promise._result = undefined;
  promise._subscribers = [];
}

function Enumerator(Constructor, input) {
  this._instanceConstructor = Constructor;
  this.promise = new Constructor(noop);

  if (!this.promise[PROMISE_ID]) {
    makePromise(this.promise);
  }

  if (isArray(input)) {
    this._input = input;
    this.length = input.length;
    this._remaining = input.length;

    this._result = new Array(this.length);

    if (this.length === 0) {
      fulfill(this.promise, this._result);
    } else {
      this.length = this.length || 0;
      this._enumerate();
      if (this._remaining === 0) {
        fulfill(this.promise, this._result);
      }
    }
  } else {
    _reject(this.promise, validationError());
  }
}

function validationError() {
  return new Error('Array Methods must be provided an Array');
};

Enumerator.prototype._enumerate = function () {
  var length = this.length;
  var _input = this._input;

  for (var i = 0; this._state === PENDING && i < length; i++) {
    this._eachEntry(_input[i], i);
  }
};

Enumerator.prototype._eachEntry = function (entry, i) {
  var c = this._instanceConstructor;
  var resolve$$ = c.resolve;

  if (resolve$$ === resolve) {
    var _then = getThen(entry);

    if (_then === then && entry._state !== PENDING) {
      this._settledAt(entry._state, i, entry._result);
    } else if (typeof _then !== 'function') {
      this._remaining--;
      this._result[i] = entry;
    } else if (c === Promise) {
      var promise = new c(noop);
      handleMaybeThenable(promise, entry, _then);
      this._willSettleAt(promise, i);
    } else {
      this._willSettleAt(new c(function (resolve$$) {
        return resolve$$(entry);
      }), i);
    }
  } else {
    this._willSettleAt(resolve$$(entry), i);
  }
};

Enumerator.prototype._settledAt = function (state, i, value) {
  var promise = this.promise;

  if (promise._state === PENDING) {
    this._remaining--;

    if (state === REJECTED) {
      _reject(promise, value);
    } else {
      this._result[i] = value;
    }
  }

  if (this._remaining === 0) {
    fulfill(promise, this._result);
  }
};

Enumerator.prototype._willSettleAt = function (promise, i) {
  var enumerator = this;

  subscribe(promise, undefined, function (value) {
    return enumerator._settledAt(FULFILLED, i, value);
  }, function (reason) {
    return enumerator._settledAt(REJECTED, i, reason);
  });
};

/**
  `Promise.all` accepts an array of promises, and returns a new promise which
  is fulfilled with an array of fulfillment values for the passed promises, or
  rejected with the reason of the first passed promise to be rejected. It casts all
  elements of the passed iterable to promises as it runs this algorithm.

  Example:

  ```javascript
  let promise1 = resolve(1);
  let promise2 = resolve(2);
  let promise3 = resolve(3);
  let promises = [ promise1, promise2, promise3 ];

  Promise.all(promises).then(function(array){
    // The array here would be [ 1, 2, 3 ];
  });
  ```

  If any of the `promises` given to `all` are rejected, the first promise
  that is rejected will be given as an argument to the returned promises's
  rejection handler. For example:

  Example:

  ```javascript
  let promise1 = resolve(1);
  let promise2 = reject(new Error("2"));
  let promise3 = reject(new Error("3"));
  let promises = [ promise1, promise2, promise3 ];

  Promise.all(promises).then(function(array){
    // Code here never runs because there are rejected promises!
  }, function(error) {
    // error.message === "2"
  });
  ```

  @method all
  @static
  @param {Array} entries array of promises
  @param {String} label optional string for labeling the promise.
  Useful for tooling.
  @return {Promise} promise that is fulfilled when all `promises` have been
  fulfilled, or rejected if any of them become rejected.
  @static
*/
function all(entries) {
  return new Enumerator(this, entries).promise;
}

/**
  `Promise.race` returns a new promise which is settled in the same way as the
  first passed promise to settle.

  Example:

  ```javascript
  let promise1 = new Promise(function(resolve, reject){
    setTimeout(function(){
      resolve('promise 1');
    }, 200);
  });

  let promise2 = new Promise(function(resolve, reject){
    setTimeout(function(){
      resolve('promise 2');
    }, 100);
  });

  Promise.race([promise1, promise2]).then(function(result){
    // result === 'promise 2' because it was resolved before promise1
    // was resolved.
  });
  ```

  `Promise.race` is deterministic in that only the state of the first
  settled promise matters. For example, even if other promises given to the
  `promises` array argument are resolved, but the first settled promise has
  become rejected before the other promises became fulfilled, the returned
  promise will become rejected:

  ```javascript
  let promise1 = new Promise(function(resolve, reject){
    setTimeout(function(){
      resolve('promise 1');
    }, 200);
  });

  let promise2 = new Promise(function(resolve, reject){
    setTimeout(function(){
      reject(new Error('promise 2'));
    }, 100);
  });

  Promise.race([promise1, promise2]).then(function(result){
    // Code here never runs
  }, function(reason){
    // reason.message === 'promise 2' because promise 2 became rejected before
    // promise 1 became fulfilled
  });
  ```

  An example real-world use case is implementing timeouts:

  ```javascript
  Promise.race([ajax('foo.json'), timeout(5000)])
  ```

  @method race
  @static
  @param {Array} promises array of promises to observe
  Useful for tooling.
  @return {Promise} a promise which settles in the same way as the first passed
  promise to settle.
*/
function race(entries) {
  /*jshint validthis:true */
  var Constructor = this;

  if (!isArray(entries)) {
    return new Constructor(function (_, reject) {
      return reject(new TypeError('You must pass an array to race.'));
    });
  } else {
    return new Constructor(function (resolve, reject) {
      var length = entries.length;
      for (var i = 0; i < length; i++) {
        Constructor.resolve(entries[i]).then(resolve, reject);
      }
    });
  }
}

/**
  `Promise.reject` returns a promise rejected with the passed `reason`.
  It is shorthand for the following:

  ```javascript
  let promise = new Promise(function(resolve, reject){
    reject(new Error('WHOOPS'));
  });

  promise.then(function(value){
    // Code here doesn't run because the promise is rejected!
  }, function(reason){
    // reason.message === 'WHOOPS'
  });
  ```

  Instead of writing the above, your code now simply becomes the following:

  ```javascript
  let promise = Promise.reject(new Error('WHOOPS'));

  promise.then(function(value){
    // Code here doesn't run because the promise is rejected!
  }, function(reason){
    // reason.message === 'WHOOPS'
  });
  ```

  @method reject
  @static
  @param {Any} reason value that the returned promise will be rejected with.
  Useful for tooling.
  @return {Promise} a promise rejected with the given `reason`.
*/
function reject(reason) {
  /*jshint validthis:true */
  var Constructor = this;
  var promise = new Constructor(noop);
  _reject(promise, reason);
  return promise;
}

function needsResolver() {
  throw new TypeError('You must pass a resolver function as the first argument to the promise constructor');
}

function needsNew() {
  throw new TypeError("Failed to construct 'Promise': Please use the 'new' operator, this object constructor cannot be called as a function.");
}

/**
  Promise objects represent the eventual result of an asynchronous operation. The
  primary way of interacting with a promise is through its `then` method, which
  registers callbacks to receive either a promise's eventual value or the reason
  why the promise cannot be fulfilled.

  Terminology
  -----------

  - `promise` is an object or function with a `then` method whose behavior conforms to this specification.
  - `thenable` is an object or function that defines a `then` method.
  - `value` is any legal JavaScript value (including undefined, a thenable, or a promise).
  - `exception` is a value that is thrown using the throw statement.
  - `reason` is a value that indicates why a promise was rejected.
  - `settled` the final resting state of a promise, fulfilled or rejected.

  A promise can be in one of three states: pending, fulfilled, or rejected.

  Promises that are fulfilled have a fulfillment value and are in the fulfilled
  state.  Promises that are rejected have a rejection reason and are in the
  rejected state.  A fulfillment value is never a thenable.

  Promises can also be said to *resolve* a value.  If this value is also a
  promise, then the original promise's settled state will match the value's
  settled state.  So a promise that *resolves* a promise that rejects will
  itself reject, and a promise that *resolves* a promise that fulfills will
  itself fulfill.


  Basic Usage:
  ------------

  ```js
  let promise = new Promise(function(resolve, reject) {
    // on success
    resolve(value);

    // on failure
    reject(reason);
  });

  promise.then(function(value) {
    // on fulfillment
  }, function(reason) {
    // on rejection
  });
  ```

  Advanced Usage:
  ---------------

  Promises shine when abstracting away asynchronous interactions such as
  `XMLHttpRequest`s.

  ```js
  function getJSON(url) {
    return new Promise(function(resolve, reject){
      let xhr = new XMLHttpRequest();

      xhr.open('GET', url);
      xhr.onreadystatechange = handler;
      xhr.responseType = 'json';
      xhr.setRequestHeader('Accept', 'application/json');
      xhr.send();

      function handler() {
        if (this.readyState === this.DONE) {
          if (this.status === 200) {
            resolve(this.response);
          } else {
            reject(new Error('getJSON: `' + url + '` failed with status: [' + this.status + ']'));
          }
        }
      };
    });
  }

  getJSON('/posts.json').then(function(json) {
    // on fulfillment
  }, function(reason) {
    // on rejection
  });
  ```

  Unlike callbacks, promises are great composable primitives.

  ```js
  Promise.all([
    getJSON('/posts'),
    getJSON('/comments')
  ]).then(function(values){
    values[0] // => postsJSON
    values[1] // => commentsJSON

    return values;
  });
  ```

  @class Promise
  @param {function} resolver
  Useful for tooling.
  @constructor
*/
function Promise(resolver) {
  this[PROMISE_ID] = nextId();
  this._result = this._state = undefined;
  this._subscribers = [];

  if (noop !== resolver) {
    typeof resolver !== 'function' && needsResolver();
    this instanceof Promise ? initializePromise(this, resolver) : needsNew();
  }
}

Promise.all = all;
Promise.race = race;
Promise.resolve = resolve;
Promise.reject = reject;
Promise._setScheduler = setScheduler;
Promise._setAsap = setAsap;
Promise._asap = asap;

Promise.prototype = {
  constructor: Promise,

  /**
    The primary way of interacting with a promise is through its `then` method,
    which registers callbacks to receive either a promise's eventual value or the
    reason why the promise cannot be fulfilled.
  
    ```js
    findUser().then(function(user){
      // user is available
    }, function(reason){
      // user is unavailable, and you are given the reason why
    });
    ```
  
    Chaining
    --------
  
    The return value of `then` is itself a promise.  This second, 'downstream'
    promise is resolved with the return value of the first promise's fulfillment
    or rejection handler, or rejected if the handler throws an exception.
  
    ```js
    findUser().then(function (user) {
      return user.name;
    }, function (reason) {
      return 'default name';
    }).then(function (userName) {
      // If `findUser` fulfilled, `userName` will be the user's name, otherwise it
      // will be `'default name'`
    });
  
    findUser().then(function (user) {
      throw new Error('Found user, but still unhappy');
    }, function (reason) {
      throw new Error('`findUser` rejected and we're unhappy');
    }).then(function (value) {
      // never reached
    }, function (reason) {
      // if `findUser` fulfilled, `reason` will be 'Found user, but still unhappy'.
      // If `findUser` rejected, `reason` will be '`findUser` rejected and we're unhappy'.
    });
    ```
    If the downstream promise does not specify a rejection handler, rejection reasons will be propagated further downstream.
  
    ```js
    findUser().then(function (user) {
      throw new PedagogicalException('Upstream error');
    }).then(function (value) {
      // never reached
    }).then(function (value) {
      // never reached
    }, function (reason) {
      // The `PedgagocialException` is propagated all the way down to here
    });
    ```
  
    Assimilation
    ------------
  
    Sometimes the value you want to propagate to a downstream promise can only be
    retrieved asynchronously. This can be achieved by returning a promise in the
    fulfillment or rejection handler. The downstream promise will then be pending
    until the returned promise is settled. This is called *assimilation*.
  
    ```js
    findUser().then(function (user) {
      return findCommentsByAuthor(user);
    }).then(function (comments) {
      // The user's comments are now available
    });
    ```
  
    If the assimliated promise rejects, then the downstream promise will also reject.
  
    ```js
    findUser().then(function (user) {
      return findCommentsByAuthor(user);
    }).then(function (comments) {
      // If `findCommentsByAuthor` fulfills, we'll have the value here
    }, function (reason) {
      // If `findCommentsByAuthor` rejects, we'll have the reason here
    });
    ```
  
    Simple Example
    --------------
  
    Synchronous Example
  
    ```javascript
    let result;
  
    try {
      result = findResult();
      // success
    } catch(reason) {
      // failure
    }
    ```
  
    Errback Example
  
    ```js
    findResult(function(result, err){
      if (err) {
        // failure
      } else {
        // success
      }
    });
    ```
  
    Promise Example;
  
    ```javascript
    findResult().then(function(result){
      // success
    }, function(reason){
      // failure
    });
    ```
  
    Advanced Example
    --------------
  
    Synchronous Example
  
    ```javascript
    let author, books;
  
    try {
      author = findAuthor();
      books  = findBooksByAuthor(author);
      // success
    } catch(reason) {
      // failure
    }
    ```
  
    Errback Example
  
    ```js
  
    function foundBooks(books) {
  
    }
  
    function failure(reason) {
  
    }
  
    findAuthor(function(author, err){
      if (err) {
        failure(err);
        // failure
      } else {
        try {
          findBoooksByAuthor(author, function(books, err) {
            if (err) {
              failure(err);
            } else {
              try {
                foundBooks(books);
              } catch(reason) {
                failure(reason);
              }
            }
          });
        } catch(error) {
          failure(err);
        }
        // success
      }
    });
    ```
  
    Promise Example;
  
    ```javascript
    findAuthor().
      then(findBooksByAuthor).
      then(function(books){
        // found books
    }).catch(function(reason){
      // something went wrong
    });
    ```
  
    @method then
    @param {Function} onFulfilled
    @param {Function} onRejected
    Useful for tooling.
    @return {Promise}
  */
  then: then,

  /**
    `catch` is simply sugar for `then(undefined, onRejection)` which makes it the same
    as the catch block of a try/catch statement.
  
    ```js
    function findAuthor(){
      throw new Error('couldn't find that author');
    }
  
    // synchronous
    try {
      findAuthor();
    } catch(reason) {
      // something went wrong
    }
  
    // async with promises
    findAuthor().catch(function(reason){
      // something went wrong
    });
    ```
  
    @method catch
    @param {Function} onRejection
    Useful for tooling.
    @return {Promise}
  */
  'catch': function _catch(onRejection) {
    return this.then(null, onRejection);
  }
};

function polyfill() {
    var local = undefined;

    if (typeof global !== 'undefined') {
        local = global;
    } else if (typeof self !== 'undefined') {
        local = self;
    } else {
        try {
            local = Function('return this')();
        } catch (e) {
            throw new Error('polyfill failed because global object is unavailable in this environment');
        }
    }

    var P = local.Promise;

    if (P) {
        var promiseToString = null;
        try {
            promiseToString = Object.prototype.toString.call(P.resolve());
        } catch (e) {
            // silently ignored
        }

        if (promiseToString === '[object Promise]' && !P.cast) {
            return;
        }
    }

    local.Promise = Promise;
}

// Strange compat..
Promise.polyfill = polyfill;
Promise.Promise = Promise;

return Promise;

})));
//# sourceMappingURL=es6-promise.map


/*** EXPORTS FROM exports-loader ***/
module.exports = global.Promise;
}.call(global));
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(15), __webpack_require__(16)))

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
function string(value) {
    return value + '';
}
exports.string = string;
function int(value) {
    return (typeof value === 'boolean') ? +value : parseInt(value, 10);
}
exports.int = int;


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
function getAll() {
    var cookies = {};
    for (var _i = 0, _a = document.cookie.split('; '); _i < _a.length; _i++) {
        var cookie = _a[_i];
        var _b = cookie.split('='), name_1 = _b[0], value = _b[1];
        cookies[name_1] = decodeURIComponent(value);
    }
    return cookies;
}
exports.getAll = getAll;
function get(key) {
    return getAll()[key];
}
exports.get = get;


/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
var UrlHelper = __webpack_require__(6);
var Route = (function () {
    function Route(path, resolver) {
        this.pathParts = path.replace(/^\//, '').split('/');
        this.resolver = resolver;
    }
    Route.prototype.hasEqualPathParts = function (pathPartsToResolve) {
        if (this.pathParts.length !== pathPartsToResolve.length) {
            return false;
        }
        return this.pathParts.every(function (part, i) { return part === pathPartsToResolve[i]; });
    };
    return Route;
}());
var UrlRouteParser = (function () {
    function UrlRouteParser() {
        this.routes = [];
        this.params = {};
    }
    UrlRouteParser.prototype.extractParams = function (routePathParts, pathPartsToResolve) {
        var routePathPartsLength = routePathParts.length;
        if (routePathPartsLength < pathPartsToResolve.length) {
            return true;
        }
        for (var i = 0; i < routePathPartsLength; i++) {
            var routePart = routePathParts[i];
            var partToResolve = pathPartsToResolve[i];
            if (partToResolve) {
                if (routePart.charAt(0) === ':') {
                    this.params[routePart.substr(1)] = partToResolve;
                    continue;
                }
                if (routePart !== partToResolve) {
                    return false;
                }
            }
            else if (routePart.charAt(0) !== ':') {
                return false;
            }
        }
        return true;
    };
    UrlRouteParser.prototype.add = function (path, resolver) {
        var route = new Route(path, resolver);
        this.routes.push(route);
    };
    UrlRouteParser.prototype.resolve = function () {
        var origin = window.location.origin;
        var href = window.location.href;
        var url = href
            .substr(origin.length)
            .replace(/\/+/g, '/')
            .replace(/^\/|\/($|\?)/, '')
            .replace(/#.*$/, '');
        var _a = url.split('?', 2), path = _a[0], query = _a[1];
        var pathParts = path.split('/');
        for (var _i = 0, _b = this.routes; _i < _b.length; _i++) {
            var route = _b[_i];
            if (route.hasEqualPathParts(pathParts)) {
                route.resolver({}, UrlHelper.urlQueryToMap(query));
                return true;
            }
            if (this.extractParams(route.pathParts, pathParts)) {
                route.resolver(this.params, UrlHelper.urlQueryToMap(query));
                return true;
            }
        }
        return false;
    };
    return UrlRouteParser;
}());
exports.default = UrlRouteParser;


/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(Promise) {
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = y[op[0] & 2 ? "return" : op[0] ? "throw" : "next"]) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [0, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
Object.defineProperty(exports, "__esModule", { value: true });
var to = __webpack_require__(1);
var upload = __webpack_require__(12);
var status_1 = __webpack_require__(10);
var photos = __webpack_require__(9);
var Page = (function () {
    function Page() {
        var urlParts = [];
        for (var _i = 0; _i < arguments.length; _i++) {
            urlParts[_i] = arguments[_i];
        }
        var params = urlParts[0];
        var group = to.int(params['group']);
        this.params = { group: group };
        this.query = {};
        this.pageContainer = $('#page-container');
        this.form = this.pageContainer.find('#photos-form');
        this.fileInput = this.form.children('#photos-file-input');
        this.alerts = {
            failed: this.pageContainer.find('#failed-alert'),
            uploaded: this.pageContainer.find('#uploaded-alert'),
            generic: this.pageContainer.find('#generic-alert')
        };
        this.list = new photos.List(this.pageContainer.find('#photos'), group);
    }
    Page.prototype.boot = function () {
        var _this = this;
        this.list.on(photos.EventType.PHOTO_COVER_FAILURE, this.handlePhotoCoverFailure.bind(this));
        this.list.on(photos.EventType.PHOTO_COVER_SUCCESS, this.hideAlerts.bind(this));
        this.list.on(photos.EventType.PHOTO_DELETE_FAILURE, this.handlePhotoDeleteFailure.bind(this));
        this.list.on(photos.EventType.PHOTO_DELETE_SUCCESS, function () {
            _this.updatePhotosCounter();
            _this.hideAlerts();
            status_1.Status.update(_this.params.group);
        });
        this.fileInput.on('change', this.handleFileUpload.bind(this));
        this.form.data({
            'defaultLabel': this.form.children('span').text(),
            'defaultIcon': this.form.children('.icon').attr('class')
        });
    };
    Page.prototype.resetForm = function () {
        this.form[0].reset();
    };
    Page.prototype.hideAlerts = function () {
        for (var alert_1 in this.alerts) {
            this.alerts[alert_1].addClass('hidden');
        }
    };
    Page.prototype.enableForm = function (isEnable, isLoading) {
        if (isEnable === void 0) { isEnable = true; }
        if (isLoading === void 0) { isLoading = false; }
        var isFormDisabled = !isEnable;
        this.setFormLabel((isLoading) ? 'Uploading...' : undefined);
        this.setFormIcon((isLoading) ? 'notched circle loading icon' : undefined);
        this.form
            .prop('disabled', isFormDisabled)
            .toggleClass('disabled', isFormDisabled);
    };
    Page.prototype.setFormIcon = function (iconClassNames) {
        if (iconClassNames === void 0) { iconClassNames = this.form.data('defaultIcon'); }
        this.form.children('i').attr('class', iconClassNames);
    };
    Page.prototype.setFormLabel = function (label) {
        if (label === void 0) { label = this.form.data('defaultLabel'); }
        this.form.children('span').text(label);
    };
    Page.prototype.listFailedPhotos = function (failedUploads) {
        if (failedUploads === void 0) { failedUploads = []; }
        var failedLength = failedUploads.length;
        if (!failedLength) {
            this.alerts.failed.addClass('hidden');
            return;
        }
        var alert = this.alerts.failed;
        var listItems = failedUploads.map(function (filed) { return ("\n      <li>\n        <em>`" + filed.filename + "`</em> &mdash; " + filed.errors.slice(-1) + "\n      </li>\n    "); });
        alert
            .find('ul')
            .html(listItems.join(''));
        alert.removeClass('hidden');
    };
    Page.prototype.listUploadedPhotos = function (uploaded, allLength) {
        var uploadedLength = uploaded.length;
        var alert = this.alerts.uploaded;
        if (!uploadedLength) {
            alert.addClass('hidden');
            return;
        }
        var message = "<p>" + uploadedLength + " out of " + allLength + " photos successfully uploaded.</p>";
        if (uploadedLength === allLength) {
            message = "<p>" + uploadedLength + " photo(s) successfully uploaded.</p>";
        }
        alert
            .html(message)
            .removeClass('hidden');
        for (var _i = 0, uploaded_1 = uploaded; _i < uploaded_1.length; _i++) {
            var photo = uploaded_1[_i];
            var thumbnail = photo.thumbnail_src || photo.large_src || photo.original_src;
            this.list.add(new photos.Photo(photo.id, thumbnail, photo.is_cover, this.list));
        }
    };
    Page.prototype.updatePhotosCounter = function () {
        var photosLength = this.list.length;
        var maxPhotosLength = to.int($('input[name="max_photos"]').val());
        this.enableForm(photosLength < maxPhotosLength);
        $('#counter').children('span:first').text(photosLength);
    };
    Page.prototype.handlePhotoCoverFailure = function () {
        this.hideAlerts();
        this.alerts.failed
            .html('<p>There was a problem setting new cover photo. Please try again.</p>')
            .removeClass('hidden');
    };
    Page.prototype.handlePhotoDeleteFailure = function () {
        this.hideAlerts();
        this.alerts.failed
            .html('<p>There was a problem deleting the photo. Please try again.</p>')
            .removeClass('hidden');
    };
    Page.prototype.handleProgress = function (percent) {
        this.setFormLabel("Uploading... " + percent + "%");
    };
    Page.prototype.handleUploadFailure = function () {
        this.alerts.generic
            .html('<p>There was a problem uploading your file(s).</p>')
            .removeClass('hidden');
    };
    Page.prototype.handleFileUpload = function () {
        return __awaiter(this, void 0, void 0, function () {
            var file, fileList, filesLength, _a, uploaded, failed, error_1;
            return __generator(this, function (_b) {
                switch (_b.label) {
                    case 0:
                        file = this.fileInput[0];
                        fileList = Array.from(file.files || []);
                        filesLength = fileList.length;
                        if (!filesLength || this.uploader && this.uploader.isUploading) {
                            return [2];
                        }
                        this.hideAlerts();
                        this.enableForm(false, true);
                        this.uploader = new upload.Uploader("/api/v1/classes/" + this.params.group + "/photos", 'photos[]', fileList);
                        this.uploader.onProgress = this.handleProgress.bind(this);
                        _b.label = 1;
                    case 1:
                        _b.trys.push([1, 3, , 4]);
                        return [4, this.uploader.upload()];
                    case 2:
                        _a = _b.sent(), uploaded = _a.uploaded, failed = _a.failed;
                        this.listUploadedPhotos(uploaded, uploaded.length + failed.length);
                        this.listFailedPhotos(failed);
                        return [3, 4];
                    case 3:
                        error_1 = _b.sent();
                        console.log('error', error_1);
                        this.handleUploadFailure();
                        return [3, 4];
                    case 4:
                        this.uploader.dispose();
                        this.uploader = null;
                        this.updatePhotosCounter();
                        this.list.ensureCoverPhoto();
                        this.resetForm();
                        status_1.Status.update(this.params.group);
                        return [2];
                }
            });
        });
    };
    return Page;
}());
function init() {
    var urlParts = [];
    for (var _i = 0; _i < arguments.length; _i++) {
        urlParts[_i] = arguments[_i];
    }
    var page = new (Page.bind.apply(Page, [void 0].concat(urlParts)))();
    page.boot();
}
exports.init = init;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(0)))

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
var Str;
(function (Str) {
    function sprintf() {
        var args = [];
        for (var _i = 0; _i < arguments.length; _i++) {
            args[_i] = arguments[_i];
        }
        var text = args[0];
        var i = 1;
        return text.replace(/%s/g, function () { return (i < args.length) ? args[i++] : ''; });
    }
    Str.sprintf = sprintf;
})(Str = exports.Str || (exports.Str = {}));


/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var __assign = (this && this.__assign) || Object.assign || function(t) {
    for (var s, i = 1, n = arguments.length; i < n; i++) {
        s = arguments[i];
        for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
            t[p] = s[p];
    }
    return t;
};
Object.defineProperty(exports, "__esModule", { value: true });
function urlQueryToMap(queryString) {
    if (queryString === void 0) { queryString = window.location.search; }
    var query = (queryString.charAt(0) === '?') ? queryString.substring(1) : queryString;
    if (!query) {
        return {};
    }
    return JSON.parse("{\"" + decodeURI(query).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') + "\"}");
}
exports.urlQueryToMap = urlQueryToMap;
;
function mapToUrlQuery(map, separator) {
    if (separator === void 0) { separator = '&'; }
    return Object.keys(map).map(function (key) { return key + "=" + map[key]; }).join(separator);
}
exports.mapToUrlQuery = mapToUrlQuery;
;
function windowFromUrl(url, title, features) {
    if (features === void 0) { features = {}; }
    var featuresWithDefault = __assign({ copyhistory: 'no', directories: 'no', location: 'yes', menubar: 'no', resizable: 'no', scrollbars: 'yes', status: 'no', toolbar: 'no' }, features);
    return window.open(url, title, mapToUrlQuery(featuresWithDefault, ','));
}
exports.windowFromUrl = windowFromUrl;
;


/***/ }),
/* 7 */,
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
var UrlRouteParser_1 = __webpack_require__(3);
var photos = __webpack_require__(4);
var router = new UrlRouteParser_1.default();
router.add('home/classes/:group/photos', photos.init);
router.resolve();


/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(Promise) {
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = y[op[0] & 2 ? "return" : op[0] ? "throw" : "next"]) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [0, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
Object.defineProperty(exports, "__esModule", { value: true });
var to = __webpack_require__(1);
var photoService = __webpack_require__(11);
var EventType;
(function (EventType) {
    EventType[EventType["PHOTO_DELETE_FAILURE"] = 0] = "PHOTO_DELETE_FAILURE";
    EventType[EventType["PHOTO_DELETE_SUCCESS"] = 1] = "PHOTO_DELETE_SUCCESS";
    EventType[EventType["PHOTO_COVER_FAILURE"] = 2] = "PHOTO_COVER_FAILURE";
    EventType[EventType["PHOTO_COVER_SUCCESS"] = 3] = "PHOTO_COVER_SUCCESS";
})(EventType = exports.EventType || (exports.EventType = {}));
var Subscription = (function () {
    function Subscription(eventType, callback) {
        this.eventType = eventType;
        this.callback = callback;
    }
    return Subscription;
}());
var List = (function () {
    function List(element, groupId) {
        var _this = this;
        this.subscriptions = [];
        this.groupId = groupId;
        this.element = element
            .on('click', '.button', this.handleButtonClick.bind(this));
        this.photos = element
            .children()
            .toArray()
            .map(function (node) { return _this.domNodeToPhoto(node); });
        this.ensureCoverPhoto();
    }
    Object.defineProperty(List.prototype, "length", {
        get: function () {
            return this.photos.length;
        },
        enumerable: true,
        configurable: true
    });
    List.prototype.dispatchEvent = function (eventType, photoId) {
        var subscription = this.subscriptions.find(function (subscription) { return subscription.eventType === eventType; });
        if (subscription) {
            subscription.callback(photoId);
        }
    };
    List.prototype.on = function (eventType, callback) {
        this.subscriptions.push(new Subscription(eventType, callback));
    };
    List.prototype.domNodeToPhoto = function (node) {
        var element = $(node);
        var id = to.int(element.attr('data-id'));
        var thumbnail = element.find('img:first').attr('src');
        var isCover = !!to.int(element.attr('data-is-cover'));
        return new Photo(id, thumbnail, isCover, this, element);
    };
    List.prototype.trans = function (key) {
        return this.element.data('translations')[key];
    };
    List.prototype.add = function (photo) {
        var element = this.element
            .append(photo.toHtmlString())
            .children()
            .last();
        photo.element = element;
        this.photos.push(photo);
    };
    List.prototype.ensureCoverPhoto = function () {
        if (this.length) {
            var hasCoverPhoto = this.photos.some(function (photo) { return photo.isCover; });
            if (!hasCoverPhoto) {
                this.setPhotoAsCover(this.photos[0].id);
            }
        }
    };
    List.prototype.handleButtonClick = function (e) {
        return __awaiter(this, void 0, void 0, function () {
            var button, photoId;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        button = $(e.currentTarget);
                        photoId = to.int(button.closest('.hoverable').attr('data-id'));
                        if (!button.children('.trash').length) return [3, 2];
                        return [4, this.destroyPhoto(photoId)];
                    case 1:
                        _a.sent();
                        return [3, 4];
                    case 2:
                        if (!(button.children('.star').length && !button.is('.yellow'))) return [3, 4];
                        return [4, this.setPhotoAsCover(photoId, true)];
                    case 3:
                        _a.sent();
                        _a.label = 4;
                    case 4: return [2];
                }
            });
        });
    };
    List.prototype.destroyPhoto = function (photoId) {
        return __awaiter(this, void 0, void 0, function () {
            var index, photo, ex_1;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        index = this.photos.findIndex(function (photo) { return photo.id === photoId; });
                        if (!(index !== -1)) return [3, 4];
                        photo = this.photos[index];
                        if (photo.isDisabled) {
                            return [2];
                        }
                        _a.label = 1;
                    case 1:
                        _a.trys.push([1, 3, , 4]);
                        photo.isDisabled = true;
                        return [4, photoService.destroy(photo.id, this.groupId)];
                    case 2:
                        _a.sent();
                        photo.dispose();
                        this.photos.splice(index, 1);
                        this.ensureCoverPhoto();
                        this.dispatchEvent(EventType.PHOTO_DELETE_SUCCESS, photoId);
                        return [3, 4];
                    case 3:
                        ex_1 = _a.sent();
                        photo.isDisabled = false;
                        this.dispatchEvent(EventType.PHOTO_DELETE_FAILURE, photoId);
                        return [3, 4];
                    case 4: return [2];
                }
            });
        });
    };
    List.prototype.setPhotoAsCover = function (photoId, isDispatchSuccess) {
        if (photoId === void 0) { photoId = this.photos[0].id; }
        return __awaiter(this, void 0, void 0, function () {
            var currentCoverPhoto, photo, ex_2;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        currentCoverPhoto = this.photos.find(function (photo) { return photo.isCover; });
                        photo = this.photos.find(function (photo) { return photo.id === photoId; });
                        if (photo.isDisabled) {
                            return [2];
                        }
                        if (currentCoverPhoto) {
                            currentCoverPhoto.setAsCover(false);
                        }
                        photo.setAsCover();
                        _a.label = 1;
                    case 1:
                        _a.trys.push([1, 3, , 4]);
                        return [4, photoService.setAsCover(photo.id, this.groupId)];
                    case 2:
                        _a.sent();
                        if (isDispatchSuccess) {
                            this.dispatchEvent(EventType.PHOTO_COVER_SUCCESS, photoId);
                        }
                        return [3, 4];
                    case 3:
                        ex_2 = _a.sent();
                        photo.setAsCover(false);
                        if (currentCoverPhoto) {
                            currentCoverPhoto.setAsCover();
                        }
                        this.dispatchEvent(EventType.PHOTO_COVER_FAILURE, photoId);
                        return [3, 4];
                    case 4: return [2];
                }
            });
        });
    };
    return List;
}());
exports.List = List;
var Photo = (function () {
    function Photo(id, thumbnail, isCover, list, element) {
        this.id = id;
        this.thumbnail = thumbnail;
        this.isCover = isCover;
        this.element = element;
        this.list = list;
    }
    Object.defineProperty(Photo.prototype, "isDisabled", {
        get: function () {
            return this.element.hasClass('disabled');
        },
        set: function (isDisabled) {
            this.element.toggleClass('disabled', isDisabled);
        },
        enumerable: true,
        configurable: true
    });
    Photo.prototype.dispose = function () {
        this.element
            .find('img')
            .prop('src', '');
        this.element
            .find('[data-content]')['popup']('destroy');
        this.element.remove();
        this.element = null;
        this.list = null;
    };
    Photo.prototype.setAsCover = function (isCover) {
        if (isCover === void 0) { isCover = true; }
        this.isCover = isCover;
        this.element
            .attr('data-is-cover', to.int(isCover))
            .find('.button:last')
            .toggleClass('yellow', isCover)
            .attr('data-content', this.getCoverButtonLabel());
    };
    Photo.prototype.toHtmlString = function () {
        return "\n      <div\n        class=\"ui left floated segment hoverable\"\n        style=\"margin-top: 0;\"\n        data-id=\"" + this.id + "\"\n        data-is-cover=\"" + to.int(this.isCover) + "\"\n      >\n        <div style=\"overflow: hidden; width: 14rem; height: 9rem;\">\n          <img class=\"ui fluid image\" src=\"" + this.thumbnail + "\" style=\"width: 100%; -webkit-transform: translateY(-50%); transform: translateY(-50%); top: 50%;\">\n          <div class=\"ui icon button\"><i class=\"trash icon\"></i></div>\n          <div class=\"ui icon button " + ((this.isCover) ? 'yellow' : '') + "\" data-content=\"" + this.getCoverButtonLabel() + "\" data-position=\"top center\"><i class=\"star icon purple\"></i></div>\n        </div>\n      </div>\n    ";
    };
    Photo.prototype.getCoverButtonLabel = function () {
        return this.list.trans((this.isCover) ? 'group/photos.is_cover_photo' : 'group/photos.set_cover_photo');
    };
    return Photo;
}());
exports.Photo = Photo;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(0)))

/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(Promise) {
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = y[op[0] & 2 ? "return" : op[0] ? "throw" : "next"]) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [0, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
Object.defineProperty(exports, "__esModule", { value: true });
var cookies = __webpack_require__(2);
var Str_1 = __webpack_require__(5);
var to = __webpack_require__(1);
var Status;
(function (Status) {
    function getStatus(groupId) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: "/api/v1/classes/" + groupId + "/menu",
                headers: {
                    'X-XSRF-TOKEN': cookies.get('XSRF-TOKEN')
                },
                cache: false,
                success: resolve,
                error: function () { return reject(new Error('Generic error when retrieving group status.')); }
            });
        });
    }
    function updateMenu(status) {
        var menu = $('#menu');
        var menuStatus = menu.next('#menu-status');
        var steps = status.steps_to_complete;
        var activeItem = menu.find('.item.active');
        var href = activeItem.attr('href');
        var slug = href.substring(href.lastIndexOf('/') + 1);
        if (status[slug] === true) {
            activeItem.children('i').removeClass('plus red').addClass('checkmark green');
        }
        else if (status[slug] === false) {
            activeItem.children('i').removeClass('checkmark green').addClass('plus red');
        }
        var templateIndex = (steps > 0) ? 0 : 1;
        var template = menuStatus.children("span:eq(" + templateIndex + ")")[0].firstChild.nodeValue;
        var output = (steps > 0) ? Str_1.Str.sprintf(template, to.string(steps)) : template;
        menuStatus.children('p').html(output).find('strong[data-label-plural]').each(function () {
            var attr = 'data-label';
            if (steps > 1) {
                attr += '-plural';
            }
            this.innerHTML = steps + " " + this.getAttribute(attr);
        });
    }
    function updateStatusBar(status) {
        var statusBar = $('#status-bar');
        var steps = status.steps_to_complete;
        var templateIndex = (steps > 0) ? 0 : 1;
        var template = statusBar.children("span:eq(" + templateIndex + ")")[0].firstChild.nodeValue;
        var output = template;
        if (steps > 0) {
            output = Str_1.Str.sprintf(template, to.string(steps));
            statusBar.addClass('warning').removeClass('success');
        }
        else {
            statusBar.addClass('success').removeClass('warning');
        }
        statusBar.children('p').html(output).find('strong[data-label-plural]').each(function () {
            var attr = 'data-label';
            if (steps > 1) {
                attr += '-plural';
            }
            this.innerHTML = steps + " " + this.getAttribute(attr);
        });
    }
    function update(groupId) {
        return __awaiter(this, void 0, void 0, function () {
            var status;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4, getStatus(groupId)];
                    case 1:
                        status = _a.sent();
                        updateStatusBar(status);
                        updateMenu(status);
                        return [2];
                }
            });
        });
    }
    Status.update = update;
})(Status = exports.Status || (exports.Status = {}));

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(0)))

/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(Promise) {
Object.defineProperty(exports, "__esModule", { value: true });
var cookies = __webpack_require__(2);
function destroy(photoId, groupId) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "/api/v1/classes/" + groupId + "/photos/" + photoId,
            method: 'DELETE',
            headers: {
                'X-XSRF-TOKEN': cookies.get('XSRF-TOKEN')
            },
            cache: false,
            success: resolve,
            error: function () { return reject(new Error('Generic error when deleting photo.')); }
        });
    });
}
exports.destroy = destroy;
function setAsCover(photoId, groupId) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "/api/v1/classes/" + groupId + "/cover_photo",
            method: 'PUT',
            headers: {
                'X-XSRF-TOKEN': cookies.get('XSRF-TOKEN')
            },
            data: {
                photo_id: photoId
            },
            success: resolve,
            error: function () { return reject(new Error('Generic error when setting photo as cover.')); }
        });
    });
}
exports.setAsCover = setAsCover;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(0)))

/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(Promise) {
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = y[op[0] & 2 ? "return" : op[0] ? "throw" : "next"]) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [0, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
Object.defineProperty(exports, "__esModule", { value: true });
var cookies = __webpack_require__(2);
var to = __webpack_require__(1);
exports.MAX_SIMULTANEOUS_UPLOADS = 6;
var FailedResult = (function () {
    function FailedResult(filename, errors) {
        this.filename = filename;
        this.errors = errors;
    }
    return FailedResult;
}());
exports.FailedResult = FailedResult;
var Result = (function () {
    function Result(uploaded, failed) {
        if (uploaded === void 0) { uploaded = []; }
        if (failed === void 0) { failed = []; }
        this.uploaded = uploaded;
        this.failed = failed;
    }
    Object.defineProperty(Result.prototype, "length", {
        get: function () {
            return this.uploaded.length + this.failed.length;
        },
        enumerable: true,
        configurable: true
    });
    return Result;
}());
exports.Result = Result;
var Uploader = (function () {
    function Uploader(endpoint, formKey, fileList) {
        this.endpoint = endpoint;
        this.formKey = formKey;
        this.result = new Result();
        this.fileList = Array.from(fileList);
        this.isUploading = false;
        this.simultaneousXhr = 1;
        this.onProgressCallback = function () { };
    }
    Object.defineProperty(Uploader.prototype, "simultaneousXhrRequests", {
        set: function (count) {
            this.simultaneousXhr = Math.min(count, exports.MAX_SIMULTANEOUS_UPLOADS);
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(Uploader.prototype, "onProgress", {
        set: function (callback) {
            this.onProgressCallback = callback;
        },
        enumerable: true,
        configurable: true
    });
    Uploader.prototype.dispose = function () {
        if (this.xhr) {
            this.xhr.abort();
            this.xhr = null;
        }
        this.onProgressCallback = null;
        this.fileList = null;
        this.result = null;
    };
    Uploader.prototype.addFiles = function (fileList) {
        this.fileList = Array.from(fileList);
    };
    Uploader.prototype.upload = function () {
        return __awaiter(this, void 0, void 0, function () {
            var filesLength, i;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        filesLength = this.fileList.length;
                        if (!filesLength) return [3, 4];
                        this.isUploading = true;
                        i = 0;
                        _a.label = 1;
                    case 1:
                        if (!(i < filesLength)) return [3, 4];
                        return [4, this.triggerUpload(this.fileList.slice(i, i += this.simultaneousXhr))];
                    case 2:
                        _a.sent();
                        this.onProgressCallback(to.int(i / filesLength * 100));
                        _a.label = 3;
                    case 3: return [3, 1];
                    case 4:
                        this.fileList = [];
                        this.isUploading = false;
                        return [2, this.result];
                }
            });
        });
    };
    Uploader.prototype.triggerUpload = function (files) {
        return __awaiter(this, void 0, void 0, function () {
            var formData, result, error_1, failed, _a, _b, _c;
            return __generator(this, function (_d) {
                switch (_d.label) {
                    case 0:
                        formData = this.buildFormData(this.formKey, files);
                        _d.label = 1;
                    case 1:
                        _d.trys.push([1, 3, , 4]);
                        return [4, this.uploadToServer(formData)];
                    case 2:
                        result = _d.sent();
                        (_a = this.result.uploaded).push.apply(_a, result.uploaded);
                        (_b = this.result.failed).push.apply(_b, result.failed);
                        return [3, 4];
                    case 3:
                        error_1 = _d.sent();
                        failed = files.map(function (file) { return new FailedResult(file.name, [error_1.message]); });
                        (_c = this.result.failed).push.apply(_c, failed);
                        return [3, 4];
                    case 4: return [2];
                }
            });
        });
    };
    Uploader.prototype.buildFormData = function (key, files, formData) {
        if (formData === void 0) { formData = new FormData(); }
        for (var _i = 0, files_1 = files; _i < files_1.length; _i++) {
            var file = files_1[_i];
            formData.append(key, file);
        }
        return formData;
    };
    Uploader.prototype.uploadToServer = function (data) {
        var _this = this;
        return new Promise(function (resolve, reject) {
            _this.xhr = $.ajax({
                url: _this.endpoint,
                method: 'POST',
                headers: {
                    'X-XSRF-TOKEN': cookies.get('XSRF-TOKEN')
                },
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: resolve,
                error: function () { return reject(new Error('Generic error when file uploading.')); },
                complete: function () {
                    _this.xhr = null;
                }
            });
        });
    };
    return Uploader;
}());
exports.Uploader = Uploader;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(0)))

/***/ }),
/* 13 */,
/* 14 */,
/* 15 */
/***/ (function(module, exports) {

// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };


/***/ }),
/* 16 */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || Function("return this")() || (1,eval)("this");
} catch(e) {
	// This works if the window reference is available
	if(typeof window === "object")
		g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),
/* 17 */
/***/ (function(module, exports) {

/* (ignored) */

/***/ })
/******/ ]);
//# sourceMappingURL=group-edit.js.map