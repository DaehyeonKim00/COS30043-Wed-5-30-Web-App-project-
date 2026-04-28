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
    $result = mysqli_query($conn, "SELECT cart.*, products.name, products.price, products.image, products.stock FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = '$user_id'");
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
    $quantity = mysqli_real_escape_string($conn, $input['quantity']);
    $check = mysqli_query($conn, "SELECT id, quantity FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'");
    if (mysqli_num_rows($check) > 0) {
      $existingItem = mysqli_fetch_assoc($check);
      $newQuantity = (int)$existingItem['quantity'] + (int)$quantity;
      mysqli_query($conn, "UPDATE cart SET quantity = '$newQuantity' WHERE id = '" . $existingItem['id'] . "'");
    } else {
      mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')");
    }
    echo mysqli_affected_rows($conn);
    break;
  case 'DELETE':
    $id = mysqli_real_escape_string($conn, $input['id']);
    mysqli_query($conn, "DELETE FROM cart WHERE id = '$id'");
    echo mysqli_affected_rows($conn);
    break;
}

mysqli_close($conn);
?>
