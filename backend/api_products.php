<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  exit(0);
}

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
  case 'GET':
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
      $result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
      $product = mysqli_fetch_assoc($result);
      echo json_encode($product);
    } elseif (isset($_GET['recommend'])) {
      // Search for recommended products based on category and exclude current product (Advanced teammember tuan)
      $category = mysqli_real_escape_string($conn, $_GET['category']);
      $exclude = (int)$_GET['exclude'];
      $result = mysqli_query($conn,
        "SELECT * FROM products
         WHERE category = '$category'
         AND id != $exclude
         ORDER BY RAND()
         LIMIT 4"
      );
      $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
      echo json_encode($products);
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
    break;
  case 'POST':
    $name = $input['name'];
    $category = $input['category'];
    $description = $input['description'];
    $price = $input['price'];
    $image = $input['image'];
    $stock = $input['stock'];
    $result = mysqli_query($conn,
      "INSERT INTO products (name, category, description, price, image, stock)
       VALUES ('$name', '$category', '$description', '$price', '$image', '$stock')"
    );
    if ($result) {
      echo json_encode(['success' => true, 'id' => mysqli_insert_id($conn)]);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
    break;
  case 'PUT':
    $id = $input['id'];
    $name = $input['name'];
    $category = $input['category'];
    $description = $input['description'];
    $price = $input['price'];
    $image = $input['image'];
    $stock = $input['stock'];
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
    break;
  case 'DELETE':
    $id = $input['id'];
    $result = mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
    break;
}

mysqli_close($conn);
?>
