<?php

	use yii\helpers\Html;
	use yii\helpers\Url;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Yii::$app->params['homeUrl'] ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">Evaluation</a></li>
		<li class="breadcrumb-item active" aria-current="page">Add  evaluation</li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left"><h3>Add evaluation</h3></span>
		<span class="float-right">

		</span>
	</div>
	<div class="card-body">
		<?=
			$this->render('_form', [
				'model' => $model,
				'employees' => $employees,
				'evaluationItems' => $evaluationItems,
			])
		?>
	</div>
</div>
