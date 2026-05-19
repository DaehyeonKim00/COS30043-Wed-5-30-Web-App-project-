<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json');

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $result = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC");
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($orders);
    break;
}

mysqli_close($conn);
?>