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
			$currentURL = 'http://' . $_SERVER['HTTP_HOST'] . Yii::$app->homeUrl;
			$accountsURL = str_replace('account', 'accounts', $currentURL);
			return $this->redirect($accountsURL);
		}

		public function actionLogout() {
			/*
			  Yii::$app->user->logout();

			  return $this->goHome();
			 */
			$currentURL = 'http://' . $_SERVER['HTTP_HOST'] . Yii::$app->homeUrl;
			$accountsURL = str_replace('account', 'accounts', $currentURL);
			return $this->redirect($accountsURL . '/site/logout');
		}

	}
	