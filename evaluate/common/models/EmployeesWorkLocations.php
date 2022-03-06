<?php

	namespace common\models;

	use Yii;

	/**
	 * This is the model class for table "employees_work_locations".
	 *
	 * @property int $id
	 * @property int $employeeID
	 * @property int $companyLocationID
	 */
	class EmployeesWorkLocations extends \yii\db\ActiveRecord {

		/**
		 * {@inheritdoc}
		 */
		public static function tableName() {
			return 'employees_work_locations';
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
				[['employeeID', 'companyLocationID'], 'required'],
				[['employeeID', 'companyLocationID'], 'integer'],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID',
				'employeeID' => 'Employee ID',
				'companyLocationID' => 'Company Location ID',
			];
		}

		public function getEmployeeLocation($employeeID) {
			$locationsTable = Yii::$app->params['mainDb'] . '.company_locations';
			$employeesLocationsTable = Yii::$app->params['hrDb'] . '.employees_work_locations';

			$location = self::find()
				->select([
					$locationsTable . '.name'
				])
				->join('join', $locationsTable, $locationsTable . '.id = ' . $employeesLocationsTable . '.companyLocationID')
				->where([
					$employeesLocationsTable . '.employeeID' => $employeeID,
				])
				->asArray()
				->one();
			if (!empty($location)) {
				return json_encode($location, JSON_UNESCAPED_UNICODE);
			}
		}

		public function getLocationEmployees($locationID) {
			$personsTable = Yii::$app->params['mainDb'] . '.persons';
			$employeesWorkLocationsTable = Yii::$app->params['hrDb'] . '.employees_work_locations';
			$employees = self::find()
				->select([
					$personsTable . '.id',
					$personsTable . '.nameAR',
				])
				->join('join', $personsTable, $personsTable . '.id = ' . $employeesWorkLocationsTable . '.employeeID')
				->where([
					$employeesWorkLocationsTable . '.companyLocationID' => $locationID,
				])
				->andWhere([
					'<>', $personsTable . '.id', Yii::$app->user->identity->id
				])
				->orderBy($personsTable . '.nameAR ASC')
				->asArray()
				->all();
			if (!empty($employees)) {
				return json_encode($employees, JSON_UNESCAPED_UNICODE);
			}
		}

		public function getLocationEmployeesIDs($locationID) {
			$employeesIDsArray = self::find()
				->select('employeeID')
				->where([
					'companyLocationID' => $locationID,
				])
				->asArray()
				->all();
			if (!empty($employeesIDsArray)) {
				$employeesIDs = [];
				foreach ($employeesIDsArray as $key => $value) {
					array_push($employeesIDs, $value['employeeID']);
				}
				return $employeesIDs;
			}
		}

		public function getEmployeeLocationID($employeeID) {
			$locationID = self::find()
				->select(['companyLocationID as branchID'])
				->where([
					'employeeID' => $employeeID,
				])
				->asArray()
				->one();
			if (!empty($locationID)) {
				return $locationID;
			}
		}

		public function updateEmployeeBranch($employeeID, $branchID) {
			$employeeBranch = self::find()
				->where([
					'employeeID' => $employeeID
				])
				->one();
			if (!empty($employeeBranch)) {
				$employeeBranch->companyLocationID = $branchID;
				if ($employeeBranch->save()) {
					return true;
				}
			}
			else {
				$this->employeeID = $employeeID;
				$this->companyLocationID = $branchID;
				if ($this->save()) {
					return true;
				}
			}
		}

	}
	