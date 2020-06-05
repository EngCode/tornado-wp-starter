(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

jQuery(document).ready(function ($) {
	$("#use_multiple_locations input#use_multiple_locations-on").change(function () {
		if ($(this).is(":checked")) {
			$("#single-location-settings").slideToggle();
			$("#multiple-locations-settings").slideToggle();
			$(".open_247_wrapper").hide();
			$(".default-setting").show();
			$("#sl-settings").show();
			$("#wpseo-local-permalinks").show();
			$("#wpseo-local-admin_labels").show();
			$("#wpseo-local-enhanced").show();
			$("#opening-hours-hours").hide();
			$("#wpseo-local-multiple-locations-notification").hide();
			$("#wpseo-local-multiple-locations-notification").hide();
			$("#location-coordinates-settings").hide();
		}
	});

	$("#use_multiple_locations input#use_multiple_locations-off").change(function () {
		if ($(this).is(":checked")) {
			$("#single-location-settings").slideToggle();
			$("#multiple-locations-settings").slideToggle();
			$(".open_247_wrapper").show();
			$("#sl-settings").hide();
			$(".default-setting").hide();
			$("#wpseo-local-permalinks").hide();
			$("#wpseo-local-admin_labels").hide();
			$("#wpseo-local-enhanced").hide();
			$("#wpseo-local-multiple-locations-notification").show();
			$("#location-coordinates-settings").show();

			if ($("#hide_opening_hours input#hide_opening_hours-on").is(":checked")) {
				$("#opening-hours-hours").hide();
			} else {
				$("#opening-hours-hours").show();
			}
		}
	});

	if ($("#hide_opening_hours-on").is(":checked")) {
		$("#opening-hours-hours, #opening-hours-settings").hide();
	}

	$("#hide_opening_hours input#hide_opening_hours-on").change(function () {
		if ($(this).is(":checked")) {
			$("#opening-hours-hours, #opening-hours-settings").slideUp();
			if ($("#use_multiple_locations-off").is(":checked")) {
				$("#opening-hours-hours").show();
			}
		}
	});

	$("#hide_opening_hours input#hide_opening_hours-off").change(function () {
		if ($(this).is(":checked")) {
			$("#opening-hours-hours, #opening-hours-settings").slideDown();
			if ($("#use_multiple_locations-on").is(":checked")) {
				$("#opening-hours-hours").hide();
			}
		}
	});

	// General Settings: Enable/disable Open 24/7 on click
	$("#open_247-on, #open_247-off").change(function () {
		if (!$("#use_multiple_locations").is(":checked")) {
			$("#opening-hours-rows").slideToggle();
		}
	});

	// Change multiple opening hours on single location setup
	$("#multiple_opening_hours-on, #multiple_opening_hours-off").change(function () {
		$(".opening-hours .opening-hours-second").slideToggle();
		$(".opening-hours-second-description").slideToggle();
	});

	// Change multiple opening hours on multiple locations setup
	$("#_wpseo_multiple_opening_hours").click(function () {
		if ($(this).is(":checked")) {
			$(".opening-hours .opening-hours-second").slideDown();
			$(".opening-hours-second-description").slideDown();
		} else {
			$(".opening-hours .opening-hours-second").slideUp();
			$(".opening-hours-second-description").slideUp();
		}
	});

	$(".wpseo-toggle").change(function () {
		// Switch aria-checked on change for styling purposes.
		if ($(this).find(".wpseo-toggle-switch").attr("aria-checked") === "false") {
			$(this).find(".wpseo-toggle-switch").attr("aria-checked", "true");
		} else {
			$(this).find(".wpseo-toggle-switch").attr("aria-checked", "false");
		}
		// Switch aria-checked on change along with the text shown inside the feedback label.
		if ($(this).find(".wpseo-toggle-feedback").attr("aria-checked") === "false") {
			$(this).find(".wpseo-toggle-feedback").attr("aria-checked", "true").text($(this).data("label-true"));
		} else {
			$(this).find(".wpseo-toggle-feedback").attr("aria-checked", "false").text($(this).data("label-false"));
		}
	});

	$("#opening_hours_24h-on, #opening_hours_24h-off").change(function () {
		$("#opening-hours-container select").each(function () {
			$(this).find("option").each(function () {
				if ($("#opening_hours_24h-on").is(":checked")) {
					// Use 24 hour
					if ($(this).val() != "closed") {
						$(this).text($(this).val());
					}
				} else {
					// Use 12 hour
					if ($(this).val() != "closed") {
						// Split the string between hours and minutes
						var time = $(this).val().split(":");

						// use parseInt to remove leading zeroes.
						var hour = parseInt(time[0]);
						var minutes = time[1];
						var suffix = "AM";
						// if the hours number is greater than 12, subtract 12.
						if (hour >= 12) {
							if (hour > 12) {
								hour = hour - 12;
							}
							suffix = "PM";
						}
						if (hour == 0) {
							hour = 12;
						}

						$(this).text(hour + ":" + minutes + " " + suffix);
					}
				}
			});
		});
	});

	// The 24h format on single location page (if multiple locations is set)
	$("#_wpseo_format_24h, #_wpseo_format_12h").click(function () {
		$("#hide-opening-hours select").each(function () {
			$(this).find("option").each(function () {
				if ($("#_wpseo_format_24h").length > 0 && $("#_wpseo_format_24h").is(":checked") || $("#_wpseo_format_12h").length > 0 && !$("#_wpseo_format_12h").is(":checked")) {
					// Use 24 hour
					if ($(this).val() != "closed") {
						$(this).text($(this).val());
					}
				} else {
					// Use 12 hour
					if ($(this).val() != "closed") {
						// Split the string between hours and minutes
						var time = $(this).val().split(":");

						// use parseInt to remove leading zeroes.
						var hour = parseInt(time[0]);
						var minutes = time[1];
						var suffix = "AM";
						// if the hours number is greater than 12, subtract 12.
						if (hour >= 12) {
							if (hour > 12) {
								hour = hour - 12;
							}
							suffix = "PM";
						}
						if (hour == 0) {
							hour = 12;
						}

						$(this).text(hour + ":" + minutes + " " + suffix);
					}
				}
			});
		});
	});

	// Multiple locatiosn metaboxes tab-menu
	var tabLinks = jQuery(".wpseo-local-metabox-content .wpseo-local-meta-section-link");
	tabLinks.on("click", function (e) {
		e.preventDefault();

		var targetTab = jQuery(this).attr("href");
		var targetTabElement = jQuery(targetTab);

		jQuery(".wpseo-local-metabox-menu li").removeClass("active").find("[role='tab']").removeClass("yoast-active-tab");

		jQuery(".wpseo-local-metabox-content .wpseo-local-meta-section.active").removeClass("active");

		targetTabElement.addClass("active");

		jQuery(this).parent("li").addClass("active").find("[role='tab']").addClass("yoast-active-tab");
	});

	// Single Location: Enable/disable Open 24/7 on click
	$("#_wpseo_open_247").on("click", function () {
		maybeCloseOpeningHours(this);
	});

	// Disable hours 24/7 on click
	$(".wpseo_open_24h input").on("click", function (e) {
		if ($(this).is(":checked")) {
			$("select", $(".openinghours-wrapper", $(this).closest(".opening-hours"))).attr("disabled", true);
		} else {
			$("select", $(".openinghours-wrapper", $(this).closest(".opening-hours"))).attr("disabled", false);
		}
	});

	function maybeCloseOpeningHours(elem) {
		if ($(elem).is(":checked")) {
			$("#opening-hours-rows, .opening-hours-wrap").slideUp();
		} else {
			$("#opening-hours-rows, .opening-hours-wrap").slideDown();
		}
	}

	$(".widget-content").on("click", "#wpseo-checkbox-multiple-locations-wrapper input[type=checkbox]", function () {
		wpseo_show_all_locations_selectbox($(this));
	});

	// Show locations metabox before WP SEO metabox
	if ($("#wpseo_locations").length > 0 && $("#wpseo_meta").length > 0) {
		$("#wpseo_locations").insertBefore($("#wpseo_meta"));
	}

	$(".openinghours_from").change(function () {
		var to_id = $(this).attr("id").replace("_from", "_to_wrapper");
		var second_id = $(this).attr("id").replace("_from", "_second");

		if ($(this).val() == "closed") {
			$("#" + to_id).css("display", "none");
			$("#" + second_id).css("display", "none");
		} else {
			$("#" + to_id).css("display", "inline");
			$("#" + second_id).css("display", "block");
		}
	}).change();
	$(".openinghours_from_second").change(function () {
		var to_id = $(this).attr("id").replace("_from", "_to_wrapper");

		if ($(this).val() == "closed") {
			$("#" + to_id).css("display", "none");
		} else {
			$("#" + to_id).css("display", "inline");
		}
	}).change();
	$(".openinghours_to").change(function () {
		var from_id = $(this).attr("id").replace("_to", "_from");
		var to_id = $(this).attr("id").replace("_to", "_to_wrapper");
		if ($(this).val() == "closed") {
			$("#" + to_id).css("display", "none");
			$("#" + from_id).val("closed");
		}
	});
	$(".openinghours_to_second").change(function () {
		var from_id = $(this).attr("id").replace("_to", "_from");
		var to_id = $(this).attr("id").replace("_to", "_to_wrapper");
		if ($(this).val() == "closed") {
			$("#" + to_id).css("display", "none");
			$("#" + from_id).val("closed");
		}
	});

	if ($(".set_custom_images").length > 0) {
		if (typeof wp !== "undefined" && wp.media && wp.media.editor) {
			$(".wrap, #wpseo-local-metabox").on("click", ".set_custom_images", function (e) {
				e.preventDefault();
				var button = $(this);
				var id = button.attr("data-id");

				wp.media.editor.send.attachment = function (props, attachment) {
					if (attachment.hasOwnProperty("sizes")) {
						var url = attachment.sizes[props.size].url;
					} else {
						var url = attachment.url;
					}

					$("#" + id + "_image_container").attr("src", url).show();
					$(".wpseo-local-" + id + "-wrapper .wpseo-local-hide-button").removeClass('hidden');
					$("#hidden_" + id).attr("value", attachment.id);
				};

				wp.media.editor.open(button);
				return false;
			});
		}
	}

	$(".remove_custom_image").on("click", function (e) {
		e.preventDefault();

		var id = $(this).attr("data-id");
		$("#" + id + '_image_container').attr("src", "").hide();
		$("#hidden_" + id).attr("value", "");
		$(".wpseo-local-" + id + "-wrapper .wpseo-local-hide-button").addClass('hidden');
	});

	// Copy location data
	$("#wpseo_copy_from_location").change(function () {
		var location_id = $(this).val();

		if (location_id == "") {
			return;
		}

		$.post(wpseo_local_data.ajaxurl, {
			location_id: location_id,
			security: wpseo_local_data.sec_nonce,
			action: "wpseo_copy_location"
		}, function (result) {
			if (result.charAt(result.length - 1) == 0) {
				result = result.slice(0, -1);
			} else if (result.substring(result.length - 2) == "-1") {
				result = result.slice(0, -2);
			}

			var data = $.parseJSON(result);
			if (data.success == "true" || data.success == true) {

				for (var i in data.location) {
					var value = data.location[i];

					if (value != null && value != "" && typeof value != "undefined") {
						if (i == "is_postal_address" || i == "multiple_opening_hours") {
							if (value == "1") {
								$("#wpseo_" + i).attr("checked", "checked");
								$(".opening-hours .opening-hour-second").slideDown();
							}
						} else if (i.indexOf("opening_hours") > -1) {
							$("#" + i).val(value);
						} else {
							$("#wpseo_" + i).val(value);
						}
					}
				}
			}
		});
	});
});

window.wpseo_show_all_locations_selectbox = function (obj) {
	$ = jQuery;

	$obj = $(obj);
	var parent = $obj.parents(".widget-inside");
	var $locationsWrapper = $("#wpseo-locations-wrapper", parent);

	if ($obj.is(":checked")) {
		$locationsWrapper.slideUp();
	} else {
		$locationsWrapper.slideDown();
	}
};

},{}]},{},[1])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJqcy9zcmMvd3Atc2VvLWxvY2FsLWdsb2JhbC5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7O0FDQUEsT0FBUSxRQUFSLEVBQW1CLEtBQW5CLENBQTBCLFVBQVUsQ0FBVixFQUFjO0FBQ3ZDLEdBQUcseURBQUgsRUFBK0QsTUFBL0QsQ0FBdUUsWUFBVztBQUNqRixNQUFLLEVBQUcsSUFBSCxFQUFVLEVBQVYsQ0FBYyxVQUFkLENBQUwsRUFBa0M7QUFDakMsS0FBRywyQkFBSCxFQUFpQyxXQUFqQztBQUNBLEtBQUcsOEJBQUgsRUFBb0MsV0FBcEM7QUFDQSxLQUFHLG1CQUFILEVBQXlCLElBQXpCO0FBQ0EsS0FBRyxrQkFBSCxFQUF3QixJQUF4QjtBQUNBLEtBQUcsY0FBSCxFQUFvQixJQUFwQjtBQUNBLEtBQUcseUJBQUgsRUFBK0IsSUFBL0I7QUFDQSxLQUFHLDJCQUFILEVBQWlDLElBQWpDO0FBQ0EsS0FBRyx1QkFBSCxFQUE2QixJQUE3QjtBQUNBLEtBQUcsc0JBQUgsRUFBNEIsSUFBNUI7QUFDQSxLQUFHLDhDQUFILEVBQW9ELElBQXBEO0FBQ0EsS0FBRyw4Q0FBSCxFQUFvRCxJQUFwRDtBQUNBLEtBQUcsZ0NBQUgsRUFBc0MsSUFBdEM7QUFDQTtBQUNELEVBZkQ7O0FBaUJBLEdBQUcsMERBQUgsRUFBZ0UsTUFBaEUsQ0FBd0UsWUFBVztBQUNsRixNQUFLLEVBQUcsSUFBSCxFQUFVLEVBQVYsQ0FBYyxVQUFkLENBQUwsRUFBa0M7QUFDakMsS0FBRywyQkFBSCxFQUFpQyxXQUFqQztBQUNBLEtBQUcsOEJBQUgsRUFBb0MsV0FBcEM7QUFDQSxLQUFHLG1CQUFILEVBQXlCLElBQXpCO0FBQ0EsS0FBRyxjQUFILEVBQW9CLElBQXBCO0FBQ0EsS0FBRyxrQkFBSCxFQUF3QixJQUF4QjtBQUNBLEtBQUcseUJBQUgsRUFBK0IsSUFBL0I7QUFDQSxLQUFHLDJCQUFILEVBQWlD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QUNELEdBL0JEO0FBZ0NBLEVBdkNEO0FBd0NBLENBeFZEOztBQTBWQSxPQUFPLGtDQUFQLEdBQTRDLFVBQVUsR0FBVixFQUFnQjtBQUMzRCxLQUFJLE1BQUo7O0FBRUEsUUFBTyxFQUFHLEdBQUgsQ0FBUDtBQUNBLEtBQUksU0FBUyxLQUFLLE9BQUwsQ0FBYyxnQkFBZCxDQUFiO0FBQ0EsS0FBSSxvQkFBb0IsRUFBRywwQkFBSCxFQUErQixNQUEvQixDQUF4Qjs7QUFFQSxLQUFLLEtBQUssRUFBTCxDQUFTLFVBQVQsQ0FBTCxFQUE2QjtBQUM1QixvQkFBa0IsT0FBbEI7QUFDQSxFQUZELE1BRU87QUFDTixvQkFBa0IsU0FBbEI7QUFDQTtBQUNELENBWkQiLCJmaWxlIjoiZ2VuZXJhdGVkLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXNDb250ZW50IjpbIihmdW5jdGlvbigpe2Z1bmN0aW9uIHIoZSxuLHQpe2Z1bmN0aW9uIG8oaSxmKXtpZighbltpXSl7aWYoIWVbaV0pe3ZhciBjPVwiZnVuY3Rpb25cIj09dHlwZW9mIHJlcXVpcmUmJnJlcXVpcmU7aWYoIWYmJmMpcmV0dXJuIGMoaSwhMCk7aWYodSlyZXR1cm4gdShpLCEwKTt2YXIgYT1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK2krXCInXCIpO3Rocm93IGEuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixhfXZhciBwPW5baV09e2V4cG9ydHM6e319O2VbaV1bMF0uY2FsbChwLmV4cG9ydHMsZnVuY3Rpb24ocil7dmFyIG49ZVtpXVsxXVtyXTtyZXR1cm4gbyhufHxyKX0scCxwLmV4cG9ydHMscixlLG4sdCl9cmV0dXJuIG5baV0uZXhwb3J0c31mb3IodmFyIHU9XCJmdW5jdGlvblwiPT10eXBlb2YgcmVxdWlyZSYmcmVxdWlyZSxpPTA7aTx0Lmxlbmd0aDtpKyspbyh0W2ldKTtyZXR1cm4gb31yZXR1cm4gcn0pKCkiLCJqUXVlcnkoIGRvY3VtZW50ICkucmVhZHkoIGZ1bmN0aW9uKCAkICkge1xuXHQkKCBcIiN1c2VfbXVsdGlwbGVfbG9jYXRpb25zIGlucHV0I3VzZV9tdWx0aXBsZV9sb2NhdGlvbnMtb25cIiApLmNoYW5nZSggZnVuY3Rpb24oKSB7XG5cdFx0aWYgKCAkKCB0aGlzICkuaXMoIFwiOmNoZWNrZWRcIiApICkge1xuXHRcdFx0JCggXCIjc2luZ2xlLWxvY2F0aW9uLXNldHRpbmdzXCIgKS5zbGlkZVRvZ2dsZSgpO1xuXHRcdFx0JCggXCIjbXVsdGlwbGUtbG9jYXRpb25zLXNldHRpbmdzXCIgKS5zbGlkZVRvZ2dsZSgpO1xuXHRcdFx0JCggXCIub3Blbl8yNDdfd3JhcHBlclwiICkuaGlkZSgpO1xuXHRcdFx0JCggXCIuZGVmYXVsdC1zZXR0aW5nXCIgKS5zaG93KCk7XG5cdFx0XHQkKCBcIiNzbC1zZXR0aW5nc1wiICkuc2hvdygpO1xuXHRcdFx0JCggXCIjd3BzZW8tbG9jYWwtcGVybWFsaW5rc1wiICkuc2hvdygpO1xuXHRcdFx0JCggXCIjd3BzZW8tbG9jYWwtYWRtaW5fbGFiZWxzXCIgKS5zaG93KCk7XG5cdFx0XHQkKCBcIiN3cHNlby1sb2NhbC1lbmhhbmNlZFwiICkuc2hvdygpO1xuXHRcdFx0JCggXCIjb3BlbmluZy1ob3Vycy1ob3Vyc1wiICkuaGlkZSgpO1xuXHRcdFx0JCggXCIjd3BzZW8tbG9jYWwtbXVsdGlwbGUtbG9jYXRpb25zLW5vdGlmaWNhdGlvblwiICkuaGlkZSgpO1xuXHRcdFx0JCggXCIjd3BzZW8tbG9jYWwtbXVsdGlwbGUtbG9jYXRpb25zLW5vdGlmaWNhdGlvblwiICkuaGlkZSgpO1xuXHRcdFx0JCggXCIjbG9jYXRpb24tY29vcmRpbmF0ZXMtc2V0dGluZ3NcIiApLmhpZGUoKTtcblx0XHR9XG5cdH0gKTtcblxuXHQkKCBcIiN1c2VfbXVsdGlwbGVfbG9jYXRpb25zIGlucHV0I3VzZV9tdWx0aXBsZV9sb2NhdGlvbnMtb2ZmXCIgKS5jaGFuZ2UoIGZ1bmN0aW9uKCkge1xuXHRcdGlmICggJCggdGhpcyApLmlzKCBcIjpjaGVja2VkXCIgKSApIHtcblx0XHRcdCQoIFwiI3NpbmdsZS1sb2NhdGlvbi1zZXR0aW5nc1wiICkuc2xpZGVUb2dnbGUoKTtcblx0XHRcdCQoIFwiI211bHRpcGxlLWxvY2F0aW9ucy1zZXR0aW5nc1wiICkuc2xpZGVUb2dnbGUoKTtcblx0XHRcdCQoIFwiLm9wZW5fMjQ3X3dyYXBwZXJcIiApLnNob3coKTtcblx0XHRcdCQoIFwiI3NsLXNldHRpbmdzXCIgKS5oaWRlKCk7XG5cdFx0XHQkKCBcIi5kZWZhdWx0LXNldHRpbmdcIiApLmhpZGUoKTtcblx0XHRcdCQoIFwiI3dwc2VvLWxvY2FsLXBlcm1hbGlua3NcIiApLmhpZGUoKTtcblx0XHRcdCQoIFwiI3dwc2VvLWxvY2FsLWFkbWluX2xhYmVsc1wiICkuaGlkZSgpO1xuXHRcdFx0JCggXCIjd3BzZW8tbG9jYWwtZW5oYW5jZWRcIiApLmhpZGUoKTtcblx0XHRcdCQoIFwiI3dwc2VvLWxvY2FsLW11bHRpcGxlLWxvY2F0aW9ucy1ub3RpZmljYXRpb25cIiApLnNob3coKTtcblx0XHRcdCQoIFwiI2xvY2F0aW9uLWNvb3JkaW5hdGVzLXNldHRpbmdzXCIgKS5zaG93KCk7XG5cblx0XHRcdGlmICggJCggXCIjaGlkZV9vcGVuaW5nX2hvdXJzIGlucHV0I2hpZGVfb3BlbmluZ19ob3Vycy1vblwiICkuaXMoIFwiOmNoZWNrZWRcIiApICkge1xuXHRcdFx0XHQkKCBcIiNvcGVuaW5nLWhvdXJzLWhvdXJzXCIgKS5oaWRlKCk7XG5cdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHQkKCBcIiNvcGVuaW5nLWhvdXJzLWhvdXJzXCIgKS5zaG93KCk7XG5cdFx0XHR9XG5cdFx0fVxuXHR9ICk7XG5cblx0aWYgKCAkKCBcIiNoaWRlX29wZW5pbmdfaG91cnMtb25cIiApLmlzKCBcIjpjaGVja2VkXCIgKSApIHtcblx0XHQkKCBcIiNvcGVuaW5nLWhvdXJzLWhvdXJzLCAjb3BlbmluZy1ob3Vycy1zZXR0aW5nc1wiICkuaGlkZSgpO1xuXHR9XG5cblx0JCggXCIjaGlkZV9vcGVuaW5nX2hvdXJzIGlucHV0I2hpZGVfb3BlbmluZ19ob3Vycy1vblwiICkuY2hhbmdlKCBmdW5jdGlvbigpIHtcblx0XHRpZiAoICQoIHRoaXMgKS5pcyggXCI6Y2hlY2tlZFwiICkgKSB7XG5cdFx0XHQkKCBcIiNvcGVuaW5nLWhvdXJzLWhvdXJzLCAjb3BlbmluZy1ob3Vycy1zZXR0aW5nc1wiICkuc2xpZGVVcCgpO1xuXHRcdFx0aWYgKCAkKCBcIiN1c2VfbXVsdGlwbGVfbG9jYXRpb25zLW9mZlwiICkuaXMoIFwiOmNoZWNrZWRcIiApICkge1xuXHRcdFx0XHQkKCBcIiNvcGVuaW5nLWhvdXJzLWhvdXJzXCIgKS5zaG93KCk7XG5cdFx0XHR9XG5cdFx0fVxuXHR9ICk7XG5cblx0JCggXCIjaGlkZV9vcGVuaW5nX2hvdXJzIGlucHV0I2hpZGVfb3BlbmluZ19ob3Vycy1vZmZcIiApLmNoYW5nZSggZnVuY3Rpb24oKSB7XG5cdFx0aWYgKCAkKCB0aGlzICkuaXMoIFwiOmNoZWNrZWRcIiApICkge1xuXHRcdFx0JCggXCIjb3BlbmluZy1ob3Vycy1ob3VycywgI29wZW5pbmctaG91cnMtc2V0dGluZ3NcIiApLnNsaWRlRG93bigpO1xuXHRcdFx0aWYgKCAkKCBcIiN1c2VfbXVsdGlwbGVfbG9jYXRpb25zLW9uXCIgKS5pcyggXCI6Y2hlY2tlZFwiICkgKSB7XG5cdFx0XHRcdCQoIFwiI29wZW5pbmctaG91cnMtaG91cnNcIiApLmhpZGUoKTtcblx0XHRcdH1cblx0XHR9XG5cdH0gKTtcblxuXHQvLyBHZW5lcmFsIFNldHRpbmdzOiBFbmFibGUvZGlzYWJsZSBPcGVuIDI0Lzcgb24gY2xpY2tcblx0JCggXCIjb3Blbl8yNDctb24sICNvcGVuXzI0Ny1vZmZcIiApLmNoYW5nZSggZnVuY3Rpb24oKSB7XG5cdFx0aWYgKCAhICQoIFwiI3VzZV9tdWx0aXBsZV9sb2NhdGlvbnNcIiApLmlzKCBcIjpjaGVja2VkXCIgKSApIHtcblx0XHRcdCQoIFwiI29wZW5pbmctaG91cnMtcm93c1wiICkuc2xpZGVUb2dnbGUoKTtcblx0XHR9XG5cdH0gKTtcblxuXHQvLyBDaGFuZ2UgbXVsdGlwbGUgb3BlbmluZyBob3VycyBvbiBzaW5nbGUgbG9jYXRpb24gc2V0dXBcblx0JCggXCIjbXVsdGlwbGVfb3BlbmluZ19ob3Vycy1vbiwgI211bHRpcGxlX29wZW5pbmdfaG91cnMtb2ZmXCIgKS5jaGFuZ2UoIGZ1bmN0aW9uKCkge1xuXHRcdCQoIFwiLm9wZW5pbmctaG91cnMgLm9wZW5pbmctaG91cnMtc2Vjb25kXCIgKS5zbGlkZVRvZ2dsZSgpO1xuXHRcdCQoIFwiLm9wZW5pbmctaG91cnMtc2Vjb25kLWRlc2NyaXB0aW9uXCIgKS5zbGlkZVRvZ2dsZSgpO1xuXHR9ICk7XG5cblx0Ly8gQ2hhbmdlIG11bHRpcGxlIG9wZW5pbmcgaG91cnMgb24gbXVsdGlwbGUgbG9jYXRpb25zIHNldHVwXG5cdCQoIFwiI193cHNlb19tdWx0aXBsZV9vcGVuaW5nX2hvdXJzXCIgKS5jbGljayggZnVuY3Rpb24oKSB7XG5cdFx0aWYgKCAkKCB0aGlzICkuaXMoIFwiOmNoZWNrZWRcIiApICkge1xuXHRcdFx0JCggXCIub3BlbmluZy1ob3VycyAub3BlbmluZy1ob3Vycy1zZWNvbmRcIiApLnNsaWRlRG93bigpO1xuXHRcdFx0JCggXCIub3BlbmluZy1ob3Vycy1zZWNvbmQtZGVzY3JpcHRpb25cIiApLnNsaWRlRG93bigpO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHQkKCBcIi5vcGVuaW5nLWhvdXJzIC5vcGVuaW5nLWhvdXJzLXNlY29uZFwiICkuc2xpZGVVcCgpO1xuXHRcdFx0JCggXCIub3BlbmluZy1ob3Vycy1zZWNvbmQtZGVzY3JpcHRpb25cIiApLnNsaWRlVXAoKTtcblx0XHR9XG5cdH0gKTtcblxuXHQkKCBcIi53cHNlby10b2dnbGVcIiApLmNoYW5nZSggZnVuY3Rpb24oKSB7XG5cdFx0Ly8gU3dpdGNoIGFyaWEtY2hlY2tlZCBvbiBjaGFuZ2UgZm9yIHN0eWxpbmcgcHVycG9zZXMuXG5cdFx0aWYgKCAkKCB0aGlzICkuZmluZCggXCIud3BzZW8tdG9nZ2xlLXN3aXRjaFwiICkuYXR0ciggXCJhcmlhLWNoZWNrZWRcIiApID09PSBcImZhbHNlXCIgKSB7XG5cdFx0XHQkKCB0aGlzICkuZmluZCggXCIud3BzZW8tdG9nZ2xlLXN3aXRjaFwiICkuYXR0ciggXCJhcmlhLWNoZWNrZWRcIiwgXCJ0cnVlXCIgKTtcblx0XHR9IGVsc2Uge1xuXHRcdFx0JCggdGhpcyApLmZpbmQoIFwiLndwc2VvLXRvZ2dsZS1zd2l0Y2hcIiApLmF0dHIoIFwiYXJpYS1jaGVja2VkXCIsIFwiZmFsc2VcIiApO1xuXHRcdH1cblx0XHQvLyBTd2l0Y2ggYXJpYS1jaGVja2VkIG9uIGNoYW5nZSBhbG9uZyB3aXRoIHRoZSB0ZXh0IHNob3duIGluc2lkZSB0aGUgZmVlZGJhY2sgbGFiZWwuXG5cdFx0aWYgKCAkKCB0aGlzICkuZmluZCggXCIud3BzZW8tdG9nZ2xlLWZlZWRiYWNrXCIgKS5hdHRyKCBcImFyaWEtY2hlY2tlZFwiICkgPT09IFwiZmFsc2VcIiApIHtcblx0XHRcdCQoIHRoaXMgKS5maW5kKCBcIi53cHNlby10b2dnbGUtZmVlZGJhY2tcIiApLmF0dHIoIFwiYXJpYS1jaGVja2VkXCIsIFwidHJ1ZVwiICkudGV4dCggJCggdGhpcyApLmRhdGEoIFwibGFiZWwtdHJ1ZVwiICkgKTtcblx0XHR9IGVsc2Uge1xuXHRcdFx0JCggdGhpcyApLmZpbmQoIFwiLndwc2VvLXRvZ2dsZS1mZWVkYmFja1wiICkuYXR0ciggXCJhcmlhLWNoZWNrZWRcIiwgXCJmYWxzZVwiICkudGV4dCggJCggdGhpcyApLmRhdGEoIFwibGFiZWwtZmFsc2VcIiApICk7XG5cdFx0fVxuXHR9ICk7XG5cblx0JCggXCIjb3BlbmluZ19ob3Vyc18yNGgtb24sICNvcGVuaW5nX2hvdXJzXzI0aC1vZmZcIiApLmNoYW5nZSggZnVuY3Rpb24oKSB7XG5cdFx0JCggXCIjb3BlbmluZy1ob3Vycy1jb250YWluZXIgc2VsZWN0XCIgKS5lYWNoKCBmdW5jdGlvbigpIHtcblx0XHRcdCQoIHRoaXMgKS5maW5kKCBcIm9wdGlvblwiICkuZWFjaCggZnVuY3Rpb24oKSB7XG5cdFx0XHRcdGlmICggJCggXCIjb3BlbmluZ19ob3Vyc18yNGgtb25cIiApLmlzKCBcIjpjaGVja2VkXCIgKSApIHtcblx0XHRcdFx0XHQvLyBVc2UgMjQgaG91clxuXHRcdFx0XHRcdGlmICggJCggdGhpcyApLnZhbCgpICE9IFwiY2xvc2VkXCIgKSB7XG5cdFx0XHRcdFx0XHQkKCB0aGlzICkudGV4dCggJCggdGhpcyApLnZhbCgpICk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdC8vIFVzZSAxMiBob3VyXG5cdFx0XHRcdFx0aWYgKCAkKCB0aGlzICkudmFsKCkgIT0gXCJjbG9zZWRcIiApIHtcblx0XHRcdFx0XHRcdC8vIFNwbGl0IHRoZSBzdHJpbmcgYmV0d2VlbiBob3VycyBhbmQgbWludXRlc1xuXHRcdFx0XHRcdFx0dmFyIHRpbWUgPSAkKCB0aGlzICkudmFsKCkuc3BsaXQoIFwiOlwiICk7XG5cblx0XHRcdFx0XHRcdC8vIHVzZSBwYXJzZUludCB0byByZW1vdmUgbGVhZGluZyB6ZXJvZXMuXG5cdFx0XHRcdFx0XHR2YXIgaG91ciA9IHBhcnNlSW50KCB0aW1lWyAwIF0gKTtcblx0XHRcdFx0XHRcdHZhciBtaW51dGVzID0gdGltZVsgMSBdO1xuXHRcdFx0XHRcdFx0dmFyIHN1ZmZpeCA9IFwiQU1cIjtcblx0XHRcdFx0XHRcdC8vIGlmIHRoZSBob3VycyBudW1iZXIgaXMgZ3JlYXRlciB0aGFuIDEyLCBzdWJ0cmFjdCAxMi5cblx0XHRcdFx0XHRcdGlmICggaG91ciA+PSAxMiApIHtcblx0XHRcdFx0XHRcdFx0aWYgKCBob3VyID4gMTIgKSB7XG5cdFx0XHRcdFx0XHRcdFx0aG91ciA9IGhvdXIgLSAxMjtcblx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHRzdWZmaXggPSBcIlBNXCI7XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRpZiAoIGhvdXIgPT0gMCApIHtcblx0XHRcdFx0XHRcdFx0aG91ciA9IDEyO1xuXHRcdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0XHQkKCB0aGlzICkudGV4dCggaG91ciArIFwiOlwiICsgbWludXRlcyArIFwiIFwiICsgc3VmZml4ICk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9XG5cdFx0XHR9ICk7XG5cdFx0fSApO1xuXHR9ICk7XG5cblx0Ly8gVGhlIDI0aCBmb3JtYXQgb24gc2luZ2xlIGxvY2F0aW9uIHBhZ2UgKGlmIG11bHRpcGxlIGxvY2F0aW9ucyBpcyBzZXQpXG5cdCQoIFwiI193cHNlb19mb3JtYXRfMjRoLCAjX3dwc2VvX2Zvcm1hdF8xMmhcIiApLmNsaWNrKCBmdW5jdGlvbigpIHtcblx0XHQkKCBcIiNoaWRlLW9wZW5pbmctaG91cnMgc2VsZWN0XCIgKS5lYWNoKCBmdW5jdGlvbigpIHtcblx0XHRcdCQoIHRoaXMgKS5maW5kKCBcIm9wdGlvblwiICkuZWFjaCggZnVuY3Rpb24oKSB7XG5cdFx0XHRcdGlmICggKFxuXHRcdFx0XHRcdCQoIFwiI193cHNlb19mb3JtYXRfMjRoXCIgKS5sZW5ndGggPiAwICYmICQoIFwiI193cHNlb19mb3JtYXRfMjRoXCIgKS5pcyggXCI6Y2hlY2tlZFwiIClcblx0XHRcdFx0KSB8fCAoXG5cdFx0XHRcdFx0KFxuXHRcdFx0XHRcdFx0JCggXCIjX3dwc2VvX2Zvcm1hdF8xMmhcIiApLmxlbmd0aCA+IDAgJiYgISAkKCBcIiNfd3BzZW9fZm9ybWF0XzEyaFwiICkuaXMoIFwiOmNoZWNrZWRcIiApXG5cdFx0XHRcdFx0KVxuXHRcdFx0XHQpICkge1xuXHRcdFx0XHRcdC8vIFVzZSAyNCBob3VyXG5cdFx0XHRcdFx0aWYgKCAkKCB0aGlzICkudmFsKCkgIT0gXCJjbG9zZWRcIiApIHtcblx0XHRcdFx0XHRcdCQoIHRoaXMgKS50ZXh0KCAkKCB0aGlzICkudmFsKCkgKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0Ly8gVXNlIDEyIGhvdXJcblx0XHRcdFx0XHRpZiAoICQoIHRoaXMgKS52YWwoKSAhPSBcImNsb3NlZFwiICkge1xuXHRcdFx0XHRcdFx0Ly8gU3BsaXQgdGhlIHN0cmluZyBiZXR3ZWVuIGhvdXJzIGFuZCBtaW51dGVzXG5cdFx0XHRcdFx0XHR2YXIgdGltZSA9ICQoIHRoaXMgKS52YWwoKS5zcGxpdCggXCI6XCIgKTtcblxuXHRcdFx0XHRcdFx0Ly8gdXNlIHBhcnNlSW50IHRvIHJlbW92ZSBsZWFkaW5nIHplcm9lcy5cblx0XHRcdFx0XHRcdHZhciBob3VyID0gcGFyc2VJbnQoIHRpbWVbIDAgXSApO1xuXHRcdFx0XHRcdFx0dmFyIG1pbnV0ZXMgPSB0aW1lWyAxIF07XG5cdFx0XHRcdFx0XHR2YXIgc3VmZml4ID0gXCJBTVwiO1xuXHRcdFx0XHRcdFx0Ly8gaWYgdGhlIGhvdXJzIG51bWJlciBpcyBncmVhdGVyIHRoYW4gMTIsIHN1YnRyYWN0IDEyLlxuXHRcdFx0XHRcdFx0aWYgKCBob3VyID49IDEyICkge1xuXHRcdFx0XHRcdFx0XHRpZiAoIGhvdXIgPiAxMiApIHtcblx0XHRcdFx0XHRcdFx0XHRob3VyID0gaG91ciAtIDEyO1xuXHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdHN1ZmZpeCA9IFwiUE1cIjtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdGlmICggaG91ciA9PSAwICkge1xuXHRcdFx0XHRcdFx0XHRob3VyID0gMTI7XG5cdFx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHRcdCQoIHRoaXMgKS50ZXh0KCBob3VyICsgXCI6XCIgKyBtaW51dGVzICsgXCIgXCIgKyBzdWZmaXggKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH0gKTtcblx0XHR9ICk7XG5cdH0gKTtcblxuXHQvLyBNdWx0aXBsZSBsb2NhdGlvc24gbWV0YWJveGVzIHRhYi1tZW51XG5cdHZhciB0YWJMaW5rcyA9IGpRdWVyeSggXCIud3BzZW8tbG9jYWwtbWV0YWJveC1jb250ZW50IC53cHNlby1sb2NhbC1tZXRhLXNlY3Rpb24tbGlua1wiICk7XG5cdHRhYkxpbmtzLm9uKCBcImNsaWNrXCIsIGZ1bmN0aW9uKCBlICkge1xuXHRcdGUucHJldmVudERlZmF1bHQoKTtcblxuXHRcdHZhciB0YXJnZXRUYWIgPSBqUXVlcnkoIHRoaXMgKS5hdHRyKCBcImhyZWZcIiApO1xuXHRcdHZhciB0YXJnZXRUYWJFbGVtZW50ID0galF1ZXJ5KCB0YXJnZXRUYWIgKTtcblxuXHRcdGpRdWVyeSggXCIud3BzZW8tbG9jYWwtbWV0YWJveC1tZW51IGxpXCIgKVxuXHRcdFx0LnJlbW92ZUNsYXNzKCBcImFjdGl2ZVwiIClcblx0XHRcdC5maW5kKCBcIltyb2xlPSd0YWInXVwiICkucmVtb3ZlQ2xhc3MoIFwieW9hc3QtYWN0aXZlLXRhYlwiICk7XG5cblx0XHRqUXVlcnkoIFwiLndwc2VvLWxvY2FsLW1ldGFib3gtY29udGVudCAud3BzZW8tbG9jYWwtbWV0YS1zZWN0aW9uLmFjdGl2ZVwiICkucmVtb3ZlQ2xhc3MoIFwiYWN0aXZlXCIgKTtcblxuXHRcdHRhcmdldFRhYkVsZW1lbnQuYWRkQ2xhc3MoIFwiYWN0aXZlXCIgKTtcblxuXHRcdGpRdWVyeSggdGhpcyApLnBhcmVudCggXCJsaVwiIClcblx0XHRcdC5hZGRDbGFzcyggXCJhY3RpdmVcIiApXG5cdFx0XHQuZmluZCggXCJbcm9sZT0ndGFiJ11cIiApLmFkZENsYXNzKCBcInlvYXN0LWFjdGl2ZS10YWJcIiApO1xuXHR9ICk7XG5cblx0Ly8gU2luZ2xlIExvY2F0aW9uOiBFbmFibGUvZGlzYWJsZSBPcGVuIDI0Lzcgb24gY2xpY2tcblx0JCggXCIjX3dwc2VvX29wZW5fMjQ3XCIgKS5vbiggXCJjbGlja1wiLCBmdW5jdGlvbigpIHtcblx0XHRtYXliZUNsb3NlT3BlbmluZ0hvdXJzKCB0aGlzICk7XG5cdH0gKTtcblxuXHQvLyBEaXNhYmxlIGhvdXJzIDI0Lzcgb24gY2xpY2tcblx0JCggXCIud3BzZW9fb3Blbl8yNGggaW5wdXRcIiApLm9uKCBcImNsaWNrXCIsIGZ1bmN0aW9uKCBlICkge1xuXHRcdGlmICggJCggdGhpcyApLmlzKCBcIjpjaGVja2VkXCIgKSApIHtcblx0XHRcdCQoIFwic2VsZWN0XCIsICQoIFwiLm9wZW5pbmdob3Vycy13cmFwcGVyXCIsICQoIHRoaXMgKS5jbG9zZXN0KCBcIi5vcGVuaW5nLWhvdXJzXCIgKSApICkuYXR0ciggXCJkaXNhYmxlZFwiLCB0cnVlICk7XG5cdFx0fSBlbHNlIHtcblx0XHRcdCQoIFwic2VsZWN0XCIsICQoIFwiLm9wZW5pbmdob3Vycy13cmFwcGVyXCIsICQoIHRoaXMgKS5jbG9zZXN0KCBcIi5vcGVuaW5nLWhvdXJzXCIgKSApICkuYXR0ciggXCJkaXNhYmxlZFwiLCBmYWxzZSApO1xuXHRcdH1cblx0fSApO1xuXG5cdGZ1bmN0aW9uIG1heWJlQ2xvc2VPcGVuaW5nSG91cnMoIGVsZW0gKSB7XG5cdFx0aWYgKCAkKCBlbGVtICkuaXMoIFwiOmNoZWNrZWRcIiApICkge1xuXHRcdFx0JCggXCIjb3BlbmluZy1ob3Vycy1yb3dzLCAub3BlbmluZy1ob3Vycy13cmFwXCIgKS5zbGlkZVVwKCk7XG5cdFx0fSBlbHNlIHtcblx0XHRcdCQoIFwiI29wZW5pbmctaG91cnMtcm93cywgLm9wZW5pbmctaG91cnMtd3JhcFwiICkuc2xpZGVEb3duKCk7XG5cdFx0fVxuXHR9XG5cblx0JCggXCIud2lkZ2V0LWNvbnRlbnRcIiApLm9uKCBcImNsaWNrXCIsIFwiI3dwc2VvLWNoZWNrYm94LW11bHRpcGxlLWxvY2F0aW9ucy13cmFwcGVyIGlucHV0W3R5cGU9Y2hlY2tib3hdXCIsIGZ1bmN0aW9uKCkge1xuXHRcdHdwc2VvX3Nob3dfYWxsX2xvY2F0aW9uc19zZWxlY3Rib3goICQoIHRoaXMgKSApO1xuXHR9ICk7XG5cblx0Ly8gU2hvdyBsb2NhdGlvbnMgbWV0YWJveCBiZWZvcmUgV1AgU0VPIG1ldGFib3hcblx0aWYgKCAkKCBcIiN3cHNlb19sb2NhdGlvbnNcIiApLmxlbmd0aCA+IDAgJiYgJCggXCIjd3BzZW9fbWV0YVwiICkubGVuZ3RoID4gMCApIHtcblx0XHQkKCBcIiN3cHNlb19sb2NhdGlvbnNcIiApLmluc2VydEJlZm9yZSggJCggXCIjd3BzZW9fbWV0YVwiICkgKTtcblx0fVxuXG5cdCQoIFwiLm9wZW5pbmdob3Vyc19mcm9tXCIgKS5jaGFuZ2UoIGZ1bmN0aW9uKCkge1xuXHRcdHZhciB0b19pZCA9ICQoIHRoaXMgKS5hdHRyKCBcImlkXCIgKS5yZXBsYWNlKCBcIl9mcm9tXCIsIFwiX3RvX3dyYXBwZXJcIiApO1xuXHRcdHZhciBzZWNvbmRfaWQgPSAkKCB0aGlzICkuYXR0ciggXCJpZFwiICkucmVwbGFjZSggXCJfZnJvbVwiLCBcIl9zZWNvbmRcIiApO1xuXG5cdFx0aWYgKCAkKCB0aGlzICkudmFsKCkgPT0gXCJjbG9zZWRcIiApIHtcblx0XHRcdCQoIFwiI1wiICsgdG9faWQgKS5jc3MoIFwiZGlzcGxheVwiLCBcIm5vbmVcIiApO1xuXHRcdFx0JCggXCIjXCIgKyBzZWNvbmRfaWQgKS5jc3MoIFwiZGlzcGxheVwiLCBcIm5vbmVcIiApO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHQkKCBcIiNcIiArIHRvX2lkICkuY3NzKCBcImRpc3BsYXlcIiwgXCJpbmxpbmVcIiApO1xuXHRcdFx0JCggXCIjXCIgKyBzZWNvbmRfaWQgKS5jc3MoIFwiZGlzcGxheVwiLCBcImJsb2NrXCIgKTtcblx0XHR9XG5cdH0gKS5jaGFuZ2UoKTtcblx0JCggXCIub3BlbmluZ2hvdXJzX2Zyb21fc2Vjb25kXCIgKS5jaGFuZ2UoIGZ1bmN0aW9uKCkge1xuXHRcdHZhciB0b19pZCA9ICQoIHRoaXMgKS5hdHRyKCBcImlkXCIgKS5yZXBsYWNlKCBcIl9mcm9tXCIsIFwiX3RvX3dyYXBwZXJcIiApO1xuXG5cdFx0aWYgKCAkKCB0aGlzICkudmFsKCkgPT0gXCJjbG9zZWRcIiApIHtcblx0XHRcdCQoIFwiI1wiICsgdG9faWQgKS5jc3MoIFwiZGlzcGxheVwiLCBcIm5vbmVcIiApO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHQkKCBcIiNcIiArIHRvX2lkICkuY3NzKCBcImRpc3BsYXlcIiwgXCJpbmxpbmVcIiApO1xuXHRcdH1cblx0fSApLmNoYW5nZSgpO1xuXHQkKCBcIi5vcGVuaW5naG91cnNfdG9cIiApLmNoYW5nZSggZnVuY3Rpb24oKSB7XG5cdFx0dmFyIGZyb21faWQgPSAkKCB0aGlzICkuYXR0ciggXCJpZFwiICkucmVwbGFjZSggXCJfdG9cIiwgXCJfZnJvbVwiICk7XG5cdFx0dmFyIHRvX2lkID0gJCggdGhpcyApLmF0dHIoIFwiaWRcIiApLnJlcGxhY2UoIFwiX3RvXCIsIFwiX3RvX3dyYXBwZXJcIiApO1xuXHRcdGlmICggJCggdGhpcyApLnZhbCgpID09IFwiY2xvc2VkXCIgKSB7XG5cdFx0XHQkKCBcIiNcIiArIHRvX2lkICkuY3NzKCBcImRpc3BsYXlcIiwgXCJub25lXCIgKTtcblx0XHRcdCQoIFwiI1wiICsgZnJvbV9pZCApLnZhbCggXCJjbG9zZWRcIiApO1xuXHRcdH1cblx0fSApO1xuXHQkKCBcIi5vcGVuaW5naG91cnNfdG9fc2Vjb25kXCIgKS5jaGFuZ2UoIGZ1bmN0aW9uKCkge1xuXHRcdHZhciBmcm9tX2lkID0gJCggdGhpcyApLmF0dHIoIFwiaWRcIiApLnJlcGxhY2UoIFwiX3RvXCIsIFwiX2Zyb21cIiApO1xuXHRcdHZhciB0b19pZCA9ICQoIHRoaXMgKS5hdHRyKCBcImlkXCIgKS5yZXBsYWNlKCBcIl90b1wiLCBcIl90b193cmFwcGVyXCIgKTtcblx0XHRpZiAoICQoIHRoaXMgKS52YWwoKSA9PSBcImNsb3NlZFwiICkge1xuXHRcdFx0JCggXCIjXCIgKyB0b19pZCApLmNzcyggXCJkaXNwbGF5XCIsIFwibm9uZVwiICk7XG5cdFx0XHQkKCBcIiNcIiArIGZyb21faWQgKS52YWwoIFwiY2xvc2VkXCIgKTtcblx0XHR9XG5cdH0gKTtcblxuXHRpZiAoICQoIFwiLnNldF9jdXN0b21faW1hZ2VzXCIgKS5sZW5ndGggPiAwICkge1xuXHRcdGlmICggdHlwZW9mIHdwICE9PSBcInVuZGVmaW5lZFwiICYmIHdwLm1lZGlhICYmIHdwLm1lZGlhLmVkaXRvciApIHtcblx0XHRcdCQoIFwiLndyYXAsICN3cHNlby1sb2NhbC1tZXRhYm94XCIgKS5vbiggXCJjbGlja1wiLCBcIi5zZXRfY3VzdG9tX2ltYWdlc1wiLCBmdW5jdGlvbiggZSApIHtcblx0XHRcdFx0ZS5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdFx0XHR2YXIgYnV0dG9uID0gJCggdGhpcyApO1xuXHRcdFx0XHR2YXIgaWQgPSBidXR0b24uYXR0ciggXCJkYXRhLWlkXCIgKTtcblxuXHRcdFx0XHR3cC5tZWRpYS5lZGl0b3Iuc2VuZC5hdHRhY2htZW50ID0gZnVuY3Rpb24oIHByb3BzLCBhdHRhY2htZW50ICkge1xuXHRcdFx0XHRcdGlmICggYXR0YWNobWVudC5oYXNPd25Qcm9wZXJ0eSggXCJzaXplc1wiICkgKSB7XG5cdFx0XHRcdFx0XHR2YXIgdXJsID0gYXR0YWNobWVudC5zaXplc1sgcHJvcHMuc2l6ZSBdLnVybDtcblx0XHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdFx0dmFyIHVybCA9IGF0dGFjaG1lbnQudXJsO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdCQoIFwiI1wiICsgaWQgKyBcIl9pbWFnZV9jb250YWluZXJcIiApLmF0dHIoIFwic3JjXCIsIHVybCApLnNob3coKTtcblx0XHRcdFx0XHQkKCBcIi53cHNlby1sb2NhbC1cIiArIGlkICsgXCItd3JhcHBlciAud3BzZW8tbG9jYWwtaGlkZS1idXR0b25cIiApLnJlbW92ZUNsYXNzKCdoaWRkZW4nKTtcblx0XHRcdFx0XHQkKCBcIiNoaWRkZW5fXCIgKyBpZCApLmF0dHIoIFwidmFsdWVcIiwgYXR0YWNobWVudC5pZCApO1xuXHRcdFx0XHR9O1xuXG5cdFx0XHRcdHdwLm1lZGlhLmVkaXRvci5vcGVuKCBidXR0b24gKTtcblx0XHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdFx0fSApO1xuXHRcdH1cblx0fVxuXG5cdCQoIFwiLnJlbW92ZV9jdXN0b21faW1hZ2VcIiApLm9uKCBcImNsaWNrXCIsIGZ1bmN0aW9uKCBlICkge1xuXHRcdGUucHJldmVudERlZmF1bHQoKTtcblxuXHRcdHZhciBpZCA9ICQoIHRoaXMgKS5hdHRyKCBcImRhdGEtaWRcIiApO1xuXHRcdCQoIFwiI1wiICsgaWQgKyAnX2ltYWdlX2NvbnRhaW5lcicgKS5hdHRyKCBcInNyY1wiLCBcIlwiICkuaGlkZSgpO1xuXHRcdCQoIFwiI2hpZGRlbl9cIiArIGlkICkuYXR0ciggXCJ2YWx1ZVwiLCBcIlwiICk7XG5cdFx0JCggXCIud3BzZW8tbG9jYWwtXCIgKyBpZCArIFwiLXdyYXBwZXIgLndwc2VvLWxvY2FsLWhpZGUtYnV0dG9uXCIgKS5hZGRDbGFzcygnaGlkZGVuJyk7XG5cdH0gKTtcblxuXHQvLyBDb3B5IGxvY2F0aW9uIGRhdGFcblx0JCggXCIjd3BzZW9fY29weV9mcm9tX2xvY2F0aW9uXCIgKS5jaGFuZ2UoIGZ1bmN0aW9uKCkge1xuXHRcdHZhciBsb2NhdGlvbl9pZCA9ICQoIHRoaXMgKS52YWwoKTtcblxuXHRcdGlmICggbG9jYXRpb25faWQgPT0gXCJcIiApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cblx0XHQkLnBvc3QoIHdwc2VvX2xvY2FsX2RhdGEuYWpheHVybCwge1xuXHRcdFx0bG9jYXRpb25faWQ6IGxvY2F0aW9uX2lkLFxuXHRcdFx0c2VjdXJpdHk6IHdwc2VvX2xvY2FsX2RhdGEuc2VjX25vbmNlLFxuXHRcdFx0YWN0aW9uOiBcIndwc2VvX2NvcHlfbG9jYXRpb25cIixcblx0XHR9LCBmdW5jdGlvbiggcmVzdWx0ICkge1xuXHRcdFx0aWYgKCByZXN1bHQuY2hhckF0KCByZXN1bHQubGVuZ3RoIC0gMSApID09IDAgKSB7XG5cdFx0XHRcdHJlc3VsdCA9IHJlc3VsdC5zbGljZSggMCwgLTEgKTtcblx0XHRcdH0gZWxzZSBpZiAoIHJlc3VsdC5zdWJzdHJpbmcoIHJlc3VsdC5sZW5ndGggLSAyICkgPT0gXCItMVwiICkge1xuXHRcdFx0XHRyZXN1bHQgPSByZXN1bHQuc2xpY2UoIDAsIC0yICk7XG5cdFx0XHR9XG5cblx0XHRcdHZhciBkYXRhID0gJC5wYXJzZUpTT04oIHJlc3VsdCApO1xuXHRcdFx0aWYgKCBkYXRhLnN1Y2Nlc3MgPT0gXCJ0cnVlXCIgfHwgZGF0YS5zdWNjZXNzID09IHRydWUgKSB7XG5cblx0XHRcdFx0Zm9yICggdmFyIGkgaW4gZGF0YS5sb2NhdGlvbiApIHtcblx0XHRcdFx0XHR2YXIgdmFsdWUgPSBkYXRhLmxvY2F0aW9uWyBpIF07XG5cblx0XHRcdFx0XHRpZiAoIHZhbHVlICE9IG51bGwgJiYgdmFsdWUgIT0gXCJcIiAmJiB0eXBlb2YgdmFsdWUgIT0gXCJ1bmRlZmluZWRcIiApIHtcblx0XHRcdFx0XHRcdGlmICggaSA9PSBcImlzX3Bvc3RhbF9hZGRyZXNzXCIgfHwgaSA9PSBcIm11bHRpcGxlX29wZW5pbmdfaG91cnNcIiApIHtcblx0XHRcdFx0XHRcdFx0aWYgKCB2YWx1ZSA9PSBcIjFcIiApIHtcblx0XHRcdFx0XHRcdFx0XHQkKCBcIiN3cHNlb19cIiArIGkgKS5hdHRyKCBcImNoZWNrZWRcIiwgXCJjaGVja2VkXCIgKTtcblx0XHRcdFx0XHRcdFx0XHQkKCBcIi5vcGVuaW5nLWhvdXJzIC5vcGVuaW5nLWhvdXItc2Vjb25kXCIgKS5zbGlkZURvd24oKTtcblx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0fSBlbHNlIGlmICggaS5pbmRleE9mKCBcIm9wZW5pbmdfaG91cnNcIiApID4gLTEgKSB7XG5cdFx0XHRcdFx0XHRcdCQoIFwiI1wiICsgaSApLnZhbCggdmFsdWUgKTtcblx0XHRcdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0XHRcdCQoIFwiI3dwc2VvX1wiICsgaSApLnZhbCggdmFsdWUgKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHR9ICk7XG5cdH0gKTtcbn0gKTtcblxud2luZG93Lndwc2VvX3Nob3dfYWxsX2xvY2F0aW9uc19zZWxlY3Rib3ggPSBmdW5jdGlvbiggb2JqICkge1xuXHQkID0galF1ZXJ5O1xuXG5cdCRvYmogPSAkKCBvYmogKTtcblx0dmFyIHBhcmVudCA9ICRvYmoucGFyZW50cyggXCIud2lkZ2V0LWluc2lkZVwiICk7XG5cdHZhciAkbG9jYXRpb25zV3JhcHBlciA9ICQoIFwiI3dwc2VvLWxvY2F0aW9ucy13cmFwcGVyXCIsIHBhcmVudCApO1xuXG5cdGlmICggJG9iai5pcyggXCI6Y2hlY2tlZFwiICkgKSB7XG5cdFx0JGxvY2F0aW9uc1dyYXBwZXIuc2xpZGVVcCgpO1xuXHR9IGVsc2Uge1xuXHRcdCRsb2NhdGlvbnNXcmFwcGVyLnNsaWRlRG93bigpO1xuXHR9XG59O1xuIl19
