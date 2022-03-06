<?php

	namespace c\models;

	use Yii;
	use yii\base\Model;
	use common\models\User;

	/**
	 * Password reset request form
	 */
	class PasswordResetRequestForm extends Model {

		public $email;

		/**
		 * {@inheritdoc}
		 */
		public function rules() {
			return [
				['email', 'trim'],
				['email', 'required'],
				['email', 'email'],
				['email', 'exist',
					'targetClass' => '\common\models\User',
					//'filter' => ['status' => User::STATUS_ACTIVE],
					'message' => 'There is no user with this email address.'
				],
			];
		}

		/**
		 * Sends an email with a link, for resetting the password.
		 *
		 * @return bool whether the email was send
		 */
		public function sendEmail() {
			/* @var $user User */
			$user = User::findEmailToResetPassword($this->email);

			if (!$user) {
				return 'email not found';
			}
			elseif ($user['accountStatus'] == NULL) {
				return 'account suspended';
			}


			//if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
			if (!User::isPasswordResetTokenValid($user->password)) {
				User::generatePasswordResetToken();
				if (!$user->save()) {
					return false;
				}
			}

			$sendEmail = Yii::$app
				->mailer
				->compose(
					['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user]
					//['html' => 'new-registration']
				)
				->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->params['companyName']])
				->setTo($this->email)
				->setSubject('Password reset')
				->send();
			if ($sendEmail) {
				return 'email sent';
			}
		}

	}
	