<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'), true);

$conn = mysqli_connect('localhost', 's104838522', '040900', 's104838522_db');
mysqli_set_charset($conn, 'utf8');

switch ($method) {
  case 'GET':
    if (isset($_GET['search'])) {
      $search = '%' . mysqli_real_escape_string($conn, $_GET['search']) . '%';
      $result = mysqli_query($conn, "SELECT * FROM products WHERE name LIKE '$search' OR category LIKE '$search'");
    } else if (isset($_GET['category'])) {
      $category = mysqli_real_escape_string($conn, $_GET['category']);
      $result = mysqli_query($conn, "SELECT * FROM products WHERE category = '$category'");
    } else if (isset($_GET['id'])) {
      $id = mysqli_real_escape_string($conn, $_GET['id']);
      $result = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
    } else {
      $result = mysqli_query($conn, "SELECT * FROM products");
    }
    header('Content-Type: application/json');
    echo '[';
    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
      echo ($i > 0 ? ',' : '') . json_encode(mysqli_fetch_object($result));
    }
    echo ']';
    break;
}

mysqli_close($conn);
?>
