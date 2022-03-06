<?php

	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	use kartik\select2\Select2;
	use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
	/* @var $model common\models\EmployeesGroups */
	/* @var $form yii\widgets\ActiveForm */
?>

<div class="employees-groups-form">

	<?php $form = ActiveForm::begin([
		'action' => Url::to(['employees-groups/create']),
		'options' => [
			'id' => 'add-employee-group-form'
		]
	]); ?>

	<?= $form->field($employeesGroupsModel, 'employeeID')->hiddenInput(['value' => $_GET['id']])->label(false) ?>

	<?php // $form->field($employeesGroupsModel, 'groupID')->textInput() ?>
	
	<?php
		echo $form->field($employeesGroupsModel, 'groupID')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($groups, 'id', 'name'),
			'language' => 'en',
			'options' => ['placeholder' => 'Select a group'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	?>

	<?php ActiveForm::end(); ?>

</div>
