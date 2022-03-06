<?php
	/* @var $this yii\web\View */
	/* @var $form yii\bootstrap\ActiveForm */
	/* @var $model \c\models\SignupForm */

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->params['companyNameEN'].' | Register';
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row mt-5 login-div">
	<div class="col-md-5 mr-auto ml-auto">
		<div class="text-center">
			<img src="<?= Yii::$app->params['cloudUrl'] ?>imgs/diva-logo.png" class="login-logo" alt="Diva Fitness" />
		</div>


	<!--<p>Please fill out the following fields to signup:</p>-->

		<?php $form = ActiveForm::begin(['id' => 'form-register']); ?>

		<?= $form->field($personsModel, 'nameEN')->textInput(['autofocus' => true])->label('Name in English') ?>

		<?= $form->field($personsEmailsModel, 'email') ?>

		<?= $form->field($personsModel, 'password')->passwordInput() ?>

		<div class="form-group">
			<div class="text-center">
				<img class="loading-img" src="https://cloud.diva.sa/imgs/loading.gif" alt="Loading" />	
			</div>
			<?= Html::submitButton('Register', ['class' => 'btn btn-block login-btn', 'name' => 'signup-button', 'disabled' => 'disabled']) ?>
		</div>
		<div class="text-center" style="color:#999;margin:1em 0">
			<a href="<?= Url::to(['site/login']) ?>">Back to login</a>
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
