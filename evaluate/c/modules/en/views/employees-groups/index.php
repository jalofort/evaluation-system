<?php

	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\widgets\Pjax;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Yii::$app->params['homeUrl'] ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['employees/index']) ?>">Employees</a></li>
		<li class="breadcrumb-item active" aria-current="page">Employees groups</li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left"><h3>Employees groups</h3></span>
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
//					'employeeID',
					[
						'format' => 'raw',
						'attribute' => 'employeeID',
						'value' => function ($data) {
							return '<a href="' . Url::to(['employees/view', 'id' => $data->employee->id]) . '">' . $data->employee->nameAR . '</a>';
						}
					],
//					'groupID',
					[
						'format' => 'raw',
						'attribute' => 'groupID',
						'value' => function ($data) {
							return '<a href="' . Url::to(['groups/view', 'id' => $data->group->id]) . '">' . $data->group->name . '</a>';
						}
					],
//					['class' => 'yii\grid\ActionColumn'],
				],
			]);
		?>
		<?php Pjax::end(); ?>
	</div>
</div>
