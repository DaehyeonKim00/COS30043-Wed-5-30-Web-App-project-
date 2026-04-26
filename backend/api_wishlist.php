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

// GET - fetch wishlist items
if ($method === 'GET' && isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $result = mysqli_query($conn, "SELECT wishlist.*, products.name, products.category, products.description, products.price, products.image, products.stock FROM wishlist JOIN products ON wishlist.product_id = products.id WHERE wishlist.user_id = '$user_id'");

    if (!$result) {
        echo json_encode(['error' => mysqli_error($conn)]);
        mysqli_close($conn);
        exit();
    }

    $wishlist = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($wishlist);
}

// POST - add to wishlist
if ($method === 'POST') {
    $user_id = mysqli_real_escape_string($conn, $data['user_id']);
    $product_id = mysqli_real_escape_string($conn, $data['product_id']);
    $check = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id = '$user_id' AND product_id = '$product_id'");

    if (!$check) {
        echo json_encode(['error' => mysqli_error($conn)]);
        mysqli_close($conn);
        exit();
    }

    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['success' => true, 'message' => 'Item already in wishlist']);
    } else {
        $result = mysqli_query($conn, "INSERT INTO wishlist (user_id, product_id) VALUES ('$user_id', '$product_id')");

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => mysqli_error($conn)]);
        }
    }
}

// DELETE - remove from wishlist
if ($method === 'DELETE') {
    if (isset($data['id'])) {
        $id = mysqli_real_escape_string($conn, $data['id']);
        $result = mysqli_query($conn, "DELETE FROM wishlist WHERE id = '$id'");
    } else {
        $user_id = mysqli_real_escape_string($conn, $data['user_id']);
        $product_id = mysqli_real_escape_string($conn, $data['product_id']);
        $result = mysqli_query($conn, "DELETE FROM wishlist WHERE user_id = '$user_id' AND product_id = '$product_id'");
    }

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}

mysqli_close($conn);
?>
