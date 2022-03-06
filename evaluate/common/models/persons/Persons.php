<?php

namespace common\models\persons;

use Yii;
use common\models\Countries;

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
 * @property string $recordDateTime
 * @property int $addedByPersonID
 */
class Persons extends \yii\db\ActiveRecord
{

	const SCENARIO_REGISTER = 'register';

	public static function tableName()
	{
		return 'persons';
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('mainDb');
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['nameEN', 'password'], 'required', 'on' => self::SCENARIO_REGISTER],
			//[['nameAR', 'nameEN', 'workID', 'password', 'recordDateTime'], 'required'],
			[['gender', 'maritalStatus'], 'string'],
			[['birthday', 'recordDateTime'], 'safe'],
			[['nationalityID', 'jobTitleID', 'companyID', 'accountStatus', 'addedByPersonID'], 'integer'],
			[['nameAR', 'nameEN'], 'string', 'min' => 3, 'max' => 100],
			[['workID'], 'string', 'max' => 50],
			[['password', 'accessToken'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'nameAR' => 'First name',
			'nameEN' => 'Last name',
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
			'recordDateTime' => 'Record Date Time',
			'addedByPersonID' => 'Added By Person ID',
		];
	}

	public function getNationality()
	{
		return $this->hasOne(Countries::className(), ['id', 'nationalityID']);
	}

	public function addPerson($attributesArray)
	{
		if (sizeof($attributesArray) > 1) {
			foreach ($attributesArray as $key => $value) {
				$this->$key = $value;
			}
			$this->password = password_hash($this->password, PASSWORD_DEFAULT);
			$this->recordDateTime = date('Y-m-d H:i:s');
			if (isset(Yii::$app->user->identity)) {
				$this->addedByPersonID = Yii::$app->user->identity->id;
			}
			if ($this->save()) {
				return $this->id;
			}
		}
	}

	public function deletePerson($id)
	{
		$model = SELF::findone($id);
		$model->delete();
		return $model;
	}

	public function getPersonName($id)
	{
		$personName = self::find()
			->select('nameAR, nameEN')
			->where([
				'id' => $id,
			])
			->asArray()
			->one();
		if (!empty($personName)) {
			return json_encode($personName, JSON_UNESCAPED_UNICODE);
		}
	}

	public function addCustomer($nameAR)
	{
		$this->nameAR = $nameAR;
		if ($this->save(false)) {
			return $this->id;
		}
	}

	public function updatePersonName($personID, $newName)
	{
		$person = self::findOne($personID);
		if (!empty($person)) {
			$person->nameAR = $newName;
			if ($person->save(false)) {
				return true;
			}
		}
	}
}
