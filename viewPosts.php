<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Posts</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div id = "title">
        <p>
            <a href="mainPage.php">Main Page</a>
            <a href="myPage.php">My Page</a>
        </p>
    </div>
    <div class="content">
    <?php
        session_start();
        $current_userID = $_SESSION['id'];
        $dbServername = "localhost";
        $dbUsername = "root";
        $dbPassword = "Z3(sz83Nva-nnYR9";

        try{
            $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = $conn->prepare("SELECT * FROM Posts ORDER BY Post_ID DESC"); //fetching data for all posts
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

            $sql = $conn->prepare("SELECT * FROM Follows"); //fetching data for all posts
            $sql->execute();
            $result = $sql->fetchAll();

            $followingIDs = array_column($result, 'User_ID');
            $followerIDs = array_column($result, 'Follower_ID');

            $sql = $conn->prepare("SELECT * FROM POGs"); //fetching data for all Pogs
            $sql->execute();
            $result = $sql->fetchAll();
            $dbPostID = array_column($result, 'Post_ID');
            $dbUserID = array_column($result, 'User_ID');

            for ($i = 0; $i < count($userIDs); $i++){ //iterate through all post rows
                $postUsername = "defaultName";
                for($j = 0; $j < count($userdbIDs); $j++){ //find match ID from the user table
                    if($userIDs[$i] == $userdbIDs[$j]){
                        $postUsername = $usernames[$j];
                        break;
                    }
                }

                $isFollowing = false;

                for($k = 0; $k < count($followingIDs); $k++){
                    if($userIDs[$i] == $followingIDs[$k] && $followerIDs[$k] == $current_userID){
                        $isFollowing = true;
                        break;
                    }
                }

                if($isFollowing){
                    $echoed = false;
                    $id = $_SESSION['id'];
                    echo
                    '<div class="post">
                        <div class="post-user"> Posted by: '.$postUsername.'</div>
                        <div class="post-time">'.$postDate[$i].'</div>
                        <div class="post-img">
                            <img style="width: 400px; height: auto" src="data:image/jpg;base64,'.base64_encode($pics[$i]).'" />
                        </div>
                        <div class="post-content">'.$contents[$i].'</div>
                        <div>
                        <form method="post" action = "viewPosts.php">
                            <input type="text" name="post-id" value="'.$postIDs[$i].'" style = "display:none">';
                    
                    for($l = 0; $l < count($dbPostID); $l++){
                        if($postIDs[$i] == $dbPostID[$l] && $id == $dbUserID[$l]){
                            echo   '<input type="submit" name="pog'.$postIDs[$i].'" value="UNPOG" id="pog'.$postIDs[$i].'">
                                    </form></div></div>
                                <br>';
                            $echoed = true;
                        }
                    }
                    if(!$echoed){
                        echo   '<input type="submit" name="pog'.$postIDs[$i].'" value="POG" id="pog'.$postIDs[$i].'">
                        </form></div></div>
                    <br>';
                    }
                }
            }

        }catch(PDOException $e){
            print("Error: " . $sql . "<br>" . $e->getMessage());
        }
    ?>

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
                    // echo 
                    // '<script type="text/javascript">
                    //     document.getElementById("pog").value = "POG";
                    // </script>';
                    break;
                }
            }
            if($executed == 0){
                $sql = "INSERT INTO POGs (Post_ID, User_ID) VALUES ($postID, $id)";
                $conn -> exec($sql);
                echo '<script> document.getElementById("pog'.$postID.'").value = "UNPOG";</script>';
                // echo 
                // '<script type="text/javascript">
                //     document.getElementById('pog').value = "UNPOG";
                // </script>';
            }
            $executed = 0;
         }
    ?>
    </div>
</body>
</html>