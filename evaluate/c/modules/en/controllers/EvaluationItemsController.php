<?php

	namespace c\modules\en\controllers;

	use Yii;
	use common\models\EvaluationItems;
	use yii\data\ActiveDataProvider;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;
	use yii\filters\VerbFilter;

	/**
	 * EvaluationItemsController implements the CRUD actions for EvaluationItems model.
	 */
	class EvaluationItemsController extends Controller {

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
		 * Lists all EvaluationItems models.
		 * @return mixed
		 */
		public function actionIndex() {
			if (Yii::$app->user->can('evaluate:evaluation-items')) {
				$dataProvider = new ActiveDataProvider([
					'query' => EvaluationItems::find()->where(['deleted' => null]),
				]);

				return $this->render('index', [
						'dataProvider' => $dataProvider,
				]);
			}
			throw new NotFoundHttpException('You don\'t have a permission to this page.');
		}

		/**
		 * Displays a single EvaluationItems model.
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
		 * Creates a new EvaluationItems model.
		 * If creation is successful, the browser will be redirected to the 'view' page.
		 * @return mixed
		 */
		public function actionCreate() {
			$model = new EvaluationItems();

			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['index']);
			}

			return $this->render('create', [
					'model' => $model,
			]);
		}

		/**
		 * Updates an existing EvaluationItems model.
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
		 * Deletes an existing EvaluationItems model.
		 * If deletion is successful, the browser will be redirected to the 'index' page.
		 * @param integer $id
		 * @return mixed
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		public function actionDelete($id) {
			$model = new EvaluationItems;
			$model->deleteItem($id);
			return $this->redirect(['index']);
		}

		/**
		 * Finds the EvaluationItems model based on its primary key value.
		 * If the model is not found, a 404 HTTP exception will be thrown.
		 * @param integer $id
		 * @return EvaluationItems the loaded model
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		protected function findModel($id) {
			if (($model = EvaluationItems::findOne($id)) !== null) {
				return $model;
			}

			throw new NotFoundHttpException('The requested page does not exist.');
		}

	}
	