<?php
	include('config.php');
	$conexion = mysql_connect($config['sql_host'], $config['sql_user'], $config['sql_pass']) or die(mysql_error());
	mysql_select_db($config['sql_db'], $conexion) or die(mysql_error());
	mysql_query("SET NAMES 'utf8'");