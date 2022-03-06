<?php

	namespace common\models;

	use Yii;
	use yii\data\ActiveDataProvider;

	/**
	 * This is the model class for table "evaluation".
	 *
	 * @property int $id
	 * @property int $evaluatorID
	 * @property int $evaluatedID
	 * @property int $evaluateItemID
	 * @property int $evaluation
	 * @property string $date
	 *
	 * @property EvaluationItems $evaluateItem
	 */
	class Evaluation extends \yii\db\ActiveRecord {

		/**
		 * {@inheritdoc}
		 */
		public static function tableName() {
			return 'evaluation';
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
				[['evaluatorID', 'evaluatedID', 'evaluateItemID', 'date'], 'required'],
				[['evaluatorID', 'evaluatedID', 'evaluateItemID', 'evaluation'], 'integer'],
				[['note'], 'string'],
				[['date'], 'safe'],
				[['evaluateItemID'], 'exist', 'skipOnError' => true, 'targetClass' => EvaluationItems::className(), 'targetAttribute' => ['evaluateItemID' => 'id']],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID',
				'evaluatorID' => 'Evaluator',
				'evaluatedID' => 'Evaluated',
				'evaluateItemID' => 'Evaluate Item',
				'evaluation' => 'Evaluation',
				'note' => 'Note',
				'date' => 'Date',
			];
		}

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getEvaluator() {
			return $this->hasOne(persons\Persons::className(), ['id' => 'evaluatorID']);
		}

		public function getEvaluated() {
			return $this->hasOne(persons\Persons::className(), ['id' => 'evaluatedID']);
		}

		public function getEvaluateItem() {
			return $this->hasOne(EvaluationItems::className(), ['id' => 'evaluateItemID']);
		}

		public function getEvaluation() {
			$evaluationQuery = self::find()
//				->where(['month' => $month])
				->orderBy('id DESC');

			$evaluation = new ActiveDataProvider();
			$evaluation->query = $evaluationQuery;
			$evaluation->pagination = [
				'pageSize' => 20
			];
			return $evaluation;
		}

		public function getMyEvaluation($date) {
			$dateArray = explode('-', $date);
			$month = $dateArray[1];
			$year = $dateArray[0];
			$evaluationQuery = self::find()
				->where([
					'evaluatedID' => Yii::$app->user->identity->id,
					'month(month)' => $month,
					'year(month)' => $year,
				])
				->orderBy('id DESC');

			$evaluation = new ActiveDataProvider();
			$evaluation->query = $evaluationQuery;
			return $evaluation;
		}

		public function getEmployeeEvaluation($employeeID, $groupID, $date) {
			$dateArray = explode('-', $date);
			$month = $dateArray[1];
			$year = $dateArray[0];
			$personsTable = Yii::$app->params['mainDb'] . '.persons';
			$evaluationTable = Yii::$app->params['evaluateDb'] . '.evaluation';
			$evaluationItemsTable = Yii::$app->params['evaluateDb'] . '.evaluation_items';
			$employeeEvaluation = self::find()
				->select([
					$evaluationTable . '.id',
					$evaluationTable . '.evaluatorID',
					$personsTable . '.nameAR',
					$evaluationItemsTable . '.item',
					$evaluationTable . '.evaluation',
					$evaluationTable . '.note',
					'month(' . $evaluationTable . '.month) as month',
					'year(' . $evaluationTable . '.month) as year',
					$evaluationTable . '.date',
				])
				->join('join', $personsTable, $personsTable . '.id = ' . $evaluationTable . '.evaluatorID')
				->join('join', $evaluationItemsTable, $evaluationItemsTable . '.id = ' . $evaluationTable . '.evaluateItemID')
				->where([
					$evaluationTable . '.evaluatedID' => $employeeID,
					$evaluationTable . '.groupID' => $groupID,
					'month(' . $evaluationTable . '.month)' => $month,
					'year(' . $evaluationTable . '.month)' => $year,
				])
				->asArray()
				->all();
			return json_encode($employeeEvaluation, JSON_UNESCAPED_UNICODE);
		}

		public function getEmployeesEvaluationTotal($employeesIDsArray, $year, $month) {

			$personsTable = Yii::$app->params['mainDb'] . '.persons';
			$evaluationTable = Yii::$app->params['evaluateDb'] . '.evaluation';
			$groupsTable = Yii::$app->params['evaluateDb'] . '.groups';
			$employeesEvaluation = self::find()
				->select([
					$evaluationTable . '.evaluatedID',
					$personsTable . '.nameAR',
					'sum(' . $evaluationTable . '.evaluation) as evaluationTotal',
					'count(' . $evaluationTable . '.evaluation) as evaluationCount',
					$groupsTable . '.name as groupName',
				])
				->join('join', $personsTable, $personsTable . '.id = ' . $evaluationTable . '.evaluatedID')
				->join('join', $groupsTable, $groupsTable . '.id = ' . $evaluationTable . '.groupID')
				->where([
					$evaluationTable . '.evaluatedID' => $employeesIDsArray,
					'month(' . $evaluationTable . '.month)' => $month,
					'year(' . $evaluationTable . '.month)' => $year,
				])
				->asArray()
				->groupBy([$personsTable . '.nameAR', $evaluationTable.'.groupID'])
				->all();
			return json_encode($employeesEvaluation, JSON_UNESCAPED_UNICODE);
		}

		public function saveEvaluation($employeeID, $groupID, $data, $month) {
			$inputs = json_decode($data, true);
			$evaluatorID = Yii::$app->user->identity->id;
			$inputsArray = [];
			foreach ($inputs as $input) {
				array_push($inputsArray, [$evaluatorID, $employeeID, $input['itemID'], $groupID, $input['evaluation'], $month, date('Y-m-d h:i:s')]);
			}
			$saveEvaluation = Yii::$app->evaluateDb->createCommand()->batchInsert('evaluation', ['evaluatorID', 'evaluatedID', 'evaluateItemID', 'groupID', 'evaluation', 'month', 'date'], $inputsArray)->execute();
			return $saveEvaluation;
		}

		public function updateEvaluation($evaluationID, $evaluation) {
			$evaluationRecord = self::findOne($evaluationID);
			if (!empty($evaluationRecord)) {
				$evaluationRecord->evaluation = $evaluation;
				if ($evaluationRecord->save(false)) {
					return true;
				}
			}
		}
		
		public function updateMonth($id, $year, $month) {
			$evaluationRecord = self::findOne($id);
			if (!empty($evaluationRecord)) {
				$evaluationRecord->month = $year.'-'.$month.'-'.date('d');
				if ($evaluationRecord->save(false)) {
					return true;
				}
			}
		}

	}
	