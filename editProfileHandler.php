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
    <div class="content">
        <?php
            require("config.php");

            $username = $_POST['username'];
            $img = $_SESSION['img'];

            if(file_exists($_FILES['p-image']['tmp_name'])){
                $img = addslashes(file_get_contents($_FILES['p-image']['tmp_name'])); 
            }else{
                $sql = $conn->prepare("SELECT * FROM Users WHERE ID = $current_userID");
                $sql->execute();

                $result = $sql->fetchAll();
                
                $img = addslashes(array_column($result, 'Profile_pic')[0]);  //prefill img
            }
            
            $bio = $_POST['bio'];

            $sql = "UPDATE Users SET Username = '$username', Profile_pic = '$img', Bio = '$bio' WHERE ID = '$current_userID'";
            $conn -> exec($sql);

            //update session variables with new user info
            $_SESSION['username'] = $username;
            $_SESSION['img'] = $img;
            $_SESSION['bio'] = $bio;

            
            header( "refresh:0;url=myPage.php" );
        ?>
    </div>
</body>
</html>