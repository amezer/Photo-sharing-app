<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Contents</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php

        session_start();
        $servername = "localhost";
        $username = "root";
        $dbPassword = "Z3(sz83Nva-nnYR9";

        $userID = $_SESSION['id'];
        $image = $_FILES['p-image']['tmp_name'];
        $imgContent = addslashes(file_get_contents($image)); 
        $content = $_POST['p-content'];

        try{
            $conn = new PDO("mysql:host=$servername;dbname=photo_sharing_app", $username, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "INSERT INTO Posts (User_ID, Content, Picture)
                    VALUES ('$userID', '$content', '$imgContent')";
            
            $conn -> exec($sql);

            echo "Content posted...redirecting to main page after 3 seconds...";
            header( "refresh:3;url=mainPage.php" );
        }catch(PDOException $e){
            print("Error: " . $sql . "<br>" . $e->getMessage());
        }
    ?>
</body>
</html>