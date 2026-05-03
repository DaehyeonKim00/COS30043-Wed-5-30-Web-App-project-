<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
mysqli_set_charset($conn, 'utf8');

switch ($method) {
  case 'POST':
    header('Content-Type: application/json');
    $user_id = mysqli_real_escape_string($conn, $input['user_id']);
    $total_price = mysqli_real_escape_string($conn, $input['total_price']);

    mysqli_begin_transaction($conn);

    $orderResult = mysqli_query($conn, "INSERT INTO orders (user_id, total_price) VALUES ('$user_id', '$total_price')");
    if (!$orderResult) {
      mysqli_rollback($conn);
      echo json_encode(['error' => mysqli_error($conn)]);
      mysqli_close($conn);
      exit();
    }

    $order_id = mysqli_insert_id($conn);

    foreach ($input['items'] as $item) {
      $product_id = mysqli_real_escape_string($conn, $item['product_id']);
      $quantity = mysqli_real_escape_string($conn, $item['quantity']);
      $price = mysqli_real_escape_string($conn, $item['price']);
      $itemResult = mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$quantity', '$price')");
      if (!$itemResult) {
        mysqli_rollback($conn);
        echo json_encode(['error' => mysqli_error($conn)]);
        mysqli_close($conn);
        exit();
      }
    }

    $cartResult = mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");
    if (!$cartResult) {
      mysqli_rollback($conn);
      echo json_encode(['error' => mysqli_error($conn)]);
      mysqli_close($conn);
      exit();
    }

    mysqli_commit($conn);
    echo json_encode(['success' => true, 'order_id' => $order_id]);
    break;
}

mysqli_close($conn);
?>
