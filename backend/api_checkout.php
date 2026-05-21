<?php
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json");
  header("Access-Control-Allow-Methods: POST, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");

  // Catch fatal errors and convert them to JSON so the client always sees a useful message.
  ini_set('display_errors', '0');
  error_reporting(E_ALL);

  register_shutdown_function(function () {
      $err = error_get_last();
      if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
          if (!headers_sent()) {
              http_response_code(500);
              header('Content-Type: application/json');
          }
          echo json_encode(['error' => 'PHP fatal: ' . $err['message'] . ' at ' . $err['file'] . ':' . $err['line']]);
      }
  });

  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      exit(0);
  }

  require 'db.php';

  try {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
          echo json_encode(['error' => 'Method not allowed.']);
          exit;
      }

      $data = json_decode(file_get_contents("php://input"), true);

      if (!$data || !isset($data['user_id']) || !isset($data['items'])) {
          echo json_encode(['error' => 'Invalid request payload.']);
          exit;
      }

      $user_id = (int) $data['user_id'];
      $total_price = (float) $data['total_price'];
      $items = $data['items'];

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

  } catch (Throwable $e) {
      if (isset($conn)) {
          @mysqli_query($conn, "ROLLBACK");
      }
      if (!headers_sent()) {
          http_response_code(500);
      }
      echo json_encode([
          'error' => 'Exception: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine()
      ]);
  }

  if (isset($conn)) {
      mysqli_close($conn);
  }
?>
