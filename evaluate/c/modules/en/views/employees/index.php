<?php

use yii\helpers\Url;
use richardfan\widget\JSRegister;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Url::to(['default/index']) ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item active" aria-current="page">Employees</li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left">
			<h3>Employees</h3>
		</span>
		<span class="float-right">
			<?php if (Yii::$app->user->can('evaluate:employees-groups')) { ?>
				<a href="<?= Url::to(['employees-groups/index']) ?>" class="btn btn-primary">Employees groups</a>
			<?php } ?>
			<?php if (Yii::$app->user->can('hr:add-employee')) { ?>
				<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-employee"><i class="fa fa-plus"></i> Add employee</a>
			<?php } ?>
		</span>
	</div>
	<div class="card-body">
		<table class="table table-striped table-bordered">
			<thead>
				<th>ID</th>
				<th>Name</th>
				<th></th>
			</thead>
			<tbody id="employees">

			</tbody>
		</table>
	</div>
</div>
<div id="add-employee" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add employee</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
				echo $this->renderFile('@app/modules/en/views/employees/_form.php', [
					'employeesModel' => $employeesModel,
					'nonEmployees' => $nonEmployees,
				]);
				?>
			</div>
			<div class="modal-footer">
				<button type="submit" form="add-employee-form" class="btn btn-success"><i class="far fa-check-circle"></i> Save</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="far fa-times-circle"></i> Cancel</button>
			</div>
		</div>
	</div>
</div>

<?php JSRegister::begin() ?>
<script>
	$(document).ready(function() {
		getEmployees();

		function getEmployees() {
			ajaxPost('<?= Url::to(['employees/get-employees']) ?>', {}, employeesHaveGotten, cannotGetEmployees)
		}

		function employeesHaveGotten(result) {
			var i = 1;
			employeesObj = JSON.parse(result);
			$.each(employeesObj, function() {
				$('#employees').append(`
					<tr>
						<td>` + i + `</td>
						<td>` + $(this).attr('nameAR') + `</td>
						<td><a href='<?= Url::to(['employees/view']) ?>?id=` + $(this).attr('id') + `'>Details</a></td>
				`);
				i++;
			})
		}

		function cannotGetEmployees() {
			showNotification('danger', 'Cannot get employees.');
		}
	})
</script>
<?php JSRegister::end() ?>