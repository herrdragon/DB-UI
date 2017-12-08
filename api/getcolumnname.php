<?php
header('Content-Type: application/json');
require_once "../include/connection.php";

$data = json_decode(file_get_contents('php://input'), true);
$table = $data[1]['name'];
$verb = $data[2]['name'];
if (!empty($table) && ($table == 'Book' || $table == 'Publisher' || $table == 'Author' || $table == 'Copy')) {
    if ($verb == 'Delete') {
        $query = $conn->prepare('DESCRIBE '.$table);
        $query->execute();
        $table = $query->fetch();
        echo json_encode($table);
    }
    else {
        $query = $conn->prepare('DESCRIBE '.$table);
        $query->execute();
        $table = $query->fetchAll();
        echo json_encode($table);
    }

}else
    die('{"result":"Error on name (empty)"}');
