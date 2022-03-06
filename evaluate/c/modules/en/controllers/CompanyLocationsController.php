<?php

	namespace c\modules\en\controllers;
	
	use Yii;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;
	
	class CompanyLocationsController extends Controller {
		
		public function actionGetCompanyLocations () {
			if (Yii::$app->request->isAjax) {
				$companyLocationsModel = new \common\models\CompanyLocations;
				$companyLocations = $companyLocationsModel->getCompanyLocations();
				return $companyLocations;
			}
			throw new NotFoundHttpException('Page not found.');
		}
		
	}