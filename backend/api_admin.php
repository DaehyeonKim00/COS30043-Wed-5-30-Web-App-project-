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
    // Return all products ordered by newest first (admin product list)
    $result = mysqli_query($conn, "SELECT * FROM `$table` ORDER BY created_at DESC");
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($products);
    break;
  case 'POST':
    // Create a new product from the submitted fields
    $result = mysqli_query($conn, "INSERT INTO `$table` SET $set");
    if ($result) {
      echo json_encode(['success' => true, 'id' => mysqli_insert_id($conn)]);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
    break;
  case 'PUT':
    // Update an existing product identified by its id
    $id = mysqli_real_escape_string($conn, $input['id']);
    $result = mysqli_query($conn, "UPDATE `$table` SET $set WHERE `id`='$id'");
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
    break;
  case 'DELETE':
    // Delete a product by its id
    $id = mysqli_real_escape_string($conn, $input['id']);
    $result = mysqli_query($conn, "DELETE FROM `$table` WHERE `id`='$id'");
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['error' => mysqli_error($conn)]);
    }
    break;
}

mysqli_close($conn);
?>
