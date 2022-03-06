<?php

	namespace common\models\persons;

	use Yii;
	use yii\data\ActiveDataProvider;
	use c\models\persons\PersonsEmails;

	/**
	 * This is the model class for table "persons".
	 *
	 * @property int $id
	 * @property string $nameAR
	 * @property string $nameEN
	 * @property string $gender
	 * @property string $maritalStatus
	 * @property string $birthday
	 * @property int $nationalityID
	 * @property int $jobTitleID
	 * @property int $companyID
	 * @property string $workID
	 * @property string $password
	 * @property string $accessToken
	 * @property int $accountStatus
	 */
	class Persons extends \yii\db\ActiveRecord {

		const SCENARIO_REGISTER = 'register';

		public static function tableName() {
			return 'persons';
		}

		/**
		 * @return \yii\db\Connection the database connection used by this AR class.
		 */
		public static function getDb() {
			return Yii::$app->get('mainDb');
		}

		/**
		 * {@inheritdoc}
		 */
		public function rules() {
			return [
				[['nameEN', 'password'], 'required', 'on' => self::SCENARIO_REGISTER],
				//[['nameAR', 'nameEN', 'workID', 'password'], 'required'],
				[['gender', 'maritalStatus'], 'string'],
				[['birthday'], 'safe'],
				[['nationalityID', 'jobTitleID', 'companyID', 'accountStatus'], 'integer'],
				[['nameAR', 'nameEN'], 'string', 'min' => 3, 'max' => 100],
				[['workID'], 'string', 'max' => 50],
				[['password', 'accessToken'], 'string', 'max' => 255],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID',
				'nameAR' => 'Name in Arabic',
				'nameEN' => 'Name in English',
				'gender' => 'Gender',
				'maritalStatus' => 'Marital Status',
				'birthday' => 'Birthday',
				'nationalityID' => 'Nationality ID',
				'jobTitleID' => 'Job Title ID',
				'companyID' => 'Company ID',
				'workID' => 'Work ID',
				'password' => 'Password',
				'accessToken' => 'Access Token',
				'accountStatus' => 'Account Status',
			];
		}

		public function addPerson($attributesArray) {
			if (sizeof($attributesArray) > 1) {
				foreach ($attributesArray as $key => $value) {
					$this->$key = $value;
				}
				$this->password = password_hash($this->password, PASSWORD_DEFAULT);
				if ($this->save()) {
					return $this->id;
				}
			}
		}

		public function deletePerson($id) {
			$model = SELF::findone($id);
			$model->delete();
			return $model;
		}

		public function getSystemAccounts() {
			$query = self::find()
				->select('id, nameAR, nameEN, accountStatus')
				->where(['not', ['password' => null]]);
			$data = new ActiveDataProvider([
				'query' => $query,
				'pagination' => [
					'pageSize' => 20,
				],
				'sort' => [
					'defaultOrder' => ['id' => SORT_DESC]
				],
			]);
			return $data;
		}

		public function getSystemUsersWhoHavePrivileges($currentUserID) {
			$users = self::find()
				->select('persons.id, persons.nameAR, persons.nameEN')
				->join('join', 'auth_assignment', 'auth_assignment.user_id = persons.id')
				->where([
					'<>', 'persons.id', $currentUserID,
				])
				->andWhere(['not', ['persons.password' => null]])
				->andWhere([
					'persons.accountStatus' => 1
				])
				->asArray()
				->all();

			return $users;
		}

		public function changeAccountStatus($personID) {
			$person = SELF::findone($personID);
			if ($person->accountStatus == 1) {
				$person->accountStatus = NULL;
			}
			else {
				$person->accountStatus = 1;
			}
			if ($person->save()) {
				return true;
			}
		}

		public function getUserAccountInfo($userID) {
			$userInfo = self::find()
				->select('id, nameAR, nameEN, accountStatus')
				->where([
					'id' => $userID,
				])
				->andWhere([
					'is not', 'password', null
				])
				->asArray()
				->one();
			if (!empty($userInfo)) {
				return $userInfo;
			}
		}

		public function updateUserAccountPassword($password, $userID) {
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$userAccount = self::find()
				->select('id')
				->where([
					'id' => $userID
				])
				->one();
			$userAccount->password = $hashedPassword;
			if ($userAccount->save()) {
				return true;
			}
		}

	}
	