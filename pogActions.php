<?php
    if(isset($_POST['pog'.$_POST['post-id']])) {
        addPOG($_POST['post-id']);
        unset($_POST['pog'.$_POST['post-id']]);
    }
    
    function addPOG($postID){
        session_start();
        $id = $_SESSION['id'];
        $dbServername = "localhost";
        $dbUsername = "root";
        $dbPassword = "Z3(sz83Nva-nnYR9";

        $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = $conn->prepare("SELECT * FROM POGs"); //fetching data for all Pogs
        $sql->execute();
        $result = $sql->fetchAll();
        $dbPostID = array_column($result, 'Post_ID');
        $dbUserID = array_column($result, 'User_ID');
        
        $executed = 0;

        for($i = 0; $i < count($dbPostID); $i++){
            if($postID == $dbPostID[$i] && $id == $dbUserID[$i]){
                $sql = "DELETE FROM POGs WHERE Post_ID = $postID AND User_ID = $id";
                $conn -> exec($sql);
                $executed = 1;
                echo '<script> document.getElementById("pog'.$postID.'").value = "POG";</script>';
                break;
            }
        }
        if($executed == 0){
            $sql = "INSERT INTO POGs (Post_ID, User_ID) VALUES ($postID, $id)";
            $conn -> exec($sql);
            echo '<script> document.getElementById("pog'.$postID.'").value = "UNPOG";</script>';
        }
        $executed = 0;
    }
?>