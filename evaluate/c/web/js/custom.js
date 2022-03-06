"use strict";
function showNotification(cssClass, msg) {
	$('#notification-div').append('<div><span class="alert alert-' + cssClass + '">' + msg + '</span></div>');
	$('#notification-div div').hide().fadeIn(1000);
	$('#notification-div div').fadeTo().fadeOut(3000, function () {
		$(this).remove();
	});
}

class test {
	constuctor() {}
	test1() {
		alert('test from test class');
	}
}

class UpdateValueNew {

	constructor() {}

	// function show inputs to update element
	showUpdateInputs(updateButton) {
		var theButtonParent = $(updateButton).parent(); // that contains the button or a tag which click
		var theValueParent = theButtonParent.prev(); // that contains the value you want to change
		var grandParent = theValueParent.parent(); // that contains both the value and the update button
		var updateInputsParent = grandParent.next(); // that contains update inputs to enter new values

		grandParent.addClass('d-none');
		updateInputsParent.removeClass('d-none');
	}

	// function cancel update ( hide update input and show the value ) 
	hideUpdateInputs(cancelButton) {
		var updateInputsParent = $(cancelButton).parent().parent().parent().parent();
		var theValueParent = updateInputsParent.prev();

		updateInputsParent.addClass('d-none');
		theValueParent.removeClass('d-none');
	}

	saveTheNewValue(theForm, url, params, successMsg) {

//		var formInputs = $(theForm).find(':input');
//		var i = 1;
//		var NewValue = '';
//		formInputs.each(function () {
//			if ($(this).val() != "") {
//				params['value-' + i] = $(this).val();
//
//				if ($(this).is('select')) {
//					params['text-' + i] = $("option:selected", this).text();
//					NewValue += $("option:selected", this).text();
//				} else {
//					params['text-' + i] = $(this).text();
//					NewValue += $(this).val();
//				}
//				i++;
//			}
//
//		});

		var request = $.ajax({
			url: url,
			method: "POST",
			data: params,
		});
		request.done(function () {
			showNotification('success', successMsg);
		});
		request.fail(function () {
			showNotification('danger', 'Something went wrong.');
		});

		this.hideUpdateInputs(NewValue);
	}

}

class UpdateValue {

	constructor(updateButton) {
		this.theButtonParent = $(updateButton).parent(); // that contains the button or a tag which click
		this.theValueParent = this.theButtonParent.prev(); // that contains the value you want to change
		this.grandParent = this.theValueParent.parent(); // that contains both the value and the update button
		this.updateInputsParent = this.grandParent.next(); // that contains update inputs to enter new values
	}

	// function show inputs to update element
	showUpdateInputs() {
		this.grandParent.hide();
		this.updateInputsParent.removeClass('d-none');
		this.updateInputsParent.fadeIn();
	}

	// function cancel update ( hide update input and show the value ) 
	hideUpdateInputs(theValue) {
		this.updateInputsParent.hide();
		this.theValueParent.text(theValue);
		this.grandParent.fadeIn();
	}

	saveTheNewValue(url, params, successMsg) {

//		var formInputs = $(theForm).find(':input');
//		var i = 1;
//		var NewValue = '';
//		formInputs.each(function () {
//			if ($(this).val() != "") {
//				params['value-' + i] = $(this).val();
//
//				if ($(this).is('select')) {
//					params['text-' + i] = $("option:selected", this).text();
//					NewValue += $("option:selected", this).text();
//				} else {
//					params['text-' + i] = $(this).text();
//					NewValue += $(this).val();
//				}
//				i++;
//			}
//
//		});

		var request = $.ajax({
			url: url,
			method: "POST",
			data: params,
		});
		request.done(function () {
			showNotification('success', successMsg);
		});
		request.fail(function () {
			showNotification('danger', 'Something went wrong.');
		});

		this.hideUpdateInputs(NewValue);
	}

}

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