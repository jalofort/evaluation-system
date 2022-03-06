<?php

	use yii\helpers\Url;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<div class="jumbotron text-center">
	<h1 class="display-4">Hi, <?= Yii::$app->user->identity->nameEN ?></h1>
	<p class="lead">You are logged in <?= Yii::$app->params['companyNameEN'] ?> Systems.</p>
</div>
<div class="big-links text-center">
	<?php if (Yii::$app->user->can('accounts:system-accounts')) { ?>
			<a href="<?= Url::to(['/en/accounts']) ?>" class="btn"><i class="fas fa-users-cog"></i> System accounts</a>
		<?php } ?>
</div>