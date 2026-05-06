<?php
$host = 'feenix-mariadb.swin.edu.au';
$dbname = 's104838522_db';
$username = 's104838522';
$password = '040900';

$conn = mysqli_connect($host, $username, $password, $dbname);



if (!$conn) {
    echo json_encode(["error" => "Connection failed: " . mysqli_connect_error()]);
    exit();
}

?>