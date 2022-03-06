<?php

	namespace c\models;

	use yii\base\Model;
	use common\models\User;

	/**
	 * Signup form
	 */
	class Register extends Model {

		public $nameEN;
		public $email;
		public $password;

		public function rules() {
			return [
				['nameEN', 'trim'],
				['nameEN', 'required'],
				['nameEN', 'string', 'min' => 3, 'max' => 255],
				['email', 'trim'],
				['email', 'required'],
				['email', 'email'],
				['email', 'string', 'min' => 5, 'max' => 255],
				['password', 'required'],
			];
		}

		public function attributeLabels() {
			return [
				'nameEN' => 'Name',
			];
		}

		public function signup() {
			if (!$this->validate()) {
				return null;
			}

			$user = new User();
			$user->username = $this->username;
			$user->email = $this->email;
			$user->setPassword($this->password);
			$user->generateAuthKey();

			return $user->save() ? $user : null;
		}

		public function sendValues($attributes) {
			/*
			foreach ($attributes as $key => $value) {
				$fields[$key] = $value;
			}
			*/
			
			$
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, \Yii::$app->params['url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			$result = curl_exec($ch);
			curl_close($ch);

			return $fields;
		}

	}
	