<?php

	namespace common\models;

	use Yii;

	class SendEmail {

		public static function sendEmail($contentArray, $to, $sender, $subject) {
			return Yii::$app->mailer->compose($contentArray)
					->setTo($to)
					->setFrom([$sender['email'] => $sender['name']])
					->setSubject($subject)
					->send();
		}

	}
	