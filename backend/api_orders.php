<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    // Return a user's order history, newest first
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $result = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC");
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($orders);
    break;
}

mysqli_close($conn);
?>