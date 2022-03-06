<?php

	namespace common\models;

	use Yii;

	/**
	 * This is the model class for table "company_locations".
	 *
	 * @property int $id
	 * @property string $name
	 * @property int $addressID
	 * @property int $deleted
	 */
	class CompanyLocations extends \yii\db\ActiveRecord {

		/**
		 * {@inheritdoc}
		 */
		public static function tableName() {
			return 'company_locations';
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
				[['name', 'addressID'], 'required'],
				[['addressID', 'deleted'], 'integer'],
				[['name'], 'string', 'max' => 50],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID',
				'name' => 'Name',
				'addressID' => 'Address ID',
				'deleted' => 'Deleted',
			];
		}

		public function getCompanyLocations() {
			$locations = self::find()
				->where([
					'deleted' => null
				])
				->orderBy('name ASC')
				->asArray()
				->all();
			if (!empty($locations)) {
				return json_encode($locations, JSON_UNESCAPED_UNICODE);
			}
		}

	}
	