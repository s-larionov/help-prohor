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
				'password' => 'kij23@$#5',
				'charset' => 'utf8',
			),
		),
	)
);

return $config;
