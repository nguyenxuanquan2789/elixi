/*!
 * V-Elements - Live page builder
 * Copyright 2019-2021 WebshopWorks.com & Elementor.com
 */

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
/******/ 	return __webpack_require__(__webpack_require__.s = 167);
/******/ })
/************************************************************************/
/******/ ({

/***/ 1:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});
var userAgent = navigator.userAgent;

exports.default = {
	webkit: -1 !== userAgent.indexOf('AppleWebKit'),
	firefox: -1 !== userAgent.indexOf('Firefox'),
	ie: /Trident|MSIE/.test(userAgent),
	edge: -1 !== userAgent.indexOf('Edge'),
	mac: -1 !== userAgent.indexOf('Macintosh')
};

/***/ }),

/***/ 15:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_elementorModules$Mod) {
	_inherits(_class, _elementorModules$Mod);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'get',
		value: function get(key, options) {
			options = options || {};

			var storage = void 0;

			try {
				storage = options.session ? sessionStorage : localStorage;
			} catch (e) {
				return key ? undefined : {};
			}

			var elementorStorage = storage.getItem('elementor');

			if (elementorStorage) {
				elementorStorage = JSON.parse(elementorStorage);
			} else {
				elementorStorage = {};
			}

			if (!elementorStorage.__expiration) {
				elementorStorage.__expiration = {};
			}

			var expiration = elementorStorage.__expiration;

			var expirationToCheck = [];

			if (key) {
				if (expiration[key]) {
					expirationToCheck = [key];
				}
			} else {
				expirationToCheck = Object.keys(expiration);
			}

			var entryExpired = false;

			expirationToCheck.forEach(function (expirationKey) {
				if (new Date(expiration[expirationKey]) < new Date()) {
					delete elementorStorage[expirationKey];

					delete expiration[expirationKey];

					entryExpired = true;
				}
			});

			if (entryExpired) {
				this.save(elementorStorage, options.session);
			}

			if (key) {
				return elementorStorage[key];
			}

			return elementorStorage;
		}
	}, {
		key: 'set',
		value: function set(key, value, options) {
			options = options || {};

			var elementorStorage = this.get(null, options);

			elementorStorage[key] = value;

			if (options.lifetimeInSeconds) {
				var date = new Date();

				date.setTime(date.getTime() + options.lifetimeInSeconds * 1000);

				elementorStorage.__expiration[key] = date.getTime();
			}

			this.save(elementorStorage, options.session);
		}
	}, {
		key: 'save',
		value: function save(object, session) {
			var storage = void 0;

			try {
				storage = session ? sessionStorage : localStorage;
			} catch (e) {
				return;
			}

			storage.setItem('elementor', JSON.stringify(object));
		}
	}]);

	return _class;
}(elementorModules.Module);

exports.default = _class;

/***/ }),

/***/ 16:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _environment = __webpack_require__(1);

var _environment2 = _interopRequireDefault(_environment);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var HotKeys = function () {
	function HotKeys() {
		_classCallCheck(this, HotKeys);

		this.hotKeysHandlers = {};
	}

	_createClass(HotKeys, [{
		key: 'applyHotKey',
		value: function applyHotKey(event) {
			var handlers = this.hotKeysHandlers[event.which];

			if (!handlers) {
				return;
			}

			jQuery.each(handlers, function (key, handler) {
				if (handler.isWorthHandling && !handler.isWorthHandling(event)) {
					return;
				}

				// Fix for some keyboard sources that consider alt key as ctrl key
				if (!handler.allowAltKey && event.altKey) {
					return;
				}

				event.preventDefault();

				handler.handle(event);
			});
		}
	}, {
		key: 'isControlEvent',
		value: function isControlEvent(event) {
			return event[_environment2.default.mac ? 'metaKey' : 'ctrlKey'];
		}
	}, {
		key: 'addHotKeyHandler',
		value: function addHotKeyHandler(keyCode, handlerName, handler) {
			if (!this.hotKeysHandlers[keyCode]) {
				this.hotKeysHandlers[keyCode] = {};
			}

			this.hotKeysHandlers[keyCode][handlerName] = handler;
		}
	}, {
		key: 'bindListener',
		value: function bindListener($listener) {
			$listener.on('keydown', this.applyHotKey.bind(this));
		}
	}]);

	return HotKeys;
}();

exports.default = HotKeys;

/***/ }),

/***/ 167:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _helpers = __webpack_require__(168);

var _helpers2 = _interopRequireDefault(_helpers);

var _storage = __webpack_require__(15);

var _storage2 = _interopRequireDefault(_storage);

var _hotKeys = __webpack_require__(16);

var _hotKeys2 = _interopRequireDefault(_hotKeys);

var _ajax = __webpack_require__(169);

var _ajax2 = _interopRequireDefault(_ajax);

// var _finder = __webpack_require__(170);

// var _finder2 = _interopRequireDefault(_finder);

// var _connect = __webpack_require__(177);

// var _connect2 = _interopRequireDefault(_connect);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ElementorCommonApp = function (_elementorModules$Vie) {
	_inherits(ElementorCommonApp, _elementorModules$Vie);

	function ElementorCommonApp() {
		_classCallCheck(this, ElementorCommonApp);

		return _possibleConstructorReturn(this, (ElementorCommonApp.__proto__ || Object.getPrototypeOf(ElementorCommonApp)).apply(this, arguments));
	}

	_createClass(ElementorCommonApp, [{
		key: 'setMarionetteTemplateCompiler',
		value: function setMarionetteTemplateCompiler() {
			Marionette.TemplateCache.prototype.compileTemplate = function (rawTemplate, options) {
				options = {
					evaluate: /<#([\s\S]+?)#>/g,
					interpolate: /{{{([\s\S]+?)}}}/g,
					escape: /{{([^}]+?)}}(?!})/g
				};

				return _.template(rawTemplate, options);
			};
		}
	}, {
		key: 'getDefaultElements',
		value: function getDefaultElements() {
			return {
				$window: jQuery(window),
				$document: jQuery(document),
				$body: jQuery(document.body)
			};
		}
	}, {
		key: 'initComponents',
		value: function initComponents() {
			this.helpers = new _helpers2.default();

			this.storage = new _storage2.default();

			this.hotKeys = new _hotKeys2.default();

			this.hotKeys.bindListener(this.elements.$window);

			this.dialogsManager = new DialogsManager.Instance();

			this.initModules();
		}
	}, {
		key: 'initModules',
		value: function initModules() {
			var _this2 = this;

			var activeModules = this.config.activeModules;


			var modules = {
				ajax: _ajax2.default,
				// finder: _finder2.default,
				// connect: _connect2.default
			};

			activeModules.forEach(function (name) {
				if (modules[name]) {
					_this2[name] = new modules[name](_this2.config[name]);
				}
			});
		}
	}, {
		key: 'translate',
		value: function translate(stringKey, context, templateArgs, i18nStack) {
			if (context) {
				i18nStack = this.config[context] && this.config[context].i18n;
			}

			if (!i18nStack) {
				i18nStack = this.config.i18n;
			}

			var string = i18nStack && i18nStack[stringKey];

			if (undefined === string) {
				string = stringKey;
			}

			if (templateArgs) {
				string = string.replace(/%(?:(\d+)\$)?s/g, function (match, number) {
					if (!number) {
						number = 1;
					}

					number--;

					return undefined !== templateArgs[number] ? templateArgs[number] : match;
				});
			}

			return string;
		}
	}, {
		key: 'onInit',
		value: function onInit() {
			_get(ElementorCommonApp.prototype.__proto__ || Object.getPrototypeOf(ElementorCommonApp.prototype), 'onInit', this).call(this);

			this.config = elementorCommonConfig;

			this.setMarionetteTemplateCompiler();
		}
	}]);

	return ElementorCommonApp;
}(elementorModules.ViewModule);

window.elementorCommon = new ElementorCommonApp();

elementorCommon.initComponents();

/***/ }),

/***/ 168:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Helpers = function () {
	function Helpers() {
		_classCallCheck(this, Helpers);
	}

	_createClass(Helpers, [{
		key: 'deprecatedMethod',
		value: function deprecatedMethod(methodName, version, replacement) {
			var message = '%c   %c`' + methodName + '` is deprecated since ' + version;

			var style = 'font-size: 12px; background-image: url("' + elementorCommon.config.urls.assets + 'images/logo-icon.png"); background-repeat: no-repeat; background-size: contain;';

			if (replacement) {
				message += ' - Use `' + replacement + '` instead';
			}

			console.warn(message, style, ''); // eslint-disable-line no-console
		}
	}, {
		key: 'cloneObject',
		value: function cloneObject(object) {
			return JSON.parse(JSON.stringify(object));
		}
	}, {
		key: 'firstLetterUppercase',
		value: function firstLetterUppercase(string) {
			return string[0].toUpperCase() + string.slice(1);
		}
	}]);

	return Helpers;
}();

exports.default = Helpers;

/***/ }),

/***/ 169:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_elementorModules$Mod) {
	_inherits(_class, _elementorModules$Mod);

	_createClass(_class, [{
		key: 'getDefaultSettings',
		value: function getDefaultSettings() {
			return {
				ajaxParams: {
					type: 'POST',
					url: elementorCommon.config.ajax.url,
					data: {},
					dataType: 'json'
				},
				actionPrefix: 'elementor_'
			};
		}
	}]);

	function _class() {
		var _ref;

		_classCallCheck(this, _class);

		for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
			args[_key] = arguments[_key];
		}

		var _this = _possibleConstructorReturn(this, (_ref = _class.__proto__ || Object.getPrototypeOf(_class)).call.apply(_ref, [this].concat(args)));

		_this.requests = {};

		_this.cache = {};

		_this.initRequestConstants();

		_this.debounceSendBatch = _.debounce(_this.sendBatch.bind(_this), 500);
		return _this;
	}

	_createClass(_class, [{
		key: 'initRequestConstants',
		value: function initRequestConstants() {
			this.requestConstants = {
				_nonce: this.getSettings('nonce')
			};
		}
	}, {
		key: 'addRequestConstant',
		value: function addRequestConstant(key, value) {
			this.requestConstants[key] = value;
		}
	}, {
		key: 'getCacheKey',
		value: function getCacheKey(request) {
			return JSON.stringify({
				unique_id: request.unique_id,
				data: request.data
			});
		}
	}, {
		key: 'loadObjects',
		value: function loadObjects(options) {
			var _this2 = this;

			var dataCollection = {};

			var deferredArray = [];

			if (options.before) {
				options.before();
			}

			options.ids.forEach(function (objectId) {
				deferredArray.push(_this2.load({
					action: options.action,
					unique_id: options.data.unique_id + objectId,
					data: jQuery.extend({ id: objectId }, options.data)
				}).done(function (data) {
					return dataCollection = jQuery.extend(dataCollection, data);
				}));
			});

			jQuery.when.apply(jQuery, deferredArray).done(function () {
				return options.success(dataCollection);
			});
		}
	}, {
		key: 'load',
		value: function load(request) {
			var _this3 = this;

			if (!request.unique_id) {
				request.unique_id = request.action;
			}

			if (request.before) {
				request.before();
			}

			var deferred = void 0;

			var cacheKey = this.getCacheKey(request);

			if (_.has(this.cache, cacheKey)) {
				deferred = jQuery.Deferred().done(request.success).resolve(this.cache[cacheKey]);
			} else {
				deferred = this.addRequest(request.action, {
					data: request.data,
					unique_id: request.unique_id,
					success: function success(data) {
						return _this3.cache[cacheKey] = data;
					}
				}).done(request.success);
			}

			return deferred;
		}
	}, {
		key: 'addRequest',
		value: function addRequest(action, options, immediately) {
			options = options || {};

			if (!options.unique_id) {
				options.unique_id = action;
			}

			options.deferred = jQuery.Deferred().done(options.success).fail(options.error).always(options.complete);

			var request = {
				action: action,
				options: options
			};

			if (immediately) {
				var requests = {};

				requests[options.unique_id] = request;

				options.deferred.jqXhr = this.sendBatch(requests);
			} else {
				this.requests[options.unique_id] = request;

				this.debounceSendBatch();
			}

			return options.deferred;
		}
	}, {
		key: 'sendBatch',
		value: function sendBatch(requests) {
			var actions = {};

			if (!requests) {
				requests = this.requests;

				// Empty for next batch.
				this.requests = {};
			}

			Object.entries(requests).forEach(function (_ref2) {
				var _ref3 = _slicedToArray(_ref2, 2),
				    id = _ref3[0],
				    request = _ref3[1];

				return actions[id] = {
					action: request.action,
					data: request.options.data
				};
			});

			return this.send('ajax', {
				data: {
					actions: JSON.stringify(actions)
				},
				success: function success(data) {
					Object.entries(data.responses).forEach(function (_ref4) {
						var _ref5 = _slicedToArray(_ref4, 2),
						    id = _ref5[0],
						    response = _ref5[1];

						var options = requests[id].options;

						if (options) {
							if (response.success) {
								options.deferred.resolve(response.data);
							} else if (!response.success) {
								options.deferred.reject(response.data);
							}
						}
					});
				},
				error: function error(data) {
					return Object.values(requests).forEach(function (args) {
						if (args.options) {
							args.options.deferred.reject(data);
						}
					});
				}
			});
		}
	}, {
		key: 'send',
		value: function send(action, options) {
			var _this4 = this;

			var settings = this.getSettings(),
			    ajaxParams = elementorCommon.helpers.cloneObject(settings.ajaxParams);

			options = options || {};

			action = settings.actionPrefix + action;

			jQuery.extend(ajaxParams, options);

			var requestConstants = elementorCommon.helpers.cloneObject(this.requestConstants);

			requestConstants.action = action;

			var isFormData = ajaxParams.data instanceof FormData;

			Object.entries(requestConstants).forEach(function (_ref6) {
				var _ref7 = _slicedToArray(_ref6, 2),
				    key = _ref7[0],
				    value = _ref7[1];

				if (isFormData) {
					ajaxParams.data.append(key, value);
				} else {
					ajaxParams.data[key] = value;
				}
			});

			var successCallback = ajaxParams.success,
			    errorCallback = ajaxParams.error;

			if (successCallback || errorCallback) {
				ajaxParams.success = function (response) {
					if (response.success && successCallback) {
						successCallback(response.data);
					}

					if (!response.success && errorCallback) {
						errorCallback(response.data);
					}
				};

				if (errorCallback) {
					ajaxParams.error = function (data) {
						return errorCallback(data);
					};
				} else {
					ajaxParams.error = function (xmlHttpRequest) {
						if (xmlHttpRequest.readyState || 'abort' !== xmlHttpRequest.statusText) {
							_this4.trigger('request:unhandledError', xmlHttpRequest);
						}
					};
				}
			}

			return jQuery.ajax(ajaxParams);
		}
	}]);

	return _class;
}(elementorModules.Module);

exports.default = _class;

/***/ })

/******/ });
