<?php

	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\widgets\Pjax;
	use richardfan\widget\JSRegister;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];

	$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December'];
	$dateArray = explode('-', $_GET['date']);
	$monthNumber = $dateArray[1] - 1;
	$monthName = $months[$monthNumber] . ', ' . $dateArray[0];

	$previousMonth = date('Y-m-d', strtotime($_GET['date'] . ' - 1 month'));
	$nextMonth = date('Y-m-d', strtotime($_GET['date'] . ' + 1 month'));
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Yii::$app->params['homeUrl'] ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item active" aria-current="page">My evaluation</li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left"><h3 class="mb-0">My evaluation</h3></span>
		<span class="float-right">
			<form id="date-form" action="javascript:;">
				<div class="form-row align-items-center">
					<div class="col-auto">
						<label class="sr-only" for="year-input">Year</label>
						<div class="input-group input-group-sm">
							<div class="input-group-prepend">
								<div class="input-group-text">Year</div>
							</div>
							<input type="number" class="form-control" id="year-input" min="2019" value="<?= date('Y') ?>" max="<?= date('Y') ?>" type="number" required="required">
						</div>
					</div>
					<div class="col-auto">
						<label class="sr-only" for="month-input">Month</label>
						<div class="input-group input-group-sm">
							<div class="input-group-prepend">
								<div class="input-group-text">Month</div>
							</div>
							<input type="number" class="form-control" id="month-input" min="1" max="12" type="number" required="required" autofocus="autofocus">
						</div>
					</div>
					<div class="col-auto">
						<button type="submit" class="btn btn-primary btn-sm">Go</button>
					</div>

				</div>
			</form>
		</span>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col text-left">
				<a href="<?= Url::to(['my-evaluation', 'date' => $previousMonth]) ?>">
					<i class="fas fa-chevron-circle-left fa-3x"></i>
				</a>
			</div>
			<div class="col-6 text-center">
				<h3><?= $monthName ?></h3>
			</div>
			<div class="col text-right">
				<a href="<?= Url::to(['my-evaluation', 'date' => $nextMonth]) ?>">
					<i class="fas fa-chevron-circle-right fa-3x"></i>
				</a>
			</div>
		</div>
		<div class="mt-3">
			<?php Pjax::begin(); ?>
			<?=
				GridView::widget([
					'tableOptions' => [
						'id' => 'employee-evaluation',
						'class' => 'table table-striped table-bordered'
					],
					'dataProvider' => $dataProvider,
					'columns' => [
//					['class' => 'yii\grid\SerialColumn'],
						'id',
//					'evaluatorID',
						[
							'attribute' => 'evaluatorID',
							'value' => 'evaluator.nameAR',
						],
//					'evaluateItemID',
						[
							'attribute' => 'evaluateItemID',
							'value' => 'evaluateItem.item',
						],
						//'evaluation',
						[
							'attribute' => 'evaluation',
							'value' => 'evaluation',
							'contentOptions' => [
								'id' => 'evaluation-value'
							],
						],
						'date',
//					[
//						'format' => 'raw',
//						'value' => function ($data) {
//							return '<a href="' . Url::to(['evaluation/view', 'id' => $data->id]) . '">Details</a>';
//						}
//					]
//					['class' => 'yii\grid\ActionColumn'],
					],
				]);
			?>
			<?php Pjax::end(); ?>
		</div>
	</div>
</div>
<?php JSRegister::begin() ?>
<script>
	var evaluationTotal = 0, i = 0;
	var evaluationValues = $(document).find("td#evaluation-value");
	evaluationValues.each(function () {
		evaluationValue = parseInt($(this).text())
		evaluationTotal += evaluationValue;
		i++;
	})
	if (evaluationTotal > 0) {
		var finalScore = evaluationTotal / i;
		$('#employee-evaluation').append(`
			<tfoot>
				<tr>
					<td colspan='3'></td>
					<td><b>` + finalScore + ` / 5 ( Total )</b></td>
					<td></td>
				</tr>
			</tfoot>
		`)
	}

	$(document).on('submit', '#date-form', function () {
		var year = $('input#year-input').val();
		var month = $('input#month-input').val();
		var date = year + '-' + month + '-' + '01';
		window.location.replace('my-evaluation?date=' + date);
	})
</script>
<?php JSRegister::end() ?>