<?php
    require "common.php";
    
    $id = $_GET['id'];
    
    $stmt=$dbh->prepare("UPDATE sessions SET status=? WHERE id=?");
    $stmt->execute(array(5,$id));
?>