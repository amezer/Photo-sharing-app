<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php
        session_start();
        $current_userID = $_SESSION['id'];
        $type = $_GET['type'];
        $id = $_GET['id'];

        $dbServername = "localhost";
        $dbUsername = "root";
        $dbPassword = "Z3(sz83Nva-nnYR9";

        try{
            $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($type == 0){ // remove follower
                $sql = "DELETE FROM Follows WHERE User_ID = $current_userID AND Follower_ID = $id";
                $conn -> exec($sql);
            }else if($type == 1){ //remove following
                $sql = "DELETE FROM Follows WHERE User_ID = $id AND Follower_ID = $current_userID";
                echo $sql;
                $conn -> exec($sql);
            }
        }catch(PDOException $e){
            print("Error: " . $sql . "<br>" . $e->getMessage());
        }
        header("refresh:0; url=seeFollowers.php");
    ?>
</body>
</html>