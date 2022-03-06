alert(ajaxUrl);
function changeStatus(personID, url, csrfToken) {
	resultDiv = '.result-' + personID;
	$.ajax({
		url: url,
		method: "post",
		data: {
			id: personID,
			_csrf: csrfToken,
		},
	});

	//return $(resultDiv).text(personID);
	//return alert(ajaxUrl);
}