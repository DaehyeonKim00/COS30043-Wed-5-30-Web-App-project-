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

// GET - fetch cart items
if ($method === 'GET' && isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $result = mysqli_query($conn, "SELECT cart.*, products.name, products.price, products.image FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = '$user_id'");
    $cart = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($cart);
}

// POST - add to cart
if ($method === 'POST') {
    $user_id = mysqli_real_escape_string($conn, $data['user_id']);
    $product_id = mysqli_real_escape_string($conn, $data['product_id']);
    $quantity = mysqli_real_escape_string($conn, $data['quantity']);
    mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')");
    echo json_encode(['success' => true]);
}

// DELETE - remove from cart
if ($method === 'DELETE') {
    $id = mysqli_real_escape_string($conn, $data['id']);
    mysqli_query($conn, "DELETE FROM cart WHERE id = '$id'");
    echo json_encode(['success' => true]);
}

mysqli_close($conn);
?>
