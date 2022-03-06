<?php

	namespace c\modules\en\controllers;

	use Yii;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;

	class EmployeesEvaluationController extends Controller {

		public function actionIndex() {
			if (Yii::$app->user->can('evaluate:evaluation')) {
				return $this->render('index');
			}
			throw new NotFoundHttpException('You don\'t have a permission to this page.');
		}

		public function actionGetBranchEmployeesEvaluation() {
			if (Yii::$app->request->isAjax) {
				$employeesWorkLocationsModel = new \common\models\EmployeesWorkLocations;
				$workLocationEmployeesIDs = $employeesWorkLocationsModel->getLocationEmployeesIDs(Yii::$app->request->post()['branchID']);

				$year = Yii::$app->request->post()['year'];
				$month = Yii::$app->request->post()['month'];
				$employeesEvalutionsModel = new \common\models\Evaluation;
				$employeesEvalutions = $employeesEvalutionsModel->getEmployeesEvaluationTotal($workLocationEmployeesIDs, $year, $month);

				return $employeesEvalutions;
			}
			throw new NotFoundHttpException('Page not found.');
		}

		public function actionTest() {
			
			$employeesWorkLocationsModel = new \common\models\EmployeesWorkLocations;
			$workLocationEmployeesIDs = $employeesWorkLocationsModel->getLocationEmployeesIDs(2);

			echo '<pre>';
			print_r($workLocationEmployeesIDs);
			echo '</pre>';
		}

	}
	