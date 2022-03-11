<?php 
    session_start();
    $current_userID = $_SESSION['id'];
    $dbServername = "localhost";
    $dbUsername = "root";
    $dbPassword = "Z3(sz83Nva-nnYR9";

    try{
        $current_userID = $_SESSION['id'];
        $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        print("Error: " . $sql . "<br>" . $e->getMessage());
    }
?>