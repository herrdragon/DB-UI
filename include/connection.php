<?php
$user = 'root';
$pass = '';

try {
$conn = new PDO('mysql:host=localhost;dbname=henrybooks', $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
}
 catch(PDOException $e){
  echo $e->getMessage();
 }