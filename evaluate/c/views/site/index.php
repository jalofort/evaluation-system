<?php
	$this->title = Yii::$app->params['companyNameEN'].' | My account';
	$url = str_replace('accounts', 'pos', 'http://' . $_SERVER['SERVER_NAME'] . Yii::$app->request->baseUrl . '/site/index')
?>
<div class="site-index">
	<div class="jumbotron text-center">
		<h1>Hi, <?= Yii::$app->user->identity->nameEN ?></h1>
		<p class="lead">You are logged in Diva System.</p>
	</div>
</div>
