<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging In...</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php
        $inputName = $_POST['username'];
        $inputEmail = $_POST['email'];
        $inputPassword = $_POST['password'];

        $servername = "localhost";
        $dbUsername = "root";
        $dbPassword = "Z3(sz83Nva-nnYR9";

        try{
            $conn = new PDO("mysql:host=$servername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = $conn->prepare("SELECT * FROM Users");
            $sql->execute();

            $result = $sql->fetchAll();

            $ids = array_column($result, 'ID');
            $usernames = array_column($result, 'Username');
            $hashPws = array_column($result, 'Password');
            $emails = array_column($result, 'Email');
            $profilePics = array_column($result, 'Profile_pic');
            $bio = array_column($result, 'Bio');
            $haveMatch = 0;

            for ($i = 0; $i < count($ids); $i++){
                if(strcmp($inputName, $usernames[$i]) == 0 && strcmp($inputEmail, $emails[$i]) == 0 && password_verify($inputPassword, $hashPws[$i])){
                    session_start();
                    $_SESSION['id'] = $ids[$i];
                    $_SESSION['username'] = $usernames[$i];
                    $_SESSION['email'] = $emails[$i];
                    $_SESSION['img'] = $profilePics[$i];
                    $_SESSION['bio'] = $bio[$i];
                    echo "Log in successfully... Redirecting to main page after 3 seconds...";
                    header( "refresh:3;url=mainPage.php" );
                    $haveMatch = 1;
                    break;
                }
            }
            if($haveMatch == 0){
                echo "Username/email/password incorrect, redirecting to login page in 3 seconds.";
                header("refresh:3; url=logIn.php");
            }
            
        }catch(PDOException $e){
            print("Error: " . $sql . "<br>" . $e->getMessage());
        }
    ?>
</body>
</html>