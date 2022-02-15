<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        session_start();
        $dbServername = "localhost";
        $dbUsername = "root";
        $dbPassword = "Z3(sz83Nva-nnYR9";

        try{
            $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = $conn->prepare("SELECT * FROM Posts"); //fetching data for all posts
            $sql->execute();
            $result = $sql->fetchAll();

            $postIDs = array_column($result, 'Post_ID');
            $userIDs = array_column($result, 'User_ID');
            $contents = array_column($result, 'Content');
            $pics = array_column($result, 'Picture');
            $postDate = array_column($result, 'Post_date');

            $sql = $conn->prepare("SELECT * FROM Users"); //fetching user table columns
            $sql->execute();
            $result = $sql->fetchAll();

            $userdbIDs = array_column($result, 'ID');
            $usernames = array_column($result, 'Username');

            for ($i = 0; $i < count($userIDs); $i++){ //iterate through all post rows
                $postUsername = "defaultName";
                for($j = 0; $j < count($userdbIDs); $j++){ //find match ID from the user table
                    if($userIDs[$i] == $userdbIDs[$j]){
                        $postUsername = $usernames[$j];
                        break;
                    }
                }

                echo
                '<div class="post>
                    <div class="post-user"> Posted by: '.$postUsername.'</div>
                    <div class="post-time">'.$postDate[$i].'</div>
                    <div class="post-img">
                        <img src="data:image/jpg;base64,'.base64_encode($pics[0]).'" />
                    </div>
                    <div class="post-content">'.$contents[$i].'</div>
                    <br>
                    <form method="post">
                        <input type="text" name="post-id" value="'.$userIDs[$i].'" style = "display:block">
                        <input type="submit" name="pog" value="POG">
                    <form>
                </div>';
            }

        }catch(PDOException $e){
            print("Error: " . $sql . "<br>" . $e->getMessage());
        }
    ?>

    <?php
         if(array_key_exists('pog', $_POST)) {
             addPOG($_POST['post-id']);
         }
         $sql = $conn->prepare("SELECT * FROM POGs"); //fetching data for all Pogs
        $sql->execute();
        $result = $sql->fetchAll();
        $dbPostID = array_column($result, 'Post_ID');
        $dbUserID = array_column($result, 'User_ID');
        
         function addPOG($postID){
            echo $postID."stuff";
            var_dump($sql);
            
            $executed = false;
            for($i = 0; $i = count($dbPostID); $i++){
                echo "is in";
                if($postID == $dbPostID[$i] && $_SESSION['id'] == $dbUserID[$i]){
                    // $sql = "DELETE FROM POGs WHERE Post_ID = $postID AND User_ID = $_SESSION['id']";
                    // $conn -> exec($sql);
                    // $executed = true;
                    // break;
                    
                }
            }
            // if(!$executed){
            //     $sql = "INSERT INTO POGs (Post_ID, User_ID)
            //     VALUES ('$post-id', '$_SESSION['id']')";
            //     $conn -> exec($sql);
            //     echo "Pogged";
            // }
         }
    ?>
</body>
</html>