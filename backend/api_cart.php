<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS, PUT");
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
        "SELECT cart.id, cart.product_id, cart.quantity, products.name, products.price, products.image, products.stock
        FROM cart 
        JOIN products ON cart.product_id = products.id
        WHERE cart.user_id = $user_id"
    );
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($items);

} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $data['user_id'];
    $product_id = $data['product_id'];
    $quantity = isset($data['quantity']) ? $data['quantity'] : 1;
    $check = mysqli_query($conn,
        "SELECT id, quantity FROM cart 
         WHERE user_id=$user_id AND product_id=$product_id"
    );

    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        if (isset($data['force_quantity']) && $data['force_quantity']) {
            $newQty = $quantity;
        } else {
            $newQty = $row['quantity'] + $quantity;
        }
            mysqli_query($conn, "UPDATE cart SET quantity=$newQty WHERE id={$row['id']}");
            echo json_encode(['success' => true, 'updated' => true]);
    } else {
        mysqli_query($conn,
            "INSERT INTO cart (user_id, product_id, quantity) 
             VALUES ($user_id, $product_id, $quantity)"
        );
        echo json_encode(['success' => true, 'inserted' => true]);
    }

} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $quantity = $data['quantity'];

    $result = mysqli_query($conn, "UPDATE cart SET quantity=$quantity WHERE id=$id");

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
} elseif ($method === 'DELETE') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $result = mysqli_query($conn, "DELETE FROM cart WHERE id=$id");
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}
?>