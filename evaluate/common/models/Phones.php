<?php

	namespace common\models;

	use Yii;

	/**
	 * This is the model class for table "phones".
	 *
	 * @property int $id
	 * @property int $countryID
	 * @property int $phone
	 */
	class Phones extends \yii\db\ActiveRecord {

		/**
		 * {@inheritdoc}
		 */
		public static function tableName() {
			return 'phones';
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
				[['countryID', 'phone'], 'required'],
				[['countryID', 'phone'], 'integer'],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID',
				'countryID' => 'Country ID',
				'phone' => 'Phone',
			];
		}

		public function phoneExist($countryID, $phone) {
			$exist = self::find()
				->select('id')
				->where([
					'countryID' => $countryID,
					'phone' => $phone,
				])
				->asArray()
				->one();
			if (!empty($exist)) {
				return $exist['id'];
			}
		}
		
		public function savePhone($countryID, $phone) {
			$this->countryID = $countryID;
			$this->phone = $phone;
			$this->addedByPersonID = Yii::$app->user->identity->id;
			if ($this->save()) {
				return $this->id;
			}
		}

		public function deletePhone($phoneID) {
			$phone = self::find()
				->select('id')
				->where([
					'id' => $phoneID
				])
				->one();
			if (!empty($phone)) {
				$phone->delete();
				return true;
			}
		}

	}
	