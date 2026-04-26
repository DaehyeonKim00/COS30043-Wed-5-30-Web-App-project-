<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$conn = mysqli_connect('localhost', 's104838522', '040900', 's104838522_db');

if (!$conn) {
    echo json_encode(['error' => mysqli_connect_error()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

// GET - fetch cart items
if ($method === 'GET' && isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $result = mysqli_query($conn, "SELECT cart.*, products.name, products.price, products.image, products.stock FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = '$user_id'");

    if (!$result) {
        echo json_encode(['error' => mysqli_error($conn)]);
        mysqli_close($conn);
        exit();
    }

    $cart = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($cart);
}

// POST - add to cart
if ($method === 'POST') {
    $user_id = mysqli_real_escape_string($conn, $data['user_id']);
    $product_id = mysqli_real_escape_string($conn, $data['product_id']);
    $quantity = mysqli_real_escape_string($conn, $data['quantity']);
    $check = mysqli_query($conn, "SELECT id, quantity FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'");

    if (!$check) {
        echo json_encode(['error' => mysqli_error($conn)]);
        mysqli_close($conn);
        exit();
    }

    if (mysqli_num_rows($check) > 0) {
        $existingItem = mysqli_fetch_assoc($check);
        $newQuantity = (int)$existingItem['quantity'] + (int)$quantity;
        $result = mysqli_query($conn, "UPDATE cart SET quantity = '$newQuantity' WHERE id = '" . $existingItem['id'] . "'");
    } else {
        $result = mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')");
    }

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}

// DELETE - remove from cart
if ($method === 'DELETE') {
    $id = mysqli_real_escape_string($conn, $data['id']);
    $result = mysqli_query($conn, "DELETE FROM cart WHERE id = '$id'");

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}

mysqli_close($conn);
?>
