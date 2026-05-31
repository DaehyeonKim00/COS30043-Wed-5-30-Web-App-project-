<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  exit(0);
}

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
  case 'GET':
    // Return all wishlist items for a user, joined with product details
    if (!isset($_GET['user_id'])) {
      echo json_encode(['error' => 'user_id is required']);
      exit;
    }
    $user_id = $_GET['user_id'];
    $result = mysqli_query($conn,
      "SELECT wishlist.id, products.id as product_id, products.name, products.price, products.image, products.category
       FROM wishlist
       JOIN products ON wishlist.product_id = products.id
       WHERE wishlist.user_id = $user_id"
    );
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($items);
    break;
  case 'POST':
    // Add a product to the wishlist, skipping if it is already present
    $user_id = $input['user_id'];
    $product_id = $input['product_id'];
    $check = mysqli_query($conn,
      "SELECT id FROM wishlist
       WHERE user_id=$user_id AND product_id=$product_id"
    );
    if (mysqli_num_rows($check) > 0) {
      echo json_encode(['success' => false, 'message' => 'Already in wishlist']);
    } else {
      mysqli_query($conn,
        "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)"
      );
      echo json_encode(['success' => true]);
    }
    break;
  case 'DELETE':
    // Remove a wishlist item, either by its id or by user_id + product_id
    if (isset($input['id'])) {
      $id = $input['id'];
      $result = mysqli_query($conn, "DELETE FROM wishlist WHERE id=$id");
    } elseif (isset($input['user_id']) && isset($input['product_id'])) {
      $user_id = $input['user_id'];
      $product_id = $input['product_id'];
      $result = mysqli_query($conn,
        "DELETE FROM wishlist WHERE user_id=$user_id AND product_id=$product_id"
      );
    }
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
    break;
}

mysqli_close($conn);
?>
