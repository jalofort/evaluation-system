<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use richardfan\widget\JSRegister;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Yii::$app->params['homeUrl'] ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">Employees</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['branch-employees']) ?>">Branch employees</a></li>
		<li class="breadcrumb-item active" aria-current="page"><?= $employee['nameAR'] ?></li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left">
			<h3>Employee details</h3>
		</span>
		<span class="float-right">
			<!--<a href="<?= Url::to(['update', 'id' => $employee['id']]) ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Update</a>-->
			<?php if (Yii::$app->user->can('hr:employee-unassign')) { ?>
				<?=
				Html::a('Delete', ['delete', 'id' => $employee['id']], [
					'class' => 'btn btn-danger',
					'data' => [
						'confirm' => 'Are you sure you want to delete this item?',
						'method' => 'post',
					],
				])
				?>
			<?php } ?>
		</span>
	</div>
	<div class="card-body">
		<table id="w0" class="table table-striped table-bordered detail-view">
			<tbody>
				<tr>
					<th>id</th>
					<td><span><?= $employee['id'] ?></span></td>
				</tr>
				<tr>
					<th>Employee</th>
					<td>
						<div id="employee-name">
							<span class="float-left"><?= $employee['nameAR'] ?></span>
							<?php if (Yii::$app->user->can('hr:employee-edit')) { ?>
								<span class="float-right"><a class="btn btn-primary btn-sm" href="javascript:;"><i class="far fa-edit"></i> Update</a></span>
							<?php } ?>
						</div>
						<div id="update-employee-name" class="d-none">
							<form method="POST" action="javascript:;">
								<div class="input-group">
									<input id="new-employee-name" type="text" class="form-control form-control-sm">
									<div class="input-group-append">
										<button class="btn btn-success btn-sm" type="submit">Save</button>
										<button class="btn btn-secondary btn-sm" type="button">Cancel</button>
									</div>
								</div>
							</form>
						</div>
					</td>
				</tr>
				<tr>
					<th>Branch</th>
					<td>
						<div id="employee-branch">
							<span class="float-left"></span>
							<?php if (Yii::$app->user->can('hr:employee-edit')) { ?>
								<span class="float-right"><a class="btn btn-primary btn-sm" href="javascript:;"><i class="far fa-edit"></i> Update</a></span>
							<?php } ?>
						</div>
						<div id="update-employee-branch" class="d-none">
							<form method="POST" action="javascript:;">
								<div class="input-group">
									<select id="branches" class="form-control form-control-sm"></select>
									<div class="input-group-append">
										<button class="btn btn-success btn-sm" type="submit">Save</button>
										<button class="btn btn-secondary btn-sm" type="button">Cancel</button>
									</div>
								</div>
							</form>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="mt-3"></div>
<div class="card">
	<div class="card-header">
		<span class="float-left">
			<h3>Employee evaluation groups</h3>
		</span>
		<span class="float-right">
			<?php if (Yii::$app->user->can('evaluate:employees-groups-add')) { ?>
				<a href="#" data-toggle="modal" data-target="#add-item" class="btn btn-primary"><i class="fa fa-plus"></i> Add employee group</a>
			<?php } ?>
		</span>
	</div>
	<div class="card-body">
		<?=
		GridView::widget([
			'dataProvider' => $employeesGroups,
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],
				//					'id',
				//					'name',
				[
					'format' => 'raw',
					'attribute' => 'name',
					'label' => 'Group name',
					'value' => function ($data) {
						return '<a href="' . Url::to(['employees/evaluate', 'employeeID' => $_GET['id'], 'groupID' => $data->groupID]) . '">' . $data->group->name . '</a>';
						//							return 'mm';
					}
				],
				[
					'format' => 'raw',
					'value' => function ($data) {
						if (Yii::$app->user->can('evaluate:employees-groups-delete')) {
							return '<a class="btn btn-danger btn-sm" href="' . Url::to(['employees-groups/delete', 'id' => $data->id, 'employeeID' => $_GET['id']]) . '" data-method="POST" data-confirm="Are you sure you want to delete this item?">Delete</a>';
						}
					}
				],
			],
		])
		?>
	</div>
</div>
<div id="add-item" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add group item</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
				echo $this->renderFile('@app/modules/en/views/employees/_form-employee-groups.php', [
					'groups' => $groups,
					'employeesGroupsModel' => $employeesGroupsModel,
				]);
				?>
			</div>
			<div class="modal-footer">
				<button type="submit" form="add-employee-group-form" class="btn btn-success"><i class="far fa-check-circle"></i> Save</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="far fa-times-circle"></i> Cancel</button>
			</div>
		</div>
	</div>
</div>
<?php JSRegister::begin() ?>
<script>
	// on click update employe name
	$(document).on('click', '#employee-name a', function() {
		var updateEmployeeName = new UpdateValueNew();
		updateEmployeeName.showUpdateInputs($(this));
	});

	// on click cancel update employee name
	$(document).on('click', "#update-employee-name button[type='button']", function() {
		var updateEvaluationValue = new UpdateValueNew();
		updateEvaluationValue.hideUpdateInputs($(this));
	})

	// on submit update employee name
	$(document).on('submit', '#update-employee-name form', function() {

		var employeeNameForm = $(this);
		var employeeNameInput = $(this).find('#new-employee-name');
		var employeeName = employeeNameInput.val();

		var params = {
			employeeID: <?= $_GET['id'] ?>,
			employeeName: employeeName,
		}

		ajaxPost("<?= Url::to(['employees/update-employee-name']) ?>", params, employeeNameUpdated, cannotUpdateEmployeeName);

		function employeeNameUpdated() {
			showNotification('success', 'Employee name updated.')

			var updateInputsParent = employeeNameForm.parent();
			var theValueParent = updateInputsParent.prev();

			$(theValueParent).children().first().text(employeeName);
			updateInputsParent.addClass('d-none');
			theValueParent.removeClass('d-none');
		}

		function cannotUpdateEmployeeName() {
			showNotification('danger', 'Cannot update the employee name.')
		}

	})

	// on click update employe branch
	var updateBranchClicked = false;
	$(document).on('click', '#employee-branch a', function() {
		var updateEmployeeBranch = new UpdateValueNew();
		updateEmployeeBranch.showUpdateInputs($(this));

		if (updateBranchClicked == false) {
			// get branches
			ajaxPost("<?= Url::to(['company-locations/get-company-locations']) ?>", {}, branchesHasGotton, cannotGetBranches);

			function branchesHasGotton(branches) {
				if (branches !== '') {
					var branchesObj = JSON.parse(branches);
					branchesObj.forEach(function(branch) {
						$('select#branches').append(`
						<option value='` + branch['id'] + `'>` + branch['name'] + `</option>
					`);
					})
				}
			}

			function cannotGetBranches() {
				showNotification('danger', 'Cannot get branches.')
			}
			updateBranchClicked = true;
		}
	});

	// on click cancel update employee branch
	$(document).on('click', "#update-employee-branch button[type='button']", function() {
		var updateEmployeeBranch = new UpdateValueNew();
		updateEmployeeBranch.hideUpdateInputs($(this));
	})

	// on submit update employee branch
	$(document).on('submit', '#update-employee-branch form', function() {

		var employeeBranchForm = $(this);
		var employeeBranchInput = $(this).find('#branches');
		var employeeBranch = employeeBranchInput.find(':selected').text();
		var employeeBranchID = employeeBranchInput.val();

		var params = {
			employeeID: <?= $_GET['id'] ?>,
			branchID: employeeBranchID,
		}
		ajaxPost("<?= Url::to(['employees-work-locations/update-employee-branch']) ?>", params, employeeBranchUpdated, cannotUpdateEmployeeBranch);

		function employeeBranchUpdated() {
			showNotification('success', 'Employee branch updated.')

			var updateInputsParent = employeeBranchForm.parent();
			var theValueParent = updateInputsParent.prev();

			$(theValueParent).children().first().text(employeeBranch);
			updateInputsParent.addClass('d-none');
			theValueParent.removeClass('d-none');
		}

		function cannotUpdateEmployeeBranch() {
			showNotification('danger', 'Cannot update the employee branch.')
		}

	})

	// get employee branch name
	var params = {
		employeeID: <?= $_GET['id'] ?>,
	}
	ajaxPost("<?= Url::to(['employees/get-employee-location']) ?>", params, employeeBranchHaveGotton, cannotGetEmployeeBranch);

	function employeeBranchHaveGotton(result) {
		if (result !== '') {
			var resultObj = JSON.parse(result);
			$('#employee-branch').children(":first").text(resultObj.name);
		}
	}

	function cannotGetEmployeeBranch() {
		showNotification('danger', 'Cannot get the employee branch.')
	}
</script>
<?php JSRegister::end() ?>