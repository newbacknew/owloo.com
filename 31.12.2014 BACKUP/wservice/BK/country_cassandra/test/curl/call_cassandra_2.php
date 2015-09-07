<?php

    require_once('../../../config.php');
    
    $num = $_POST['num'];
    $date = date('Ymd');
    $db_handle = new PDO("cassandra:host=".CASSANDRA_NODE_IP.";port=9160;cqlversion=3.0.0", CASSANDRA_DB_USER, CASSANDRA_DB_PASS);
    $stmt = $db_handle->prepare("INSERT INTO owloo_test.test(num, fecha, date) VALUES($num, $date, dateof(NOW()));");
    $stmt->execute();