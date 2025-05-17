<?php
session_start();
include '../includes/database.php';

// Debug helper
function dd($value)
{
  echo "<pre>", print_r($value, true), "</pre>";
  die();
}

function executeQuery($sql, $data)
{
    global $connect;
    $stmt = $connect->prepare($sql);

    $types = '';
    $values = [];

    foreach ($data as $value) {
        if (is_int($value)) {
            $types .= 'i';
        } elseif (is_double($value)) {
            $types .= 'd';
        } else {
            $types .= 's';
        }
        $values[] = $value;
    }

    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    return $stmt;
}


function selectAll($table, $conditions = [])
{
    global $connect;
    $sql = "SELECT * FROM $table";

    if (!empty($conditions)) {
        $sql .= " WHERE ";
        $sql .= implode(" AND ", array_map(fn($key) => "$key = ?", array_keys($conditions)));
    }

    $stmt = executeQuery($sql, $conditions);
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


function selectOne($table, $conditions)
{
  $records = selectAll($table, $conditions);
  return $records ? $records[0] : null;
}

function create($table, $data)
{
  $keys = array_keys($data);
  $fields = implode(', ', $keys);
  $placeholders = implode(', ', array_fill(0, count($keys), '?'));

  $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
  $stmt = executeQuery($sql, $data);
  return $stmt->insert_id;
}

function update($table, $id, $data)
{
  $setStr = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
  $sql = "UPDATE $table SET $setStr WHERE id = ?";
  $data['id'] = $id;
  $stmt = executeQuery($sql, $data);
  return $stmt->affected_rows;
}

function delete($table, $id)
{
  $sql = "DELETE FROM $table WHERE id = ?";
  $stmt = executeQuery($sql, [$id]);
  return $stmt->affected_rows;
}

function getTotalCategories($connect)
{
  $sql = "SELECT COUNT(*) AS total FROM categories";
  $result = $connect->query($sql);
  return $result->fetch_assoc()['total'] ?? 0;
}

function getTotalProducts($connect)
{
  $sql = "SELECT COUNT(*) AS total FROM products";
  $result = $connect->query($sql);
  return $result->fetch_assoc()['total'] ?? 0;
}

function getTotalCustomers($connect)
{
  $sql = "SELECT COUNT(*) AS total FROM customers";
  $result = $connect->query($sql);
  return $result->fetch_assoc()['total'] ?? 0;
}

//  product
if (isset($_SESSION['message'])) {
  echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
  unset($_SESSION['message']);
}

$sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id";
$stmt = $connect->prepare($sql);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>