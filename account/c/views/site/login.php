<?php
	/* @var $this yii\web\View */
	/* @var $form yii\bootstrap\ActiveForm */
	/* @var $model \common\models\LoginForm */

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->params['companyNameEN'].' | Login';
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row mt-5 login-div">
	<div class="col-md-5 mr-auto ml-auto">
		<div class="text-center">
			<img src="<?= Yii::$app->params['cloudUrl'] ?>imgs/diva-logo.png" class="login-logo" alt="Diva Fitness" />
		</div>
		<!-- <h1 class="text-center"><?= Html::encode($this->title) ?></h1> -->

		<!-- <p class="text-center mt-4">Please fill out the following fields to login:</p> -->


		<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

		<?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

		<?= $form->field($model, 'password')->passwordInput() ?>

		<?= $form->field($model, 'rememberMe')->checkbox() ?>

		<div class="form-group">
			<?= Html::submitButton('Login', ['class' => 'btn btn-block login-btn', 'name' => 'login-button']) ?>
		</div>

		<div class="text-center" style="color:#999;margin:1em 0">
			<div class="float-right">
				<a href="<?= Url::to(['site/request-password-reset']) ?>">Forget password</a>
			</div>
			<div class="float-left">
				<a href="<?= Url::to(['site/register']) ?>">Create account</a>
			</div>

		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
