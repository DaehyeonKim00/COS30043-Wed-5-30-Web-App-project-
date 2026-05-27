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
    if (!isset($_GET['user_id'])) {
      echo json_encode(['error' => 'user_id is required']);
      exit;
    }
    $user_id = $_GET['user_id'];
    $result = mysqli_query($conn,
      "SELECT cart.id, cart.product_id, cart.quantity, products.name, products.price, products.image, products.stock
       FROM cart
       JOIN products ON cart.product_id = products.id
       WHERE cart.user_id = $user_id"
    );
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($items);
    break;
  case 'POST':
    $user_id = $input['user_id'];
    $product_id = $input['product_id'];
    $quantity = isset($input['quantity']) ? $input['quantity'] : 1;

    // Check stock limit
    $stockResult = mysqli_query($conn, "SELECT stock FROM products WHERE id=$product_id");
    $stockRow = mysqli_fetch_assoc($stockResult);
    $stock = (int)$stockRow['stock'];

    $check = mysqli_query($conn,
      "SELECT id, quantity FROM cart
       WHERE user_id=$user_id AND product_id=$product_id"
    );
    if (mysqli_num_rows($check) > 0) {
      $row = mysqli_fetch_assoc($check);
      if (isset($input['force_quantity']) && $input['force_quantity']) {
        $newQty = $quantity;
      } else {
        $newQty = $row['quantity'] + $quantity;
      }

      // Block if exceeds stock
      if ($newQty > $stock) {
        echo json_encode(['success' => false, 'error' => 'Exceeds available stock of ' . $stock]);
        exit;
      }

      mysqli_query($conn, "UPDATE cart SET quantity=$newQty WHERE id={$row['id']}");
      echo json_encode(['success' => true, 'updated' => true]);
    } else {
      // Block if exceeds stock
      if ($quantity > $stock) {
        echo json_encode(['success' => false, 'error' => 'Exceeds available stock of ' . $stock]);
        exit;
      }

      mysqli_query($conn,
        "INSERT INTO cart (user_id, product_id, quantity)
         VALUES ($user_id, $product_id, $quantity)"
      );
      echo json_encode(['success' => true, 'inserted' => true]);
    }
    break;
  case 'PUT':
    $id = $input['id'];
    $quantity = $input['quantity'];
    $result = mysqli_query($conn, "UPDATE cart SET quantity=$quantity WHERE id=$id");
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
    break;
  case 'DELETE':
    $id = $input['id'];
    $result = mysqli_query($conn, "DELETE FROM cart WHERE id=$id");
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
    break;
}

mysqli_close($conn);
?>
