<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: GET, PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0);
    }

    require 'db.php';

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
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

    } elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['user_id']) || !isset($data['name']) || !isset($data['email'])) {
            echo json_encode(['error' => 'user_id, name and email are required']);
            exit;
        }

        $user_id = $data['user_id'];
        $name = mysqli_real_escape_string($conn, $data['name']);
        $email = mysqli_real_escape_string($conn, $data['email']);

        $result = mysqli_query($conn,
            "UPDATE users SET name='$name', email='$email' WHERE id=$user_id"
        );

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => mysqli_error($conn)]);
        }
}
?>