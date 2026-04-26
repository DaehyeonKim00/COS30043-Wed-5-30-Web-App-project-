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

// POST - place order
if ($method === 'POST') {
    $user_id = mysqli_real_escape_string($conn, $data['user_id']);
    $total_price = mysqli_real_escape_string($conn, $data['total_price']);

    mysqli_query($conn, "INSERT INTO orders (user_id, total_price) VALUES ('$user_id', '$total_price')");
    $order_id = mysqli_insert_id($conn);

    foreach ($data['items'] as $item) {
        $product_id = mysqli_real_escape_string($conn, $item['product_id']);
        $quantity = mysqli_real_escape_string($conn, $item['quantity']);
        $price = mysqli_real_escape_string($conn, $item['price']);
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$quantity', '$price')");
    }

    mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");
    echo json_encode(['success' => true, 'order_id' => $order_id]);
}

mysqli_close($conn);
?>
