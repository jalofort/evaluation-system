<?php

	namespace common\models;

	use Yii;

	/**
	 * This is the model class for table "auth_assignment".
	 *
	 * @property string $item_name
	 * @property string $user_id
	 * @property int $created_at
	 *
	 * @property AuthItem $itemName
	 */
	class AuthAssignment extends \yii\db\ActiveRecord {

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
				[['item_name'], 'string', 'max' => 64],
				[['user_id'], 'integer',],
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

		public function getUserAssignedPrivileges($userID) {
			$privileges = self::find()
				->select('auth_assignment.item_name, auth_item.description')
				->join('join', 'auth_item', 'auth_assignment.item_name = auth_item.name')
				->where([
					'user_id' => $userID,
				])
				->asArray()
				->all();
			if (!empty($privileges)) {
				return json_encode($privileges);
			}
		}

		public function assignUserPrivilege($privilegeName, $userID) {
			$this->item_name = $privilegeName;
			$this->user_id = $userID;
			$this->created_at = date('Y-m-d H:i:s');
			if ($this->save()) {
				return true;
			}
			return false;
		}

		public function removeUserPrivilege($privilegeName, $userID) {
			$privilege = self::find()
				->where([
					'item_name' => $privilegeName,
					'user_id' => $userID
				])
				->one();
			if ($privilege->delete(false)) {
				return true;
			}
			return false;
		}

		public function getUserAssignedPrivileges2($userID) {
			$userPrivileges = self::find()
				->select('item_name')
				->where([
					'user_id' => $userID
				])
				->asArray()
				->all();
			$privilegsArray = [];
			if (!empty($userPrivileges)) {
				foreach ($userPrivileges as $privilege) {
					array_push($privilegsArray, $privilege['item_name']);
				}
			}
			return $privilegsArray;
		}

		public function addUserPrivilegesLikeAnotherUser($selectedUserID, $currentUserID) {

			// get selected user assigned privileges
			$selectedUserPrivileges = self::find()
				->select('item_name')
				->where([
					'user_id' => $selectedUserID
				])
				->asArray()
				->all();

			if (!empty($selectedUserPrivileges)) {
				// get current user assigned privileges
				$currentUserPrivileges = $this->getUserAssignedPrivileges2($currentUserID);

				$inputsArray = [];
				foreach ($selectedUserPrivileges as $privilege) {
					if (!in_array($privilege['item_name'], $currentUserPrivileges)) {
						array_push($inputsArray, [$privilege['item_name'], $currentUserID, date('Y-m-d h:i:s')]);
					}
				}
				$addPrivileges = Yii::$app->mainDb->createCommand()->batchInsert('auth_assignment', ['item_name', 'user_id', 'created_at'], $inputsArray)->execute();
				return $addPrivileges;
			}
		}

		public function unassignUserAllPrivileges($userID) {
			if (self::deleteAll(['user_id' => $userID])) {
				return true;
			}
		}

	}
	