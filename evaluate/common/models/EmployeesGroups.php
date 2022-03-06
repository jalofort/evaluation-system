<?php

	namespace common\models;

	use Yii;

	/**
	 * This is the model class for table "employees_groups".
	 *
	 * @property int $id
	 * @property int $employeeID
	 * @property int $groupID
	 */
	class EmployeesGroups extends \yii\db\ActiveRecord {

		public $name;

		public static function tableName() {
			return 'employees_groups';
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
				[['employeeID', 'groupID'], 'required'],
				[['employeeID', 'groupID'], 'integer'],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID',
				'employeeID' => 'Employee',
				'groupID' => 'Group',
			];
		}

		public function getEmployeeGroups($employeeID) {
			$employeeGroupsQuery = self::find()
				->select([
					'employees_groups.id',
					'groups.id AS groupID',
				])
				->join('join', 'groups', 'groups.id = employees_groups.groupID')
				->where(['employees_groups.employeeID' => $employeeID]);

			$employeeGroups = new \yii\data\ActiveDataProvider();
			$employeeGroups->query = $employeeGroupsQuery;
			$employeeGroups->pagination = [
				'pageSize' => 20,
			];
			return $employeeGroups;
		}

		public function getEmployeesGroups() {

			$employeesGroupsTable = Yii::$app->params['evaluateDb'] . '.employees_groups';
			$employeesTable = Yii::$app->params['hrDb'] . '.employees';

			$employeeGroupsQuery = self::find()
				->join('join', $employeesTable, $employeesTable.'.personID = '.$employeesGroupsTable.'.employeeID');
			$employeeGroups = new \yii\data\ActiveDataProvider();
			$employeeGroups->query = $employeeGroupsQuery;
			$employeeGroups->pagination = [
				'pageSize' => 20,
			];
			return $employeeGroups;
		}

		public function getEmployee() {
			return $this->hasOne(persons\Persons::className(), ['id' => 'employeeID']);
		}

		public function getGroup() {
			return $this->hasOne(Groups::className(), ['id' => 'groupID']);
		}

	}
	