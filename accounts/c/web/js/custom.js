"use strict";
function showNotification(cssClass, msg) {
	$('#notification-div').append('<div><span class="alert alert-' + cssClass + '">' + msg + '</span></div>');
	$('#notification-div div').hide().fadeIn(1000);
	$('#notification-div div').fadeTo(4000, 500).fadeOut(1000, function () {
		$(this).remove();
	});
}
;
jQuery(document).ready(function ($) {
	// register page
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

	// function: show notifications

});

function ajaxPost(url, params, successFunction, failFunction) {
	$.ajax({
		url: url,
		method: "POST",
		data: params,
		success: successFunction,
		error: failFunction,
	})
}

function getMonthName(monthNumber) {
	var arrayIndex = monthNumber - 1;
	var monthsArray = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	return monthsArray[arrayIndex];
}