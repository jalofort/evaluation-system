<?php

	namespace common\models;

	use Yii;

	/**
	 * This is the model class for table "auth_assignment".
	 *
	 * @property string $item_name
	 * @property string $user_id
	 * @property string $created_at
	 *
	 * @property AuthItem $itemName
	 */
	class AuthAssignment extends \yii\db\ActiveRecord {

		/**
		 * {@inheritdoc}
		 */
		public static function tableName() {
			return 'auth_assignment';
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
				[['item_name', 'user_id'], 'required'],
				[['created_at'], 'safe'],
				[['item_name', 'user_id'], 'string', 'max' => 64],
				[['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
				[['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'item_name' => 'Item Name',
				'user_id' => 'User ID',
				'created_at' => 'Created At',
			];
		}

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getItemName() {
			return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
		}

	}
	