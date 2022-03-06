<?php

	namespace common\models;

	use Yii;

	/**
	 * This is the model class for table "mobile_phones".
	 *
	 * @property int $id
	 * @property int $countryID
	 * @property int $mobilePhone
	 * @property int $addedByPersonID
	 */
	class MobilePhones extends \yii\db\ActiveRecord {

		/**
		 * {@inheritdoc}
		 */
		public static function tableName() {
			return 'mobile_phones';
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
				[['countryID', 'mobilePhone', 'addedByPersonID'], 'required'],
				[['countryID', 'mobilePhone', 'addedByPersonID'], 'integer'],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID',
				'countryID' => 'Country ID',
				'mobilePhone' => 'Mobile Phone',
				'addedByPersonID' => 'Added By Person ID',
			];
		}

		public function mobilePhoneExist($countryID, $mobilePhone) {
			$exist = self::find()
				->select('id')
				->where([
					'countryID' => $countryID,
					'mobilePhone' => $mobilePhone,
				])
				->asArray()
				->one();
			if (!empty($exist)) {
				return $exist['id'];
			}
		}

		public function saveMobilePhone($countryID, $mobilePhone) {
			$this->countryID = $countryID;
			$this->mobilePhone = $mobilePhone;
			$this->addedByPersonID = Yii::$app->user->identity->id;
			if ($this->save()) {
				return $this->id;
			}
		}

		public function deleteMobilePhone($mobilePhoneID) {
			$mobilePhone = self::find()
				->select('id')
				->where([
					'id' => $mobilePhoneID
				])
				->one();
			if (!empty($mobilePhone)) {
				$mobilePhone->delete();
				return true;
			}
		}

	}
	