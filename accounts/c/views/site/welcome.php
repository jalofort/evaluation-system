<?php
$this->title = Yii::$app->params['companyNameEN'] . ' | Home';
?>
<div class="jumbotron text-center">
	<h1 class="display-4">Hi, <?= Yii::$app->user->identity->nameEN ?></h1>
	<p class="lead">You are logged in <?= Yii::$app->params['companyNameEN'] ?> systems.</p>
</div>
<div class="big-links text-center">
	<?php if (Yii::$app->user->can('accounts')) { ?>
		<a href="<?= Yii::$app->params['accountsUrl'] ?>" class="btn">Accounts</a>
	<?php } ?>
	<?php if (Yii::$app->user->can('evaluate')) { ?>
		<a href="<?= Yii::$app->params['evaluateUrl'] ?>" class="btn">Evaluation</a>
	<?php } ?>
</div>