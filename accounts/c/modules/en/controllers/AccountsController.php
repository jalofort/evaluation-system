<?php

	namespace c\modules\en\controllers;

	use Yii;
	use yii\web\Controller;
	use common\models\persons\Persons;
	use common\models\persons\PersonsEmails;
	use common\models\AuthItem;
	use common\models\AuthAssignment;

	class AccountsController extends Controller {

		public function actionIndex() {

			if (Yii::$app->user->can('accounts')) {
				$accountsModel = new Persons;
				$accounts = $accountsModel->getSystemAccounts();

				return $this->render('index', [
						'accounts' => $accounts,
						'accountsModel' => $accountsModel,
				]);
			}
			throw new \yii\web\NotFoundHttpException('You don\'t have permission to see that.');
		}

		public function actionView($id) {

			$personsModel = new Persons;
			$userInfo = $personsModel->getUserAccountInfo($id);

			if (!empty($userInfo)) {
				$userEmailModel = new PersonsEmails;
				$userEmail = $userEmailModel->getUserPrimaryEmail($id);

				$privilegesModel = new AuthAssignment;
				$users = $personsModel->getSystemUsersWhoHavePrivileges($id);

				return $this->render('view', [
						'userInfo' => $userInfo,
						'userEmail' => $userEmail,
						'privilegesModel' => $privilegesModel,
						'users' => $users,
				]);
			}
			throw new \yii\web\NotFoundHttpException('Page not found !');
		}

		public function actionChangeStatus() {
			if (Yii::$app->request->isAjax) {
				$accountsModel = new Persons;
				$changeAccountStatus = $accountsModel->changeAccountStatus(Yii::$app->request->post()['id']);
				return $changeAccountStatus;
			}
			throw new \yii\web\NotFoundHttpException('Page not found !');
		}

		public function actionUpdateUserAccountPassword() {
			if (Yii::$app->request->isAjax) {
				$updatePasswordModel = new Persons;
				$updatePassword = $updatePasswordModel->updateUserAccountPassword(Yii::$app->request->post()['password'], Yii::$app->request->post()['userID']);
				return $updatePassword;
			}
			throw new \yii\web\NotFoundHttpException('Page not found !');
		}

		public function actionGetUserUnassignedPrivileges() {
			if (Yii::$app->request->isAjax) {
				$privilegesModel = new AuthItem;
				$privileges = $privilegesModel->getUserUnassignedPrivileges(Yii::$app->request->post()['id']);
				return $privileges;
			}
			throw new \yii\web\NotFoundHttpException('Page not found !');
		}

		public function actionGetUserAssignedPrivileges() {
			if (Yii::$app->request->isAjax) {
				$privilegesModel = new AuthAssignment;
				$privileges = $privilegesModel->getUserAssignedPrivileges(Yii::$app->request->post()['id']);
				return $privileges;
			}
			throw new \yii\web\NotFoundHttpException('Page not found !');
		}

		public function actionAssignUserPrivilege() {
			if (Yii::$app->request->isAjax) {
				$privilegesModel = new AuthAssignment;
				$assignPrivilege = $privilegesModel->assignUserPrivilege(Yii::$app->request->post()['privilege'], Yii::$app->request->post()['userID']);
				return $assignPrivilege;
			}
			throw new \yii\web\NotFoundHttpException('Page not found !');
		}

		public function actionRemoveUserPrivilege() {
			if (Yii::$app->request->isAjax) {
				$privilegesModel = new AuthAssignment;
				$removePrivilege = $privilegesModel->removeUserPrivilege(Yii::$app->request->post()['privilege'], Yii::$app->request->post()['userID']);
				return $removePrivilege;
			}
			throw new \yii\web\NotFoundHttpException('Page not found !');
		}

		public function actionAddUserPrivilegesLikeAnotherUser() {
			if (Yii::$app->request->isAjax) {
				$privilegesModel = new AuthAssignment;
				$addPrivileges = $privilegesModel->addUserPrivilegesLikeAnotherUser(Yii::$app->request->post()['selectedUserID'], Yii::$app->request->post()['currentUserID']);
				return $addPrivileges;
			}
			throw new \yii\web\NotFoundHttpException('Page not found !');
		}

		public function actionUnassignUserAllPrivileges() {
			if (Yii::$app->request->isAjax) {
				$privilegesModel = new AuthAssignment;
				$addPrivileges = $privilegesModel->unassignUserAllPrivileges(Yii::$app->request->post()['userID']);
				return $addPrivileges;
			}
			throw new \yii\web\NotFoundHttpException('Page not found !');
		}

	}
	