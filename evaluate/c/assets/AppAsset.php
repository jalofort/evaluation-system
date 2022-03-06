<?php

	namespace c\assets;

	use yii\web\AssetBundle;

	/**
	 * Main c application asset bundle.
	 */
	class AppAsset extends AssetBundle {

		public $basePath = '@webroot';
		public $baseUrl = '@web';
		public $css = [
			'css/glyphicons.css',
			'css/main.css',
			'css/crm.css',
			'css/switch-button2.css',
			'css/print.css',
		];
		public $js = [
			'js/custom.js',
		];
		public $depends = [
			'yii\web\YiiAsset',
			//'yii\bootstrap\BootstrapAsset',
		];

	}
	