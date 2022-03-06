<?php

	namespace c\modules\en\models\user;

	use Yii;
	use yii\base\Model;

	class ChangePassword extends Model {

		public $currentPassword;
		public $newPassword;
		public $repeatedNewPassword;

		public function rules() {
			return [
				[['currentPassword', 'newPassword', 'repeatedNewPassword'], 'required'],
				[['currentPassword'], 'checkCurrentPassword'],
				[['repeatedNewPassword'], 'checkRepeatedPassword'],
			];
		}

		public function attributeLabels() {
			return [
				'currentPassword' => 'Current password',
				'newPassword' => 'New password',
				'repeatedNewPassword' => 'Repeat password',
			];
		}

		public function checkCurrentPassword($attribute) {

			$currentUserPasswordModel = new \common\models\persons\Persons;
			$currentUserPassword = $currentUserPasswordModel->getCurrentPassword();

			if (!Yii::$app->getSecurity()->validatePassword($this->$attribute, $currentUserPassword)) {
				$this->addError($attribute, 'Incorrect password');
			}
		}

		public function checkRepeatedPassword($attribute) {
			if ($this->$attribute != $this->newPassword) {
				$this->addError($attribute, 'Not matched !');
			}
		}

	}
	