"use strict";

jQuery(document).ready(function ($) {
	$("#form-register input").keyup(function () {
		var empty = true;
		$('#form-register input').each(function () {
			if ($(this).val() === "") {
				empty = false;
			}
		});
		if (empty === true) {
			$("#form-register button").removeAttr("disabled");
		} else {
			$("#form-register button").attr("disabled", "disabled");
		}
	});
	$('#form-register').submit(function () {
		$("#form-register button").hide();
		$("#form-register .loading-img").show();
	});
});
