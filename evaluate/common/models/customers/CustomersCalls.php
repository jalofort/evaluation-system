<?php

	namespace common\models\customers;

	use Yii;
	use yii\db\ActiveRecord;

	class CustomersCalls extends ActiveRecord {

		public static function getDb() {
			return Yii::$app->get('crmDb');
		}

		public static function tableName() {
			return 'customers_calls';
		}

		public function getCustomerCalls($personID) {
			$personsTable = Yii::$app->params['mainDb'] . '.persons';
			$customersCallsTable = Yii::$app->params['crmDb'] . '.customers_calls';
			$customersNotesTable = Yii::$app->params['crmDb'] . '.customers_notes';
			$calls = self::find()
				->select($customersCallsTable . '.id, ' . $customersCallsTable . '.InOrOut, ' . $customersCallsTable . '.callDate, ' . $customersNotesTable . '.note, ' . $personsTable . '.nameAR')
				->join('join', $customersNotesTable, '' . $customersNotesTable . '.id = ' . $customersCallsTable . '.noteID')
				->join('join', $personsTable, $personsTable . '.id = ' . $customersCallsTable . '.addedBy')
				->where(['customers_calls.customerID' => $personID])
				->asArray()
				->all();
			if (!empty($calls)) {
				return json_encode($calls);
			}
		}

		public function addCustomerCall($customerID, $inOrOut, $callDate, $noteID) {
			$this->customerID = $customerID;
			$this->InOrOut = $inOrOut;
			$this->callDate = $callDate;
			$this->noteID = $noteID;
			$this->addedBy = Yii::$app->user->identity->id;
			if ($this->save()) {
				return true;
			}
		}

		public function deleteCustomerCall($callID) {
			$call = self::findOne($callID);
			if (!empty($call)) {
				if ($call->delete()) {
					return true;
				}
			}
		}

	}
	