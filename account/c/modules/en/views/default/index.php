<?php

	use yii\helpers\Url;

$this->title = Yii::$app->params['companyNameEN'] . ' | My account';
	$url = str_replace('accounts', 'pos', 'http://' . $_SERVER['SERVER_NAME'] . Yii::$app->request->baseUrl . '/site/index')
?>
<div class="site-index">
	<div class="jumbotron text-center">
		<h4><a href="<?= Url::to(['user/change-password']) ?>">Change your password</a></h4>
	</div>
</div>
