<?php

	namespace c\modules\en\controllers;
	
	use Yii;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;
	use common\models\EmployeesWorkLocations;
	
	class EmployeesWorkLocationsController extends Controller {
		
		public function actionUpdateEmployeeBranch () {
			if (Yii::$app->request->isAjax) {
				$employeeID = Yii::$app->request->post()['employeeID'];
				$branchID = Yii::$app->request->post()['branchID'];
				$employeesWorkLocationsModel = new EmployeesWorkLocations;
				$updateBranch = $employeesWorkLocationsModel->updateEmployeeBranch($employeeID, $branchID);
				return $updateBranch;
			}
			throw new NotFoundHttpException('Page not found.');
		}
		
	}