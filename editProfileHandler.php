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
            session_start();
            $dbServername = "localhost";
            $dbUsername = "root";
            $dbPassword = "Z3(sz83Nva-nnYR9";

            try{
                $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                print("Error: " . $sql . "<br>" . $e->getMessage());
            }

            $current_userID = $_SESSION['id'];
            $username = $_POST['username'];
            $img = $_SESSION['img'];

            if(file_exists($_FILES['p-image']['tmp_name'])){
                $img = addslashes(file_get_contents($_FILES['p-image']['tmp_name'])); 
            }else{
                $sql = $conn->prepare("SELECT * FROM Users WHERE ID = $current_userID");
                $sql->execute();

                $result = $sql->fetchAll();
                
                $img = addslashes(array_column($result, 'Profile_pic')[0]);
            }
            
            $bio = $_POST['bio'];

            

            $sql = "UPDATE Users SET Username = '$username', Profile_pic = '$img', Bio = '$bio' WHERE ID = '$current_userID'";
            $conn -> exec($sql);

            $_SESSION['username'] = $username;
            $_SESSION['img'] = $img;
            $_SESSION['bio'] = $bio;

            
            header( "refresh:0;url=myPage.php" );
        ?>
    </div>
</body>
</html>