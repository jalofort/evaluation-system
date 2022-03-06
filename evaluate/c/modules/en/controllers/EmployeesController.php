<?php

	namespace c\modules\en\controllers;

	use Yii;
	use common\models\Employees;
	use common\models\persons\Persons;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;
	use yii\filters\VerbFilter;

	/**
	 * EmployeesController implements the CRUD actions for Employees model.
	 */
	class EmployeesController extends Controller {

		/**
		 * {@inheritdoc}
		 */
		public function behaviors() {
			return [
				'verbs' => [
					'class' => VerbFilter::className(),
					'actions' => [
						'delete' => ['POST'],
					],
				],
			];
		}

		/**
		 * Lists all Employees models.
		 * @return mixed
		 */
		public function actionIndex() {
			if (Yii::$app->user->can('evaluate:employees')) {
				$employeesModel = new Employees;
				$nonEmployees = $employeesModel->getNonEmployees();
				return $this->render('index', [
						'nonEmployees' => $nonEmployees,
						'employeesModel' => $employeesModel
				]);
			}
			throw new NotFoundHttpException('You don\'t have a permission to this page.');
		}

		public function actionBranchEmployees() {
			if (Yii::$app->user->can('evaluate:employees-branch')) {
				return $this->render('branch-employees');
			}
			throw new NotFoundHttpException('You don\'t have a permission to this page.');
		}

		/**
		 * Displays a single Employees model.
		 * @param integer $id
		 * @return mixed
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		public function actionView($id) {
			$employeesModel = new Employees;
			$employee = $employeesModel->getEmployee($id);

			$employeesGroupsModel = new \common\models\EmployeesGroups;
			$employeesGroups = $employeesGroupsModel->getEmployeeGroups($id);

			$groupsModel = new \common\models\Groups;
			$groups = $groupsModel->getNonChosenGroups($id);

			return $this->render('view', [
					'employee' => $employee,
					'groups' => $groups,
					'employeesGroupsModel' => $employeesGroupsModel,
					'employeesGroups' => $employeesGroups,
			]);
		}

		/**
		 * Creates a new Employees model.
		 * If creation is successful, the browser will be redirected to the 'view' page.
		 * @return mixed
		 */
		public function actionCreate() {
			$model = new Employees();

			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['index']);
			}

			return $this->render('create', [
					'model' => $model,
			]);
		}

		/**
		 * Updates an existing Employees model.
		 * If update is successful, the browser will be redirected to the 'view' page.
		 * @param integer $id
		 * @return mixed
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		public function actionUpdate($id) {
			return $this->render('update');
		}

		/**
		 * Deletes an existing Employees model.
		 * If deletion is successful, the browser will be redirected to the 'index' page.
		 * @param integer $id
		 * @return mixed
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		public function actionDelete($id) {
			$this->findModel($id)->delete();

			return $this->redirect(['index']);
		}

		/**
		 * Finds the Employees model based on its primary key value.
		 * If the model is not found, a 404 HTTP exception will be thrown.
		 * @param integer $id
		 * @return Employees the loaded model
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		protected function findModel($id) {
			if (($model = Employees::findOne($id)) !== null) {
				return $model;
			}

			throw new NotFoundHttpException('The requested page does not exist.');
		}

		public function actionGetEmployees() {
			if (Yii::$app->request->isAjax) {
				$employeesModel = new Employees;
				$employees = $employeesModel->getEmployees();
				return $employees;
			}
			throw new NotFoundHttpException('Page not found.');
		}

		public function actionGetBranchEmployees() {
			if (Yii::$app->request->isAjax) {

				// first get user branch to get his/her branch employees
				$employeesWorkLocationsModel = new \common\models\EmployeesWorkLocations;
				$userBranchID = $employeesWorkLocationsModel->getEmployeeLocationID(Yii::$app->user->identity->id);

				if (!empty($userBranchID)) {
					// get branch employees
					$branchEmployees = $employeesWorkLocationsModel->getLocationEmployees($userBranchID['branchID']);
					return $branchEmployees;
				}
				else {
					return 'no branch';
				}
			}
			throw new NotFoundHttpException('Page not found.');
		}

		public function actionEvaluate($employeeID, $groupID) {

			$employeesModel = new Employees;
			$employee = $employeesModel->getEmployee($employeeID);

			if (!empty($employee)) {
				$groupsModel = new \common\models\Groups;
				$group = $groupsModel->getGroup($groupID);
				if (!empty($group)) {
					return $this->render('evaluate', [
							'employee' => $employee,
							'group' => $group,
					]);
				}
			}
			throw new NotFoundHttpException('Page not found.');
		}

		public function actionUpdateEmployeeName() {
			if (Yii::$app->request->isAjax) {
				$personID = Yii::$app->request->post()['employeeID'];
				$newName = Yii::$app->request->post()['employeeName'];
				$personsModel = new Persons;
				$updateName = $personsModel->updatePersonName($personID, $newName);
				return $updateName;
			}
			throw new NotFoundHttpException('Page not found.');
		}

		public function actionGetEmployeeLocation() {
			if (Yii::$app->request->isAjax) {
				$employeeID = Yii::$app->request->post()['employeeID'];
				$employeesLocationsModel = new \common\models\EmployeesWorkLocations;
				$employeeLocation = $employeesLocationsModel->getEmployeeLocation($employeeID);
				return $employeeLocation;
			}
			throw new NotFoundHttpException('Page not found.');
		}

	}
	