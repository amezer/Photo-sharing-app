<?php 
    echo "trying to connect...";
    session_start();
    $current_userID = $_SESSION['id'];
    $dbServername = "us-cdbr-east-05.cleardb.net";
    $dbUsername = "b29c6cdf9ed757";
    $dbPassword = "955082fb";
    $database = "heroku_8839b7520284fd2";
    // $dbUsername = "root";
    // $dbPassword = "Z3(sz83Nva-nnYR9";

    //mysql://b29c6cdf9ed757:955082fb@us-cdbr-east-05.cleardb.net/heroku_8839b7520284fd2?reconnect=true

    try{
        $current_userID = $_SESSION['id'];
        $conn = new PDO("mysql:host=$dbServername;dbname=$database", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "connected";
    }catch(PDOException $e){
        print("Error: " . $sql . "<br>" . $e->getMessage());
    }
?>