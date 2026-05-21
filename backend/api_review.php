<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
mysqli_set_charset($conn, 'utf8');

switch ($method) {
  case 'GET':
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
    header('Content-Type: application/json');
    echo '[';
    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
      echo ($i > 0 ? ',' : '') . json_encode(mysqli_fetch_object($result));
    }
    echo ']';
    break;
  case 'POST':
    $user_id = mysqli_real_escape_string($conn, $input['user_id']);
    $product_id = mysqli_real_escape_string($conn, $input['product_id']);
    $rating = mysqli_real_escape_string($conn, $input['rating']);
    $comment = mysqli_real_escape_string($conn, $input['comment']);
    mysqli_query($conn, "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES ('$user_id', '$product_id', '$rating', '$comment')");
    echo mysqli_insert_id($conn);
    break;
  case 'PUT':
    $id = mysqli_real_escape_string($conn, $input['id']);
    $rating = mysqli_real_escape_string($conn, $input['rating']);
    $comment = mysqli_real_escape_string($conn, $input['comment']);
    mysqli_query($conn, "UPDATE reviews SET rating = '$rating', comment = '$comment' WHERE id = '$id'");
    echo mysqli_affected_rows($conn);
    break;
  case 'DELETE':
    $id = mysqli_real_escape_string($conn, $input['id']);
    mysqli_query($conn, "DELETE FROM reviews WHERE id = '$id'");
    echo mysqli_affected_rows($conn);
    break;
}

mysqli_close($conn);
?>
