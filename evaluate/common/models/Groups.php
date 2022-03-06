<?php

	namespace common\models;

	use Yii;

	/**
	 * This is the model class for table "groups".
	 *
	 * @property int $id
	 * @property string $name
	 * @property int $deleted
	 *
	 * @property Evaluation[] $evaluations
	 */
	class Groups extends \yii\db\ActiveRecord {

		/**
		 * {@inheritdoc}
		 */
		public static function tableName() {
			return 'groups';
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
				[['name'], 'required'],
				[['deleted'], 'integer'],
				[['name'], 'string', 'max' => 255],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID',
				'name' => 'Name',
				'deleted' => 'Deleted',
			];
		}

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getEvaluations() {
			return $this->hasMany(Evaluation::className(), ['evaluateItemID' => 'id']);
		}

		public function getNonChosenGroups($employeeID) {
			$groups = self::find()
				->select([
					'groups.id',
					'groups.name',
				])
				->leftJoin('employees_groups as joined_employees_group', 'joined_employees_group.groupID = groups.id AND joined_employees_group.employeeID = :employeeID')
				->where([
					'IS', 'joined_employees_group.groupID', null
				])
				->andWhere([
					'groups.deleted' => null
				])
				->addParams([':employeeID' => $employeeID])
				->orderBy('groups.name ASC')
				->asArray()
				->all();
			return $groups;
		}

		public function getGroup($groupID) {
			$group = self::find()
				->where(['id' => $groupID])
				->one();
			return $group;
		}
	}
	