<?php
header('Content-Type: application/json');
require_once "../include/connection.php";

$data = json_decode(file_get_contents('php://input'), true);
$title = $data['booktitle'];
if (!empty($title)) {
    $query = $conn->prepare("SELECT Book.title, Inventory.OnHand, Branch.branchName, Branch.branchLocation, 
Author.authorFirst, Author.authorLast, Publisher.publisherName, Publisher.city FROM Book, Inventory, Author, Branch, 
Publisher, Wrote WHERE Book.title LIKE '%$title%' AND Book.bookCode=Inventory.BookCode AND Branch.branchNum=Inventory.BranchNum 
AND Book.publisherCode=Publisher.publisherCode AND Author.authorNum=Wrote.authorNum AND Inventory.BookCode=Wrote.bookCode");
    $query->execute();
    $title = $query->fetchAll();
    if (!empty(json_encode($title))){
        echo json_encode($title);
    }
    else {
        echo "no match";
    }

}else
    die('{"result":"Error on name (empty)"}');
