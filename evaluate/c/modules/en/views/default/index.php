<?php

	use yii\helpers\Url;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<div class="jumbotron text-center">
	<h1 class="display-4"><?= Yii::$app->params['systemName'] ?> system</h1>
</div>
<div class="big-links text-center">
	<?php if (Yii::$app->user->can('evaluate:employees')) { ?>
			<a href="<?= Url::to(['employees/index']) ?>" class="btn">All employees</a>
		<?php } ?>
	<?php if (Yii::$app->user->can('evaluate:employees-branch')) { ?>
			<a href="<?= Url::to(['employees/branch-employees']) ?>" class="btn">Branch employees</a>
		<?php } ?>
	<?php if (Yii::$app->user->can('evaluate:employees-groups')) { ?>
			<a href="<?= Url::to(['employees-groups/index']) ?>" class="btn">Employees groups</a>
		<?php } ?>
	<?php if (Yii::$app->user->can('evaluate:evaluation')) { ?>
			<a href="<?= Url::to(['evaluation/index']) ?>" class="btn">Evaluation</a>
		<?php } ?>
	<?php if (Yii::$app->user->can('evaluate:evaluation')) { ?>
			<a href="<?= Url::to(['employees-evaluation/index']) ?>" class="btn">Employees evaluation per branch</a>
		<?php } ?>
	<?php if (Yii::$app->user->can('evaluate:evaluation-items')) { ?>
			<a href="<?= Url::to(['evaluation-items/index']) ?>" class="btn">Evaluation items</a>
		<?php } ?>
	<?php if (Yii::$app->user->can('evaluate:groups')) { ?>
			<a href="<?= Url::to(['groups/index']) ?>" class="btn">Groups</a>
		<?php } ?>
	<?php if (Yii::$app->user->can('evaluate:groups-items')) { ?>
			<a href="<?= Url::to(['groups-items/index']) ?>" class="btn">Groups items</a>
		<?php } ?>
	<a href="<?= Url::to(['evaluation/my-evaluation', 'date' => date('Y-m-d')]) ?>" class="btn">My evaluation</a>
</div>