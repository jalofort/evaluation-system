<?php

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\DetailView;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
	$monthsArray = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	$dateArray = explode('-', $model->month);
	$index = $dateArray[1] - 1; // month - 1
	$year = $dateArray[0];
	$evaluationMonth = $monthsArray[$index] . ', ' . $year;
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Yii::$app->params['homeUrl'] ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">Evaluation</a></li>
		<li class="breadcrumb-item active" aria-current="page"><?= $model->id ?></li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left"><h3>Evaluation view</h3></span>
		<span class="float-right">
			<?php if (Yii::$app->user->can('evaluate:delete-employee-evaluation')) { ?>
			<?=
				Html::a('Delete', ['delete', 'id' => $model->id], [
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

		<?=
			DetailView::widget([
				'model' => $model,
				'attributes' => [
					'id',
					[
						'attribute' => 'month',
						'value' => $evaluationMonth
					],
//					'evaluatorID',
					[
						'format' => 'raw',
						'attribute' => 'evaluatorID',
						'value' => function($model) {
							return $model->evaluator->nameAR . ' <i class="fas fa-user-alt"></i>';
						}
					],
//					'evaluatedID',
					[
						'attribute' => 'evaluatedID',
						'value' => $model->evaluated->nameAR
					],
//					'evaluateItemID',
					[
						'attribute' => 'evaluateItemID',
						'value' => $model->evaluateItem->item
					],
					'evaluation',
					'date',
				],
			])
		?>

	</div>
</div>
