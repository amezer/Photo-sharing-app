<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signing up...</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php

        $user = $_POST['username'];
        $email = $_POST['email'];
        $pw = $_POST['password'];
        $image = $_FILES['p-image']['tmp_name'];
        $imgContent = addslashes(file_get_contents($image)); 
        $bio = $_POST['bio'];

        $servername = "localhost";
        $username = "root";
        $dbPassword = "Z3(sz83Nva-nnYR9";

        try{
            $conn = new PDO("mysql:host=$servername;dbname=photo_sharing_app", $username, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $hashpw = password_hash($pw, PASSWORD_BCRYPT);

            $sql = $conn->prepare("SELECT Email FROM Users");
            $sql->execute();

            $result = $sql->fetchAll();

            $dbEmails = array_column($result, 'Email');

            for ($i = 0; $i < count($dbEmails); $i++){
                if(strcmp($email, $dbEmails[$i]) == 0){
                    echo "The email is already used. Please choose another email to sign up. Redirecting to Sign Up page after 3 seconds...";
                    header( "refresh:3;url=signUp.php" );
                    break;
                }else{
                    $sql = "INSERT INTO Users (Username, Password, Email, Profile_pic, Bio)
                    VALUES ('$user', '$hashpw', '$email', '$imgContent', '$bio')";

                    $conn -> exec($sql);
        
                    $sql = $conn->prepare("SELECT ID FROM Users");
                    $sql->execute();
        
                    $result = $sql->fetchAll();
        
                    $id = array_column($result, 'ID');
        
                    session_start();
        
                    $_SESSION['username'] = $user;
                    $_SESSION['id'] = $id[count($id)-1];
                    $_SESSION['email'] = $email;
                    $_SESSION['img'] = $imgContent;
                    $_SESSION['bio'] = $bio;
        
                    echo "Signed up successfully... Redirecting to main page after 3 seconds...";
        
                    header( "refresh:3;url=mainPage.php" );
                    break;
                }
            }
        }catch (PDOException $e) {
            print("Error: " . $sql . "<br>" . $e->getMessage());
        }
    ?>
</body>
</html>