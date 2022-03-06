<?php

	namespace c\controllers;

	use Yii;
	use yii\base\InvalidParamException;
	use yii\web\BadRequestHttpException;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\filters\AccessControl;
	use common\models\LoginForm;
	use c\models\PasswordResetRequestForm;
	use c\models\ResetPasswordForm;
	use c\models\Register;
	use c\models\SignupForm;
	use c\models\ContactForm;
	use c\models\persons\Persons;
	use c\models\persons\PersonsEmails;
	use common\models\SendEmail;

	/**
	 * Site controller
	 */
	class SiteController extends Controller {

		public function actions() {
			return [
				'error' => [
					'class' => 'yii\web\ErrorAction',
				],
				'captcha' => [
					'class' => 'yii\captcha\CaptchaAction',
					'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
				],
			];
		}

		public function actionIndex() {
			//return $this->render('index');
			return $this->redirect(\yii\helpers\Url::to('en'));
		}

		public function actionLogin() {
			/*
			  if (!Yii::$app->user->isGuest) {
			  return $this->goHome();
			  }

			  $model = new LoginForm();
			  if ($model->load(Yii::$app->request->post()) && $model->login()) {
			  return $this->goBack();
			  }
			  else {
			  $this->layout = 'login-register-layout';
			  $model->password = '';

			  return $this->render('login', [
			  'model' => $model,
			  ]);
			  }
			 */
			return $this->redirect(Yii::$app->params['accountsUrl']);
		}

		public function actionLogout() {
			/*
			  Yii::$app->user->logout();

			  return $this->goHome();
			 */

			return $this->redirect(Yii::$app->params['accountsUrl'].'c/site/logout');
		}
		
		public function actionTest() {
			// get employee email to send notification
			$employeeEmailsModel = new \common\models\persons\PersonsEmails;
			$employeeEmailJSON = $employeeEmailsModel->getPersonEmails(9);
			$employeeEmails = json_decode($employeeEmailJSON);
			
			echo '<pre>';
			print_r($employeeEmails[0]->email);
			echo '</pre>';
		}

	}
	