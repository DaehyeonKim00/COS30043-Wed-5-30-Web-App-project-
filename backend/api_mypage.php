<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT');
header('Access-Control-Allow-Headers: Content-Type');

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
mysqli_set_charset($conn, 'utf8');

switch ($method) {
  case 'GET':
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $result = mysqli_query($conn, "SELECT id, name, email, role FROM users WHERE id = '$user_id'");
    header('Content-Type: application/json');
    echo '[';
    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
      echo ($i > 0 ? ',' : '') . json_encode(mysqli_fetch_object($result));
    }
    echo ']';
    break;
  case 'PUT':
    $user_id = mysqli_real_escape_string($conn, $input['user_id']);
    $name = mysqli_real_escape_string($conn, $input['name']);
    $email = mysqli_real_escape_string($conn, $input['email']);
    mysqli_query($conn, "UPDATE users SET name = '$name', email = '$email' WHERE id = '$user_id'");
    echo mysqli_affected_rows($conn);
    break;
}

mysqli_close($conn);
?>
