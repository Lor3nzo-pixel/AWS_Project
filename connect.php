<?php
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'database';

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die('Connessione database fallita: ' . $conn->connect_error);
    }

    //DEBUG

    //if($conn) die("nice");
?>
