<?php

	namespace common\models;

	use Yii;
	use yii\db\ActiveRecord;

	class Regions extends ActiveRecord {

		public static function getDb() {
			return Yii::$app->get('mainDb');
		}

		public static function tableName() {
			return 'regions';
		}

		public function rules() {
			return [
				[['nameAR', 'nameEN', 'cityID'], 'required'],
				[['cityID', 'deleted'], 'integer'],
				[['nameAR', 'nameEN'], 'string', 'max' => 50],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID',
				'nameAR' => 'Name Ar',
				'nameEN' => 'Name En',
				'cityID' => 'City ID',
				'deleted' => 'Deleted',
			];
		}

		public function getRegionByID($regionID) {
			$region = self::find()
				->select('regions.nameAR, cities.nameAR')
				->join('join', 'cities', 'cities.id = regions.cityID')
				->where(['regions.id' => $regionID])
				->asArray()
				->one();

			return $region;
		}

		public function getCityRegions($cityID) {
			$regions = self::find()
				->select('id, nameAR as name')
				->where([
					'cityID' => $cityID,
				])
				->asArray()
				->orderBy('name ASC')
				->all();

			return $regions;
		}

	}
	