<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conn = mysqli_connect('localhost', 's104838522', '040900', 'swinmusic_db');

if (!$conn) {
    echo json_encode(['error' => mysqli_connect_error()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

// POST - login
if ($method === 'POST' && isset($data['action']) && $data['action'] === 'login') {
    $email = mysqli_real_escape_string($conn, $data['email']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($data['password'], $user['password'])) {
        unset($user['password']);
        echo json_encode($user);
    } else {
        echo json_encode(null);
    }
}

// POST - register
if ($method === 'POST' && isset($data['action']) && $data['action'] === 'register') {
    $name = mysqli_real_escape_string($conn, $data['name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['error' => 'Email already exists']);
    } else {
        mysqli_query($conn, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
        echo json_encode(['success' => true]);
    }
}

mysqli_close($conn);
?>
