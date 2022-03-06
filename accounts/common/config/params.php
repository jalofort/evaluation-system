<?php

	$systemFolder = 'accounts';
	$systemName = 'Accounts';
	$currentURL = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$homeURL = substr($currentURL, 0, strpos($currentURL, $systemFolder) + 8);
	$welcometURL = $homeURL . '/c/site/welcome';
	
	$dbName = 'db_mohymen_';

	return [
		'companyNameAR' => 'شركة',
		'companyNameEN' => 'Company',
		'systemName' => $systemName,
		'currentUrl' => $currentURL,
		'welcomeUrl' => $welcometURL,
		'homeUrl' => $homeURL,
		'assetsUrl' => 'http://cdn.msapps.net/assets/',
		'projectFilesUrl' => 'http://cdn.msapps.net/customers/mohymen/',
		'myAccountUrl' => str_replace($systemFolder, 'account', $homeURL),
		'accountsUrl' => str_replace($systemFolder, 'accounts', $homeURL),
		'adminUrl' => str_replace($systemFolder, 'admin', $homeURL),
		'evaluateUrl' => str_replace($systemFolder, 'evaluate', $homeURL),
		'mainDb' => $dbName . 'main',
		'evaluateDb' => $dbName . 'evaluate',
		'noReplyEmail' => 'no-reply@mohymen.sa',
		'supportEmail' => 'support@example.com',
		'bsVersion' => '4.x',
		'user.passwordResetTokenExpire' => 3600,
	];
	