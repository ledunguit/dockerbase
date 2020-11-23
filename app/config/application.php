<?php
    return array(
        'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
        'ds' => DIRECTORY_SEPARATOR,
		'resourceFolder' => 'publics',
        'name' => 'Quiz Learning',
        'defaultModule' => 'frontend',
        'defaultController' => 'Index',
        'defaultAction' => 'index',
        'defaultTemplate' => 'default',
        'db' => array(
            'connectionString'=>'mysql:host=mysql-product;dbname=nt208;charset=utf8',
            'emulatePrepare'=>true,
            'username'=>'root',
            'password'=>'root',
            'charset'=>'utf8',
		)
    );