<?php

	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	use kartik\select2\Select2;
	use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
	/* @var $model common\models\GroupsItems */
	/* @var $form yii\widgets\ActiveForm */
?>

<div class="groups-items-form">

	<?php
		$form = ActiveForm::begin([
				'action' => Url::to(['groups-items/create']),
				'options' => [
					'id' => 'add-group-item-form'
				]
		]);
	?>

	<?= $form->field($groupsItemsModel, 'groupID')->hiddenInput(['value' => $_GET['id']])->label(false) ?>

	<?php // $form->field($groupsItemsModel, 'itemID')->textInput() ?>

	<?php
		echo $form->field($groupsItemsModel, 'itemID')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($items, 'id', 'item'),
			'language' => 'en',
			'options' => ['placeholder' => 'Select a item'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	?>

	<?php ActiveForm::end(); ?>

</div>
