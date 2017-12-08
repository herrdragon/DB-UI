<?php
header('Content-Type: application/json');
require_once "../include/connection.php";

$data = json_decode(file_get_contents('php://input'), true);
$table = $data['name'];
if (!empty($table) && ($table == 'Book' || $table == 'Publisher' || $table == 'Author' || $table == 'Copy')) {
    $query = $conn->prepare('SELECT * FROM '.$table.' ');
    $query->execute();
    $table = $query->fetchAll();
    echo json_encode($table);
}else
    die('{"result":"Error on name (empty)"}');
