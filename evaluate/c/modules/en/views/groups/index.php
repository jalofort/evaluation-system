<?php

	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\widgets\Pjax;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Yii::$app->params['homeUrl'] ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item active" aria-current="page">Groups</li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left"><h3>Groups</h3></span>
		<span class="float-right">
			<?php if (Yii::$app->user->can('evaluate:groups-items')) { ?>
					<a href="<?= Url::to(['groups-items/index']) ?>" class="btn btn-primary">Groups items</a>
				<?php } ?>
			<?php if (Yii::$app->user->can('evaluate:groups-add')) { ?>
					<a href="<?= Url::to(['create']) ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add group</a>
				<?php } ?>
		</span>
	</div>
	<div class="card-body">
		<?php Pjax::begin(); ?>
		<?=
			GridView::widget([
				'dataProvider' => $dataProvider,
				'columns' => [
//				['class' => 'yii\grid\SerialColumn'],
					'id',
					'name',
//				'deleted',
//				['class' => 'yii\grid\ActionColumn'],
					[
						'format' => 'raw',
						'value' => function ($data) {
							return '<a href="' . Url::to(['groups/view', 'id' => $data->id]) . '">Details</a>';
						}
					]
				],
			]);
		?>
		<?php Pjax::end(); ?>
	</div>
</div>