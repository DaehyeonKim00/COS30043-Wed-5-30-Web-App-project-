<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'), true);

$conn = mysqli_connect('localhost', 's104838522', '040900', 's104838522_db');
mysqli_set_charset($conn, 'utf8');

switch ($method) {
  case 'GET':
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $result = mysqli_query($conn, "SELECT wishlist.*, products.name, products.category, products.description, products.price, products.image, products.stock FROM wishlist JOIN products ON wishlist.product_id = products.id WHERE wishlist.user_id = '$user_id'");
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
    $check = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id = '$user_id' AND product_id = '$product_id'");
    if (mysqli_num_rows($check) > 0) {
      echo 0;
    } else {
      mysqli_query($conn, "INSERT INTO wishlist (user_id, product_id) VALUES ('$user_id', '$product_id')");
      echo mysqli_insert_id($conn);
    }
    break;
  case 'DELETE':
    if (isset($input['id'])) {
      $id = mysqli_real_escape_string($conn, $input['id']);
      mysqli_query($conn, "DELETE FROM wishlist WHERE id = '$id'");
    } else {
      $user_id = mysqli_real_escape_string($conn, $input['user_id']);
      $product_id = mysqli_real_escape_string($conn, $input['product_id']);
      mysqli_query($conn, "DELETE FROM wishlist WHERE user_id = '$user_id' AND product_id = '$product_id'");
    }
    echo mysqli_affected_rows($conn);
    break;
}

mysqli_close($conn);
?>
