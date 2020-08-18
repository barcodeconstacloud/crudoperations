<?php
include_once 'include/connection.php';
$createDataBase = new Connection();
$createDataBase -> createDatabase();
?>