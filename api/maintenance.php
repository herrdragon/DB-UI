<?php
error_reporting(E_ALL); ini_set('display_errors', 1);

header('Content-Type: application/json');
require_once "../include/connection.php";

// Getting Request
$verb = @$_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

// Parse data
$table = $data['selectTable']['name'];
$tmp = null;
$pkey = null;
$pval = null;

// Interact with database
if ($verb == 'POST') {
    $pkey = $data['data'][0]['Field'];
    $pval = '"'.$data['data'][0]['value'].'"';

    $query = $conn->prepare("SELECT 1 FROM $table WHERE $pkey = $pval");
    $query->execute();
    if ($query->fetch()) die('{"result":"Error on INSERT (duplicated primary key)"}');
    //INSERT
    foreach ($data['data'] as $k => $v) {
        $tmp.= $v['Field'].'="'.$v['value'].'",';
    }
    $tmp = substr($tmp,0,-1);

    $query = $conn->prepare("INSERT $table SET $tmp");
} elseif ($verb == 'PUT') {
    foreach ($data['data'] as $k => $v) {
        $pkey = $k;
        $pval = '"'.$v.'"';
        break;
    }
    //UPDATE
    foreach ($data['data'] as $k => $v) {
        $tmp.= $k.'="'.$v.'",';
    }
    $tmp = substr($tmp,0,-1);
    $query = $conn->prepare("UPDATE $table SET $tmp WHERE $pkey = $pval");
} elseif ($verb == 'DELETE') {
    $table = $_GET['selectTable'];
    $pkey = $_GET['field'];
    $pval = '"'.$_GET['val'].'"';
    //DELETE
    $query = $conn->prepare("DELETE FROM $table WHERE $pkey = $pval");
} else die('{"result":"Error on request"}');

// Execute prepared statement
$query->execute();
