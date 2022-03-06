<?php

	use yii\widgets\ActiveForm;
	use kartik\select2\Select2;
	use yii\helpers\ArrayHelper;
?>

<div>
	<p class="text-muted">Select a user to add a copy of his/her privileges to the current user.</p>
	<?php
		$form = ActiveForm::begin([
				'action' => 'javascript:;',
				'options' => [
					'id' => 'add-user-privileges-form'
				]
		]);
	?>

	<?php
		echo $form->field($privilegesModel, 'user_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($users, 'id', 'nameAR'),
			'language' => 'en',
			'options' => ['placeholder' => 'Select a user'],
			'pluginOptions' => [
				'allowClear' => true
			],
		])->label('User');
	?>

	<?php ActiveForm::end(); ?>

</div>
