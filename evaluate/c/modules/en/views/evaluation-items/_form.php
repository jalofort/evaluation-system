<?php

	use yii\helpers\Url;
	use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
	/* @var $model common\models\EvaluationItems */
	/* @var $form yii\widgets\ActiveForm */
?>

<div class="evaluation-items-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'item')->textInput(['maxlength' => true]) ?>

	<?php // $form->field($model, 'deleted')->textInput() ?>

	<div class="form-group">
		<button type="submit" class="btn btn-success"><i class="far fa-times-circle"></i> Save</button>
		<a href="<?= Url::to(['index']) ?>" class="btn btn-secondary"><i class="far fa-times-circle"></i> Cancel</a>
	</div>

	<?php ActiveForm::end(); ?>

</div>
