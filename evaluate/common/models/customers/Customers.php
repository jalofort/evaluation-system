<?php

	namespace common\models\customers;

	use Yii;
	use yii\db\ActiveRecord;
	use yii\data\ActiveDataProvider;

	class Customers extends ActiveRecord {

		public $id;
		public $nameAR;

		public static function getDb() {
			return Yii::$app->get('crmDb');
		}

		public static function tableName() {
			return 'customers';
		}

		public function getCustomers() {

			$query = self::find()
				->select(Yii::$app->params['mainDb'] . '.persons.id, ' . Yii::$app->params['mainDb'] . '.persons.nameAR')
				->from(Yii::$app->params['crmDb'] . '.customers')
				->join('join', Yii::$app->params['mainDb'] . '.persons', Yii::$app->params['crmDb'] . '.customers.personID = ' . Yii::$app->params['mainDb'] . '.persons.id')
				->where([Yii::$app->params['crmDb'] . '.customers.deleted' => null]);

			$customers = new ActiveDataProvider([
				'query' => $query,
				'pagination' => [
					'pageSize' => 20,
				],
				'sort' => [
				//'defaultOrder' => ['id' => SORT_DESC]
				],
			]);

			return $customers;
		}

		public function getCustomersCount() {
			$customersCount = self::find()
				->select('count(personID) AS customersCount')
				->where(['deleted' => null])
				->count();
			return $customersCount;
		}

		public function findCustomersByName($name) {
			$customers = self::find()
				->select(Yii::$app->params['mainDb'] . '.persons.id, ' . Yii::$app->params['mainDb'] . '.persons.nameAR')
				->from(Yii::$app->params['crmDb'] . '.customers')
				->join('join', Yii::$app->params['mainDb'] . '.persons', Yii::$app->params['crmDb'] . '.customers.personID = ' . Yii::$app->params['mainDb'] . '.persons.id')
				->where([Yii::$app->params['crmDb'] . '.customers.deleted' => null])
				->andWhere(['like', Yii::$app->params['mainDb'] . '.persons.nameAR', $name])
				->asArray()
				->all();
			if (!empty($customers)) {
				return \yii\helpers\Json::encode($customers);
			}
		}

		public function getCustomersByID($id) {
			$customer = self::find()
				->select(Yii::$app->params['mainDb'] . '.persons.id, ' . Yii::$app->params['mainDb'] . '.persons.nameAR')
				->from(Yii::$app->params['crmDb'] . '.customers')
				->join('join', Yii::$app->params['mainDb'] . '.persons', Yii::$app->params['crmDb'] . '.customers.personID = ' . Yii::$app->params['mainDb'] . '.persons.id')
				->where([
					Yii::$app->params['mainDb'] . '.persons.id' => $id,
					Yii::$app->params['crmDb'] . '.customers.deleted' => null,
				])
				->asArray()
				->one();
			return $customer;
		}

		public function addCustomer($personID) {
			$this->personID = $personID;
			$this->date = date('Y-m-d');
			$this->addedBy = Yii::$app->user->identity->id;
			if ($this->save(false)) {
				return true;
			}
		}

		public function deleteCustomer($customerID) {
			$customer = self::findOne(['personID' => $customerID]);
			if (!empty($customer)) {
				$customer->deleted = 1;
				if ($customer->save(false)) {
					return true;
				}
			}
		}

	}
	