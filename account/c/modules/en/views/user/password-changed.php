<?php

	use yii\helpers\Url;

$this->title = Yii::$app->params['companyNameEN'] . ' | My account';
	$url = str_replace('accounts', 'pos', 'http://' . $_SERVER['SERVER_NAME'] . Yii::$app->request->baseUrl . '/site/index')
?>
<div class="col-md-6 offset-md-3">
	<div class="card">
		<div class="card-body">
			<p class="card-text text-center">Your password changed.</p>
			<div class="submit-btn">
				<a href="<?= Url::to(['/']) ?>" class="btn btn-primary btn-block"><i class="far fa-arrow-alt-circle-left"></i> Back</a>
			</div>
		</div>
	</div>
</div>