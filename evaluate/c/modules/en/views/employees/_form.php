<?php

	use yii\widgets\ActiveForm;
	use kartik\select2\Select2;
	use yii\helpers\ArrayHelper;
?>

<div class="employees-form">

	<?php
		$form = ActiveForm::begin([
				'action' => 'create',
				'options' => [
					'id' => 'add-employee-form'
				]
		]);
	?>

	<?php // $form->field($employeesModel, 'personID')->textInput()  ?>

	<?php
		echo $form->field($employeesModel, 'personID')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($nonEmployees, 'id', 'nameAR'),
			'language' => 'en',
			'options' => ['placeholder' => 'Select a person'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	?>

<?php ActiveForm::end(); ?>

</div>
