<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
        $product = mysqli_fetch_assoc($result);
        echo json_encode($product);

    } elseif (isset($_GET['category'])) {
        $category = $_GET['category'];
        $result = mysqli_query($conn, "SELECT * FROM products WHERE category='$category'");
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode($products);

    } elseif (isset($_GET['search'])) {
        $search = $_GET['search'];
        $result = mysqli_query($conn, "SELECT * FROM products WHERE name LIKE '%$search%' OR category LIKE '%$search%'");
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode($products);

    } else {
        $result = mysqli_query($conn, "SELECT * FROM products");
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode($products);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $name = $data['name'];
    $category = $data['category'];
    $description = $data['description'];
    $price = $data['price'];
    $image = $data['image'];
    $stock = $data['stock'];

    $result = mysqli_query($conn, 
        "INSERT INTO products (name, category, description, price, image, stock) 
         VALUES ('$name', '$category', '$description', '$price', '$image', '$stock')"
    );

    if ($result) {
        echo json_encode(['success' => true, 'id' => mysqli_insert_id($conn)]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }

} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['id'];
    $name = $data['name'];
    $category = $data['category'];
    $description = $data['description'];
    $price = $data['price'];
    $image = $data['image'];
    $stock = $data['stock'];

    $result = mysqli_query($conn,
        "UPDATE products SET 
            name='$name', category='$category', description='$description',
            price='$price', image='$image', stock='$stock'
         WHERE id=$id"
    );

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }

} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $result = mysqli_query($conn, "DELETE FROM products WHERE id=$id");

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}
?>