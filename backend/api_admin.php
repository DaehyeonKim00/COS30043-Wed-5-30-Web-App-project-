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

// POST - add product
if ($method === 'POST') {
    $name = mysqli_real_escape_string($conn, $data['name']);
    $category = mysqli_real_escape_string($conn, $data['category']);
    $description = mysqli_real_escape_string($conn, $data['description']);
    $price = mysqli_real_escape_string($conn, $data['price']);
    $image = mysqli_real_escape_string($conn, $data['image']);
    $stock = mysqli_real_escape_string($conn, $data['stock']);
    mysqli_query($conn, "INSERT INTO products (name, category, description, price, image, stock) VALUES ('$name', '$category', '$description', '$price', '$image', '$stock')");
    echo json_encode(['success' => true]);
}

// PUT - update product
if ($method === 'PUT') {
    $id = mysqli_real_escape_string($conn, $data['id']);
    $name = mysqli_real_escape_string($conn, $data['name']);
    $category = mysqli_real_escape_string($conn, $data['category']);
    $description = mysqli_real_escape_string($conn, $data['description']);
    $price = mysqli_real_escape_string($conn, $data['price']);
    $image = mysqli_real_escape_string($conn, $data['image']);
    $stock = mysqli_real_escape_string($conn, $data['stock']);
    mysqli_query($conn, "UPDATE products SET name='$name', category='$category', description='$description', price='$price', image='$image', stock='$stock' WHERE id='$id'");
    echo json_encode(['success' => true]);
}

// DELETE - delete product
if ($method === 'DELETE') {
    $id = mysqli_real_escape_string($conn, $data['id']);
    mysqli_query($conn, "DELETE FROM products WHERE id = '$id'");
    echo json_encode(['success' => true]);
}

mysqli_close($conn);
?>
