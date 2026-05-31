<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  exit(0);
}

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
  case 'GET':
    // Fetch the profile (name, email, role) of a single user
    if (!isset($_GET['user_id'])) {
      echo json_encode(['error' => 'user_id is required']);
      exit;
    }
    $user_id = $_GET['user_id'];
    $result = mysqli_query($conn,
      "SELECT id, name, email, role FROM users WHERE id = $user_id"
    );
    if (mysqli_num_rows($result) === 0) {
      echo json_encode(['error' => 'User not found']);
      exit;
    }
    $user = mysqli_fetch_assoc($result);
    echo json_encode($user);
    break;
  case 'PUT':
    // Update the current user's name and email
    if (!isset($input['user_id']) || !isset($input['name']) || !isset($input['email'])) {
      echo json_encode(['error' => 'user_id, name and email are required']);
      exit;
    }
    $user_id = $input['user_id'];
    $name = mysqli_real_escape_string($conn, $input['name']);
    $email = mysqli_real_escape_string($conn, $input['email']);
    $result = mysqli_query($conn,
      "UPDATE users SET name='$name', email='$email' WHERE id=$user_id"
    );
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
    break;
}

mysqli_close($conn);
?>
