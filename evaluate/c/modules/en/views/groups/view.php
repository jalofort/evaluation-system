<?php

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\DetailView;
	use yii\grid\GridView;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Yii::$app->params['homeUrl'] ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">Groups</a></li>
		<li class="breadcrumb-item active" aria-current="page"><?= $model->name ?></li>
	</ol>
</nav>
<div class="card">
	<div class="card-header">
		<span class="float-left"><h3>Group details</h3></span>
		<span class="float-right">
			<?php if (Yii::$app->user->can('evaluate:groups-update')) { ?>
					<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
				<?php } ?>
			<?php if (Yii::$app->user->can('evaluate:groups-delete')) { ?>
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
					'name',
//				'deleted',
				],
			])
		?>
	</div>
</div>
<div class="mt-3"></div>
<div class="card">
	<div class="card-header">
		<span class="float-left"><h3>Group items</h3></span>
		<span class="float-right">
			<?php if (Yii::$app->user->can('evaluate:groups-items-add')) { ?>
					<a href="#" data-toggle="modal" data-target="#add-item" class="btn btn-primary"><i class="fa fa-plus"></i> Add item</a>
				<?php } ?>
		</span>
	</div>
	<div class="card-body">
		<?=
			GridView::widget([
				'dataProvider' => $groupItems,
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
//					'id',
//					'itemName',
					[
						'format' => 'raw',
						'attribute' => 'itemName',
						'value' => function ($data) {
							return '<a href="' . Url::to(['evaluation-items/view', 'id' => $data->itemID]) . '">' . $data->itemName . '</a>';
						}
					],
					[
						'format' => 'raw',
						'value' => function ($data) {
							if (Yii::$app->user->can('evaluate:groups-items-delete')) {
								return '<a class="btn btn-danger btn-sm" href="' . Url::to(['groups-items/delete', 'id' => $data->id, 'groupID' => $_GET['id']]) . '" data-method="POST" data-confirm="Are you sure you want to delete this item?">Delete</a>';
							}
						}
					],
				],
			])
		?>
	</div>
</div>
<div id="add-item" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add group item</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
					echo $this->renderFile('@app/modules/en/views/groups/_form-group-items.php', [
						'items' => $items,
						'groupsItemsModel' => $groupsItemsModel,
					]);
				?>
			</div>
			<div class="modal-footer">
				<button type="submit" form="add-group-item-form" class="btn btn-success"><i class="far fa-check-circle"></i> Save</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="far fa-times-circle"></i> Cancel</button>
			</div>
		</div>
	</div>
</div>