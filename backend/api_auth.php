<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
  case 'POST':
    // Handle authentication: log in an existing user or register a new account based on the 'action' field
    if (isset($input['action']) && $input['action'] === 'login') {
      $email = mysqli_real_escape_string($conn, $input['email']);
      $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
      $user = mysqli_fetch_assoc($result);
      if ($user && md5($input['password']) === $user['password']) { // Replace 
        unset($user['password']);
        echo json_encode($user);
      } else {
        echo json_encode(null);
      }
    } else if (isset($input['action']) && $input['action'] === 'register') {
      $name = mysqli_real_escape_string($conn, $input['name']);
      $email = mysqli_real_escape_string($conn, $input['email']);
      $password = md5($input['password']); // Replace password_hash with md5 for compatibility
      $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
      if (mysqli_num_rows($check) > 0) {
        echo json_encode(['error' => 'Email already exists']);
      } else {
        $result = mysqli_query($conn, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
        if ($result) {
          echo json_encode(['success' => true, 'id' => mysqli_insert_id($conn)]);
        } else {
          echo json_encode(['error' => mysqli_error($conn)]);
        }
      }
    }
    break;
}

mysqli_close($conn);
?>
