<?php

	use yii\helpers\Url;

$this->title = 'Diva | Registered successfully';
?>
<div class="mt-5">
	<img src="https://cloud.diva.sa/imgs/diva-logo.png" alt="Diva Fitness" width="10%" />
	<div class="jumbotron text-center mt-3">
		<h1>Great! You have been registered successfully.</h1>
		<p class="lead">Your account needs to be activated. please ask your system administrator to activate it.</p>
		<div class="pt-3">
			<a href="<?= Url::to('site/login') ?>">Go to login page</a>
		</div>
	</div>
</div>
