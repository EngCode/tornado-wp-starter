(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
	value: true
});
/**
 * wpseoLocalGeocodingRepository class for geocoding addresses.
 */
class GeocodingRepository {
	/**
  * Geocode the address based using the Google maps JavaScript geocoding API
  *
  * @var object An object containing either { "address": <address as a string> } or { "location": <the LatLng coordinates>}
  */
	static async geoCodeAddress(location) {
		const geocoder = new google.maps.Geocoder();

		if (typeof location === "object") {
			return new Promise((resolve, reject) => {
				geocoder.geocode(location, (results, status) => {
					if (status === "OK") {
						return resolve(results);
					}

					return reject(status);
				});
			});
		}

		throw new Error("Location should be an object");
	}
}
exports.default = GeocodingRepository;

},{}]},{},[1])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJqcy9zcmMvd3Atc2VvLWxvY2FsLWdlb2NvZGluZy1yZXBvc2l0b3J5LmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBOzs7Ozs7QUNBQTs7O0FBR2UsTUFBTSxtQkFBTixDQUEwQjtBQUN4Qzs7Ozs7QUFLQSxjQUFhLGNBQWIsQ0FBNkIsUUFBN0IsRUFBd0M7QUFDdkMsUUFBTSxXQUFXLElBQUksT0FBTyxJQUFQLENBQVksUUFBaEIsRUFBakI7O0FBRUEsTUFBSyxPQUFPLFFBQVAsS0FBb0IsUUFBekIsRUFBb0M7QUFDbkMsVUFBTyxJQUFJLE9BQUosQ0FBYSxDQUFFLE9BQUYsRUFBVyxNQUFYLEtBQXVCO0FBQzFDLGFBQVMsT0FBVCxDQUFrQixRQUFsQixFQUE0QixDQUFFLE9BQUYsRUFBVyxNQUFYLEtBQXVCO0FBQ2xELFNBQUssV0FBVyxJQUFoQixFQUF1QjtBQUN0QixhQUFPLFFBQVMsT0FBVCxDQUFQO0FBQ0E7O0FBRUQsWUFBTyxPQUFRLE1BQVIsQ0FBUDtBQUNBLEtBTkQ7QUFPQSxJQVJNLENBQVA7QUFTQTs7QUFFRCxRQUFNLElBQUksS0FBSixDQUFXLDhCQUFYLENBQU47QUFDQTtBQXRCdUM7a0JBQXBCLG1CIiwiZmlsZSI6ImdlbmVyYXRlZC5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzQ29udGVudCI6WyIoZnVuY3Rpb24oKXtmdW5jdGlvbiByKGUsbix0KXtmdW5jdGlvbiBvKGksZil7aWYoIW5baV0pe2lmKCFlW2ldKXt2YXIgYz1cImZ1bmN0aW9uXCI9PXR5cGVvZiByZXF1aXJlJiZyZXF1aXJlO2lmKCFmJiZjKXJldHVybiBjKGksITApO2lmKHUpcmV0dXJuIHUoaSwhMCk7dmFyIGE9bmV3IEVycm9yKFwiQ2Fubm90IGZpbmQgbW9kdWxlICdcIitpK1wiJ1wiKTt0aHJvdyBhLmNvZGU9XCJNT0RVTEVfTk9UX0ZPVU5EXCIsYX12YXIgcD1uW2ldPXtleHBvcnRzOnt9fTtlW2ldWzBdLmNhbGwocC5leHBvcnRzLGZ1bmN0aW9uKHIpe3ZhciBuPWVbaV1bMV1bcl07cmV0dXJuIG8obnx8cil9LHAscC5leHBvcnRzLHIsZSxuLHQpfXJldHVybiBuW2ldLmV4cG9ydHN9Zm9yKHZhciB1PVwiZnVuY3Rpb25cIj09dHlwZW9mIHJlcXVpcmUmJnJlcXVpcmUsaT0wO2k8dC5sZW5ndGg7aSsrKW8odFtpXSk7cmV0dXJuIG99cmV0dXJuIHJ9KSgpIiwiLyoqXG4gKiB3cHNlb0xvY2FsR2VvY29kaW5nUmVwb3NpdG9yeSBjbGFzcyBmb3IgZ2VvY29kaW5nIGFkZHJlc3Nlcy5cbiAqL1xuZXhwb3J0IGRlZmF1bHQgY2xhc3MgR2VvY29kaW5nUmVwb3NpdG9yeSB7XG5cdC8qKlxuXHQgKiBHZW9jb2RlIHRoZSBhZGRyZXNzIGJhc2VkIHVzaW5nIHRoZSBHb29nbGUgbWFwcyBKYXZhU2NyaXB0IGdlb2NvZGluZyBBUElcblx0ICpcblx0ICogQHZhciBvYmplY3QgQW4gb2JqZWN0IGNvbnRhaW5pbmcgZWl0aGVyIHsgXCJhZGRyZXNzXCI6IDxhZGRyZXNzIGFzIGEgc3RyaW5nPiB9IG9yIHsgXCJsb2NhdGlvblwiOiA8dGhlIExhdExuZyBjb29yZGluYXRlcz59XG5cdCAqL1xuXHRzdGF0aWMgYXN5bmMgZ2VvQ29kZUFkZHJlc3MoIGxvY2F0aW9uICkge1xuXHRcdGNvbnN0IGdlb2NvZGVyID0gbmV3IGdvb2dsZS5tYXBzLkdlb2NvZGVyKCk7XG5cblx0XHRpZiAoIHR5cGVvZiBsb2NhdGlvbiA9PT0gXCJvYmplY3RcIiApIHtcblx0XHRcdHJldHVybiBuZXcgUHJvbWlzZSggKCByZXNvbHZlLCByZWplY3QgKSA9PiB7XG5cdFx0XHRcdGdlb2NvZGVyLmdlb2NvZGUoIGxvY2F0aW9uLCAoIHJlc3VsdHMsIHN0YXR1cyApID0+IHtcblx0XHRcdFx0XHRpZiAoIHN0YXR1cyA9PT0gXCJPS1wiICkge1xuXHRcdFx0XHRcdFx0cmV0dXJuIHJlc29sdmUoIHJlc3VsdHMgKTtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHRyZXR1cm4gcmVqZWN0KCBzdGF0dXMgKTtcblx0XHRcdFx0fSApO1xuXHRcdFx0fSApO1xuXHRcdH1cblxuXHRcdHRocm93IG5ldyBFcnJvciggXCJMb2NhdGlvbiBzaG91bGQgYmUgYW4gb2JqZWN0XCIgKTtcblx0fVxufSJdfQ==
