<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GroupsItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="groups-items-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'groupID')->textInput() ?>

    <?= $form->field($model, 'itemID')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
