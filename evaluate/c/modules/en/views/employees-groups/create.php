<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\EmployeesGroups */

$this->title = 'Create Employees Groups';
$this->params['breadcrumbs'][] = ['label' => 'Employees Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employees-groups-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
