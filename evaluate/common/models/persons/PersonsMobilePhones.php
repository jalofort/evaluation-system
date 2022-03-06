<?php

	namespace common\models\persons;

	use Yii;
	use yii\db\ActiveRecord;

	class PersonsMobilePhones extends ActiveRecord {

		public function rules() {
			return [
				[['personID', 'mobilePhoneID', 'description', 'addedBy'], 'required'],
				[['personID', 'mobilePhoneID', 'addedBy'], 'integer'],
				[['description'], 'string', 'max' => 50],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID',
				'personID' => 'Person ID',
				'mobilePhoneID' => 'Mobile Phone ID',
				'description' => 'Description',
				'addedBy' => 'Added By',
			];
		}

		public static function getDb() {
			return Yii::$app->get('mainDb');
		}

		public static function tableName() {
			return 'persons_mobile_phones';
		}

		public function getPersonMobilePhones($personID) {
			$countriesTable = Yii::$app->params['mainDb'] . '.countries';
			$mobilePhonesTable = Yii::$app->params['mainDb'] . '.mobile_phones';
			$personsMobilePhonesTable = Yii::$app->params['mainDb'] . '.persons_mobile_phones';

			$mobilePhones = self::find()
				->select([
					$mobilePhonesTable . '.id',
					$countriesTable . '.countryCode',
					$mobilePhonesTable . '.mobilePhone'
				])
				->join('join', $mobilePhonesTable, $mobilePhonesTable . '.id = ' . $personsMobilePhonesTable . '.mobilePhoneID')
				->join('join', $countriesTable, $mobilePhonesTable . '.countryID = ' . $countriesTable . '.id')
				->where([
					$personsMobilePhonesTable . '.personID' => $personID,
				])
				->asArray()
				->all();
			if (!empty($mobilePhones)) {
				return \yii\helpers\Json::encode($mobilePhones);
			}
		}

		public function savePersonMobilePhone($mobilePhoneID, $personID, $description) {
			$this->personID = $personID;
			$this->mobilePhoneID = $mobilePhoneID;
			$this->description = $description;
			$this->addedBy = Yii::$app->user->identity->id;
			if ($this->save(false)) {
				return true;
			}
		}

		public function mobilePhoneIsUsedForAnotherPerson($mobilePhoneID) {
			$mobilePhoneIsUsed = self::find()
				->select('count(id)')
				->where([
					'mobilePhoneID' => $mobilePhoneID
				])
				->asArray()
				->one();
			if (!empty($mobilePhoneIsUsed)) {
				return $mobilePhoneIsUsed['count(id)'];
			}
		}

		public function deletePersonMobilePhone($mobilePhoneID, $personID) {
			$personMobilePhone = self::find()
				->where([
					'personID' => $personID,
					'mobilePhoneID' => $mobilePhoneID
				])
				->one();
			if ($personMobilePhone->delete()) {
				return true;
			}
		}

	}
	