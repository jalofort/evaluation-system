<?php

	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\widgets\Pjax;
	use richardfan\widget\JSRegister;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];

	$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Yii::$app->params['homeUrl'] ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item active" aria-current="page">Evaluation</li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left"><h3>Evaluation</h3></span>
		<span class="float-right">
			<?php if (Yii::$app->user->can('evaluate:add-employee-evaluation')) { ?>
					<a href="<?= Url::to(['create']) ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add employee evaluation</a>
				<?php } ?>
		</span>
	</div>
	<div class="card-body">
		<?php Pjax::begin(); ?>
		<?=
			GridView::widget([
				'dataProvider' => $dataProvider,
				'columns' => [
//					['class' => 'yii\grid\SerialColumn'],
					'id',
//					'evaluatorID',
					[
						'attribute' => 'evaluatorID',
						'value' => 'evaluator.nameAR',
					],
//					'evaluatedID',
					[
						'attribute' => 'evaluatedID',
						'value' => 'evaluated.nameAR',
					],
//					'evaluateItemID',
					[
						'attribute' => 'month',
						'format' => 'raw',
						'value' => function ($data) use ($months) {
							$monthArray = explode('-', $data->month);
							$monthNumber = $monthArray[1] - 1;
							return $months[$monthNumber] . ', ' . $monthArray[0] . ' <a href="javascript:;" id="' . $data->id . '" class="change-month-btn"><i class="fa fa-edit"></i></a>';
						}
					],
					[
						'attribute' => 'evaluateItemID',
						'value' => 'evaluateItem.item',
					],
					'evaluation',
					'note',
					'date',
					[
						'format' => 'raw',
						'value' => function ($data) {
							return '<a href="' . Url::to(['evaluation/view', 'id' => $data->id]) . '">Details</a>';
						}
					]
//					['class' => 'yii\grid\ActionColumn'],
				],
			]);
		?>
		<?php Pjax::end(); ?>
	</div>
</div>
<?php JSRegister::begin() ?>
<script>
	$(document).on('click', '.change-month-btn', function () {
		monthTD = $(this).parent();
		btnID = $(this).attr('id');

		var oldMonth = $(monthTD).html();
		$(monthTD).empty();

		var currentDate = new Date();
		var currentYear = currentDate.getFullYear();
		var currentMonth = currentDate.getMonth() + 1;
		var lastMonth = currentDate.getMonth();

		$(monthTD).html(`
			<form action="javascript:;" id="change-month">
				<input type='hidden' value='` + btnID + `' name='row-id'>
				<div class="form-row align-items-center">
					<div class="col-4">
						<label class="sr-only" for="inlineFormInput">Month</label>
						<input type="number" name='month' class="form-control form-control-sm" id="inlineFormInput" placeholder="Month">
					</div>
					<div class="col-4">
						<label class="sr-only" for="inlineFormInput">Year</label>
						<input type="number" name='year' class="form-control form-control-sm" id="inlineFormInput" placeholder="Year" min="2019">
					</div>
					<div class="col-1">
						<button type="submit" class="btn btn-success btn-sm">Change</button>
					</div>
					
				</div>
				<div class="mt-1">
					<button id="cancel-change-month-btn" type="button" class="btn btn-default btn-sm">Cancel</button>
				</div>
			</form>
		`);

		$(monthTD).find('input[name="month"]').val(lastMonth);
		$(monthTD).find('input[name="month"]').attr('max', currentMonth);
		$(monthTD).find('input[name="year"]').val(currentYear);
		$(monthTD).find('input[name="year"]').attr('max', currentYear);

		$(document).on('submit', '#change-month', function () {
			var id = $(this).find('input[name="row-id"]').val();
			var year = $(this).find('input[name="year"]').val();
			var month = $(this).find('input[name="month"]').val();

			var params = {
				id: id,
				year: year,
				month: month,
			}
			ajaxPost('<?= Url::to(['evaluation/update-month']) ?>', params, monthChanged, cannotChangeMonth)

			function monthChanged() {
				$(monthTD).html(`
					` + month + `, ` + year + `
					<a href="javascript:;" id="` + id + `" class="change-month-btn"><i class="fa fa-edit"></i></a>
				`);
				showNotification('success', 'Month changed.');
			}
			function cannotChangeMonth() {
				showNotification('danger', 'Cannot change month.');
			}
		})

		$(document).on('click', '#cancel-change-month-btn', function () {
			$(this).closest('td').html(oldMonth);
		})

	})
</script>
<?php JSRegister::end() ?>
