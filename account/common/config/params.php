<?php

	$systemFolder = 'account';
	$systemName = 'My account';
	$currentURL = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	$homeURL = substr($currentURL, 0, strpos($currentURL, $systemFolder) + 8);
	$accountsUrl = str_replace($systemFolder, 'accounts', $homeURL);
	$welcometURL = $accountsUrl . 'c/site/welcome';

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
		'accountsUrl' => $accountsUrl,
		'adminUrl' => str_replace($systemFolder, 'admin', $homeURL),
		'evaluateUrl' => str_replace($systemFolder, 'evaluate', $homeURL),
		'mainDb' => $dbName . 'main',
		'evaluateDb' => $dbName . 'evaluate',
		'hrDb' => $dbName . 'hr',
		'noReplyEmail' => 'no-reply@mohymen.sa',
		'supportEmail' => 'support@example.com',
		'user.passwordResetTokenExpire' => 3600,
	];
	