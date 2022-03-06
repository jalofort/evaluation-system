<?php

	use yii\helpers\Url;
	use richardfan\widget\JSRegister;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Url::to(['default/index']) ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item active" aria-current="page">Employees evaluation per branch</li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left">
			<h3>Employees evaluation per branch</h3>
		</span>
		<span class="float-right">
			<span>
				<form action="javascript:;">
					<div class="input-group input-group-sm">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Branch</span>
						</div>
						<select id="branches" class="form-control" autofocus="autofocus"></select>
						<div class="input-group-prepend ml-1">
							<span class="input-group-text" id="inputGroup-sizing-sm">Year</span>
						</div>
						<input id="year" type="number" class="form-control" value="<?= date('Y') ?>" min="2019" max="<?= date('Y') ?>">
						<div class="input-group-prepend ml-1">
							<span class="input-group-text" id="inputGroup-sizing-sm">Month</span>
						</div>
						<input id="month" type="number" class="form-control" value="<?= date('m') ?>" min="1" max="12">
						<div class="ml-1">
							<button id="go" class="btn btn-primary btn-sm" type="submit" id="button-addon2">Go</button>
						</div>
					</div>
				</form>
			</span>
		</span>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col text-left">
				<a id="previous-month" href="javascript:;">
					<i class="fas fa-chevron-circle-left fa-3x"></i>
				</a>
			</div>
			<div class="col-6 text-center">
				<h3 id="month-title"></h3>
			</div>
			<div class="col text-right">
				<a id="next-month" href="javascript:;">
					<i class="fas fa-chevron-circle-right fa-3x"></i>
				</a>
			</div>
		</div>
		<div class="mt-3">
			<table class="table table-striped table-bordered">
				<thead>
				<th>Employee</th>
				<th>Month</th>
				<th>Evaluation total</th>
				<th>Evaluation group</th>
				</thead>
				<tbody id="employees">

				</tbody>
			</table>
		</div>
	</div>
</div>

<?php JSRegister::begin() ?>
<script>
	$(document).ready(function () {
		var year = $('#year').val();
		var month = $('#month').val();
		var noneIndexMonth = month - 1;

		getCompanyLocations();

		function getCompanyLocations() {
			ajaxPost('<?= Url::to(['company-locations/get-company-locations']) ?>', {}, companyLocationsHaveGotten, cannotGetCompanyLocations)
			function companyLocationsHaveGotten(result) {
				if (result != '') {
					companyLocationsObj = JSON.parse(result);
					$.each(companyLocationsObj, function () {
						$('#branches').append(`
							<option value=` + $(this).attr('id') + `'>` + $(this).attr('name') + `</option>
						`);
					})
					var branchID = companyLocationsObj[0].id;

					getBranchEmployeesEvaluation(branchID, year, month);
				}
			}
			function cannotGetCompanyLocations() {
				showNotification('danger', 'Cannot get branches.');
			}
		}

		function setMonthTitle(year, month) {
			$('#month-title').text(month + ', ' + year);
		}

		function getBranchEmployeesEvaluation(branchID, year, month) {
			setMonthTitle(year, month);
			var params = {
				branchID: branchID,
				year: year,
				month: month,
			};
			ajaxPost('<?= Url::to(['employees-evaluation/get-branch-employees-evaluation']) ?>', params, branchEmployeesEvaluationHaveGotten, cannotGetBranchEmployeesEvaluation)
			function branchEmployeesEvaluationHaveGotten(result) {
				if (result != '') {
					employeesEvaluationObj = JSON.parse(result);
					$.each(employeesEvaluationObj, function () {
						$('#employees').append(`evaluationCount
							<tr>
								<td>` + $(this).attr('nameAR') + `</td>
								<td>` + year + ` - ` + month + `</td>
								<td><b>` + $(this).attr('evaluationTotal') / $(this).attr('evaluationCount') + ` / 5</b> (` + $(this).attr('evaluationTotal') + ` Points - ` + $(this).attr('evaluationCount') + ` Evaluation item)</td>
								<td>` + $(this).attr('groupName') + `</td>
							</tr>
						`);
					})
				}
			}
			function cannotGetBranchEmployeesEvaluation() {
				showNotification('danger', 'Cannot get employees evaluation.');
			}
		}

		$(document).on('click', '#go', function () {
			$('#employees').empty();
			var branchID = $('#branches :selected').val();
			year = $('#year').val();
			month = $('#month').val();
			getBranchEmployeesEvaluation(branchID, year, month);
			noneIndexMonth = month - 1;
		})

		$(document).on('click', '#previous-month', function () {
			$('#employees').empty();
			var branchID = $('#branches :selected').val();
			noneIndexMonth--;
			var newDate = new Date(year, noneIndexMonth, 1);
			var newYear = newDate.getFullYear();
			var newMonth = newDate.getMonth() + 1;
			getBranchEmployeesEvaluation(branchID, newYear, newMonth);
		})

		$(document).on('click', '#next-month', function () {
			$('#employees').empty();
			var branchID = $('#branches :selected').val();
			noneIndexMonth++;
			var newDate = new Date(year, noneIndexMonth, 1);
			var newYear = newDate.getFullYear();
			var newMonth = newDate.getMonth() + 1;
			getBranchEmployeesEvaluation(branchID, newYear, newMonth);
		})
	})

</script>
<?php JSRegister::end() ?>