<?php
	/* @var $this yii\web\View */
	/* @var $form yii\bootstrap\ActiveForm */
	/* @var $model \frontend\models\PasswordResetRequestForm */

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->params['companyNameEN'] . ' | Forget password';
	$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row mt-5 login-div">
	<div class="col-md-5 mr-auto ml-auto">
		<div class="text-center">
			<img src="<?= Yii::$app->params['cloudUrl'] ?>imgs/koob-logo.png" class="login-logo" alt="<?= Yii::$app->params['companyNameEN'] ?>" />
		</div>

		<p>Please fill out your registered email. A link to reset password will be sent there.</p>
		<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

		<?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

		<div class="form-group">
			<?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
		</div>

		<div class="text-center" style="color:#999;margin:1em 0">
			<a href="<?= Url::to(['site/login']) ?>">Back to login</a>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
