<?php
$server = "localhost";
$username = "username";
$pwd = "";
$dbname = "prabhuram";

$db_connection = new mysqli($server, $username, $pwd, $dbname);

if ($db_connection->connect_error) {
    die("Connection failed: " . $db_connection->connect_error);
}


