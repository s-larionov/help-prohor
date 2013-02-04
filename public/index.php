<?php

defined('YII_DEBUG')		or define('YII_DEBUG',			true);
defined('YII_TRACE_LEVEL')	or define('YII_TRACE_LEVEL',	3);
defined('APPLICATION_MODE')	or define('APPLICATION_MODE',	isset($_SERVER['APPLICATION_MODE'])? $_SERVER['APPLICATION_MODE']: 'production');

// calculate filename for config
$configFileName = realpath(__DIR__ . '/../protected/config/' . preg_replace('/[^A-Za-z0-9_\-]/', '_', APPLICATION_MODE) . '.php');

// include library
require_once('framework/yii.php');
$app = Yii::createWebApplication($configFileName)
		->run();
