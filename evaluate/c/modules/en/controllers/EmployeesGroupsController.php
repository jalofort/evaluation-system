<?php

	namespace c\modules\en\controllers;

	use Yii;
	use common\models\EmployeesGroups;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;
	use yii\filters\VerbFilter;

	class EmployeesGroupsController extends Controller {

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

		public function actionIndex() {
			if (Yii::$app->user->can('evaluate:employees-groups')) {
				$employeesGroupsModel = new EmployeesGroups;
				$employeesGroups = $employeesGroupsModel->getEmployeesGroups();

				return $this->render('index', [
						'dataProvider' => $employeesGroups,
				]);
			}
			throw new NotFoundHttpException('You don\'t have a permission to this page.');
		}

		public function actionCreate() {
			$model = new EmployeesGroups();

			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['employees/view', 'id' => $model->employeeID]);
			}

//			return $this->render('create', [
//					'model' => $model,
//			]);
			throw new NotFoundHttpException('Something went wrong.');
		}

		public function actionDelete($id, $employeeID) {
			$this->findModel($id)->delete();

			return $this->redirect(['employees/view', 'id' => $employeeID]);
		}

		protected function findModel($id) {
			if (($model = EmployeesGroups::findOne($id)) !== null) {
				return $model;
			}

			throw new NotFoundHttpException('The requested page does not exist.');
		}

	}
	