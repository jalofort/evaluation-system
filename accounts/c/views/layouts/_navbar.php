<?php

use yii\helpers\Url;

$currentURL = 'http://' . $_SERVER['HTTP_HOST'] . Yii::$app->homeUrl;
$myAccountUrl = str_replace('accounts', 'account', $currentURL);
$evaluateUrl = str_replace('accounts', 'evaluate', $currentURL);
?>
<nav class="navbar navbar-expand-lg">
	<div class="container">
		<a class="navbar-brand" href="<?= Yii::$app->homeUrl ?>"><?= Yii::$app->params['companyNameEN'] ?> | <small><?= Yii::$app->params['systemName'] ?></small></a>

		<div class="form-inline my-2 my-md-0">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown07" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-th"></i>
					</a>
					<div class="dropdown-menu" aria-labelledby="dropdown07">
						<a class="dropdown-item" href="<?= Yii::$app->params['welcomeUrl'] ?>"><i class="fas fa-home"></i> Home</a>
						<div class="dropdown-divider"></div>
						<?php if (Yii::$app->user->can('accounts')) { ?>
							<a class="dropdown-item" href="<?= Yii::$app->params['accountsUrl'] ?>"><i class="fas fa-users-cog"></i> Accounts</a>
						<?php } ?>
						<?php if (Yii::$app->user->can('evaluate')) { ?>
							<a class="dropdown-item" href="<?= Yii::$app->params['evaluateUrl'] ?>"><i class="fas fa-users"></i> Evaluation</a>
						<?php } ?>
					</div>
				</li>
			</ul>
			<ul class="navbar-nav mr-auto">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown07" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-user"></i>
						<?= Yii::$app->user->identity->nameEN ?>
					</a>
					<div class="dropdown-menu text-center" aria-labelledby="dropdown07">
						<a class="dropdown-item" href="<?= $myAccountUrl ?>"><i class="fa fa-user"></i> My account</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="<?= Url::to(['/site/logout']) ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div id="notification-div"></div>
</nav>