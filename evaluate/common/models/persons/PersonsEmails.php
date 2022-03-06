<?php

	namespace common\models\persons;

	use Yii;
	use yii\db\ActiveRecord;

	class PersonsEmails extends ActiveRecord {

		public static function getDb() {
			return Yii::$app->get('mainDb');
		}

		public static function tableName() {
			return 'persons_emails';
		}

		public function getPersonEmails($personID) {
			$emails = self::find()
				->select('email')
				->where(['personID' => $personID])
				->asArray()
				->all();
			if (!empty($emails)) {
				return \yii\helpers\Json::encode($emails);
			}
		}

	}
	