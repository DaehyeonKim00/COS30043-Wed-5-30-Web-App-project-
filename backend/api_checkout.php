<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  exit(0);
}

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
  case 'POST':
    // Place an order in a transaction: verify stock, create the order and its items, decrement stock, and clear the cart
    if (!$input || !isset($input['user_id']) || !isset($input['items'])) {
      echo json_encode(['error' => 'Invalid request payload.']);
      exit;
    }
    $user_id = (int) $input['user_id'];
    $total_price = (float) $input['total_price'];
    $items = $input['items'];

    if (!is_array($items) || count($items) === 0) {
      echo json_encode(['error' => 'Cart is empty.']);
      exit;
    }

    mysqli_query($conn, "START TRANSACTION");

    // Verify stock for every item before inserting anything
    foreach ($items as $item) {
      $pid = (int) $item['product_id'];
      $qty = (int) $item['quantity'];
      $stockCheck = mysqli_query($conn, "SELECT stock, name FROM products WHERE id = $pid");
      if (!$stockCheck || mysqli_num_rows($stockCheck) === 0) {
        mysqli_query($conn, "ROLLBACK");
        echo json_encode(['error' => 'Product not found (id=' . $pid . ').']);
        exit;
      }
      $row = mysqli_fetch_assoc($stockCheck);
      if ((int) $row['stock'] < $qty) {
        mysqli_query($conn, "ROLLBACK");
        echo json_encode(['error' => 'Not enough stock for ' . $row['name'] . '.']);
        exit;
      }
    }

    $orderResult = mysqli_query($conn,
      "INSERT INTO orders (user_id, total_price) VALUES ($user_id, $total_price)"
    );
    if (!$orderResult) {
      mysqli_query($conn, "ROLLBACK");
      echo json_encode(['error' => 'Order create failed: ' . mysqli_error($conn)]);
      exit;
    }
    $order_id = mysqli_insert_id($conn);

    foreach ($items as $item) {
      $product_id = (int) $item['product_id'];
      $quantity = (int) $item['quantity'];
      $price = (float) $item['price'];

      $itemResult = mysqli_query($conn,
        "INSERT INTO order_items (order_id, product_id, quantity, price)
         VALUES ($order_id, $product_id, $quantity, $price)"
      );
      if (!$itemResult) {
        mysqli_query($conn, "ROLLBACK");
        echo json_encode(['error' => 'Order item insert failed: ' . mysqli_error($conn)]);
        exit;
      }

      $stockResult = mysqli_query($conn,
        "UPDATE products SET stock = stock - $quantity WHERE id = $product_id"
      );
      if (!$stockResult) {
        mysqli_query($conn, "ROLLBACK");
        echo json_encode(['error' => 'Stock update failed: ' . mysqli_error($conn)]);
        exit;
      }
    }

    $cartResult = mysqli_query($conn,
      "DELETE FROM cart WHERE user_id = $user_id"
    );
    if (!$cartResult) {
      mysqli_query($conn, "ROLLBACK");
      echo json_encode(['error' => 'Cart clear failed: ' . mysqli_error($conn)]);
      exit;
    }

    mysqli_query($conn, "COMMIT");
    echo json_encode(['success' => true, 'order_id' => $order_id]);
    break;
}

mysqli_close($conn);
?>
