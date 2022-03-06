<?php

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\DetailView;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Yii::$app->params['homeUrl'] ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">Evaluation Items</a></li>
		<li class="breadcrumb-item active" aria-current="page"><?= $model->item ?></li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left"><h3>Evaluation Items Details</h3></span>
		<span class="float-right">
			<?php if (Yii::$app->user->can('evaluate:evaluation-items-update')) { ?>
					<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
				<?php } ?>
			<?php if (Yii::$app->user->can('evaluate:evaluation-items-delete')) { ?>
					<?=
					Html::a('Delete', ['delete', 'id' => $model->id], [
						'class' => 'btn btn-danger',
						'data' => [
							'confirm' => 'Are you sure you want to delete this item?',
							'method' => 'post',
						],
					])
					?>
				<?php } ?>
		</span>
	</div>
	<div class="card-body">
		<?=
			DetailView::widget([
				'model' => $model,
				'attributes' => [
					'id',
					'item',
//					'deleted',
				],
			])
		?>
	</div>
</div>