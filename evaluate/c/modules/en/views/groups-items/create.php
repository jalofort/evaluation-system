<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GroupsItems */

$this->title = 'Create Groups Items';
$this->params['breadcrumbs'][] = ['label' => 'Groups Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="groups-items-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
