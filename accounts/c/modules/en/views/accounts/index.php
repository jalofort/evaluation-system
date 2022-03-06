<?php

use yii\grid\GridView;
use richardfan\widget\JSRegister;
use yii\helpers\Url;

$this->title = Yii::$app->params['companyNameEN'] . ' | ' . Yii::$app->params['systemName'];
$url = str_replace('accounts', 'pos', 'https://' . $_SERVER['SERVER_NAME'] . Yii::$app->request->baseUrl . '/site/index');
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= Url::to(['default/index']) ?>"><i class="fas fa-home"></i> Home</a></li>
		<li class="breadcrumb-item active" aria-current="page">Accounts</li>
	</ol>
</nav>
<div class="site-index">
	<div class="card">
		<h3 class="card-header"><i class="fas fa-users-cog"></i> System accounts</h3>
		<div class="card-body">
			<div>
				<?=
				GridView::widget([
					'dataProvider' => $accounts,
					//'filterModel' => $accountsModel,
					'summary' => '',
					'columns' => [
						//['class' => 'yii\grid\SerialColumn'],
						'id',
						//'nameAR',
						[
							'attribute' => 'nameAR',
							'format' => 'raw',
							'value' => function ($data) {
								return '<a href="' . Url::to(['view', 'id' => $data->id]) . '">' . $data->nameAR . '</a>';
							}
						],
						//'accountStatus',
						[
							'attribute' => 'accountStatus',
							'format' => 'raw',
							'value' => function ($data) {
								$template = '<div id="btns-div">';
								if ($data->accountStatus == 1) {
									$template .= '<button id="' . $data->id . '" class="btn btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">';
									$template .= '<div class="handle"></div>';
								} else {
									$template .= '<button id="' . $data->id . '" class="btn btn-toggle active" data-toggle="button" aria-pressed="true" autocomplete="off">';
									$template .= '<div class="handle"></div>';
								}
								$template .= '</button></div>';
								return $template;
							}
						],
						//['class' => 'yii\grid\ActionColumn'],
					],
				]);
				?>
			</div>
		</div>
	</div>
</div>
<?php
$changeStatusUrl = 'https://' . $_SERVER['SERVER_NAME'] . Url::to(['change-status']);
?>
<?php JSRegister::begin() ?>
<script>
	$("#btns-div button").click(function() {
		btnID = $(this).attr("id");
		console.log(btnID)
		btnClass = $(this).attr("class");
		if (btnClass === "btn btn-toggle focus") {
			toRemoveClass = "btn btn-toggle focus active";
			toAddClass = "btn btn-toggle focus";
			accountsStatusChanged = 'User account disabled';
		} else if (btnClass === "btn btn-toggle active focus" || btnClass === "btn btn-toggle focus active") {
			toRemoveClass = "btn btn-toggle focus";
			toAddClass = "btn btn-toggle active focus";
			accountsStatusChanged = 'User account activated';
		}
		var changeAccountStatus = $.ajax({
			url: "<?= $changeStatusUrl ?>",
			type: "post",
			data: {
				id: btnID,
			}
		});
		changeAccountStatus.done(function() {
			showNotification('success', accountsStatusChanged);
		});
		changeAccountStatus.fail(function() {
			$("button[id=" + btnID + "]").removeClass(toRemoveClass);
			$("button[id=" + btnID + "]").addClass(toAddClass);
			showNotification('danger', 'Something went wrong, please try again.');
		});
	});
</script>
<?php JSRegister::end() ?>