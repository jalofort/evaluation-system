<?php

	use yii\helpers\Url;
	use richardfan\widget\JSRegister;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Url::to(['default/index']) ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item active" aria-current="page">Branch employees</li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left">
			<h3>Branch employees<span></span></h3>
		</span>
		<span class="float-right"></span>
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

<?php JSRegister::begin() ?>
<script>
	$(document).ready(function () {
		getEmployees();
		function getEmployees() {
			ajaxPost('<?= Url::to(['employees/get-branch-employees']) ?>', {}, employeesHaveGotten, cannotGetEmployees)
		}
		function employeesHaveGotten(result) {
			if (result == 'no branch') {
				showNotification('danger', 'You are no assinged to any branch.');
			} else if (result == '') {
				showNotification('danger', 'No such employee assinged to this branch.');
			} else {
				var i = 1;
				employeesObj = JSON.parse(result);
				$.each(employeesObj, function () {
					$('#employees').append(`
					<tr>
						<td>` + i + `</td>
						<td>` + $(this).attr('nameAR') + `</td>
						<td><a href='<?= Url::to(['employees/view']) ?>?id=` + $(this).attr('id') + `'>Details</a></td>
				`);
					i++;
				})
			}
		}
		function cannotGetEmployees() {
			showNotification('danger', 'Cannot get employees.');
		}
	})
</script>
<?php JSRegister::end() ?>