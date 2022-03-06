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
	use common\models\persons\Persons;
	use common\models\persons\PersonsEmails;
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

		/**
		 * Displays homepage.
		 *
		 * @return mixed
		 */
		public function actionWelcome() {
			$this->layout = 'main2';
			return $this->render('welcome');
		}
		
		public function actionIndex() {
			return $this->redirect(\yii\helpers\Url::to('en'));
		}

		/**
		 * Logs in a user.
		 *
		 * @return mixed
		 */
		public function actionLogin() {
			if (!Yii::$app->user->isGuest) {
//				return $this->goHome();
				return $this->redirect(Yii::$app->params['welcomeUrl']);
			}

			$model = new LoginForm();
			if ($model->load(Yii::$app->request->post()) && $model->login()) {
//				return $this->goBack();
				return $this->redirect(Yii::$app->params['welcomeUrl']);
			}
			else {
				$this->layout = 'login-register-layout';
				$model->password = '';

				return $this->render('login', [
						'model' => $model,
				]);
			}
		}

		/**
		 * Logs out the current user.
		 *
		 * @return mixed
		 */
		public function actionLogout() {
			Yii::$app->user->logout();

			return $this->goHome();
		}

		/**
		 * Displays contact page.
		 *
		 * @return mixed
		 */
//		public function actionContact() {
//			$model = new ContactForm();
//			if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//				if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
//					Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
//				}
//				else {
//					Yii::$app->session->setFlash('error', 'There was an error sending your message.');
//				}
//
//				return $this->refresh();
//			}
//			else {
//				$this->layout = 'login-register-layout';
//				return $this->render('contact', [
//						'model' => $model,
//				]);
//			}
//		}

		/**
		 * Displays about page.
		 *
		 * @return mixed
		 */
//		public function actionAbout() {
//			$this->layout = 'login-register-layout';
//			return $this->render('registered');
//		}

		/**
		 * Signs user up.
		 *
		 * @return mixed
		 */
		public function actionRegister() {
			$personsModel = new Persons(['scenario' => Persons::SCENARIO_REGISTER]);
			$personsEmailsModel = new PersonsEmails;

			$this->layout = 'login-register-layout';

			if ($personsModel->load(Yii::$app->request->post()) && $personsModel->validate() &&
				$personsEmailsModel->load(Yii::$app->request->post()) && $personsEmailsModel->validate()) {

				$personAttributes['nameEN'] = $personsModel->attributes['nameEN'];
				$personAttributes['password'] = $personsModel->attributes['password'];

				// inserting person
				if ($personsModel->addPerson($personAttributes)) {

					// inserting email
					if ($personsEmailsModel->addEmail($personsModel->id, $personsEmailsModel->attributes['email'])) {

						// $contentArray = ['html' => 'new-registration',];
						// $to = $personsEmailsModel->attributes['email'];
						// $sender['email'] = 'no-reply@diva.sa';
						// $sender['name'] = 'Diva Fitness';
						// $subject = 'Welcome to Diva Fitness';

						// SendEmail::sendEmail($contentArray, $to, $sender, $subject);

						// return $this->render('registered');
					}
					else {
						$personsModel->deletePerson($personsModel->id);
					}
				}
			}

			return $this->render('register', [
					'personsModel' => $personsModel,
					'personsEmailsModel' => $personsEmailsModel,
			]);
		}

		public function actionSignup() {
			$model = new SignupForm();
			if ($model->load(Yii::$app->request->post())) {
				if ($user = $model->signup()) {
					if (Yii::$app->getUser()->login($user)) {
						return $this->goHome();
					}
				}
			}

			$this->layout = 'login-register-layout';
			return $this->render('signup', [
					'model' => $model,
			]);
		}

		/**
		 * Requests password reset.
		 *
		 * @return mixed
		 */
		public function actionRequestPasswordReset() {
			$model = new PasswordResetRequestForm();
			if ($model->load(Yii::$app->request->post()) && $model->validate()) {

				if ($model->sendEmail() == 'email sent') {
					Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

					return $this->goHome();
				}
				elseif ($model->sendEmail() == 'email not found') {
					Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
				}
				elseif ($model->sendEmail() == 'account suspended') {
					Yii::$app->session->setFlash('error', 'Sorry, your account is suspended, kindly contact your system administrator to activate it.');
				}
			}

			$this->layout = 'login-register-layout';
			return $this->render('requestPasswordResetToken', [
					'model' => $model,
			]);
		}

		/**
		 * Resets password.
		 *
		 * @param string $token
		 * @return mixed
		 * @throws BadRequestHttpException
		 */
		public function actionResetPassword($token) {
			try {
				$model = new ResetPasswordForm($token);
			}
			catch (InvalidParamException $e) {
				throw new BadRequestHttpException($e->getMessage());
			}

			if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
				Yii::$app->session->setFlash('success', 'New password saved.');

				return $this->goHome();
			}

			return $this->render('resetPassword', [
					'model' => $model,
			]);
		}
		
	}
	