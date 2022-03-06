<?php

	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\widgets\Pjax;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Yii::$app->params['homeUrl'] ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['groups/index']) ?>">Groups</a></li>
		<li class="breadcrumb-item active" aria-current="page">Groups items</li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left"><h3>Groups items</h3></span>
		<span class="float-right"></span>
	</div>
	<div class="card-body">
		<?php Pjax::begin(); ?>
		<?=
			GridView::widget([
				'dataProvider' => $dataProvider,
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
//					'id',
//					'groupID',
					[
						'attribute' => 'groupID',
						'value' => 'group.name',
					],
//					'itemID',
					[
						'attribute' => 'itemID',
						'value' => 'item.item',
					],
//					['class' => 'yii\grid\ActionColumn'],
				],
			]);
		?>
		<?php Pjax::end(); ?>
	</div>
</div>
