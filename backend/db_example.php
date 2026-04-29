<?php
$host = 'host';
$dbname = 'dbname';
$username = 'username';
$password = 'password';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}
?>