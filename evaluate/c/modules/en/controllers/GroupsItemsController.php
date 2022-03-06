<?php

	namespace c\modules\en\controllers;

	use Yii;
	use common\models\GroupsItems;
	use yii\data\ActiveDataProvider;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;
	use yii\filters\VerbFilter;

	/**
	 * GroupsItemsController implements the CRUD actions for GroupsItems model.
	 */
	class GroupsItemsController extends Controller {

		/**
		 * {@inheritdoc}
		 */
		public function behaviors() {
			return [
				'verbs' => [
					'class' => VerbFilter::className(),
					'actions' => [
						'delete' => ['POST'],
					],
				],
			];
		}

		/**
		 * Lists all GroupsItems models.
		 * @return mixed
		 */
		public function actionIndex() {
			if (Yii::$app->user->can('evaluate:groups-items')) {
				$dataProvider = new ActiveDataProvider([
					'query' => GroupsItems::find(),
				]);

				return $this->render('index', [
						'dataProvider' => $dataProvider,
				]);
			}
			throw new NotFoundHttpException('You don\'t have a permission to this page.');
		}

		/**
		 * Displays a single GroupsItems model.
		 * @param integer $id
		 * @return mixed
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		public function actionView($id) {
			return $this->render('view', [
					'model' => $this->findModel($id),
			]);
		}

		/**
		 * Creates a new GroupsItems model.
		 * If creation is successful, the browser will be redirected to the 'view' page.
		 * @return mixed
		 */
		public function actionCreate() {
			$model = new GroupsItems();

			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['groups/view', 'id' => $model->groupID]);
			}

//			return $this->render('create', [
//					'model' => $model,
//			]);
			throw new NotFoundHttpException('Something went wrong.');
		}

		/**
		 * Updates an existing GroupsItems model.
		 * If update is successful, the browser will be redirected to the 'view' page.
		 * @param integer $id
		 * @return mixed
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		public function actionUpdate($id) {
			$model = $this->findModel($id);

			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}

			return $this->render('update', [
					'model' => $model,
			]);
		}

		/**
		 * Deletes an existing GroupsItems model.
		 * If deletion is successful, the browser will be redirected to the 'index' page.
		 * @param integer $id
		 * @return mixed
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		public function actionDelete($id, $groupID) {
			$groupsItemsModel = new GroupsItems;
			if ($groupsItemsModel->deleteGroupItem($id)) {
				return $this->redirect(['groups/view', 'id' => $groupID]);
			}
			throw new NotFoundHttpException('Something went wrong.');
		}

		/**
		 * Finds the GroupsItems model based on its primary key value.
		 * If the model is not found, a 404 HTTP exception will be thrown.
		 * @param integer $id
		 * @return GroupsItems the loaded model
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		protected function findModel($id) {
			if (($model = GroupsItems::findOne($id)) !== null) {
				return $model;
			}

			throw new NotFoundHttpException('The requested page does not exist.');
		}

		public function actionGetGroupItems() {
			if (Yii::$app->request->isAjax) {
				$groupsItemsModel = new GroupsItems;
				$groupsItems = $groupsItemsModel->getGroupItems2(Yii::$app->request->post()['groupID']);
				return $groupsItems;
			}
			throw new NotFoundHttpException('Page not found.');
		}

	}
	