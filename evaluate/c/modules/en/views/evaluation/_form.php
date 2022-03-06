<?php

	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	use kartik\select2\Select2;
	use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
	/* @var $model common\models\Evaluation */
	/* @var $form yii\widgets\ActiveForm */
?>

<div class="evaluation-form">

	<?php $form = ActiveForm::begin(); ?>

	<?php // $form->field($model, 'evaluatorID')->textInput() ?>

	<?php
		echo $form->field($model, 'evaluatorID')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($employees, 'id', 'nameAR'),
			'language' => 'en',
			'options' => ['placeholder' => 'Select a supervisior'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	?>

	<?php // $form->field($model, 'evaluatedID')->textInput() ?>
	
	<?php
		echo $form->field($model, 'evaluatedID')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($employees, 'id', 'nameAR'),
			'language' => 'en',
			'options' => ['placeholder' => 'Select an employee'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	?>

	<?php // $form->field($model, 'evaluateItemID')->textInput() ?>
	
	<?php
		echo $form->field($model, 'evaluateItemID')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($evaluationItems, 'id', 'item'),
			'language' => 'en',
			'options' => ['placeholder' => 'Select the evaluation item'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	?>

	<?php // $form->field($model, 'evaluation')->textInput() ?>

	<?php // $form->field($model, 'date')->textInput() ?>

	<div class="form-group">
		<button type="submit" class="btn btn-success"><i class="far fa-check-circle"></i> Save</button>
		<a href="<?= Url::to(['index']) ?>" class="btn btn-secondary"><i class="far fa-times-circle"></i> Cancel</a>
	</div>

	<?php ActiveForm::end(); ?>

</div>
