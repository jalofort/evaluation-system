<?php

	namespace c\modules\en\controllers;

	use Yii;
	use yii\web\Controller;

	class UserController extends Controller {

		/**
		 * Renders the index view for the module
		 * @return string
		 */
		public function actionChangePassword() {

			$changePasswordModel = new \c\modules\en\models\user\ChangePassword;

			if ($changePasswordModel->load(Yii::$app->request->post()) && $changePasswordModel->validate()) {
				$personsModel = new \common\models\persons\Persons;
				if ($personsModel->saveNewPassword(Yii::$app->request->post('ChangePassword')['newPassword'])) {
					return $this->redirect(['password-changed']);
				}
			}

			return $this->render('change-password', [
					'changePasswordModel' => $changePasswordModel
			]);
		}

		public function actionPasswordChanged() {
			return $this->render('password-changed');
		}

	}
	