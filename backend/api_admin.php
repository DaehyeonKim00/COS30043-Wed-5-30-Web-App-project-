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

// GET - fetch all products for admin page
if ($method === 'GET') {
    $result = mysqli_query($conn, "SELECT * FROM products ORDER BY created_at DESC");

    if (!$result) {
        echo json_encode(['error' => mysqli_error($conn)]);
        mysqli_close($conn);
        exit();
    }

    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($products);
}

// POST - add product
if ($method === 'POST') {
    $name = mysqli_real_escape_string($conn, $data['name']);
    $category = mysqli_real_escape_string($conn, $data['category']);
    $description = mysqli_real_escape_string($conn, $data['description']);
    $price = mysqli_real_escape_string($conn, $data['price']);
    $image = mysqli_real_escape_string($conn, $data['image']);
    $stock = mysqli_real_escape_string($conn, $data['stock']);
    $result = mysqli_query($conn, "INSERT INTO products (name, category, description, price, image, stock) VALUES ('$name', '$category', '$description', '$price', '$image', '$stock')");

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
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
    $result = mysqli_query($conn, "UPDATE products SET name='$name', category='$category', description='$description', price='$price', image='$image', stock='$stock' WHERE id='$id'");

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}

// DELETE - delete product
if ($method === 'DELETE') {
    $id = mysqli_real_escape_string($conn, $data['id']);
    $result = mysqli_query($conn, "DELETE FROM products WHERE id = '$id'");

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}

mysqli_close($conn);
?>
