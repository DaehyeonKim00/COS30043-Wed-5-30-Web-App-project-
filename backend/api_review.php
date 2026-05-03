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
    $product_id = mysqli_real_escape_string($conn, $_GET['product_id']);
    $result = mysqli_query($conn, "SELECT reviews.*, users.name FROM reviews JOIN users ON reviews.user_id = users.id WHERE reviews.product_id = '$product_id'");
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
