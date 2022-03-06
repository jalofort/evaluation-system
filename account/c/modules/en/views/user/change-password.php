<?php

	use yii\helpers\Url;
	use yii\widgets\ActiveForm;

$this->title = Yii::$app->params['companyNameEN'] . ' | My account';
	$url = str_replace('accounts', 'pos', 'http://' . $_SERVER['SERVER_NAME'] . Yii::$app->request->baseUrl . '/site/index')
?>
<div class="col-md-6 offset-md-3">
	<div class="card">
		<h5 class="card-header"><i class="fas fa-lock"></i> Change your password</h5>
		<div class="card-body">
			<p class="card-text">To change your password enter the current password and the new password</p>
			<?php $form = ActiveForm::begin() ?>
			<?= $form->field($changePasswordModel, 'currentPassword')->passwordInput() ?>
			<?= $form->field($changePasswordModel, 'newPassword')->passwordInput() ?>
			<?= $form->field($changePasswordModel, 'repeatedNewPassword')->passwordInput() ?>
			<div class="submit-btn">
				<div class="text-center">
					<img class="loading-img" src="<?= Yii::$app->homeUrl ?>web/imgs/loading.gif" alt="Loading" />	
				</div>
				<button type="submit" class="btn btn-success"><i class="far fa-check-circle"></i> Save</button>
				<a href="<?= Url::to(['default/index']) ?>" class="btn btn-secondary"><i class="far fa-times-circle"></i> Cancel</a>
			</div>
			<?php ActiveForm::end() ?>
		</div>
	</div>
</div>