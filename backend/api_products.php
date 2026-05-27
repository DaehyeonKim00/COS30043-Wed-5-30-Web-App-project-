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
    } elseif (isset($_GET['recommend'])) { // e.g. ?recommend=1&exclude=5&category=Electronics (advanced feature: member tuan)
    $current_id = (int)$_GET['exclude'];
    $category = mysqli_real_escape_string($conn, $_GET['category']);

    // Step 1: Get products from same orders
    $result = mysqli_query($conn,
        "SELECT DISTINCT p.* 
         FROM products p
         JOIN order_items oi ON p.id = oi.product_id
         WHERE oi.order_id IN (
             SELECT order_id FROM order_items WHERE product_id = $current_id
         )
         AND p.id != $current_id
         LIMIT 4"
    );
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Step 2: If less than 4, fill with random from same category
    if (count($products) < 4) {
        $existingIds = array();
        foreach ($products as $p) {
          $existingIds[] = $p['id'];
        }
        $existingIds[] = $current_id;
        $excludeIds = implode(',', $existingIds);

        $needed = 4 - count($products);
        $fallback = mysqli_query($conn,
            "SELECT * FROM products 
             WHERE category = '$category' 
             AND id NOT IN ($excludeIds)
             ORDER BY RAND()
             LIMIT $needed"
        );
        $fallbackProducts = mysqli_fetch_all($fallback, MYSQLI_ASSOC);
        $products = array_merge($products, $fallbackProducts);
    }
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
