<?php

	use richardfan\widget\JSRegister;
	use yii\helpers\Url;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Url::to(['default/index']) ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['accounts/index']) ?>">Accounts</a></li>
		<li class="breadcrumb-item active" aria-current="page"><?= $userInfo['nameAR'] ?></li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<h3><i class="fa fa-user"></i> User account</h3>
	</div>
	<div class="card-body">
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item">
				<a href="#main" class="nav-link active" role="tab" data-toggle="tab" aria-expanded="true"><i class="far fa-address-card"></i> Main information</a>
			</li>
			<li class="nav-item">
				<a href="#privileges" class="nav-link" role="tab" data-toggle="tab" aria-expanded="false"><i class="fab fa-whmcs"></i> Privileges</a>
			</li>
		</ul>
		<div class="tab-content pt-3">
			<div class="tab-pane fade show active" id="main">
				<div id="user-info">
					<table id="w0" class="table table-striped table-bordered detail-view">
						<tbody id="customer-info">
							<tr><th>User ID</th><td><?= $userInfo['id'] ?></td></tr>
							<tr><th>Name in Arabic</th><td><?= $userInfo['nameAR'] ?></td></tr>
							<tr><th>Primary email</th><td><?= $userEmail['email'] ?></td></tr>
							<tr><th>Password</th><td id="password"><a id="edit-password" href="javascript:;">Update the password</a></td></tr>
							<tr><th>Account status</th>
								<td>
									<?php
										if ($userInfo['accountStatus'] == 1) {
											$class = 'btn btn-toggle';
											$ariaPressed = 'false';
										}
										else {
											$class = 'btn btn-toggle active';
											$ariaPressed = 'true';
										}
									?>
									<div id="btns-div">
										<button id="change-status" class="<?= $class ?>" data-toggle="button" aria-pressed="<?= $ariaPressed ?>" autocomplete="off">
											<div class="handle"></div>
										</button>
									</div>
								</td>
							</tr>
					</table>
				</div>
			</div>
			<div class="tab-pane fade" id="privileges">
				<div class="privileges-div">
					<div class="clearfix mb-3">
						<span class="float-right">
							<a href="#" data-toggle="modal" data-target="#add-privileges" class="btn btn-primary">
								<i class="fa fa-plus" ></i> Add privileges like another user
							</a>
							<span id="delete-privileges">
								<a href="javascript:;" id="delete" class="btn btn-danger"><i class="fas fa-trash-alt" ></i> Unassign all user privileges</a>
								<span id="are-you-sure-delete-privileges" class="d-none">
									Are you sure?
									<a id="yes-sure-delete-privileges" href="javascript:;" class="btn btn-success btn-sm">Yes</a>
									<a href="javascript:;" id="cancel-delete-privileges" class="btn btn-secondary btn-sm">Cancel</a>
								</span>
							</span>
						</span>
					</div>
					<div class="row">
						<div id="unassigned-list-div1" class="col-6">
							<div class="text-center">
								<img id="unassigned-list-loading-img" src="<?= Yii::$app->request->baseUrl ?>/images/loading.gif" alt="loading" width="10%">
							</div>
							<div class="mt-3 mb-4 unassigned-list-div2">
								<h3><i class="fas fa-list-ul"></i> Privileges list</h3>
							</div>
							<ul id="unassigned-list" class="list-group"></ul>
						</div>
						<div id="assigned-list-div1" class="col-6">
							<div class="text-center">
								<img id="assigned-list-loading-img" src="<?= Yii::$app->request->baseUrl ?>/images/loading.gif" alt="loading" width="10%">
							</div>
							<div class="mt-3 mb-4 assigned-list-div2">
								<h3><i class="fas fa-user-check"></i> User privileges</h3>
							</div>
							<ul id="assigned-list" class="list-group"></ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="add-privileges">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add user privilegs</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
					echo $this->renderFile('@app/modules/en/views/accounts/_select-user-form.php', [
						'privilegesModel' => $privilegesModel,
						'users' => $users,
					]);
				?>
			</div>
			<div class="modal-footer">
				<button type="submit" form="add-user-privileges-form" class="btn btn-success"><i class="far fa-check-circle"></i> Save</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="far fa-times-circle"></i> Cancel</button>
			</div>
		</div>
	</div>
</div>
<?php
	$updatePasswordUrl = 'https://' . $_SERVER['SERVER_NAME'] . Url::to(['update-user-account-password']);
	$changeStatusUrl = 'https://' . $_SERVER['SERVER_NAME'] . Url::to(['change-status']);
	$unassignedPrivilegesUrl = 'https://' . $_SERVER['SERVER_NAME'] . Url::to(['get-user-unassigned-privileges']);
	$assignedPrivilegesUrl = 'https://' . $_SERVER['SERVER_NAME'] . Url::to(['get-user-assigned-privileges']);
	$assignUserPrivilegeUrl = 'https://' . $_SERVER['SERVER_NAME'] . Url::to(['assign-user-privilege']);
	$removeUserPrivilegeUrl = 'https://' . $_SERVER['SERVER_NAME'] . Url::to(['remove-user-privilege']);
?>
<?php JSRegister::begin(); ?>
<script>
	// changing user account password
	$(document).on('click', 'a#edit-password', function () {
		$('td#password').html('<form id="update-password" method="POST" action="javascript:;"><div class="input-group"><input id="new-password" type="password" class="form-control form-control-sm" placeholder="Enter the new password" /><div class="input-group-append"><button class="btn btn-success btn-sm" type="submit">Save</button></div></div><div class="invalid-feedback"></div></form>');
		$("input#new-password").focus();
	});
	$(document).on('keypress', 'input#new-password', function () {
		$('td#password .invalid-feedback').hide();
		$('#new-password').removeClass('is-invalid');
	});
	$(document).on('submit', 'form#update-password', function () {
		var newPassword = $('input#new-password').val();
		if (newPassword.length < 3) {
			$('td#password .invalid-feedback').show();
			$('td#password .invalid-feedback').html('Short password');
			$('#new-password').addClass('is-invalid');
			$("input#new-password").focus();
		} else {
			var updatePassword = $.ajax({
				url: "<?= $updatePasswordUrl ?>",
				method: "POST",
				data: {
					password: newPassword,
					userID: <?= $_GET['id'] ?>
				}
			});
			updatePassword.done(function () {
				$('td#password').html('<a id="edit-password" href="javascript:;">Update the password</a>');
				showNotification('success', 'Password updated.');
			});
			updatePassword.fail(function () {
				showNotification('danger', 'Something went wrong, please try again.');
			});
		}
	});

	// change user account status
	$("button#change-status").click(function () {
		btnClass = $(this).attr("class");
		if (btnClass === "btn btn-toggle focus") {
			toRemoveClass = "btn btn-toggle focus active";
			toAddClass = "btn btn-toggle focus";
			accountsStatusChanged = 'User account disabled';
		} else if (btnClass === "btn btn-toggle active focus" || btnClass === "btn btn-toggle focus active") {
			toRemoveClass = "btn btn-toggle focus";
			toAddClass = "btn btn-toggle active focus";
			accountsStatusChanged = 'User account activated';
		}
		var changeAccountStatus = $.ajax({
			url: "<?= $changeStatusUrl ?>",
			type: "post",
			data: {
				id: <?= $_GET['id'] ?>,
			}
		});
		changeAccountStatus.done(function () {
			showNotification('success', accountsStatusChanged);
		});
		changeAccountStatus.fail(function () {
			$("button#change-status").removeClass(toRemoveClass);
			$("button#change-status").addClass(toAddClass);
			showNotification('danger', 'Something went wrong, please try again.');
		});
	});

	// function : get user unassigned privilegs
	function getUserUnassignedPrivileges() {
		var request = $.ajax({
			url: "<?= $unassignedPrivilegesUrl ?>",
			method: "POST",
			data: {
				id: "<?= $_GET['id'] ?>",
			},
			dataType: "html"
		});
		request.done(function (result) {
			$("#unassigned-list-loading-img").hide();
			$('#unassigned-list li').remove();
			if (result === "") {
				$("#unassigned-list").append("<li class='list-group-item d-flex justify-content-between align-items-center no-more-privileges'>No more privileges !</li>");
			} else {
				resultObj = JSON.parse(result);
				$.each(resultObj, function () {
					$("#unassigned-list").append("<li class='list-group-item d-flex justify-content-between align-items-center'>" + $(this).attr("name") + " ( " + $(this).attr("description") + " ) <a class='unassigned-privilege' id='" + $(this).attr("name") + "' href='javascript:;'>Add <i class='fas fa-arrow-right'></i></a></li>");
				});
			}
			$("#unassigned-list-div1").fadeIn();
		});
	}

	// function : get user assigned privilegs
	function getUserAssignedPrivileges() {
		var request = $.ajax({
			url: "<?= $assignedPrivilegesUrl ?>",
			method: "POST",
			data: {
				id: "<?= $_GET['id'] ?>",
			},
			dataType: "html"
		});
		request.done(function (result) {
			$("#assigned-list-loading-img").hide();
			$('#assigned-list li').remove();
			if (result === "") {
				thereIsNoAssignedPrivileges();
			} else {
				resultObj = JSON.parse(result);
				$.each(resultObj, function () {
					$("#assigned-list").append("<li class='list-group-item d-flex justify-content-between align-items-center'>" + $(this).attr("item_name") + " ( " + $(this).attr("description") + " ) <a class='assigned-privilege' id='" + $(this).attr("item_name") + "' href='javascript:;'><i class='fas fa-trash-alt'></i></a></li>");
				});
			}
			$("#assigned-list-div1").fadeIn();
		});
	}

	function thereIsNoAssignedPrivileges() {
		$('#delete-privileges').hide();
		$("#assigned-list").append("<li class='list-group-item d-flex justify-content-between align-items-center no-assigned-privileges'>No assigned privileges found !</li>");
	}

	// get user assigned privilegs 
	var privilegesTabClicked = false;
	$("a[href='#privileges']").on('click', function () {
		if (privilegesTabClicked === false) {
			getUserUnassignedPrivileges();
			getUserAssignedPrivileges();
			privilegesTabClicked = true;
		}
	});

	// adding one privilege to the user when a tag clicked in li
	$(document).on('click', '.unassigned-privilege', function () {
		var toAssignPrivilegeName = $(this).attr('id'); // get a tag id ( privilege name )
		var clickedPrivilegeLi = $(this).parent(); //select the li
		$(this).remove(); // removing the a tag from the li
		var clickedprivilegeValue = $(clickedPrivilegeLi).text();  // get li  content ( the item name + add word ) & removing add word from the item value.
		clickedPrivilegeLi.remove(); // removing the li after clicking on it 
		$('.no-assigned-privileges').remove(); // removing the li ( no assigned privileges for the user ) if it exists
		// addding the li to the user privileges at the top
		$("#assigned-list").prepend("<li class='list-group-item d-flex justify-content-between align-items-center'>" + clickedprivilegeValue + "<a class='assigned-privilege' id='" + toAssignPrivilegeName + "' href='javascript:;'><i class='fas fa-trash-alt'></i></a></li>");
		var unassignedPrivilegesCount = $('.unassigned-privilege').length; // get how many privileges remains ( how many li remains )
		if (unassignedPrivilegesCount < 1) {
			// if no more privileges add ( no more privileges ) li
			$("#unassigned-list").append("<li class='list-group-item d-flex justify-content-between align-items-center no-more-privileges'>No more privileges !</li>");
		}
		var assignUserPrivilege = $.ajax({
			url: "<?= $assignUserPrivilegeUrl ?>",
			method: "POST",
			data: {
				privilege: toAssignPrivilegeName,
				userID: <?= $_GET['id'] ?>
			},
			dataType: "html"
		});
		assignUserPrivilege.done(function () {
			$('#notification-div').append('<div><span id="assigning-ok-' + toAssignPrivilegeName + '" class="alert alert-success">Privilege assigned.</span></div>');
			$('#notification-div div').hide().fadeIn(1000);
			$('#notification-div div').fadeTo(4000, 500).fadeOut(1000, function () {
				$(this).remove();
			});
			$('#delete-privileges').show();
		});
		assignUserPrivilege.fail(function () {
			getUserUnassignedPrivileges();
			getUserAssignedPrivileges();
			$('#notification-div').append('<div><span id="assigning-er-' + toAssignPrivilegeName + '" class="alert alert-danger">Something went wrong, please try again.</span></div>');
			$('#notification-div div').hide().fadeIn(1000);
			$('#notification-div div').fadeTo(4000, 500).fadeOut(1000, function () {
				$(this).remove();
			});
		});
	});

	// removing one privilege from the user
	$(document).on('click', '.assigned-privilege', function () {
		var toRemovePrivilegeName = $(this).attr('id'); // get a tag id ( privilege name )
		var toRemovePrivilegeLi = $(this).parent(); //select the li
		$(this).remove(); // removing the a tag from the li
		var clickedprivilegeValue = $(toRemovePrivilegeLi).text();  // get li  content ( the item name + add word ) & removing add word from the item value.
		toRemovePrivilegeLi.remove(); // removing the li after clicking on it 
		$('.no-more-privileges').remove(); // removing the li ( no assigned privileges for the user ) if it exists
		// addding the li to the user privileges at the top
		$("#unassigned-list").prepend("<li class='list-group-item d-flex justify-content-between align-items-center'>" + clickedprivilegeValue + "<a class='unassigned-privilege' id='" + toRemovePrivilegeName + "' href='javascript:;'>Add <i class='fas fa-arrow-right'></i></a></li>");
		var assignedPrivilegesCount = $('.assigned-privilege').length; // get how many assigned privileges ( how many li remains )
		if (assignedPrivilegesCount < 1) {
			// if no more privileges add ( no assigned privileges ) li
			thereIsNoAssignedPrivileges();
		}
		var removeUserPrivilege = $.ajax({
			url: "<?= $removeUserPrivilegeUrl ?>",
			method: "POST",
			data: {
				privilege: toRemovePrivilegeName,
				userID: <?= $_GET['id'] ?>
			},
			dataType: "html"
		});
		removeUserPrivilege.done(function () {
			$('#notification-div').append('<div><span id="assigning-ok-' + toRemovePrivilegeName + '" class="alert alert-success">Privilege unassigned.</span></div>');
			$('#notification-div div').hide().fadeIn(1000);
			$('#notification-div div').fadeTo(4000, 500).fadeOut(1000, function () {
				$(this).remove();
			});
		});
		removeUserPrivilege.fail(function () {
			getUserUnassignedPrivileges();
			getUserAssignedPrivileges();
			$('#notification-div').append('<div><span id="assigning-er-' + toRemovePrivilegeName + '" class="alert alert-danger">Something went wrong, please try again.</span></div>');
			$('#notification-div div').hide().fadeIn(1000);
			$('#notification-div div').fadeTo(4000, 500).fadeOut(1000, function () {
				$(this).remove();
			});
		});
	});

	$(document).on('click', '#delete-privileges #delete', function () {
		$(this).hide();
		$(this).next().removeClass('d-none');
	})

	$(document).on('click', '#delete-privileges #cancel-delete-privileges', function () {
		$(this).parent().addClass('d-none');
		$(this).parent().prev().show();
	})

	$(document).on('click', '#delete-privileges #yes-sure-delete-privileges', function () {
		$(this).parent().addClass('d-none');
		$(this).parent().prev().show();
		unassignUserAllPrivileges();
	})

	function unassignUserAllPrivileges() {
		ajaxPost("<?= Url::to(['accounts/unassign-user-all-privileges']) ?>", {userID: <?= $_GET['id'] ?>}, userPrivilegesDeleted, cannotDeleteUserPrivileges);
		function userPrivilegesDeleted() {
			getUserUnassignedPrivileges();
			getUserAssignedPrivileges();
			showNotification('success', 'User privileges deleted.');
		}
		function cannotDeleteUserPrivileges() {
			showNotification('danger', 'Cannot delete user privileges.');
		}
	}

	$(document).on('submit', '#add-user-privileges-form', function () {
		var selectedUserID = $(this).find('select :selected').val();
		var params = {
			selectedUserID: selectedUserID,
			currentUserID: <?= $_GET['id'] ?>,
		}
		ajaxPost("<?= Url::to(['accounts/add-user-privileges-like-another-user']) ?>", params, privilegsAdded, cannotAddPrivileges);
	})

	function privilegsAdded(addPrivilegesCount) {
		var notificationMsg = '';
		if (addPrivilegesCount == 0) {
			notificationMsg = 'No privileges added, the current user already have theses privileges.'
		} else if (addPrivilegesCount > 0) {
			notificationMsg = 'Privileges added.'
		}
		getUserUnassignedPrivileges();
		getUserAssignedPrivileges();
		$('#add-privileges').modal('toggle');
		showNotification('success', notificationMsg);
		$('#delete-privileges').show();
	}

	function cannotAddPrivileges() {
		$('#add-privileges').modal('toggle');
		showNotification('danger', 'Cannot add privileges.');
	}

</script>
<?php JSRegister::end(); ?>