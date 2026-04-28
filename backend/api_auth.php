<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'), true);

$conn = mysqli_connect('localhost', 's104838522', '040900', 's104838522_db');
mysqli_set_charset($conn, 'utf8');

switch ($method) {
  case 'POST':
    header('Content-Type: application/json');
    if (isset($input['action']) && $input['action'] === 'login') {
      $email = mysqli_real_escape_string($conn, $input['email']);
      $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
      $user = mysqli_fetch_assoc($result);
      if ($user && password_verify($input['password'], $user['password'])) {
        unset($user['password']);
        echo json_encode($user);
      } else {
        echo json_encode(null);
      }
    } else if (isset($input['action']) && $input['action'] === 'register') {
      $name = mysqli_real_escape_string($conn, $input['name']);
      $email = mysqli_real_escape_string($conn, $input['email']);
      $password = password_hash($input['password'], PASSWORD_DEFAULT);
      $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
      if (mysqli_num_rows($check) > 0) {
        echo json_encode(['error' => 'Email already exists']);
      } else {
        mysqli_query($conn, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
        echo mysqli_insert_id($conn);
      }
    }
    break;
}

mysqli_close($conn);
?>
