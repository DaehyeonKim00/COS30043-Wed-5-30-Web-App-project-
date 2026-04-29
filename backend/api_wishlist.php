<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (!isset($_GET['user_id'])) {
        echo json_encode(['error' => 'user_id is required']);
        exit;
    }

    $user_id = $_GET['user_id'];
    $result = mysqli_query($conn,
        "SELECT wishlist.id, products.name, products.price, products.image, products.category
         FROM wishlist
         JOIN products ON wishlist.product_id = products.id
         WHERE wishlist.user_id = $user_id"
    );
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($items);

} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $data['user_id'];
    $product_id = $data['product_id'];

    $check = mysqli_query($conn,
        "SELECT id FROM wishlist 
         WHERE user_id=$user_id AND product_id=$product_id"
    );

    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Already in wishlist']);
    } else {
        mysqli_query($conn,
            "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)"
        );
        echo json_encode(['success' => true]);
    }

} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        $id = $data['id'];
        $result = mysqli_query($conn, "DELETE FROM wishlist WHERE id=$id");

    } elseif (isset($data['user_id']) && isset($data['product_id'])) {
        $user_id = $data['user_id'];
        $product_id = $data['product_id'];
        $result = mysqli_query($conn,
            "DELETE FROM wishlist WHERE user_id=$user_id AND product_id=$product_id"
        );
    }

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}
?>