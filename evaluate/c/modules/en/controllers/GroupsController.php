<?php

	namespace c\modules\en\controllers;

	use Yii;
	use common\models\Groups;
	use yii\data\ActiveDataProvider;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;
	use yii\filters\VerbFilter;
	use common\models\EvaluationItems;

	/**
	 * GroupsController implements the CRUD actions for Groups model.
	 */
	class GroupsController extends Controller {

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
		 * Lists all Groups models.
		 * @return mixed
		 */
		public function actionIndex() {
			if (Yii::$app->user->can('evaluate:groups')) {
				$dataProvider = new ActiveDataProvider([
					'query' => Groups::find(),
				]);

				return $this->render('index', [
						'dataProvider' => $dataProvider,
				]);
			}
			throw new NotFoundHttpException('You don\'t have a permission to this page.');
		}

		/**
		 * Displays a single Groups model.
		 * @param integer $id
		 * @return mixed
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		public function actionView($id) {
			$model = $this->findModel($id);
			$groupsItemsModel = new \common\models\GroupsItems;
			$groupItems = $groupsItemsModel->getGroupItems($id);

			$itemsModel = new EvaluationItems;
			$items = $itemsModel->getItems($id);

			return $this->render('view', [
					'model' => $model,
					'groupItems' => $groupItems,
					'groupsItemsModel' => $groupsItemsModel,
					'items' => $items,
			]);
		}

		/**
		 * Creates a new Groups model.
		 * If creation is successful, the browser will be redirected to the 'view' page.
		 * @return mixed
		 */
		public function actionCreate() {
			$model = new Groups();

			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}

			return $this->render('create', [
					'model' => $model,
			]);
		}

		/**
		 * Updates an existing Groups model.
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
		 * Deletes an existing Groups model.
		 * If deletion is successful, the browser will be redirected to the 'index' page.
		 * @param integer $id
		 * @return mixed
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		public function actionDelete($id) {
			$this->findModel($id)->delete();

			return $this->redirect(['index']);
		}

		/**
		 * Finds the Groups model based on its primary key value.
		 * If the model is not found, a 404 HTTP exception will be thrown.
		 * @param integer $id
		 * @return Groups the loaded model
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		protected function findModel($id) {
			if (($model = Groups::findOne($id)) !== null) {
				return $model;
			}

			throw new NotFoundHttpException('The requested page does not exist.');
		}

	}
	