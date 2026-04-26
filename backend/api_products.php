<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$conn = mysqli_connect('localhost', 's104838522', '040900', 's104838522_db');

if (!$conn) {
    echo json_encode(['error' => mysqli_connect_error()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

// GET - fetch all products
if ($method === 'GET') {
    // Search by keyword
    if (isset($_GET['search'])) {
        $search = '%' . mysqli_real_escape_string($conn, $_GET['search']) . '%';
        $result = mysqli_query($conn, "SELECT * FROM products WHERE name LIKE '$search' OR category LIKE '$search'");
    }
    // Filter by category
    else if (isset($_GET['category'])) {
        $category = mysqli_real_escape_string($conn, $_GET['category']);
        $result = mysqli_query($conn, "SELECT * FROM products WHERE category = '$category'");
    }
    // Get single product by id
    else if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $result = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
    }
    // Get all products
    else {
        $result = mysqli_query($conn, 'SELECT * FROM products');
    }

    if (!$result) {
        echo json_encode(['error' => mysqli_error($conn)]);
        mysqli_close($conn);
        exit();
    }

    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($products);
}

mysqli_close($conn);
?>
