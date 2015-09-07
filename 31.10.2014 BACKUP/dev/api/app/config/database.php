<?php

return array(

	'fetch' => PDO::FETCH_CLASS,

	'default' => 'owloo_owloo',

	'connections' => array(

		'sqlite' => array(
			'driver'   => 'sqlite',
			'database' => __DIR__.'/../database/production.sqlite',
			'prefix'   => '',
		),

		'owloo_owloo' => array(
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => 'owloo_owloo',
			'username'  => 'owloo_admin',
			'password'  => 'fblatamx244',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'owloo_twitter' => array(
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => 'owloo_twitter',
			'username'  => 'owloo_admin',
			'password'  => 'fblatamx244',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'owloo_userManagement' => array(
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => 'owloo_userManagement',
			'username'  => 'owloo_admin',
			'password'  => 'fblatamx244',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		)
	),

	'migrations' => 'migrations',

	'redis' => array(

		'cluster' => false,

		'default' => array(
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 0,
		),

	),

);