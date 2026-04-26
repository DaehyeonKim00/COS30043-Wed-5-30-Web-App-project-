<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT');
header('Access-Control-Allow-Headers: Content-Type');

$conn = mysqli_connect('localhost', 's104838522', '040900', 's104838522_db');

if (!$conn) {
    echo json_encode(['error' => mysqli_connect_error()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

// GET - fetch user info
if ($method === 'GET' && isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $result = mysqli_query($conn, "SELECT id, name, email, role FROM users WHERE id = '$user_id'");

    if (!$result) {
        echo json_encode(['error' => mysqli_error($conn)]);
        mysqli_close($conn);
        exit();
    }

    $user = mysqli_fetch_assoc($result);
    echo json_encode($user);
}

// PUT - update user info
if ($method === 'PUT') {
    $user_id = mysqli_real_escape_string($conn, $data['user_id']);
    $name = mysqli_real_escape_string($conn, $data['name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $result = mysqli_query($conn, "UPDATE users SET name = '$name', email = '$email' WHERE id = '$user_id'");

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}

mysqli_close($conn);
?>
