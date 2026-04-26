<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$conn = mysqli_connect('localhost', 's104838522', '040900', 's104838522_db');

if (!$conn) {
    echo json_encode(['error' => mysqli_connect_error()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

// GET - fetch reviews
if ($method === 'GET' && isset($_GET['product_id'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['product_id']);
    $result = mysqli_query($conn, "SELECT reviews.*, users.name FROM reviews JOIN users ON reviews.user_id = users.id WHERE reviews.product_id = '$product_id'");

    if (!$result) {
        echo json_encode(['error' => mysqli_error($conn)]);
        mysqli_close($conn);
        exit();
    }

    $reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($reviews);
}

// POST - add review
if ($method === 'POST') {
    $user_id = mysqli_real_escape_string($conn, $data['user_id']);
    $product_id = mysqli_real_escape_string($conn, $data['product_id']);
    $rating = mysqli_real_escape_string($conn, $data['rating']);
    $comment = mysqli_real_escape_string($conn, $data['comment']);
    $result = mysqli_query($conn, "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES ('$user_id', '$product_id', '$rating', '$comment')");

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}

// PUT - update review
if ($method === 'PUT') {
    $id = mysqli_real_escape_string($conn, $data['id']);
    $rating = mysqli_real_escape_string($conn, $data['rating']);
    $comment = mysqli_real_escape_string($conn, $data['comment']);
    $result = mysqli_query($conn, "UPDATE reviews SET rating = '$rating', comment = '$comment' WHERE id = '$id'");

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}

// DELETE - delete review
if ($method === 'DELETE') {
    $id = mysqli_real_escape_string($conn, $data['id']);
    $result = mysqli_query($conn, "DELETE FROM reviews WHERE id = '$id'");

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}

mysqli_close($conn);
?>
