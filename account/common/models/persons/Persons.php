<?php

	namespace common\models\persons;

	use Yii;
	use yii\db\ActiveRecord;

	class Persons extends ActiveRecord {

		public static function getDb() {
			return Yii::$app->get('mainDb');
		}

		public static function tableName() {
			return 'persons';
		}

		public function getCurrentPassword() {
			$currentPassword = self::find()
				->select('password')
				->where(['id' => Yii::$app->user->identity->id])
				->asArray()
				->one();
			return $currentPassword['password'];
		}

		public function saveNewPassword($password) {

			// hash the password
			$hashedPassword = Yii::$app->getSecurity()->generatePasswordHash($password);

			$person = self::findOne(Yii::$app->user->identity->id);
			$person->password = $hashedPassword;
			$person->save();
			return true;
		}

	}
	