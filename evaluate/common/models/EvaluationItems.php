<?php

	namespace common\models;

	use Yii;

	/**
	 * This is the model class for table "evaluation_items".
	 *
	 * @property int $id
	 * @property string $item
	 * @property int $deleted
	 *
	 * @property Evaluation[] $evaluations
	 */
	class EvaluationItems extends \yii\db\ActiveRecord {

		/**
		 * {@inheritdoc}
		 */
		public static function tableName() {
			return 'evaluation_items';
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
				[['item'], 'required'],
				[['deleted'], 'integer'],
				[['item'], 'string', 'max' => 255],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID',
				'item' => 'Item',
				'deleted' => 'Deleted',
			];
		}

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getEvaluations() {
			return $this->hasMany(Evaluation::className(), ['evaluateItemID' => 'id']);
		}

		public function deleteItem($id) {
			$item = self::findOne($id);

			if (!empty($item)) {
				$item->deleted = 1;
				if ($item->save(false)) {
					return true;
				}
			}
		}

		public function getEvaluationItems() {
			$items = self::find()
				->select([
					'evaluation_items.id',
					'evaluation_items.item',
				])
				->where([
					'evaluation_items.deleted' => null,
				])
				->asArray()
				->all();
			return $items;
		}
		
		public function getItems($groupID) {
			$items = self::find()
				->select([
					'evaluation_items.id',
					'evaluation_items.item',
				])
				->leftJoin('groups_items as new_group_items', 'new_group_items.itemID = evaluation_items.id AND new_group_items.groupID = :groupID')
				->where([
					'evaluation_items.deleted' => null,
				])
				->andWhere([
					'IS', 'new_group_items.itemID', null
				])
				->addParams([':groupID' => $groupID])
				->asArray()
				->all();
			return $items;
		}

	}
	