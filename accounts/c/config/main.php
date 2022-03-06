<?php

	use yii\web\Request;

$baseUrl = str_replace('/web', '', (new Request)->getBaseUrl());

	// set domain name local or global ( this is for single sign on )
	$domain = ''; // if server_name is localhost 127.0.0.1 set to empty
	if ($_SERVER['SERVER_NAME'] != '127.0.0.1') {
		$domain = substr($_SERVER['SERVER_NAME'], strpos($_SERVER['SERVER_NAME'], '.'));
	}

	$params = array_merge(
		require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
	);

	return [
		'id' => 'mohymen-accounts-c',
		'timeZone' => 'asia/riyadh',
		'basePath' => dirname(__DIR__),
		'bootstrap' => ['log'],
		'controllerNamespace' => 'c\controllers',
		'components' => [
			'request' => [
				'csrfParam' => '_csrf-c',
				'baseUrl' => $baseUrl,
			],
			'user' => [
				'identityClass' => 'common\models\User',
				'enableAutoLogin' => true,
				'identityCookie' => [
					'name' => '_identity-c',
					'httpOnly' => true,
				],
			],
			'session' => [
				// this is the name of the session cookie used for login on the c
				'name' => 'mohymen',
				'cookieParams' => [
					'domain' => $domain,
				],
			],
			'log' => [
				'traceLevel' => YII_DEBUG ? 3 : 0,
				'targets' => [
					[
						'class' => 'yii\log\FileTarget',
						'levels' => ['error', 'warning'],
					],
				],
			],
			'errorHandler' => [
				'errorAction' => 'site/error',
			],
			'urlManager' => [
				'enablePrettyUrl' => true,
				'showScriptName' => false,
				'baseUrl' => $baseUrl,
				'rules' => [
				],
			],
		],
		'modules' => [
			'en' => [
				'class' => 'c\modules\en\en',
			],
			'ar' => [
				'class' => 'c\modules\ar\ar',
			],
		],
		'params' => $params,
		'as beforeRequest' => [
			'class' => 'yii\filters\AccessControl',
			'rules' => [
				[
					'allow' => true,
					'actions' => ['login', 'about', 'register', 'contact', 'request-password-reset', 'reset-password'],
				],
				[
					'allow' => true,
					'roles' => ['@'],
				],
			],
			'denyCallback' => function () {
				return Yii::$app->response->redirect(['site/login']);
			},
		],
	];
	