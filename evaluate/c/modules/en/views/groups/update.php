<?php

	use yii\helpers\Url;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Yii::$app->params['homeUrl'] ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">Groups</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['view', 'id' => $model->id]) ?>"><?= $model->name ?></a></li>
		<li class="breadcrumb-item active" aria-current="page">Update</li>
	</ol>
</nav>
<div class="row">
	<div class="col-md-6 offset-md-3">
		<div class="card">
			<div class="card-header">
				<h3>Update group</h3>
			</div>
			<div class="card-body">
				<?=
					$this->render('_form', [
						'model' => $model,
					])
				?>
			</div>
		</div>
	</div>
</div>
