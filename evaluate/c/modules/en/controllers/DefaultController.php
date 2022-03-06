<?php

	namespace c\modules\en\controllers;

	use Yii;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;

	/**
	 * Default controller for the `en` module
	 */
	class DefaultController extends Controller {

		/**
		 * Renders the index view for the module
		 * @return string
		 */
		public function actionIndex() {
			if (Yii::$app->user->can('evaluate')) {
				return $this->render('index');
			}
			throw new NotFoundHttpException('You don\'t have a permission to this page.');
		}

	}
	