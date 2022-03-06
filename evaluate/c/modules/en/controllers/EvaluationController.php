<?php

	namespace c\modules\en\controllers;

	use Yii;
	use common\models\Evaluation;
	use common\models\Employees;
	use common\models\EvaluationItems;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;
	use yii\filters\VerbFilter;
	use common\models\SendEmail;

	/**
	 * EvaluationController implements the CRUD actions for Evaluation model.
	 */
	class EvaluationController extends Controller {

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
		 * Lists all Evaluation models.
		 * @return mixed
		 */
		public function actionIndex() {
			if (Yii::$app->user->can('evaluate:evaluation')) {
				$evaluationModel = new Evaluation;
				$evaluation = $evaluationModel->getEvaluation();

				return $this->render('index', [
						'dataProvider' => $evaluation,
				]);
			}
			throw new NotFoundHttpException('You don\'t have a permission to this page.');
		}

		/**
		 * Displays a single Evaluation model.
		 * @param integer $id
		 * @return mixed
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		public function actionView($id) {
			return $this->render('view', [
					'model' => $this->findModel($id),
			]);
		}

		/**
		 * Creates a new Evaluation model.
		 * If creation is successful, the browser will be redirected to the 'view' page.
		 * @return mixed
		 */
		public function actionCreate() {
			$model = new Evaluation();

			if ($model->load(Yii::$app->request->post())) {

				$model->month = date('Y-m-d');
				$model->date = date('Y-m-d h:i:s');

				if ($model->save()) {
					return $this->redirect(['view', 'id' => $model->id]);
				}
			}

			$employeeModel = new Employees;
			$employees = $employeeModel->getEmployees2();
			$evaluationItemsModel = new EvaluationItems;
			$evaluationItems = $evaluationItemsModel->getEvaluationItems();

			return $this->render('create', [
					'model' => $model,
					'employees' => $employees,
					'evaluationItems' => $evaluationItems,
			]);
		}

		/**
		 * Updates an existing Evaluation model.
		 * If update is successful, the browser will be redirected to the 'view' page.
		 * @param integer $id
		 * @return mixed
		 * @throws NotFoundHttpException if the model cannot be found
		 */
//		public function actionUpdate($id) {
//			$model = $this->findModel($id);
//
//			if ($model->load(Yii::$app->request->post()) && $model->save()) {
//				return $this->redirect(['view', 'id' => $model->id]);
//			}
//
//			return $this->render('update', [
//					'model' => $model,
//			]);
//		}

		/**
		 * Deletes an existing Evaluation model.
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
		 * Finds the Evaluation model based on its primary key value.
		 * If the model is not found, a 404 HTTP exception will be thrown.
		 * @param integer $id
		 * @return Evaluation the loaded model
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		protected function findModel($id) {
			if (($model = Evaluation::findOne($id)) !== null) {
				return $model;
			}

			throw new NotFoundHttpException('The requested page does not exist.');
		}

		public function actionGetEmployeeEvaluation() {
			if (Yii::$app->request->isAjax) {
				$evaluationModel = new Evaluation;
				return $evaluationModel->getEmployeeEvaluation(Yii::$app->request->post()['employeeID'], Yii::$app->request->post()['groupID'], Yii::$app->request->post()['date']);
			}
			throw new NotFoundHttpException('Page not found.');
		}

		public function actionSaveEvaluation() {
			if (Yii::$app->request->isAjax) {
				if (isset(Yii::$app->request->post()['date'])) {
					$month = Yii::$app->request->post()['date'];
				}
				else {
					$month = date('Y-m-d');
				}
				$evaluationModel = new Evaluation;
				$saveEvaluation = $evaluationModel->saveEvaluation(Yii::$app->request->post()['employeeID'], Yii::$app->request->post()['groupID'], Yii::$app->request->post()['data'], $month);
				
				if ($saveEvaluation > 0) {
					$result['savingEvaluation'] = true;
					
					// get employee email to send notification
					$employeeEmailsModel = new \common\models\persons\PersonsEmails;
					$employeeEmailJSON = $employeeEmailsModel->getPersonEmails(Yii::$app->request->post()['employeeID']);
					$employeeEmails = json_decode($employeeEmailJSON);
			
					// send notification by email that evaluation added 
					$contentArray = ['html' => 'new-evaluation',];
					$to = $employeeEmails[0]->email;
					$sender['email'] = 'no-reply@mohymen.com.sa';
					$sender['name'] = 'Mohymen';
					$subject = 'تقييم جديد خاص بك';

					$sendingEmail = SendEmail::sendEmail($contentArray, $to, $sender, $subject);
					if($sendingEmail) {
						$result['sendingEmail'] = true;
					}
				}
				return json_encode($result);
			}
			throw new NotFoundHttpException('Page not found.');
		}

		public function actionUpdateEvaluation() {
			if (Yii::$app->request->isAjax) {
				$evaluationModel = new Evaluation;
				return $evaluationModel->updateEvaluation(Yii::$app->request->post()['evaluationID'], Yii::$app->request->post()['evaluation']);
			}
			throw new NotFoundHttpException('Page not found.');
		}

		public function actionMyEvaluation($date) {
			$evaluationModel = new Evaluation;
			$evaluation = $evaluationModel->getMyEvaluation($date);

			return $this->render('my-evaluation', [
					'dataProvider' => $evaluation,
			]);
		}

		public function actionUpdateMonth() {
			$id = Yii::$app->request->post()['id'];
			$year = Yii::$app->request->post()['year'];
			$month = Yii::$app->request->post()['month'];

			$evaluationModel = new Evaluation;
			$changeMonth = $evaluationModel->updateMonth($id, $year, $month);
			return $changeMonth;
		}

	}
	