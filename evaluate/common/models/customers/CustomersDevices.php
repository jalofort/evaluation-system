<?php

	namespace common\models\customers;

	use Yii;
	use yii\db\ActiveRecord;
	use common\models\Devices;
	use common\models\persons\Persons;
	use common\models\LocationsTypes;

	class CustomersDevices extends ActiveRecord {

		public static function getDb() {
			return Yii::$app->get('crmDb');
		}

		public static function tableName() {
			return 'customers_devices';
		}

		public function rules() {
			return [
				[['personID', 'deviceID', 'addressID', 'locationTypeID', 'invoiceDate', 'addedBy'], 'required'],
				[['personID', 'deviceID', 'addressID', 'locationTypeID', 'price', 'invoiceNo', 'technicianID', 'addedBy', 'deleted'], 'integer'],
				[['invoiceDate', 'installationDate'], 'safe'],
				[['deviceID'], 'exist', 'skipOnError' => true, 'targetClass' => Devices::className(), 'targetAttribute' => ['deviceID' => 'id']],
			];
		}

		public function attributeLabels() {
			return [
				'id' => 'ID',
				'personID' => 'Person ID',
				'deviceID' => 'Device',
				'addressID' => 'Address ID',
				'locationTypeID' => 'Locations types',
				'price' => 'Price',
				'invoiceNo' => 'Invoice No',
				'invoiceDate' => 'Invoice Date',
				'installationDate' => 'Installation Date',
				'technicianID' => 'Technician ID',
				'addedBy' => 'Added By',
				'deleted' => 'Deleted',
			];
		}

		public function getCustomerDevices($customerID) {
			$devices = self::find()
				->select('
					' . Yii::$app->params['crmDb'] . '.customers_devices.id,
					' . Yii::$app->params['crmDb'] . '.devices.name AS deviceName,
					' . Yii::$app->params['crmDb'] . '.customers_devices.addressID,
					' . Yii::$app->params['crmDb'] . '.locations_types.nameAR AS locationType,
					' . Yii::$app->params['crmDb'] . '.customers_devices.price,
					' . Yii::$app->params['crmDb'] . '.customers_devices.invoiceNo,
					' . Yii::$app->params['crmDb'] . '.customers_devices.invoiceDate,
					' . Yii::$app->params['crmDb'] . '.customers_devices.installationDate,
					' . Yii::$app->params['mainDb'] . '.persons.nameAR as technician
				')
				->join('join', Yii::$app->params['crmDb'] . '.devices', Yii::$app->params['crmDb'] . '.devices.id = ' . Yii::$app->params['crmDb'] . '.customers_devices.deviceID')
				->join('join', Yii::$app->params['mainDb'] . '.persons', Yii::$app->params['mainDb'] . '.persons.id = ' . Yii::$app->params['crmDb'] . '.customers_devices.technicianID')
				->leftjoin(Yii::$app->params['crmDb'] . '.locations_types', Yii::$app->params['crmDb'] . '.locations_types.id = ' . Yii::$app->params['crmDb'] . '.customers_devices.locationTypeID')
				->where([
					Yii::$app->params['crmDb'] . '.customers_devices.personID' => $customerID,
					Yii::$app->params['crmDb'] . '.customers_devices.deleted' => null
				])
				->asArray()
				->all();

			if (!empty($devices)) {
				return json_encode($devices);
			}
		}

		public function getDeviceDetails($id) {
			$deviceDetails = self::find()
				->select('personID, deviceID, addressID, locationTypeID, price, invoiceNo, invoiceDate, installationDate, technicianID, addedBy')
				->where(['id' => $id])
				->asArray()
				->one();
			if (!empty($deviceDetails)) {
				return json_encode($deviceDetails, JSON_UNESCAPED_UNICODE);
			}
		}

		public function getTechnician() {
			return $this->hasOne(Persons::className(), ['id' => 'technicianID']);
		}

		public function getEmployee() {
			return $this->hasOne(Persons::className(), ['id' => 'addedBy']);
		}

		public function getCustomer() {
			return $this->hasOne(Persons::className(), ['id' => 'personID']);
		}

		public function getDevice() {
			return $this->hasOne(Devices::className(), ['id' => 'deviceID']);
		}

		public function getLocation() {
			return $this->hasOne(LocationsTypes::className(), ['id' => 'locationTypeID']);
		}

		public function deleteCustomerDevice($customerDeviceID) {
			$customerDevice = self::findOne($customerDeviceID);
			if (!empty($customerDevice)) {
				$customerDevice->deleted = 1;
				if ($customerDevice->save(false)) {
					return true;
				}
			}
		}

		public function updateDeviceID($customerDeviceID, $newDeivceID) {
			$customerDevice = self::findOne($customerDeviceID);
			if (!empty($customerDevice)) {
				$customerDevice->deviceID = $newDeivceID;
				if ($customerDevice->save(false)) {
					return true;
				}
			}
		}

		public function updateLocationTypeID($customerDeviceID, $newLocationTypeID) {
			$customerDevice = self::findOne($customerDeviceID);
			if (!empty($customerDevice)) {
				$customerDevice->locationTypeID = $newLocationTypeID;
				if ($customerDevice->save(false)) {
					return true;
				}
			}
		}

		public function updateTechnicianID($customerDeviceID, $newTechnicianID) {
			$customerDevice = self::findOne($customerDeviceID);
			if (!empty($customerDevice)) {
				$customerDevice->technicianID = $newTechnicianID;
				if ($customerDevice->save(false)) {
					return true;
				}
			}
		}

		public function updatePrice($customerDeviceID, $newPrice) {
			$customerDevice = self::findOne($customerDeviceID);
			if (!empty($customerDevice)) {
				$customerDevice->price = $newPrice;
				if ($customerDevice->save(false)) {
					return true;
				}
			}
		}

		public function updateInvoice($customerDeviceID, $newInvoice) {
			$customerDevice = self::findOne($customerDeviceID);
			if (!empty($customerDevice)) {
				$customerDevice->invoiceNo = $newInvoice;
				if ($customerDevice->save(false)) {
					return true;
				}
			}
		}

		public function updateInvoiceDate($customerDeviceID, $newInvoiceDate) {
			$customerDevice = self::findOne($customerDeviceID);
			if (!empty($customerDevice)) {
				$customerDevice->invoiceDate = $newInvoiceDate;
				if ($customerDevice->save(false)) {
					return true;
				}
			}
		}

		public function updateInstallationDate($customerDeviceID, $newInstallationDate) {
			$customerDevice = self::findOne($customerDeviceID);
			if (!empty($customerDevice)) {
				$customerDevice->installationDate = $newInstallationDate;
				if ($customerDevice->save(false)) {
					return true;
				}
			}
		}

		public function addCutomerDeviceAddress($customerDeviceID, $addressID) {
			$customerDevice = self::findOne($customerDeviceID);
			if (!empty($customerDevice)) {
				$customerDevice->addressID = $addressID;
				$customerDevice->save(false);
				return true;
			}
		}

	}
	