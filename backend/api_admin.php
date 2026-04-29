<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'), true);

$conn = mysqli_connect('localhost', 's104838522', '040900', 's104838522_db');
mysqli_set_charset($conn, 'utf8');

$table = 'products';

if (isset($input)) {
  $columns = preg_replace('/[^a-z0-9_]+/i', '', array_keys($input));
  $values = array_map(function ($value) use ($conn) {
    if ($value === null) return null;
    return mysqli_real_escape_string($conn, (string)$value);
  }, array_values($input));

  $set = '';
  for ($i = 0; $i < count($columns); $i++) {
    $set .= ($i > 0 ? ',' : '') . '`' . $columns[$i] . '`=';
    $set .= ($values[$i] === null ? 'NULL' : '"' . $values[$i] . '"');
  }
}

switch ($method) {
  case 'GET':
    $result = mysqli_query($conn, "SELECT * FROM `$table` ORDER BY created_at DESC");
    header('Content-Type: application/json');
    echo '[';
    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
      echo ($i > 0 ? ',' : '') . json_encode(mysqli_fetch_object($result));
    }
    echo ']';
    break;
  case 'POST':
    mysqli_query($conn, "INSERT INTO `$table` SET $set");
    echo mysqli_insert_id($conn);
    break;
  case 'PUT':
    $id = mysqli_real_escape_string($conn, $input['id']);
    mysqli_query($conn, "UPDATE `$table` SET $set WHERE `id`='$id'");
    echo mysqli_affected_rows($conn);
    break;
  case 'DELETE':
    $id = mysqli_real_escape_string($conn, $input['id']);
    mysqli_query($conn, "DELETE FROM `$table` WHERE `id`='$id'");
    echo mysqli_affected_rows($conn);
    break;
}

mysqli_close($conn);
?>
