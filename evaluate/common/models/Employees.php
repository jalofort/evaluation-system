<?php

	namespace common\models;

	use Yii;

	/**
	 * This is the model class for table "employees".
	 *
	 * @property int $personID
	 */
	class Employees extends \yii\db\ActiveRecord {

		public static function tableName() {
			return 'employees';
		}

		/**
		 * @return \yii\db\Connection the database connection used by this AR class.
		 */
		public static function getDb() {
			return Yii::$app->get('hrDb');
		}

		/**
		 * {@inheritdoc}
		 */
		public function rules() {
			return [
				[['personID'], 'required'],
				[['personID'], 'integer'],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'personID' => 'Person',
			];
		}

		public function getNonEmployees() {
			$personsTable = Yii::$app->params['mainDb'] . '.persons';
			$employeesTable = Yii::$app->params['hrDb'] . '.employees';
			$nonEmployees = self::find()
				->select([
					$personsTable . '.id',
					$personsTable . '.nameAR',
				])
				->rightJoin($personsTable, $personsTable . '.id = ' . $employeesTable . '.personID')
				->where([
					'IS', $employeesTable . '.personID', null
				])
				->orderBy($personsTable . '.nameAR ASC')
				->asArray()
				->all();
			return $nonEmployees;
		}

		public function getEmployees() {
			$personsTable = Yii::$app->params['mainDb'] . '.persons';
			$employeesTable = Yii::$app->params['hrDb'] . '.employees';
			$employees = self::find()
				->select([
					$personsTable . '.id',
					$personsTable . '.nameAR',
					$personsTable . '.nameEN',
				])
				->join('join', $personsTable, $personsTable . '.id = ' . $employeesTable . '.personID')
				->orderBy($personsTable . '.nameAR ASC')
				->asArray()
				->all();

			if (!empty($employees)) {
				return json_encode($employees, JSON_UNESCAPED_UNICODE);
			}
		}

		public function getEmployees2() {
			$personsTable = Yii::$app->params['mainDb'] . '.persons';
			$employeesTable = Yii::$app->params['hrDb'] . '.employees';
			$employees = self::find()
				->select([
					$personsTable . '.id',
					$personsTable . '.nameAR',
				])
				->join('join', $personsTable, $personsTable . '.id = ' . $employeesTable . '.personID')
				->orderBy($personsTable . '.nameAR ASC')
				->asArray()
				->all();

			return $employees;
		}

		public function getEmployee($employeeID) {
			$personsTable = Yii::$app->params['mainDb'] . '.persons';
			$employeesTable = Yii::$app->params['hrDb'] . '.employees';
			$employee = self::find()
				->select([
					$personsTable . '.id',
					$personsTable . '.nameAR',
					$personsTable . '.nameEN',
				])
				->join('join', $personsTable, $personsTable . '.id = ' . $employeesTable . '.personID')
				->where([
					$employeesTable . '.personID' => $employeeID
				])
				->asArray()
				->one();
			return $employee;
		}

	}
	