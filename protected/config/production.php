<?php

Yii::import('system.collections.CMap');

$config = CMap::mergeArray(
	require('_main.php'),
	array(
		'components' => array(
			'db' => array(
				'connectionString' => 'mysql:host=127.0.0.1;dbname=prohor_db',
				'emulatePrepare' => true,
				'username' => 'prohor',
				'password' => 'j3278J^&@^HJB',
				'charset' => 'utf8',
			),
		),
	)
);

return $config;
