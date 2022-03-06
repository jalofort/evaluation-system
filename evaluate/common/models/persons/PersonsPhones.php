<?php

	namespace common\models\persons;

	use Yii;
	use yii\db\ActiveRecord;

	class PersonsPhones extends ActiveRecord {

		public static function getDb() {
			return Yii::$app->get('mainDb');
		}

		public static function tableName() {
			return 'persons_phones';
		}

		public function getPersonPhones($personID) {
			$countriesTable = Yii::$app->params['mainDb'] . '.countries';
			$phonesTable = Yii::$app->params['mainDb'] . '.phones';
			$personsPhonesTable = Yii::$app->params['mainDb'] . '.persons_phones';

			$phones = self::find()
				->select([
					$phonesTable . '.id',
					$countriesTable . '.countryCode',
					$phonesTable . '.phone'
				])
				->join('join', $phonesTable, $phonesTable . '.id = ' . $personsPhonesTable . '.phoneID')
				->join('join', $countriesTable, $phonesTable . '.countryID = ' . $countriesTable . '.id')
				->where([
					$personsPhonesTable . '.personID' => $personID,
				])
				->asArray()
				->all();
			if (!empty($phones)) {
				return \yii\helpers\Json::encode($phones);
			}
		}
		
		public function savePersonPhone($phoneID, $personID, $description) {
			$this->personID = $personID;
			$this->phoneID = $phoneID;
			$this->description = $description;
			$this->addedBy = Yii::$app->user->identity->id;
			if ($this->save(false)) {
				return true;
			}
		}
		
		public function phoneIsUsedForAnotherPerson($phoneID) {
			$phoneIsUsed = self::find()
				->select('count(id)')
				->where([
					'phoneID' => $phoneID
				])
				->asArray()
				->one();
			if (!empty($phoneIsUsed)) {
				return $phoneIsUsed['count(id)'];
			}
		}
		
		public function deletePersonPhone($phoneID, $personID) {
			$personPhone = self::find()
				->where([
					'personID' => $personID,
					'phoneID' => $phoneID
				])
				->one();
			if ($personPhone->delete()) {
				return true;
			}
		}
	}
	