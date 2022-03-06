<?php

	use yii\helpers\Url;
	use richardfan\widget\JSRegister;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Yii::$app->params['homeUrl'] ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">Employees</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['view', 'id' => $employee['id']]) ?>"><?= $employee['nameAR'] ?></a></li>
		<li class="breadcrumb-item active" aria-current="page">Evaluation</li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left"><h3>Employee</h3></span>
		<span class="float-right">

		</span>
	</div>
	<div class="card-body">
		<table id="w0" class="table table-striped table-bordered detail-view">
			<tbody>
				<tr><th>id</th><td><span><?= $employee['id'] ?></span></td></tr>
				<tr><th>Employee</th><td><span><?= $employee['nameAR'] ?></span></td></tr>
				<tr><th>Evaluation group</th><td><span><?= $group['name'] ?></span></td></tr>
			</tbody>
		</table>
	</div>
</div>
<div class="mt-3"></div>
<div class="card">
	<div class="card-header">
		<span class="float-left"><h3>Employee evaluation</h3></span>
		<span class="float-right">
		</span>
	</div>
	<div id="evaluation" class="card-body">
		<div id="evaluation-group-empty" class="invalid-feedback mb-3" style="font-size: 100%">
			The evaluation group doesn't have any items.
		</div>
		<form id="evaluation-form" action="javascript:;" method="POST">
			<table class="table table-striped table-bordered detail-view">
				<thead>
				<th>id</th>
				<th>Evaluation item</th>
				<th>Evaluation</th>
				</thead>
				<tbody id="evaluation-tbody">
					<tr><td>-</td><td>-</td><td>-</td></tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<template id="evaluation-records">
	<table id="evaluation-records" class="table table-striped table-bordered detail-view">
		<thead>
			<tr>
				<th>#</th>
				<th>Month</th>
				<th>Evaluation item</th>
				<th>Evaluation</th>
				<th>Evaluator</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</template>
<?php JSRegister::begin() ?>
<script>
	$(document).ready(function () {
		new EmployeeEvalution;
	})

	class EmployeeEvalution {
		constructor() {
			this.employeeEvaluation;
			this.params = {
				employeeID: <?= $_GET['employeeID'] ?>,
				groupID: <?= $_GET['groupID'] ?>,
				date: '<?= date('Y-m-d') ?>',
			}
			this.getEmployeeEvalution();
		}
		getEmployeeEvalution() {
			ajaxPost('<?= Url::to(['evaluation/get-employee-evaluation']) ?>', this.params, this.evaluationHaveGotten, this.cannotGetEvaluation);
		}
		evaluationHaveGotten(result) {
			var evaluationRecords = JSON.parse(result);
			if (evaluationRecords.length == 0) {
				getEvaluationGroupItems();
			} else {
				showEvaluationRecords(evaluationRecords);
			}
		}
		cannotGetEvaluation() {
			showNotification('danger', 'Cannot get the evaluation.');
		}

	}

	function showEvaluationRecords(evaluationRecords) {
		var evaluationRecordTable = $('template#evaluation-records').html();
		function updateEvalutionTemplate(id, evaluation, note, evaluatorID) {
			var updateTagsTemplate = '';
			var updateButtonDisabled = 'disabled="disabled"';
			var userID = <?= Yii::$app->user->identity->id ?>;
			if (userID == evaluatorID) {
				updateButtonDisabled = '';
				updateTagsTemplate = `<div id="update-evaluation-value" class="d-none">
								<form method="POST" action="javascript:;">
									<div>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text" id="evaluation-value-prepend">Evaluation</span>
											</div>
											<input evaluation-id="` + id + `" id="new-evaluation-value" type="number" min="0" max="5" class="form-control" value="` + evaluation + `" aria-describedby="evaluation-value-prepend" >
										</div>
									</div>
									<div>
										<label for="new-evaluation-note">Notes</label>
										<textarea evaluation-id="` + id + `" id="new-evaluation-note" type="text" class="form-control form-control-sm" value="` + note + `"></textarea>
									</div>
									<div class="mt-1">
										<button class="btn btn-success btn-sm" type="submit">Save</button>
										<button class="btn btn-secondary btn-sm" type="button">Cancel</button>
									</div>
								</form>
							</div>`;
			}
			return `<div id="evaluation-value">
					<span class="float-left">` + evaluation + `<br> ( ` + note + ` )</span>
					<span class="float-right"><button class="btn btn-primary btn-sm" href="javascript:;" ` + updateButtonDisabled + `><i class="far fa-edit"></i> Update</button></span>
				</div>` + updateTagsTemplate;
		}
		$('#evaluation').html(evaluationRecordTable);

		$.each(evaluationRecords, function () {
			$('#evaluation-records tbody').append(`
				<tr>
					<td>` + $(this).attr('id') + `</td>
					<td>` + getMonthName($(this).attr('month')) + `, ` + $(this).attr('year') + `</td>
					<td>` + $(this).attr('item') + `</td>
					<td>
						` + updateEvalutionTemplate($(this).attr('id'), $(this).attr('evaluation'), $(this).attr('note'), $(this).attr('evaluatorID')) + `
					</td>
					<td>` + $(this).attr('nameAR') + `</td>
					<td>` + $(this).attr('date') + `</td>
				</tr>
			`)
		})
	}

	function getEvaluationGroupItems() {
		params = {
			groupID: <?= $_GET['groupID'] ?>,
		}

		ajaxPost('<?= Url::to(['groups-items/get-group-items']) ?>', params, groupItemsHaveGotten, cannotGetGroupItems);
		function groupItemsHaveGotten(result) {
			$('#evaluation-tbody').empty();
			var itemsObj = JSON.parse(result);
			$.each(itemsObj, function (index) {
				$('#evaluation-tbody').append(`
					<tr>
						<td>` + (index + 1) + `</td>
						<td>` + $(this).attr('itemName') + `</td>
						<td><input item-id="` + $(this).attr('itemID') + `" type="number" min="0" max="5" class="form-control"></td>
					</tr>
				`)
			})
			$('#evaluation-tbody').append(`<tr><td></td><td></td><td><button type="submit" class="btn btn-success"><i class="far fa-check-circle"></i> Save</button></td></tr>`)
		}

		function cannotGetGroupItems() {
			showNotification('danger', 'Cannot get group items');
		}
	}

	$(document).on('submit', '#evaluation-form', function () {
		var emptyInputs = true;
		var evaluationInputObj = []
		var formInputs = $('#evaluation-form').find('input[type="number"]')
		formInputs.each(function () {
			if ($(this).val() != '') {
				emptyInputs = false;
			}
			evaluationInputObj.push({
				itemID: $(this).attr('item-id'), 'evaluation': $(this).val()
			})
		})
		if (emptyInputs == true) {
			showNotification('danger', 'You should enter one value at least.')
		} else {
			var evaluationInput = JSON.stringify(evaluationInputObj);
			saveTheEvaluationInputs(evaluationInput);
		}
	})

	function saveTheEvaluationInputs(evaluationInput) {
		params = {
			employeeID: <?= $_GET['employeeID'] ?>,
			groupID: <?= $_GET['groupID'] ?>,
			data: evaluationInput
		}

		ajaxPost('<?= Url::to(['evaluation/save-evaluation']) ?>', params, theEvaluationSaved, cannotSaveTheEvaluation);

		function theEvaluationSaved() {
			window.location.replace("<?= Url::to(['employees/evaluate', 'employeeID' => $_GET['employeeID'], 'groupID' => $_GET['groupID']]) ?>");
		}

		function cannotSaveTheEvaluation() {
			showNotification('danger', 'Cannot save the evaluation inputs.');
		}
	}

	// on click update evaluation
	$(document).on('click', '#evaluation-value button', function () {
		var updateEvaluationValue = new UpdateValueNew();
		updateEvaluationValue.showUpdateInputs($(this));
	});

	// on click cancel update evaluation
	$(document).on('click', "#update-evaluation-value button[type='button']", function () {
		var updateEvaluationValue = new UpdateValueNew();
		updateEvaluationValue.hideUpdateInputs($(this));
	})

	// on submit update evaluation
	$(document).on('submit', '#update-evaluation-value form', function () {
		var evaluationForm = $(this);
		var evaluationInput = $(this).find('#new-evaluation-value');
		var evaluation = evaluationInput.val();
		var evaluationID = evaluationInput.attr('evaluation-id');

		var params = {
			evaluationID: evaluationID,
			evaluation: evaluation,
		}
		ajaxPost("<?= Url::to(['evaluation/update-evaluation']) ?>", params, evaluationValueUpdated, cannotUpdateEvaluationValue);

		function evaluationValueUpdated() {
			showNotification('success', 'Updated.')

			var updateInputsParent = evaluationForm.parent();
			var theValueParent = updateInputsParent.prev();

			$(theValueParent).children().first().text(evaluation);
			updateInputsParent.addClass('d-none');
			theValueParent.removeClass('d-none');
		}

		function cannotUpdateEvaluationValue() {
			showNotification('danger', 'Cannot update the value.')
		}

	})

</script>
<?php JSRegister::end() ?>