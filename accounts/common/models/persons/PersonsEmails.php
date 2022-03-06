<?php

	namespace common\models\persons;

	use Yii;

	class PersonsEmails extends \yii\db\ActiveRecord {

		const SCENARIO_REGISTER = 'register';
		const SCENARIO_addingMail = 'register';

		public $nameEN;
		public $accountStatus;
		public $password;

		public static function tableName() {
			return 'persons_emails';
		}

		public static function getDb() {
			return Yii::$app->get('mainDb');
		}

		public function rules() {
			return [
				[['email'], 'required', 'on' => self::SCENARIO_REGISTER],
				[['personID', 'forAuthentication'], 'integer'],
				[['email'], 'string', 'min' => 5, 'max' => 100],
				//[['verificationCode'], 'string', 'max' => 10],
				[['email'], 'uniqueEmail'],
			];
		}

		public function attributeLabels() {
			return [
				'id' => 'ID',
				'personID' => 'Person ID',
				'email' => 'Email',
				'forAuthentication' => 'For Authentication',
				'verificationCode' => 'Verification Code',
				'verified' => 'Verified',
			];
		}

		public function checkEmail($enteredEmail) {
			$email = SELF::find()
				->where(['email' => $enteredEmail,])
				->one();
			if (empty($email)) {
				$emailStatus['email'] = 'not exist';
			}
			else {
				$emailStatus['email'] = 'exist';

				if ($email['verified'] == 1) {
					$emailStatus['verified'] = 'yes';
				}
				else {
					$emailStatus['verified'] = 'no';
				}
			}
			return $emailStatus;
		}

		public function generateVerificationCode($enteredEmail) {
			$generatingCodeModel = new \v1\models\GeneratingCodes;
			do {
				$code = $generatingCodeModel->randomCode();
				$checkCode = SELF::find()->where(['verificationCode' => $code,])->one();
			}
			while (!empty($checkCode));
			if (empty($checkCode)) {
				$this->email = $enteredEmail;
				$this->verificationCode = $code;
				$this->save();
			}
			return $code;
		}

		public function uniqueEmail($attribute, $params) {
			// check if the email is already token.
			$email = self::findOne(['email' => $this->email]);
			if (!empty($email)) {
				return $this->addError($attribute, 'This email is already registered.');
			}
		}

		public function addEmail($personID, $email) {
			$this->personID = $personID;
			$this->email = $email;
			$this->forAuthentication = 1;
			if ($this->save()) {
				return true;
			}
		}

		public function findEmailToResetPassword($email) {
			$person = SELF::find()
				->select(' persons.nameEN, persons.accountStatus, persons.password')
				->join('join', 'persons', 'persons_emails.personID = persons.id')
				->where([
					'persons_emails.email' => $email,
					'persons_emails.forAuthentication' => 1,
				])
				->one();
			return $person;
		}

		public function getUserPrimaryEmail($userID) {
			$userEmail = self::find()
				->select('email')
				->where([
					'personID' => $userID,
					'forAuthentication' => 1
				])
				->asArray()
				->one();
			return $userEmail;
		}

	}
	