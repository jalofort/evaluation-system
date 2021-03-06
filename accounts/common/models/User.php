<?php

	namespace common\models;

	use Yii;
	use yii\base\NotSupportedException;
	use yii\behaviors\TimestampBehavior;
	use yii\db\ActiveRecord;
	use yii\web\IdentityInterface;

	/**
	 * User model
	 *
	 * @property integer $id
	 * @property string $username
	 * @property string $password_hash
	 * @property string $password_reset_token
	 * @property string $email
	 * @property string $auth_key
	 * @property integer $status
	 * @property integer $created_at
	 * @property integer $updated_at
	 * @property string $password write-only password
	 */
	class User extends ActiveRecord implements IdentityInterface {

		const STATUS_DELETED = 0;
		const STATUS_ACTIVE = 10;

		public $nameAR;
		public $nameEN;
		public $password;
		public $auth_key;
		public $accountStatus;

		/**
		 * {@inheritdoc}
		 */
		public static function getDb() {
			return Yii::$app->mainDb;
		}

		public static function tableName() {
			return 'persons_emails';
		}

		/**
		 * {@inheritdoc}
		 */
		public function behaviors() {
			return [
				TimestampBehavior::class,
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function rules() {
			return [
				['status', 'default', 'value' => self::STATUS_ACTIVE],
				['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public static function findIdentity($id) {
			//return static::findOne(['id' => $id]);

			$identity = User::find()
				->select('persons.id, persons.nameAR, persons.nameEN')
				->from('persons')
				->where([
					'persons.id' => $id,
					'persons.accountStatus' => 1,
				])
				->one();

			return $identity;
		}

		/**
		 * {@inheritdoc}
		 */
		public static function findIdentityByAccessToken($token, $type = null) {
			throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
		}

		/**
		 * Finds user by username
		 *
		 * @param string $username
		 * @return static|null
		 */
		public static function findByUsername($username) {
			return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
		}

		public static function findByEmail($email) {
			//return static::findOne(['email' => $email, 'accountStatus' => 1]);
			$person = User::find()
				->select('persons_emails.personID AS id, persons.password AS password')
				->join('join', 'persons', 'persons_emails.personID = persons.id')
				->where([
					'persons_emails.email' => $email,
					'persons_emails.forAuthentication' => 1,
					'persons.accountStatus' => 1,
				])
				->one();
			return $person;
		}

		/**
		 * Finds user by password reset token
		 *
		 * @param string $token password reset token
		 * @return static|null
		 */
		public static function findByPasswordResetToken($token) {
			if (!static::isPasswordResetTokenValid($token)) {
				return null;
			}

			return static::findOne([
					'password_reset_token' => $token,
					'status' => self::STATUS_ACTIVE,
			]);
		}

		/**
		 * Finds out if password reset token is valid
		 *
		 * @param string $token password reset token
		 * @return bool
		 */
		public static function isPasswordResetTokenValid($token) {
			if (empty($token)) {
				return false;
			}

			$timestamp = (int) substr($token, strrpos($token, '_') + 1);
			$expire = Yii::$app->params['user.passwordResetTokenExpire'];
			return $timestamp + $expire >= time();
		}

		/**
		 * {@inheritdoc}
		 */
		public function getId() {
			return $this->getPrimaryKey();
		}

		/**
		 * {@inheritdoc}
		 */
		public function getAuthKey() {
			return $this->auth_key;
		}

		/**
		 * {@inheritdoc}
		 */
		public function validateAuthKey($authKey) {
			return $this->getAuthKey() === $authKey;
		}

		/**
		 * Validates password
		 *
		 * @param string $password password to validate
		 * @return bool if password provided is valid for current user
		 */
		public function validatePassword($password) {
			return Yii::$app->security->validatePassword($password, $this->password);
		}

		/**
		 * Generates password hash from password and sets it to the model
		 *
		 * @param string $password
		 */
		public function setPassword($password) {
			$this->password_hash = Yii::$app->security->generatePasswordHash($password);
		}

		/**
		 * Generates "remember me" authentication key
		 */
		public function generateAuthKey() {
			$this->auth_key = Yii::$app->security->generateRandomString();
		}

		/**
		 * Generates new password reset token
		 */
		public static function generatePasswordResetToken() {
			//$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
			return Yii::$app->security->generateRandomString() . '_' . time();
		}

		/**
		 * Removes password reset token
		 */
		public function removePasswordResetToken() {
			$this->password_reset_token = null;
		}

		// find email to reset password
		public static function findEmailToResetPassword($email) {
			$emailModel = new \c\models\persons\PersonsEmails;
			return $emailModel->findEmailToResetPassword($email);
		}

	}
	