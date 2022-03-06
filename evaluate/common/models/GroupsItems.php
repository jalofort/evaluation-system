<?php

	namespace common\models;

	use Yii;

	/**
	 * This is the model class for table "groups_items".
	 *
	 * @property int $id
	 * @property int $groupID
	 * @property int $itemID
	 */
	class GroupsItems extends \yii\db\ActiveRecord {

		public $itemName;

		public static function tableName() {
			return 'groups_items';
		}

		/**
		 * @return \yii\db\Connection the database connection used by this AR class.
		 */
		public static function getDb() {
			return Yii::$app->get('evaluateDb');
		}

		/**
		 * {@inheritdoc}
		 */
		public function rules() {
			return [
				[['groupID', 'itemID'], 'required'],
				[['groupID', 'itemID'], 'integer'],
				['groupID', 'exist', 'targetClass' => Groups::class, 'targetAttribute' => ['groupID' => 'id']],
				['itemID', 'exist', 'targetClass' => EvaluationItems::class, 'targetAttribute' => ['itemID' => 'id']],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID',
				'groupID' => 'Group',
				'itemID' => 'Item',
			];
		}

		public function getGroupItems($groupID) {
			$groupItemsQuery = self::find()
				->select([
					'groups_items.id',
					'evaluation_items.id as itemID',
					'evaluation_items.item as itemName',
				])
				->join('join', 'evaluation_items', 'evaluation_items.id = groups_items.itemID')
				->where(['groups_items.groupID' => $groupID])
				->orderBy('item ASC');

			$groupItems = new \yii\data\ActiveDataProvider();
			$groupItems->query = $groupItemsQuery;
			$groupItems->pagination = [
				'pageSize' => 20,
			];
			return $groupItems;
		}

		public function getGroupItems2($groupID) {
			$groupItems = self::find()
				->select([
					'evaluation_items.id as itemID',
					'evaluation_items.item as itemName',
				])
				->join('join', 'evaluation_items', 'evaluation_items.id = groups_items.itemID')
				->where(['groups_items.groupID' => $groupID])
				->orderBy('item ASC')
				->asArray()
				->all();

			if (!empty($groupItems)) {
				return json_encode($groupItems, JSON_UNESCAPED_UNICODE);
			}
		}

		public function deleteGroupItem($id) {
			$groupItem = self::findOne($id);
			if ($groupItem->delete()) {
				return true;
			}
		}

		public function getGroup() {
			return $this->hasOne(Groups::className(), ['id' => 'groupID']);
		}

		public function getItem() {
			return $this->hasOne(EvaluationItems::className(), ['id' => 'itemID']);
		}

	}
	