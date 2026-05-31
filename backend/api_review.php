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
    // Return reviews (with user and product info), filtered by product_id when provided
    // If product_id is provided, filter by it; otherwise return all reviews.
    // Join with products to include the product name for "all reviews" view.
    if (isset($_GET['product_id']) && $_GET['product_id'] !== '') {
      $product_id = mysqli_real_escape_string($conn, $_GET['product_id']);
      $sql = "SELECT reviews.*, users.name, products.name AS product_name, products.image AS product_image
              FROM reviews
              JOIN users ON reviews.user_id = users.id
              JOIN products ON reviews.product_id = products.id
              WHERE reviews.product_id = '$product_id'
              ORDER BY reviews.created_at DESC";
    } else {
      $sql = "SELECT reviews.*, users.name, products.name AS product_name, products.image AS product_image
              FROM reviews
              JOIN users ON reviews.user_id = users.id
              JOIN products ON reviews.product_id = products.id
              ORDER BY reviews.created_at DESC";
    }
    $result = mysqli_query($conn, $sql);
    $reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($reviews);
    break;
  case 'POST':
    // Create a new review (rating and comment) for a product
    $user_id = mysqli_real_escape_string($conn, $input['user_id']);
    $product_id = mysqli_real_escape_string($conn, $input['product_id']);
    $rating = mysqli_real_escape_string($conn, $input['rating']);
    $comment = mysqli_real_escape_string($conn, $input['comment']);
    $result = mysqli_query($conn, "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES ('$user_id', '$product_id', '$rating', '$comment')");
    if ($result) {
      echo json_encode(['success' => true, 'id' => mysqli_insert_id($conn)]);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
    break;
  case 'PUT':
    // Update an existing review's rating and comment
    $id = mysqli_real_escape_string($conn, $input['id']);
    $rating = mysqli_real_escape_string($conn, $input['rating']);
    $comment = mysqli_real_escape_string($conn, $input['comment']);
    $result = mysqli_query($conn, "UPDATE reviews SET rating = '$rating', comment = '$comment' WHERE id = '$id'");
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
    break;
  case 'DELETE':
    // Delete a review by its id
    $id = mysqli_real_escape_string($conn, $input['id']);
    $result = mysqli_query($conn, "DELETE FROM reviews WHERE id = '$id'");
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
    break;
}

mysqli_close($conn);
?>
