(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});
var _yoast$analysis = yoast.analysis;
const Paper = _yoast$analysis.Paper,
      Researcher = _yoast$analysis.Researcher,
      AssessmentResult = _yoast$analysis.AssessmentResult,
      Assessment = _yoast$analysis.Assessment;
class LocalSchemaAssessment extends Assessment {
	constructor(settings) {
		super();
		this.settings = settings;
	}

	/**
  * Runs an assessment for scoring schema in the paper's text.
  *
  * @param {Paper} paper The paper to run this assessment on.
  * @param {Researcher} researcher The researcher used for the assessment.
  * @param {Object} i18n The i18n-object used for parsing translations.
  *
  * @returns {object} an assessment result with the score and formatted text.
  */
	getResult(paper, researcher, i18n) {
		const assessmentResult = new AssessmentResult();
		const schema = new RegExp('class=["\']wpseo-location["\']', 'ig');
		const addressBlock = new RegExp('class=["\']wp-block-yoast-seo-local-address["\']', 'ig');
		const matches = paper.getText().match(schema) || paper.getText().match(addressBlock) || 0;
		const result = this.score(matches);

		assessmentResult.setScore(result.score);
		assessmentResult.setText(result.text);

		return assessmentResult;
	}

	/**
  * Scores the content based on the matches of the location schema.
  *
  * @param {Array} matches The matches in the video title.
  *
  * @returns {{score: number, text: *}} An object containing the score and text
  */
	score(matches) {
		if (matches.length > 0) {
			return {
				score: 9,
				text: this.settings.address_schema
			};
		}
		return {
			score: 4,
			text: this.settings.no_address_schema
		};
	}
}
exports.default = LocalSchemaAssessment;

},{}],2:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});
var _yoast$analysis = yoast.analysis;
const Paper = _yoast$analysis.Paper,
      Researcher = _yoast$analysis.Researcher,
      AssessmentResult = _yoast$analysis.AssessmentResult,
      Assessment = _yoast$analysis.Assessment;
class LocalTitleAssessment extends Assessment {
	constructor(settings) {
		super();
		this.settings = settings;
	}

	/**
  * Tests if the selected location is present in the title or headings.
  *
  * @param {Paper} paper The paper to run this assessment on.
  * @param {Researcher} researcher The researcher used for the assessment.
  * @param {Object} i18n The i18n-object used for parsing translations.
  *
  * @returns {object} an assessment result with the score and formatted text.
  */
	getResult(paper, researcher, i18n) {
		const assessmentResult = new AssessmentResult();
		if (this.settings.location !== '') {
			const businessCity = new RegExp(this.settings.location, 'ig');
			let matches = paper.getTitle().match(businessCity) || 0;
			let result = this.scoreTitle(matches);

			// When no results, check for the location in h1 or h2 tags in the content.
			if (!matches) {
				const headings = new RegExp('<h(1|2)>.*?' + this.settings.location + '.*?<\/h(1|2)>', 'ig');
				matches = paper.getText().match(headings) || 0;
				result = this.scoreHeadings(matches);
			}

			assessmentResult.setScore(result.score);
			assessmentResult.setText(result.text);
		}
		return assessmentResult;
	}

	/**
  * Scores the url based on the matches of the location's city in the title.
  *
  * @param {Array} matches The matches in the video title.
  *
  * @returns {{score: number, text: *}} An object containing the score and text
  */
	scoreTitle(matches) {
		if (matches.length > 0) {
			return {
				score: 9,
				text: this.settings.title_location
			};
		}
		return {
			score: 4,
			text: this.settings.title_no_location
		};
	}

	/**
  * Scores the url based on the matches of the location's city in headings.
  *
  * @param {Array} matches The matches in the video title.
  *
  * @returns {{score: number, text: *}} An object containing the score and text
  */
	scoreHeadings(matches) {
		if (matches.length > 0) {
			return {
				score: 9,
				text: this.settings.heading_location
			};
		}
		return {
			score: 4,
			text: this.settings.heading_no_location
		};
	}
}
exports.default = LocalTitleAssessment;

},{}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _escapeRegExp = require("lodash/escapeRegExp");

var _escapeRegExp2 = _interopRequireDefault(_escapeRegExp);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var _yoast$analysis = yoast.analysis;
const Paper = _yoast$analysis.Paper,
      Researcher = _yoast$analysis.Researcher,
      AssessmentResult = _yoast$analysis.AssessmentResult,
      Assessment = _yoast$analysis.Assessment;
class LocalUrlAssessment extends Assessment {
	constructor(settings) {
		super();
		this.settings = settings;
	}

	/**
  * Runs an assessment for scoring the location in the URL.
  *
  * @param {Paper} paper The paper to run this assessment on.
  * @param {Researcher} researcher The researcher used for the assessment.
  * @param {Object} i18n The i18n-object used for parsing translations.
  *
  * @returns {object} an assessment result with the score and formatted text.
  */
	getResult(paper, researcher, i18n) {
		const assessmentResult = new AssessmentResult();
		if (this.settings.location !== '') {
			let location = this.settings.location;
			location = location.replace("'", "").replace(/\s/ig, "-");
			location = (0, _escapeRegExp2.default)(location);
			const business_city = new RegExp(location, 'ig');
			const matches = paper.getUrl().match(business_city) || 0;
			const result = this.score(matches);
			assessmentResult.setScore(result.score);
			assessmentResult.setText(result.text);
		}
		return assessmentResult;
	}

	/**
  * Scores the url based on the matches of the location.
  *
  * @param {Array} matches The matches in the video title.
  *
  * @returns {{score: number, text: *}} An object containing the score and text
  */
	score(matches) {
		if (matches.length > 0) {
			return {
				score: 9,
				text: this.settings.url_location
			};
		}
		return {
			score: 4,
			text: this.settings.url_no_location
		};
	}
}
exports.default = LocalUrlAssessment;

},{"lodash/escapeRegExp":13}],4:[function(require,module,exports){
'use strict';

var _localTitleAssessment = require('./assessments/local-title-assessment');

var _localTitleAssessment2 = _interopRequireDefault(_localTitleAssessment);

var _localUrlAssessment = require('./assessments/local-url-assessment');

var _localUrlAssessment2 = _interopRequireDefault(_localUrlAssessment);

var _localSchemaAssessment = require('./assessments/local-schema-assessment');

var _localSchemaAssessment2 = _interopRequireDefault(_localSchemaAssessment);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

class LocalLocationsWorker {
	register() {
		analysisWorker.registerMessageHandler('initializeLocations', this.initialize.bind(this), 'YoastLocalSEO');
	}

	initialize(settings) {
		this.titleAssessment = new _localTitleAssessment2.default(settings);
		this.urlAssessment = new _localUrlAssessment2.default(settings);
		this.schemaAssessment = new _localSchemaAssessment2.default(settings);

		analysisWorker.registerAssessment('localTitle', this.titleAssessment, 'YoastLocalSEO');
		analysisWorker.registerAssessment('localUrl', this.urlAssessment, 'YoastLocalSEO');
		analysisWorker.registerAssessment('localSchema', this.schemaAssessment, 'YoastLocalSEO');
	}
} /* global analysisWorker */


const localLocationsWorker = new LocalLocationsWorker();

localLocationsWorker.register();

},{"./assessments/local-schema-assessment":1,"./assessments/local-title-assessment":2,"./assessments/local-url-assessment":3}],5:[function(require,module,exports){
var root = require('./_root');

/** Built-in value references. */
var Symbol = root.Symbol;

module.exports = Symbol;

},{"./_root":12}],6:[function(require,module,exports){
/**
 * A specialized version of `_.map` for arrays without support for iteratee
 * shorthands.
 *
 * @private
 * @param {Array} [array] The array to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array} Returns the new mapped array.
 */
function arrayMap(array, iteratee) {
  var index = -1,
      length = array == null ? 0 : array.length,
      result = Array(length);

  while (++index < length) {
    result[index] = iteratee(array[index], index, array);
  }
  return result;
}

module.exports = arrayMap;

},{}],7:[function(require,module,exports){
var Symbol = require('./_Symbol'),
    getRawTag = require('./_getRawTag'),
    objectToString = require('./_objectToString');

/** `Object#toString` result references. */
var nullTag = '[object Null]',
    undefinedTag = '[object Undefined]';

/** Built-in value references. */
var symToStringTag = Symbol ? Symbol.toStringTag : undefined;

/**
 * The base implementation of `getTag` without fallbacks for buggy environments.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the `toStringTag`.
 */
function baseGetTag(value) {
  if (value == null) {
    return value === undefined ? undefinedTag : nullTag;
  }
  return (symToStringTag && symToStringTag in Object(value))
    ? getRawTag(value)
    : objectToString(value);
}

module.exports = baseGetTag;

},{"./_Symbol":5,"./_getRawTag":10,"./_objectToString":11}],8:[function(require,module,exports){
var Symbol = require('./_Symbol'),
    arrayMap = require('./_arrayMap'),
    isArray = require('./isArray'),
    isSymbol = require('./isSymbol');

/** Used as references for various `Number` constants. */
var INFINITY = 1 / 0;

/** Used to convert symbols to primitives and strings. */
var symbolProto = Symbol ? Symbol.prototype : undefined,
    symbolToString = symbolProto ? symbolProto.toString : undefined;

/**
 * The base implementation of `_.toString` which doesn't convert nullish
 * values to empty strings.
 *
 * @private
 * @param {*} value The value to process.
 * @returns {string} Returns the string.
 */
function baseToString(value) {
  // Exit early for strings to avoid a performance hit in some environments.
  if (typeof value == 'string') {
    return value;
  }
  if (isArray(value)) {
    // Recursively convert values (susceptible to call stack limits).
    return arrayMap(value, baseToString) + '';
  }
  if (isSymbol(value)) {
    return symbolToString ? symbolToString.call(value) : '';
  }
  var result = (value + '');
  return (result == '0' && (1 / value) == -INFINITY) ? '-0' : result;
}

module.exports = baseToString;

},{"./_Symbol":5,"./_arrayMap":6,"./isArray":14,"./isSymbol":16}],9:[function(require,module,exports){
(function (global){
/** Detect free variable `global` from Node.js. */
var freeGlobal = typeof global == 'object' && global && global.Object === Object && global;

module.exports = freeGlobal;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})

},{}],10:[function(require,module,exports){
var Symbol = require('./_Symbol');

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/** Built-in value references. */
var symToStringTag = Symbol ? Symbol.toStringTag : undefined;

/**
 * A specialized version of `baseGetTag` which ignores `Symbol.toStringTag` values.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the raw `toStringTag`.
 */
function getRawTag(value) {
  var isOwn = hasOwnProperty.call(value, symToStringTag),
      tag = value[symToStringTag];

  try {
    value[symToStringTag] = undefined;
    var unmasked = true;
  } catch (e) {}

  var result = nativeObjectToString.call(value);
  if (unmasked) {
    if (isOwn) {
      value[symToStringTag] = tag;
    } else {
      delete value[symToStringTag];
    }
  }
  return result;
}

module.exports = getRawTag;

},{"./_Symbol":5}],11:[function(require,module,exports){
/** Used for built-in method references. */
var objectProto = Object.prototype;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/**
 * Converts `value` to a string using `Object.prototype.toString`.
 *
 * @private
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 */
function objectToString(value) {
  return nativeObjectToString.call(value);
}

module.exports = objectToString;

},{}],12:[function(require,module,exports){
var freeGlobal = require('./_freeGlobal');

/** Detect free variable `self`. */
var freeSelf = typeof self == 'object' && self && self.Object === Object && self;

/** Used as a reference to the global object. */
var root = freeGlobal || freeSelf || Function('return this')();

module.exports = root;

},{"./_freeGlobal":9}],13:[function(require,module,exports){
var toString = require('./toString');

/**
 * Used to match `RegExp`
 * [syntax characters](http://ecma-international.org/ecma-262/7.0/#sec-patterns).
 */
var reRegExpChar = /[\\^$.*+?()[\]{}|]/g,
    reHasRegExpChar = RegExp(reRegExpChar.source);

/**
 * Escapes the `RegExp` special characters "^", "$", "\", ".", "*", "+",
 * "?", "(", ")", "[", "]", "{", "}", and "|" in `string`.
 *
 * @static
 * @memberOf _
 * @since 3.0.0
 * @category String
 * @param {string} [string=''] The string to escape.
 * @returns {string} Returns the escaped string.
 * @example
 *
 * _.escapeRegExp('[lodash](https://lodash.com/)');
 * // => '\[lodash\]\(https://lodash\.com/\)'
 */
function escapeRegExp(string) {
  string = toString(string);
  return (string && reHasRegExpChar.test(string))
    ? string.replace(reRegExpChar, '\\$&')
    : string;
}

module.exports = escapeRegExp;

},{"./toString":17}],14:[function(require,module,exports){
/**
 * Checks if `value` is classified as an `Array` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an array, else `false`.
 * @example
 *
 * _.isArray([1, 2, 3]);
 * // => true
 *
 * _.isArray(document.body.children);
 * // => false
 *
 * _.isArray('abc');
 * // => false
 *
 * _.isArray(_.noop);
 * // => false
 */
var isArray = Array.isArray;

module.exports = isArray;

},{}],15:[function(require,module,exports){
/**
 * Checks if `value` is object-like. A value is object-like if it's not `null`
 * and has a `typeof` result of "object".
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is object-like, else `false`.
 * @example
 *
 * _.isObjectLike({});
 * // => true
 *
 * _.isObjectLike([1, 2, 3]);
 * // => true
 *
 * _.isObjectLike(_.noop);
 * // => false
 *
 * _.isObjectLike(null);
 * // => false
 */
function isObjectLike(value) {
  return value != null && typeof value == 'object';
}

module.exports = isObjectLike;

},{}],16:[function(require,module,exports){
var baseGetTag = require('./_baseGetTag'),
    isObjectLike = require('./isObjectLike');

/** `Object#toString` result references. */
var symbolTag = '[object Symbol]';

/**
 * Checks if `value` is classified as a `Symbol` primitive or object.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a symbol, else `false`.
 * @example
 *
 * _.isSymbol(Symbol.iterator);
 * // => true
 *
 * _.isSymbol('abc');
 * // => false
 */
function isSymbol(value) {
  return typeof value == 'symbol' ||
    (isObjectLike(value) && baseGetTag(value) == symbolTag);
}

module.exports = isSymbol;

},{"./_baseGetTag":7,"./isObjectLike":15}],17:[function(require,module,exports){
var baseToString = require('./_baseToString');

/**
 * Converts `value` to a string. An empty string is returned for `null`
 * and `undefined` values. The sign of `-0` is preserved.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 * @example
 *
 * _.toString(null);
 * // => ''
 *
 * _.toString(-0);
 * // => '-0'
 *
 * _.toString([1, 2, 3]);
 * // => '1,2,3'
 */
function toString(value) {
  return value == null ? '' : baseToString(value);
}

module.exports = toString;

},{"./_baseToString":8}]},{},[4])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJqcy9zcmMvYXNzZXNzbWVudHMvbG9jYWwtc2NoZW1hLWFzc2Vzc21lbnQuanMiLCJqcy9zcmMvYXNzZXNzbWVudHMvbG9jYWwtdGl0bGUtYXNzZXNzbWVudC5qcyIsImpzL3NyYy9hc3Nlc3NtZW50cy9sb2NhbC11cmwtYXNzZXNzbWVudC5qcyIsImpzL3NyYy93cC1zZW8tbG9jYWwtd29ya2VyLWxvY2F0aW9ucy5qcyIsIm5vZGVfbW9kdWxlcy9sb2Rhc2gvX1N5bWJvbC5qcyIsIm5vZGVfbW9kdWxlcy9sb2Rhc2gvX2FycmF5TWFwLmpzIiwibm9kZV9tb2R1bGVzL2xvZGFzaC9fYmFzZUdldFRhZy5qcyIsIm5vZGVfbW9kdWxlcy9sb2Rhc2gvX2Jhc2VUb1N0cmluZy5qcyIsIm5vZGVfbW9kdWxlcy9sb2Rhc2gvX2ZyZWVHbG9iYWwuanMiLCJub2RlX21vZHVsZXMvbG9kYXNoL19nZXRSYXdUYWcuanMiLCJub2RlX21vZHVsZXMvbG9kYXNoL19vYmplY3RUb1N0cmluZy5qcyIsIm5vZGVfbW9kdWxlcy9sb2Rhc2gvX3Jvb3QuanMiLCJub2RlX21vZHVsZXMvbG9kYXNoL2VzY2FwZVJlZ0V4cC5qcyIsIm5vZGVfbW9kdWxlcy9sb2Rhc2gvaXNBcnJheS5qcyIsIm5vZGVfbW9kdWxlcy9sb2Rhc2gvaXNPYmplY3RMaWtlLmpzIiwibm9kZV9tb2R1bGVzL2xvZGFzaC9pc1N5bWJvbC5qcyIsIm5vZGVfbW9kdWxlcy9sb2Rhc2gvdG9TdHJpbmcuanMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7Ozs7OztzQkNBNEQsTUFBTSxRO01BQTFELEssbUJBQUEsSztNQUFPLFUsbUJBQUEsVTtNQUFZLGdCLG1CQUFBLGdCO01BQWtCLFUsbUJBQUEsVTtBQUU5QixNQUFNLHFCQUFOLFNBQW9DLFVBQXBDLENBQStDO0FBQzdELGFBQWEsUUFBYixFQUF3QjtBQUN2QjtBQUNBLE9BQUssUUFB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QkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUM1QkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FDckNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7QUNKQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQzlDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ3RCQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNUQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDaENBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUMxQkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQzdCQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDN0JBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJmaWxlIjoiZ2VuZXJhdGVkLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXNDb250ZW50IjpbIihmdW5jdGlvbigpe2Z1bmN0aW9uIHIoZSxuLHQpe2Z1bmN0aW9uIG8oaSxmKXtpZighbltpXSl7aWYoIWVbaV0pe3ZhciBjPVwiZnVuY3Rpb25cIj09dHlwZW9mIHJlcXVpcmUmJnJlcXVpcmU7aWYoIWYmJmMpcmV0dXJuIGMoaSwhMCk7aWYodSlyZXR1cm4gdShpLCEwKTt2YXIgYT1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK2krXCInXCIpO3Rocm93IGEuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixhfXZhciBwPW5baV09e2V4cG9ydHM6e319O2VbaV1bMF0uY2FsbChwLmV4cG9ydHMsZnVuY3Rpb24ocil7dmFyIG49ZVtpXVsxXVtyXTtyZXR1cm4gbyhufHxyKX0scCxwLmV4cG9ydHMscixlLG4sdCl9cmV0dXJuIG5baV0uZXhwb3J0c31mb3IodmFyIHU9XCJmdW5jdGlvblwiPT10eXBlb2YgcmVxdWlyZSYmcmVxdWlyZSxpPTA7aTx0Lmxlbmd0aDtpKyspbyh0W2ldKTtyZXR1cm4gb31yZXR1cm4gcn0pKCkiLCJjb25zdCB7IFBhcGVyLCBSZXNlYXJjaGVyLCBBc3Nlc3NtZW50UmVzdWx0LCBBc3Nlc3NtZW50IH0gPSB5b2FzdC5hbmFseXNpcztcblxuZXhwb3J0IGRlZmF1bHQgY2xhc3MgTG9jYWxTY2hlbWFBc3Nlc3NtZW50IGV4dGVuZHMgQXNzZXNzbWVudCB7XG5cdGNvbnN0cnVjdG9yKCBzZXR0aW5ncyApIHtcblx0XHRzdXBlcigpO1xuXHRcdHRoaXMuc2V0dGluZ3MgPSBzZXR0aW5ncztcblx0fVxuXG5cdC8qKlxuXHQgKiBSdW5zIGFuIGFzc2Vzc21lbnQgZm9yIHNjb3Jpbmcgc2NoZW1hIGluIHRoZSBwYXBlcidzIHRleHQuXG5cdCAqXG5cdCAqIEBwYXJhbSB7UGFwZXJ9IHBhcGVyIFRoZSBwYXBlciB0byBydW4gdGhpcyBhc3Nlc3NtZW50IG9uLlxuXHQgKiBAcGFyYW0ge1Jlc2VhcmNoZXJ9IHJlc2VhcmNoZXIgVGhlIHJlc2VhcmNoZXIgdXNlZCBmb3IgdGhlIGFzc2Vzc21lbnQuXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBpMThuIFRoZSBpMThuLW9iamVjdCB1c2VkIGZvciBwYXJzaW5nIHRyYW5zbGF0aW9ucy5cblx0ICpcblx0ICogQHJldHVybnMge29iamVjdH0gYW4gYXNzZXNzbWVudCByZXN1bHQgd2l0aCB0aGUgc2NvcmUgYW5kIGZvcm1hdHRlZCB0ZXh0LlxuXHQgKi9cblx0Z2V0UmVzdWx0KCBwYXBlciwgcmVzZWFyY2hlciwgaTE4biApIHtcblx0XHRjb25zdCBhc3Nlc3NtZW50UmVzdWx0ID0gbmV3IEFzc2Vzc21lbnRSZXN1bHQoKTtcblx0XHRjb25zdCBzY2hlbWEgPSBuZXcgUmVnRXhwKCAnY2xhc3M9W1wiXFwnXXdwc2VvLWxvY2F0aW9uW1wiXFwnXScsICdpZycgKTtcblx0XHRjb25zdCBhZGRyZXNzQmxvY2sgPSBuZXcgUmVnRXhwKCAnY2xhc3M9W1wiXFwnXXdwLWJsb2NrLXlvYXN0LXNlby1sb2NhbC1hZGRyZXNzW1wiXFwnXScsICdpZycgKTtcblx0XHRjb25zdCBtYXRjaGVzID0gcGFwZXIuZ2V0VGV4dCgpLm1hdGNoKCBzY2hlbWEgKSB8fCBwYXBlci5nZXRUZXh0KCkubWF0Y2goIGFkZHJlc3NCbG9jayApIHx8IDA7XG5cdFx0Y29uc3QgcmVzdWx0ID0gdGhpcy5zY29yZSggbWF0Y2hlcyApO1xuXG5cdFx0YXNzZXNzbWVudFJlc3VsdC5zZXRTY29yZSggcmVzdWx0LnNjb3JlICk7XG5cdFx0YXNzZXNzbWVudFJlc3VsdC5zZXRUZXh0KCByZXN1bHQudGV4dCApO1xuXG5cdFx0cmV0dXJuIGFzc2Vzc21lbnRSZXN1bHQ7XG5cdH1cblxuXHQvKipcblx0ICogU2NvcmVzIHRoZSBjb250ZW50IGJhc2VkIG9uIHRoZSBtYXRjaGVzIG9mIHRoZSBsb2NhdGlvbiBzY2hlbWEuXG5cdCAqXG5cdCAqIEBwYXJhbSB7QXJyYXl9IG1hdGNoZXMgVGhlIG1hdGNoZXMgaW4gdGhlIHZpZGVvIHRpdGxlLlxuXHQgKlxuXHQgKiBAcmV0dXJucyB7e3Njb3JlOiBudW1iZXIsIHRleHQ6ICp9fSBBbiBvYmplY3QgY29udGFpbmluZyB0aGUgc2NvcmUgYW5kIHRleHRcblx0ICovXG5cdHNjb3JlKCBtYXRjaGVzICkge1xuXHRcdGlmICggbWF0Y2hlcy5sZW5ndGggPiAwICkge1xuXHRcdFx0cmV0dXJue1xuXHRcdFx0XHRzY29yZTogOSxcblx0XHRcdFx0dGV4dDogdGhpcy5zZXR0aW5ncy5hZGRyZXNzX3NjaGVtYVxuXHRcdFx0fVxuXHRcdH1cblx0XHRyZXR1cm57XG5cdFx0XHRzY29yZTogNCxcblx0XHRcdHRleHQ6IHRoaXMuc2V0dGluZ3Mubm9fYWRkcmVzc19zY2hlbWFcblx0XHR9XG5cdH1cbn1cbiIsImNvbnN0IHsgUGFwZXIsIFJlc2VhcmNoZXIsIEFzc2Vzc21lbnRSZXN1bHQsIEFzc2Vzc21lbnQgfSA9IHlvYXN0LmFuYWx5c2lzO1xuXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBMb2NhbFRpdGxlQXNzZXNzbWVudCBleHRlbmRzIEFzc2Vzc21lbnQge1xuXHRjb25zdHJ1Y3Rvciggc2V0dGluZ3MgKSB7XG5cdFx0c3VwZXIoKTtcblx0XHR0aGlzLnNldHRpbmdzID0gc2V0dGluZ3M7XG5cdH1cblxuXHQvKipcblx0ICogVGVzdHMgaWYgdGhlIHNlbGVjdGVkIGxvY2F0aW9uIGlzIHByZXNlbnQgaW4gdGhlIHRpdGxlIG9yIGhlYWRpbmdzLlxuXHQgKlxuXHQgKiBAcGFyYW0ge1BhcGVyfSBwYXBlciBUaGUgcGFwZXIgdG8gcnVuIHRoaXMgYXNzZXNzbWVudCBvbi5cblx0ICogQHBhcmFtIHtSZXNlYXJjaGVyfSByZXNlYXJjaGVyIFRoZSByZXNlYXJjaGVyIHVzZWQgZm9yIHRoZSBhc3Nlc3NtZW50LlxuXHQgKiBAcGFyYW0ge09iamVjdH0gaTE4biBUaGUgaTE4bi1vYmplY3QgdXNlZCBmb3IgcGFyc2luZyB0cmFuc2xhdGlvbnMuXG5cdCAqXG5cdCAqIEByZXR1cm5zIHtvYmplY3R9IGFuIGFzc2Vzc21lbnQgcmVzdWx0IHdpdGggdGhlIHNjb3JlIGFuZCBmb3JtYXR0ZWQgdGV4dC5cblx0ICovXG5cdGdldFJlc3VsdCggcGFwZXIsIHJlc2VhcmNoZXIsIGkxOG4gKSB7XG5cdFx0Y29uc3QgYXNzZXNzbWVudFJlc3VsdCA9IG5ldyBBc3Nlc3NtZW50UmVzdWx0KCk7XG5cdFx0aWYoIHRoaXMuc2V0dGluZ3MubG9jYXRpb24gIT09ICcnICkge1xuXHRcdFx0Y29uc3QgYnVzaW5lc3NDaXR5ID0gbmV3IFJlZ0V4cCggdGhpcy5zZXR0aW5ncy5sb2NhdGlvbiwgJ2lnJyk7XG5cdFx0XHRsZXQgbWF0Y2hlcyA9IHBhcGVyLmdldFRpdGxlKCkubWF0Y2goIGJ1c2luZXNzQ2l0eSApIHx8IDA7XG5cdFx0XHRsZXQgcmVzdWx0ID0gdGhpcy5zY29yZVRpdGxlKCBtYXRjaGVzICk7XG5cblx0XHRcdC8vIFdoZW4gbm8gcmVzdWx0cywgY2hlY2sgZm9yIHRoZSBsb2NhdGlvbiBpbiBoMSBvciBoMiB0YWdzIGluIHRoZSBjb250ZW50LlxuXHRcdFx0aWYoICEgbWF0Y2hlcyApIHtcblx0XHRcdFx0Y29uc3QgaGVhZGluZ3MgPSBuZXcgUmVnRXhwKCAnPGgoMXwyKT4uKj8nICsgdGhpcy5zZXR0aW5ncy5sb2NhdGlvbiArICcuKj88XFwvaCgxfDIpPicsICdpZycgKTtcblx0XHRcdFx0bWF0Y2hlcyA9IHBhcGVyLmdldFRleHQoKS5tYXRjaCggaGVhZGluZ3MgKSB8fCAwO1xuXHRcdFx0XHRyZXN1bHQgPSB0aGlzLnNjb3JlSGVhZGluZ3MoIG1hdGNoZXMgKTtcblx0XHRcdH1cblxuXHRcdFx0YXNzZXNzbWVudFJlc3VsdC5zZXRTY29yZSggcmVzdWx0LnNjb3JlICk7XG5cdFx0XHRhc3Nlc3NtZW50UmVzdWx0LnNldFRleHQoIHJlc3VsdC50ZXh0ICk7XG5cdFx0fVxuXHRcdHJldHVybiBhc3Nlc3NtZW50UmVzdWx0O1xuXHR9XG5cblx0LyoqXG5cdCAqIFNjb3JlcyB0aGUgdXJsIGJhc2VkIG9uIHRoZSBtYXRjaGVzIG9mIHRoZSBsb2NhdGlvbidzIGNpdHkgaW4gdGhlIHRpdGxlLlxuXHQgKlxuXHQgKiBAcGFyYW0ge0FycmF5fSBtYXRjaGVzIFRoZSBtYXRjaGVzIGluIHRoZSB2aWRlbyB0aXRsZS5cblx0ICpcblx0ICogQHJldHVybnMge3tzY29yZTogbnVtYmVyLCB0ZXh0OiAqfX0gQW4gb2JqZWN0IGNvbnRhaW5pbmcgdGhlIHNjb3JlIGFuZCB0ZXh0XG5cdCAqL1xuXHRzY29yZVRpdGxlKCBtYXRjaGVzICkge1xuXHRcdGlmICggbWF0Y2hlcy5sZW5ndGggPiAwICkge1xuXHRcdFx0cmV0dXJuIHtcblx0XHRcdFx0c2NvcmU6IDksXG5cdFx0XHRcdHRleHQ6IHRoaXMuc2V0dGluZ3MudGl0bGVfbG9jYXRpb25cblx0XHRcdH1cblx0XHR9XG5cdFx0cmV0dXJuIHtcblx0XHRcdHNjb3JlOiA0LFxuXHRcdFx0dGV4dDogdGhpcy5zZXR0aW5ncy50aXRsZV9ub19sb2NhdGlvblxuXHRcdH1cblx0fVxuXG5cdC8qKlxuXHQgKiBTY29yZXMgdGhlIHVybCBiYXNlZCBvbiB0aGUgbWF0Y2hlcyBvZiB0aGUgbG9jYXRpb24ncyBjaXR5IGluIGhlYWRpbmdzLlxuXHQgKlxuXHQgKiBAcGFyYW0ge0FycmF5fSBtYXRjaGVzIFRoZSBtYXRjaGVzIGluIHRoZSB2aWRlbyB0aXRsZS5cblx0ICpcblx0ICogQHJldHVybnMge3tzY29yZTogbnVtYmVyLCB0ZXh0OiAqfX0gQW4gb2JqZWN0IGNvbnRhaW5pbmcgdGhlIHNjb3JlIGFuZCB0ZXh0XG5cdCAqL1xuXHRzY29yZUhlYWRpbmdzKCBtYXRjaGVzICkge1xuXHRcdGlmICggbWF0Y2hlcy5sZW5ndGggPiAwICkge1xuXHRcdFx0cmV0dXJue1xuXHRcdFx0XHRzY29yZTogOSxcblx0XHRcdFx0dGV4dDogdGhpcy5zZXR0aW5ncy5oZWFkaW5nX2xvY2F0aW9uXG5cdFx0XHR9XG5cdFx0fVxuXHRcdHJldHVybntcblx0XHRcdHNjb3JlOiA0LFxuXHRcdFx0dGV4dDogdGhpcy5zZXR0aW5ncy5oZWFkaW5nX25vX2xvY2F0aW9uXG5cdFx0fVxuXHR9XG59XG4iLCJpbXBvcnQgZXNjYXBlUmVnRXhwIGZyb20gXCJsb2Rhc2gvZXNjYXBlUmVnRXhwXCI7XG5cbmNvbnN0IHsgUGFwZXIsIFJlc2VhcmNoZXIsIEFzc2Vzc21lbnRSZXN1bHQsIEFzc2Vzc21lbnQgfSA9IHlvYXN0LmFuYWx5c2lzO1xuXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBMb2NhbFVybEFzc2Vzc21lbnQgZXh0ZW5kcyBBc3Nlc3NtZW50IHtcblx0Y29uc3RydWN0b3IoIHNldHRpbmdzICkge1xuXHRcdHN1cGVyKCk7XG5cdFx0dGhpcy5zZXR0aW5ncyA9IHNldHRpbmdzO1xuXHR9XG5cblx0LyoqXG5cdCAqIFJ1bnMgYW4gYXNzZXNzbWVudCBmb3Igc2NvcmluZyB0aGUgbG9jYXRpb24gaW4gdGhlIFVSTC5cblx0ICpcblx0ICogQHBhcmFtIHtQYXBlcn0gcGFwZXIgVGhlIHBhcGVyIHRvIHJ1biB0aGlzIGFzc2Vzc21lbnQgb24uXG5cdCAqIEBwYXJhbSB7UmVzZWFyY2hlcn0gcmVzZWFyY2hlciBUaGUgcmVzZWFyY2hlciB1c2VkIGZvciB0aGUgYXNzZXNzbWVudC5cblx0ICogQHBhcmFtIHtPYmplY3R9IGkxOG4gVGhlIGkxOG4tb2JqZWN0IHVzZWQgZm9yIHBhcnNpbmcgdHJhbnNsYXRpb25zLlxuXHQgKlxuXHQgKiBAcmV0dXJucyB7b2JqZWN0fSBhbiBhc3Nlc3NtZW50IHJlc3VsdCB3aXRoIHRoZSBzY29yZSBhbmQgZm9ybWF0dGVkIHRleHQuXG5cdCAqL1xuXHRnZXRSZXN1bHQoIHBhcGVyLCByZXNlYXJjaGVyLCBpMThuICkge1xuXHRcdGNvbnN0IGFzc2Vzc21lbnRSZXN1bHQgPSBuZXcgQXNzZXNzbWVudFJlc3VsdCgpO1xuXHRcdGlmKCB0aGlzLnNldHRpbmdzLmxvY2F0aW9uICE9PSAnJyApIHtcblx0XHRcdGxldCBsb2NhdGlvbiA9IHRoaXMuc2V0dGluZ3MubG9jYXRpb247XG5cdFx0XHRsb2NhdGlvbiA9IGxvY2F0aW9uLnJlcGxhY2UoIFwiJ1wiLCBcIlwiICkucmVwbGFjZSggL1xccy9pZywgXCItXCIgKTtcblx0XHRcdGxvY2F0aW9uID0gZXNjYXBlUmVnRXhwKCBsb2NhdGlvbiApO1xuXHRcdFx0Y29uc3QgYnVzaW5lc3NfY2l0eSA9IG5ldyBSZWdFeHAoIGxvY2F0aW9uLCAnaWcnICk7XG5cdFx0XHRjb25zdCBtYXRjaGVzID0gcGFwZXIuZ2V0VXJsKCkubWF0Y2goIGJ1c2luZXNzX2NpdHkgKSB8fCAwO1xuXHRcdFx0Y29uc3QgcmVzdWx0ID0gdGhpcy5zY29yZSggbWF0Y2hlcyApO1xuXHRcdFx0YXNzZXNzbWVudFJlc3VsdC5zZXRTY29yZSggcmVzdWx0LnNjb3JlICk7XG5cdFx0XHRhc3Nlc3NtZW50UmVzdWx0LnNldFRleHQoIHJlc3VsdC50ZXh0ICk7XG5cdFx0fVxuXHRcdHJldHVybiBhc3Nlc3NtZW50UmVzdWx0O1xuXHR9XG5cblx0LyoqXG5cdCAqIFNjb3JlcyB0aGUgdXJsIGJhc2VkIG9uIHRoZSBtYXRjaGVzIG9mIHRoZSBsb2NhdGlvbi5cblx0ICpcblx0ICogQHBhcmFtIHtBcnJheX0gbWF0Y2hlcyBUaGUgbWF0Y2hlcyBpbiB0aGUgdmlkZW8gdGl0bGUuXG5cdCAqXG5cdCAqIEByZXR1cm5zIHt7c2NvcmU6IG51bWJlciwgdGV4dDogKn19IEFuIG9iamVjdCBjb250YWluaW5nIHRoZSBzY29yZSBhbmQgdGV4dFxuXHQgKi9cblx0c2NvcmUoIG1hdGNoZXMgKSB7XG5cdFx0aWYgKCBtYXRjaGVzLmxlbmd0aCA+IDAgKSB7XG5cdFx0XHRyZXR1cm57XG5cdFx0XHRcdHNjb3JlOiA5LFxuXHRcdFx0XHR0ZXh0OiB0aGlzLnNldHRpbmdzLnVybF9sb2NhdGlvblxuXHRcdFx0fVxuXHRcdH1cblx0XHRyZXR1cm57XG5cdFx0XHRzY29yZTogNCxcblx0XHRcdHRleHQ6IHRoaXMuc2V0dGluZ3MudXJsX25vX2xvY2F0aW9uXG5cdFx0fVxuXHR9XG59XG4iLCIvKiBnbG9iYWwgYW5hbHlzaXNXb3JrZXIgKi9cbmltcG9ydCBMb2NhbFRpdGxlQXNzZXNzbWVudCBmcm9tICcuL2Fzc2Vzc21lbnRzL2xvY2FsLXRpdGxlLWFzc2Vzc21lbnQnO1xuaW1wb3J0IExvY2FsVXJsQXNzZXNzbWVudCBmcm9tICcuL2Fzc2Vzc21lbnRzL2xvY2FsLXVybC1hc3Nlc3NtZW50JztcbmltcG9ydCBMb2NhbFNjaGVtYUFzc2Vzc21lbnQgZnJvbSAnLi9hc3Nlc3NtZW50cy9sb2NhbC1zY2hlbWEtYXNzZXNzbWVudCc7XG5cbmNsYXNzIExvY2FsTG9jYXRpb25zV29ya2VyIHtcblx0cmVnaXN0ZXIoKSB7XG5cdFx0YW5hbHlzaXNXb3JrZXIucmVnaXN0ZXJNZXNzYWdlSGFuZGxlciggJ2luaXRpYWxpemVMb2NhdGlvbnMnLCB0aGlzLmluaXRpYWxpemUuYmluZCggdGhpcyApLCAnWW9hc3RMb2NhbFNFTycgKTtcblx0fVxuXG5cdGluaXRpYWxpemUoIHNldHRpbmdzICkge1xuXHRcdHRoaXMudGl0bGVBc3Nlc3NtZW50ID0gbmV3IExvY2FsVGl0bGVBc3Nlc3NtZW50KCBzZXR0aW5ncyApO1xuXHRcdHRoaXMudXJsQXNzZXNzbWVudCA9IG5ldyBMb2NhbFVybEFzc2Vzc21lbnQoIHNldHRpbmdzICk7XG5cdFx0dGhpcy5zY2hlbWFBc3Nlc3NtZW50ID0gbmV3IExvY2FsU2NoZW1hQXNzZXNzbWVudCggc2V0dGluZ3MgKTtcblxuXHRcdGFuYWx5c2lzV29ya2VyLnJlZ2lzdGVyQXNzZXNzbWVudCggJ2xvY2FsVGl0bGUnLCB0aGlzLnRpdGxlQXNzZXNzbWVudCwgJ1lvYXN0TG9jYWxTRU8nICk7XG5cdFx0YW5hbHlzaXNXb3JrZXIucmVnaXN0ZXJBc3Nlc3NtZW50KCAnbG9jYWxVcmwnLCB0aGlzLnVybEFzc2Vzc21lbnQsICdZb2FzdExvY2FsU0VPJyApO1xuXHRcdGFuYWx5c2lzV29ya2VyLnJlZ2lzdGVyQXNzZXNzbWVudCggJ2xvY2FsU2NoZW1hJywgdGhpcy5zY2hlbWFBc3Nlc3NtZW50LCAnWW9hc3RMb2NhbFNFTycgKTtcblx0fVxufVxuXG5jb25zdCBsb2NhbExvY2F0aW9uc1dvcmtlciA9IG5ldyBMb2NhbExvY2F0aW9uc1dvcmtlcigpO1xuXG5sb2NhbExvY2F0aW9uc1dvcmtlci5yZWdpc3RlcigpO1xuIiwidmFyIHJvb3QgPSByZXF1aXJlKCcuL19yb290Jyk7XG5cbi8qKiBCdWlsdC1pbiB2YWx1ZSByZWZlcmVuY2VzLiAqL1xudmFyIFN5bWJvbCA9IHJvb3QuU3ltYm9sO1xuXG5tb2R1bGUuZXhwb3J0cyA9IFN5bWJvbDtcbiIsIi8qKlxuICogQSBzcGVjaWFsaXplZCB2ZXJzaW9uIG9mIGBfLm1hcGAgZm9yIGFycmF5cyB3aXRob3V0IHN1cHBvcnQgZm9yIGl0ZXJhdGVlXG4gKiBzaG9ydGhhbmRzLlxuICpcbiAqIEBwcml2YXRlXG4gKiBAcGFyYW0ge0FycmF5fSBbYXJyYXldIFRoZSBhcnJheSB0byBpdGVyYXRlIG92ZXIuXG4gKiBAcGFyYW0ge0Z1bmN0aW9ufSBpdGVyYXRlZSBUaGUgZnVuY3Rpb24gaW52b2tlZCBwZXIgaXRlcmF0aW9uLlxuICogQHJldHVybnMge0FycmF5fSBSZXR1cm5zIHRoZSBuZXcgbWFwcGVkIGFycmF5LlxuICovXG5mdW5jdGlvbiBhcnJheU1hcChhcnJheSwgaXRlcmF0ZWUpIHtcbiAgdmFyIGluZGV4ID0gLTEsXG4gICAgICBsZW5ndGggPSBhcnJheSA9PSBudWxsID8gMCA6IGFycmF5Lmxlbmd0aCxcbiAgICAgIHJlc3VsdCA9IEFycmF5KGxlbmd0aCk7XG5cbiAgd2hpbGUgKCsraW5kZXggPCBsZW5ndGgpIHtcbiAgICByZXN1bHRbaW5kZXhdID0gaXRlcmF0ZWUoYXJyYXlbaW5kZXhdLCBpbmRleCwgYXJyYXkpO1xuICB9XG4gIHJldHVybiByZXN1bHQ7XG59XG5cbm1vZHVsZS5leHBvcnRzID0gYXJyYXlNYXA7XG4iLCJ2YXIgU3ltYm9sID0gcmVxdWlyZSgnLi9fU3ltYm9sJyksXG4gICAgZ2V0UmF3VGFnID0gcmVxdWlyZSgnLi9fZ2V0UmF3VGFnJyksXG4gICAgb2JqZWN0VG9TdHJpbmcgPSByZXF1aXJlKCcuL19vYmplY3RUb1N0cmluZycpO1xuXG4vKiogYE9iamVjdCN0b1N0cmluZ2AgcmVzdWx0IHJlZmVyZW5jZXMuICovXG52YXIgbnVsbFRhZyA9ICdbb2JqZWN0IE51bGxdJyxcbiAgICB1bmRlZmluZWRUYWcgPSAnW29iamVjdCBVbmRlZmluZWRdJztcblxuLyoqIEJ1aWx0LWluIHZhbHVlIHJlZmVyZW5jZXMuICovXG52YXIgc3ltVG9TdHJpbmdUYWcgPSBTeW1ib2wgPyBTeW1ib2wudG9TdHJpbmdUYWcgOiB1bmRlZmluZWQ7XG5cbi8qKlxuICogVGhlIGJhc2UgaW1wbGVtZW50YXRpb24gb2YgYGdldFRhZ2Agd2l0aG91dCBmYWxsYmFja3MgZm9yIGJ1Z2d5IGVudmlyb25tZW50cy5cbiAqXG4gKiBAcHJpdmF0ZVxuICogQHBhcmFtIHsqfSB2YWx1ZSBUaGUgdmFsdWUgdG8gcXVlcnkuXG4gKiBAcmV0dXJucyB7c3RyaW5nfSBSZXR1cm5zIHRoZSBgdG9TdHJpbmdUYWdgLlxuICovXG5mdW5jdGlvbiBiYXNlR2V0VGFnKHZhbHVlKSB7XG4gIGlmICh2YWx1ZSA9PSBudWxsKSB7XG4gICAgcmV0dXJuIHZhbHVlID09PSB1bmRlZmluZWQgPyB1bmRlZmluZWRUYWcgOiBudWxsVGFnO1xuICB9XG4gIHJldHVybiAoc3ltVG9TdHJpbmdUYWcgJiYgc3ltVG9TdHJpbmdUYWcgaW4gT2JqZWN0KHZhbHVlKSlcbiAgICA/IGdldFJhd1RhZyh2YWx1ZSlcbiAgICA6IG9iamVjdFRvU3RyaW5nKHZhbHVlKTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBiYXNlR2V0VGFnO1xuIiwidmFyIFN5bWJvbCA9IHJlcXVpcmUoJy4vX1N5bWJvbCcpLFxuICAgIGFycmF5TWFwID0gcmVxdWlyZSgnLi9fYXJyYXlNYXAnKSxcbiAgICBpc0FycmF5ID0gcmVxdWlyZSgnLi9pc0FycmF5JyksXG4gICAgaXNTeW1ib2wgPSByZXF1aXJlKCcuL2lzU3ltYm9sJyk7XG5cbi8qKiBVc2VkIGFzIHJlZmVyZW5jZXMgZm9yIHZhcmlvdXMgYE51bWJlcmAgY29uc3RhbnRzLiAqL1xudmFyIElORklOSVRZID0gMSAvIDA7XG5cbi8qKiBVc2VkIHRvIGNvbnZlcnQgc3ltYm9scyB0byBwcmltaXRpdmVzIGFuZCBzdHJpbmdzLiAqL1xudmFyIHN5bWJvbFByb3RvID0gU3ltYm9sID8gU3ltYm9sLnByb3RvdHlwZSA6IHVuZGVmaW5lZCxcbiAgICBzeW1ib2xUb1N0cmluZyA9IHN5bWJvbFByb3RvID8gc3ltYm9sUHJvdG8udG9TdHJpbmcgOiB1bmRlZmluZWQ7XG5cbi8qKlxuICogVGhlIGJhc2UgaW1wbGVtZW50YXRpb24gb2YgYF8udG9TdHJpbmdgIHdoaWNoIGRvZXNuJ3QgY29udmVydCBudWxsaXNoXG4gKiB2YWx1ZXMgdG8gZW1wdHkgc3RyaW5ncy5cbiAqXG4gKiBAcHJpdmF0ZVxuICogQHBhcmFtIHsqfSB2YWx1ZSBUaGUgdmFsdWUgdG8gcHJvY2Vzcy5cbiAqIEByZXR1cm5zIHtzdHJpbmd9IFJldHVybnMgdGhlIHN0cmluZy5cbiAqL1xuZnVuY3Rpb24gYmFzZVRvU3RyaW5nKHZhbHVlKSB7XG4gIC8vIEV4aXQgZWFybHkgZm9yIHN0cmluZ3MgdG8gYXZvaWQgYSBwZXJmb3JtYW5jZSBoaXQgaW4gc29tZSBlbnZpcm9ubWVudHMuXG4gIGlmICh0eXBlb2YgdmFsdWUgPT0gJ3N0cmluZycpIHtcbiAgICByZXR1cm4gdmFsdWU7XG4gIH1cbiAgaWYgKGlzQXJyYXkodmFsdWUpKSB7XG4gICAgLy8gUmVjdXJzaXZlbHkgY29udmVydCB2YWx1ZXMgKHN1c2NlcHRpYmxlIHRvIGNhbGwgc3RhY2sgbGltaXRzKS5cbiAgICByZXR1cm4gYXJyYXlNYXAodmFsdWUsIGJhc2VUb1N0cmluZykgKyAnJztcbiAgfVxuICBpZiAoaXNTeW1ib2wodmFsdWUpKSB7XG4gICAgcmV0dXJuIHN5bWJvbFRvU3RyaW5nID8gc3ltYm9sVG9TdHJpbmcuY2FsbCh2YWx1ZSkgOiAnJztcbiAgfVxuICB2YXIgcmVzdWx0ID0gKHZhbHVlICsgJycpO1xuICByZXR1cm4gKHJlc3VsdCA9PSAnMCcgJiYgKDEgLyB2YWx1ZSkgPT0gLUlORklOSVRZKSA/ICctMCcgOiByZXN1bHQ7XG59XG5cbm1vZHVsZS5leHBvcnRzID0gYmFzZVRvU3RyaW5nO1xuIiwiLyoqIERldGVjdCBmcmVlIHZhcmlhYmxlIGBnbG9iYWxgIGZyb20gTm9kZS5qcy4gKi9cbnZhciBmcmVlR2xvYmFsID0gdHlwZW9mIGdsb2JhbCA9PSAnb2JqZWN0JyAmJiBnbG9iYWwgJiYgZ2xvYmFsLk9iamVjdCA9PT0gT2JqZWN0ICYmIGdsb2JhbDtcblxubW9kdWxlLmV4cG9ydHMgPSBmcmVlR2xvYmFsO1xuIiwidmFyIFN5bWJvbCA9IHJlcXVpcmUoJy4vX1N5bWJvbCcpO1xuXG4vKiogVXNlZCBmb3IgYnVpbHQtaW4gbWV0aG9kIHJlZmVyZW5jZXMuICovXG52YXIgb2JqZWN0UHJvdG8gPSBPYmplY3QucHJvdG90eXBlO1xuXG4vKiogVXNlZCB0byBjaGVjayBvYmplY3RzIGZvciBvd24gcHJvcGVydGllcy4gKi9cbnZhciBoYXNPd25Qcm9wZXJ0eSA9IG9iamVjdFByb3RvLmhhc093blByb3BlcnR5O1xuXG4vKipcbiAqIFVzZWQgdG8gcmVzb2x2ZSB0aGVcbiAqIFtgdG9TdHJpbmdUYWdgXShodHRwOi8vZWNtYS1pbnRlcm5hdGlvbmFsLm9yZy9lY21hLTI2Mi83LjAvI3NlYy1vYmplY3QucHJvdG90eXBlLnRvc3RyaW5nKVxuICogb2YgdmFsdWVzLlxuICovXG52YXIgbmF0aXZlT2JqZWN0VG9TdHJpbmcgPSBvYmplY3RQcm90by50b1N0cmluZztcblxuLyoqIEJ1aWx0LWluIHZhbHVlIHJlZmVyZW5jZXMuICovXG52YXIgc3ltVG9TdHJpbmdUYWcgPSBTeW1ib2wgPyBTeW1ib2wudG9TdHJpbmdUYWcgOiB1bmRlZmluZWQ7XG5cbi8qKlxuICogQSBzcGVjaWFsaXplZCB2ZXJzaW9uIG9mIGBiYXNlR2V0VGFnYCB3aGljaCBpZ25vcmVzIGBTeW1ib2wudG9TdHJpbmdUYWdgIHZhbHVlcy5cbiAqXG4gKiBAcHJpdmF0ZVxuICogQHBhcmFtIHsqfSB2YWx1ZSBUaGUgdmFsdWUgdG8gcXVlcnkuXG4gKiBAcmV0dXJucyB7c3RyaW5nfSBSZXR1cm5zIHRoZSByYXcgYHRvU3RyaW5nVGFnYC5cbiAqL1xuZnVuY3Rpb24gZ2V0UmF3VGFnKHZhbHVlKSB7XG4gIHZhciBpc093biA9IGhhc093blByb3BlcnR5LmNhbGwodmFsdWUsIHN5bVRvU3RyaW5nVGFnKSxcbiAgICAgIHRhZyA9IHZhbHVlW3N5bVRvU3RyaW5nVGFnXTtcblxuICB0cnkge1xuICAgIHZhbHVlW3N5bVRvU3RyaW5nVGFnXSA9IHVuZGVmaW5lZDtcbiAgICB2YXIgdW5tYXNrZWQgPSB0cnVlO1xuICB9IGNhdGNoIChlKSB7fVxuXG4gIHZhciByZXN1bHQgPSBuYXRpdmVPYmplY3RUb1N0cmluZy5jYWxsKHZhbHVlKTtcbiAgaWYgKHVubWFza2VkKSB7XG4gICAgaWYgKGlzT3duKSB7XG4gICAgICB2YWx1ZVtzeW1Ub1N0cmluZ1RhZ10gPSB0YWc7XG4gICAgfSBlbHNlIHtcbiAgICAgIGRlbGV0ZSB2YWx1ZVtzeW1Ub1N0cmluZ1RhZ107XG4gICAgfVxuICB9XG4gIHJldHVybiByZXN1bHQ7XG59XG5cbm1vZHVsZS5leHBvcnRzID0gZ2V0UmF3VGFnO1xuIiwiLyoqIFVzZWQgZm9yIGJ1aWx0LWluIG1ldGhvZCByZWZlcmVuY2VzLiAqL1xudmFyIG9iamVjdFByb3RvID0gT2JqZWN0LnByb3RvdHlwZTtcblxuLyoqXG4gKiBVc2VkIHRvIHJlc29sdmUgdGhlXG4gKiBbYHRvU3RyaW5nVGFnYF0oaHR0cDovL2VjbWEtaW50ZXJuYXRpb25hbC5vcmcvZWNtYS0yNjIvNy4wLyNzZWMtb2JqZWN0LnByb3RvdHlwZS50b3N0cmluZylcbiAqIG9mIHZhbHVlcy5cbiAqL1xudmFyIG5hdGl2ZU9iamVjdFRvU3RyaW5nID0gb2JqZWN0UHJvdG8udG9TdHJpbmc7XG5cbi8qKlxuICogQ29udmVydHMgYHZhbHVlYCB0byBhIHN0cmluZyB1c2luZyBgT2JqZWN0LnByb3RvdHlwZS50b1N0cmluZ2AuXG4gKlxuICogQHByaXZhdGVcbiAqIEBwYXJhbSB7Kn0gdmFsdWUgVGhlIHZhbHVlIHRvIGNvbnZlcnQuXG4gKiBAcmV0dXJucyB7c3RyaW5nfSBSZXR1cm5zIHRoZSBjb252ZXJ0ZWQgc3RyaW5nLlxuICovXG5mdW5jdGlvbiBvYmplY3RUb1N0cmluZyh2YWx1ZSkge1xuICByZXR1cm4gbmF0aXZlT2JqZWN0VG9TdHJpbmcuY2FsbCh2YWx1ZSk7XG59XG5cbm1vZHVsZS5leHBvcnRzID0gb2JqZWN0VG9TdHJpbmc7XG4iLCJ2YXIgZnJlZUdsb2JhbCA9IHJlcXVpcmUoJy4vX2ZyZWVHbG9iYWwnKTtcblxuLyoqIERldGVjdCBmcmVlIHZhcmlhYmxlIGBzZWxmYC4gKi9cbnZhciBmcmVlU2VsZiA9IHR5cGVvZiBzZWxmID09ICdvYmplY3QnICYmIHNlbGYgJiYgc2VsZi5PYmplY3QgPT09IE9iamVjdCAmJiBzZWxmO1xuXG4vKiogVXNlZCBhcyBhIHJlZmVyZW5jZSB0byB0aGUgZ2xvYmFsIG9iamVjdC4gKi9cbnZhciByb290ID0gZnJlZUdsb2JhbCB8fCBmcmVlU2VsZiB8fCBGdW5jdGlvbigncmV0dXJuIHRoaXMnKSgpO1xuXG5tb2R1bGUuZXhwb3J0cyA9IHJvb3Q7XG4iLCJ2YXIgdG9TdHJpbmcgPSByZXF1aXJlKCcuL3RvU3RyaW5nJyk7XG5cbi8qKlxuICogVXNlZCB0byBtYXRjaCBgUmVnRXhwYFxuICogW3N5bnRheCBjaGFyYWN0ZXJzXShodHRwOi8vZWNtYS1pbnRlcm5hdGlvbmFsLm9yZy9lY21hLTI2Mi83LjAvI3NlYy1wYXR0ZXJucykuXG4gKi9cbnZhciByZVJlZ0V4cENoYXIgPSAvW1xcXFxeJC4qKz8oKVtcXF17fXxdL2csXG4gICAgcmVIYXNSZWdFeHBDaGFyID0gUmVnRXhwKHJlUmVnRXhwQ2hhci5zb3VyY2UpO1xuXG4vKipcbiAqIEVzY2FwZXMgdGhlIGBSZWdFeHBgIHNwZWNpYWwgY2hhcmFjdGVycyBcIl5cIiwgXCIkXCIsIFwiXFxcIiwgXCIuXCIsIFwiKlwiLCBcIitcIixcbiAqIFwiP1wiLCBcIihcIiwgXCIpXCIsIFwiW1wiLCBcIl1cIiwgXCJ7XCIsIFwifVwiLCBhbmQgXCJ8XCIgaW4gYHN0cmluZ2AuXG4gKlxuICogQHN0YXRpY1xuICogQG1lbWJlck9mIF9cbiAqIEBzaW5jZSAzLjAuMFxuICogQGNhdGVnb3J5IFN0cmluZ1xuICogQHBhcmFtIHtzdHJpbmd9IFtzdHJpbmc9JyddIFRoZSBzdHJpbmcgdG8gZXNjYXBlLlxuICogQHJldHVybnMge3N0cmluZ30gUmV0dXJucyB0aGUgZXNjYXBlZCBzdHJpbmcuXG4gKiBAZXhhbXBsZVxuICpcbiAqIF8uZXNjYXBlUmVnRXhwKCdbbG9kYXNoXShodHRwczovL2xvZGFzaC5jb20vKScpO1xuICogLy8gPT4gJ1xcW2xvZGFzaFxcXVxcKGh0dHBzOi8vbG9kYXNoXFwuY29tL1xcKSdcbiAqL1xuZnVuY3Rpb24gZXNjYXBlUmVnRXhwKHN0cmluZykge1xuICBzdHJpbmcgPSB0b1N0cmluZyhzdHJpbmcpO1xuICByZXR1cm4gKHN0cmluZyAmJiByZUhhc1JlZ0V4cENoYXIudGVzdChzdHJpbmcpKVxuICAgID8gc3RyaW5nLnJlcGxhY2UocmVSZWdFeHBDaGFyLCAnXFxcXCQmJylcbiAgICA6IHN0cmluZztcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBlc2NhcGVSZWdFeHA7XG4iLCIvKipcbiAqIENoZWNrcyBpZiBgdmFsdWVgIGlzIGNsYXNzaWZpZWQgYXMgYW4gYEFycmF5YCBvYmplY3QuXG4gKlxuICogQHN0YXRpY1xuICogQG1lbWJlck9mIF9cbiAqIEBzaW5jZSAwLjEuMFxuICogQGNhdGVnb3J5IExhbmdcbiAqIEBwYXJhbSB7Kn0gdmFsdWUgVGhlIHZhbHVlIHRvIGNoZWNrLlxuICogQHJldHVybnMge2Jvb2xlYW59IFJldHVybnMgYHRydWVgIGlmIGB2YWx1ZWAgaXMgYW4gYXJyYXksIGVsc2UgYGZhbHNlYC5cbiAqIEBleGFtcGxlXG4gKlxuICogXy5pc0FycmF5KFsxLCAyLCAzXSk7XG4gKiAvLyA9PiB0cnVlXG4gKlxuICogXy5pc0FycmF5KGRvY3VtZW50LmJvZHkuY2hpbGRyZW4pO1xuICogLy8gPT4gZmFsc2VcbiAqXG4gKiBfLmlzQXJyYXkoJ2FiYycpO1xuICogLy8gPT4gZmFsc2VcbiAqXG4gKiBfLmlzQXJyYXkoXy5ub29wKTtcbiAqIC8vID0+IGZhbHNlXG4gKi9cbnZhciBpc0FycmF5ID0gQXJyYXkuaXNBcnJheTtcblxubW9kdWxlLmV4cG9ydHMgPSBpc0FycmF5O1xuIiwiLyoqXG4gKiBDaGVja3MgaWYgYHZhbHVlYCBpcyBvYmplY3QtbGlrZS4gQSB2YWx1ZSBpcyBvYmplY3QtbGlrZSBpZiBpdCdzIG5vdCBgbnVsbGBcbiAqIGFuZCBoYXMgYSBgdHlwZW9mYCByZXN1bHQgb2YgXCJvYmplY3RcIi5cbiAqXG4gKiBAc3RhdGljXG4gKiBAbWVtYmVyT2YgX1xuICogQHNpbmNlIDQuMC4wXG4gKiBAY2F0ZWdvcnkgTGFuZ1xuICogQHBhcmFtIHsqfSB2YWx1ZSBUaGUgdmFsdWUgdG8gY2hlY2suXG4gKiBAcmV0dXJucyB7Ym9vbGVhbn0gUmV0dXJucyBgdHJ1ZWAgaWYgYHZhbHVlYCBpcyBvYmplY3QtbGlrZSwgZWxzZSBgZmFsc2VgLlxuICogQGV4YW1wbGVcbiAqXG4gKiBfLmlzT2JqZWN0TGlrZSh7fSk7XG4gKiAvLyA9PiB0cnVlXG4gKlxuICogXy5pc09iamVjdExpa2UoWzEsIDIsIDNdKTtcbiAqIC8vID0+IHRydWVcbiAqXG4gKiBfLmlzT2JqZWN0TGlrZShfLm5vb3ApO1xuICogLy8gPT4gZmFsc2VcbiAqXG4gKiBfLmlzT2JqZWN0TGlrZShudWxsKTtcbiAqIC8vID0+IGZhbHNlXG4gKi9cbmZ1bmN0aW9uIGlzT2JqZWN0TGlrZSh2YWx1ZSkge1xuICByZXR1cm4gdmFsdWUgIT0gbnVsbCAmJiB0eXBlb2YgdmFsdWUgPT0gJ29iamVjdCc7XG59XG5cbm1vZHVsZS5leHBvcnRzID0gaXNPYmplY3RMaWtlO1xuIiwidmFyIGJhc2VHZXRUYWcgPSByZXF1aXJlKCcuL19iYXNlR2V0VGFnJyksXG4gICAgaXNPYmplY3RMaWtlID0gcmVxdWlyZSgnLi9pc09iamVjdExpa2UnKTtcblxuLyoqIGBPYmplY3QjdG9TdHJpbmdgIHJlc3VsdCByZWZlcmVuY2VzLiAqL1xudmFyIHN5bWJvbFRhZyA9ICdbb2JqZWN0IFN5bWJvbF0nO1xuXG4vKipcbiAqIENoZWNrcyBpZiBgdmFsdWVgIGlzIGNsYXNzaWZpZWQgYXMgYSBgU3ltYm9sYCBwcmltaXRpdmUgb3Igb2JqZWN0LlxuICpcbiAqIEBzdGF0aWNcbiAqIEBtZW1iZXJPZiBfXG4gKiBAc2luY2UgNC4wLjBcbiAqIEBjYXRlZ29yeSBMYW5nXG4gKiBAcGFyYW0geyp9IHZhbHVlIFRoZSB2YWx1ZSB0byBjaGVjay5cbiAqIEByZXR1cm5zIHtib29sZWFufSBSZXR1cm5zIGB0cnVlYCBpZiBgdmFsdWVgIGlzIGEgc3ltYm9sLCBlbHNlIGBmYWxzZWAuXG4gKiBAZXhhbXBsZVxuICpcbiAqIF8uaXNTeW1ib2woU3ltYm9sLml0ZXJhdG9yKTtcbiAqIC8vID0+IHRydWVcbiAqXG4gKiBfLmlzU3ltYm9sKCdhYmMnKTtcbiAqIC8vID0+IGZhbHNlXG4gKi9cbmZ1bmN0aW9uIGlzU3ltYm9sKHZhbHVlKSB7XG4gIHJldHVybiB0eXBlb2YgdmFsdWUgPT0gJ3N5bWJvbCcgfHxcbiAgICAoaXNPYmplY3RMaWtlKHZhbHVlKSAmJiBiYXNlR2V0VGFnKHZhbHVlKSA9PSBzeW1ib2xUYWcpO1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IGlzU3ltYm9sO1xuIiwidmFyIGJhc2VUb1N0cmluZyA9IHJlcXVpcmUoJy4vX2Jhc2VUb1N0cmluZycpO1xuXG4vKipcbiAqIENvbnZlcnRzIGB2YWx1ZWAgdG8gYSBzdHJpbmcuIEFuIGVtcHR5IHN0cmluZyBpcyByZXR1cm5lZCBmb3IgYG51bGxgXG4gKiBhbmQgYHVuZGVmaW5lZGAgdmFsdWVzLiBUaGUgc2lnbiBvZiBgLTBgIGlzIHByZXNlcnZlZC5cbiAqXG4gKiBAc3RhdGljXG4gKiBAbWVtYmVyT2YgX1xuICogQHNpbmNlIDQuMC4wXG4gKiBAY2F0ZWdvcnkgTGFuZ1xuICogQHBhcmFtIHsqfSB2YWx1ZSBUaGUgdmFsdWUgdG8gY29udmVydC5cbiAqIEByZXR1cm5zIHtzdHJpbmd9IFJldHVybnMgdGhlIGNvbnZlcnRlZCBzdHJpbmcuXG4gKiBAZXhhbXBsZVxuICpcbiAqIF8udG9TdHJpbmcobnVsbCk7XG4gKiAvLyA9PiAnJ1xuICpcbiAqIF8udG9TdHJpbmcoLTApO1xuICogLy8gPT4gJy0wJ1xuICpcbiAqIF8udG9TdHJpbmcoWzEsIDIsIDNdKTtcbiAqIC8vID0+ICcxLDIsMydcbiAqL1xuZnVuY3Rpb24gdG9TdHJpbmcodmFsdWUpIHtcbiAgcmV0dXJuIHZhbHVlID09IG51bGwgPyAnJyA6IGJhc2VUb1N0cmluZyh2YWx1ZSk7XG59XG5cbm1vZHVsZS5leHBvcnRzID0gdG9TdHJpbmc7XG4iXX0=
