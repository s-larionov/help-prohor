<?php

return array(
	'basePath'       => __DIR__ . DIRECTORY_SEPARATOR . '..',
	'name'           => 'Помоги Прохору',

	// preloading 'log' component
	'preload'        => array('log'),

	// autoloading model and component classes
	'import'         => array(
		'application.models.*',
		'application.components.*',
		'ext.navigation.*',
		'ext.qiwi-soap.*',
		'ext.qiwi-soap.models.*',
	),

	'language'       => 'ru',
	'sourceLanguage' => 'ru',

	// application components
	'components'     => array(
		'urlManager'   => array(
			'urlFormat'        => 'path',
			'caseSensitive'    => true,
			'matchValue'       => true,
			'showScriptName'   => false,
			'urlSuffix'        => '/',
			'useStrictParsing' => true,
			'rules'            => require('_routes.php'),
		),
		'db'           => array(
			'connectionString' => 'mysql:host=localhost;dbname=main',
			'emulatePrepare'   => true,
			'username'         => 'root',
			'password'         => '',
			'charset'          => 'utf8',
		),
		'errorHandler' => array(
			'errorAction' => 'main/error',
		),
		'log'          => array(
			'class'  => 'CLogRouter',
			'routes' => array(
				array(
					'class'  => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
			),
		),
		'viewRenderer' => array(
			'class'         => 'ext.ETwigViewRenderer',
			'twigPathAlias' => 'ext.twig.lib.Twig',
			'fileExtension' => '.twig',
			'paths'         => array(
				'layouts' => 'application.views.layouts',
				'common'  => 'application.views.common',
			),
			'functions'     => array(
				'count' => 'count',
				'ceil'  => 'ceil',
				'dump'  => 'var_dump',
				'merge' => 'array_merge',
			),
			'globals'       => array(
				'Nav' => 'Navigation',
				'Yii' => 'Yii',
			),
		),
		'clientScript' => array(
			'packages' => array(
				'jquery' => array(
					'baseUrl' => '/js/',
					'js'      => array('jquery.min.js'),
				),
			)
		),
		'qiwi'         => array(
			'class'    => 'application.components.QiwiComponent',
			'server'   => 'http://ishop.qiwi.ru/services/ishop',
			'username' => '218686',
			'password' => 'CFTOd6Jj',
		),
	),

	'params'         => array(
		'adminEmail'         => 'sergey@larionov.biz',
		'qiwiPaymentComment' => 'Благотворительная помощь Проше Писачеву',
	),
);
