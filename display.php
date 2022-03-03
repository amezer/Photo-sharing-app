<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viewing Poggers</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div id = "title">
        <p>
            <a href="mainPage.php">Main Page</a>
            <a href="viewPosts.php">View Posts</a>
        </p>
    </div>
    <!-- php for following/unfollowing users on profile page -->
    <div class="content">
        <?php
            if(isset($_POST['followBtn'])){
                echo $_POST['followBtn'];
                followUser($_POST['userID']);
                //unset($_POST['followBtn']);
            }

            function followUser($userID){
                session_start();
                $id = $_SESSION['id'];
                $dbServername = "localhost";
                $dbUsername = "root";
                $dbPassword = "Z3(sz83Nva-nnYR9";

                $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "INSERT INTO Follows (User_ID, Follower_ID) VALUES ($userID, $id)";
                $conn -> exec($sql);
                header("Refresh:0;");
            }

            if(isset($_POST['unfollowBtn'])){
                echo $_POST['unfollowBtn'];
                echo 'post user id:'. $_POST['userID'];
                unfollowUser($_POST['userID']);
                //unset($_POST['unfollowBtn']);
            }

            function unfollowUser($userID){
                session_start();
                $id = $_SESSION['id'];
                $dbServername = "localhost";
                $dbUsername = "root";
                $dbPassword = "Z3(sz83Nva-nnYR9";

                $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "DELETE FROM Follows WHERE User_ID = $userID AND Follower_ID = $id";
                $conn -> exec($sql);
                header("Refresh:0;");
            }
        ?>

        <?php
            session_start();
            $current_userID = $_SESSION['id'];
            $user_id = $_GET['id'];

            $dbServername = "localhost";
            $dbUsername = "root";
            $dbPassword = "Z3(sz83Nva-nnYR9";

            try{
                $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = $conn->prepare("SELECT * FROM Follows");
                $sql -> execute();
                $result = $sql -> fetchAll();

                $id = array_column($result, 'User_ID');
                $followers = array_column($result, 'Follower_ID');
                $isFollowing = 0;

                //check not following 
                for($i = 0; $i < count($id); $i++){
                    if($user_id == $id[$i] && $current_userID == $followers[$i]){
                        $isFollowing = 1;
                    }
                }

                if($current_userID != $user_id && $isFollowing == 0){
                    echo '
                    <form method="post" action="display.php?id='.$user_id.'" align="center">
                        <input type="text" name="userID" value="'.$user_id.'" style = "display:none">
                        <input type="submit" name="followBtn" value="Follow" class="followBtn">
                    </form>';
                }else if($current_userID != $user_id){
                    echo '
                    <form method="post" action="display.php?id='.$user_id.'" align="center">
                        <input type="text" name="userID" value="'.$user_id.'" style = "display:none">
                        <input type="submit" name="unfollowBtn" value="Unfollow" class="followBtn">
                    </form>';
                }

                $sql = $conn->prepare("SELECT * FROM Users WHERE ID = $user_id");
                $sql->execute();

                $result = $sql->fetchAll();

                echo '<div class="words" style="margin-top: 20px">';
                $name = array_column($result, 'Username');
                $email = array_column($result, 'Email');
                $bio = array_column($result, 'Bio');
                $profilePic = array_column($result, 'Profile_pic');

                echo "<b>User: </b>".$name[0]."<br>";
                echo "<b>Email: </b>".$email[0]."<br>";
                echo '<img style="width: 100px; height: auto" src="data:image/jpg;base64,'.base64_encode($profilePic[0]).'" /><br>';
                echo "<b>Bio: </b>".$bio[0]."<br>";

                echo '</div>';

                $sql = $conn->prepare("SELECT * FROM Posts WHERE User_ID = $user_id");
                $sql->execute();

                $rst = $sql ->fetchAll();
                $postIDs = array_column($rst, 'Post_ID');
                $content = array_column($rst, 'Content');
                $postPic = array_column($rst, 'Picture');
                $postDate = array_column($rst, 'Post_date');

                $sql = $conn->prepare("SELECT * FROM POGs"); //fetching data for all Pogs
                $sql->execute();
                $result = $sql->fetchAll();
                $dbPostID = array_column($result, 'Post_ID');
                $dbUserID = array_column($result, 'User_ID');

                echo '<div class="postBorder"><p>User Posts</p></div>';
                for($i = 0; $i < count($content); $i++){
                    $echoed = false;
                    echo
                    '<div class="post">
                        <div class="post-time">'.$postDate[$i].'</div>
                        <div class="post-img">
                            <img style="width: 400px; height: auto" src="data:image/jpg;base64,'.base64_encode($postPic[$i]).'" />
                        </div>
                        <div class="post-content">'.$content[$i].'</div>
                        <br>
                        <form method="post" action="display.php?id='.$user_id.'">
                            <input type="text" name="post-id" value="'.$postIDs[$i].'" style = "display:none">';
                    for($l = 0; $l < count($dbPostID); $l++){
                        if($user_id == $dbPostID[$l] && $current_userID == $dbUserID[$l]){
                            echo   '<input type="submit" name="pog'.$postIDs[$i].'" value="UNPOG" id="pog'.$postIDs[$i].'">
                                    </form>
                            </div>
                                <br>';
                            $echoed = true;
                        }
                    }
                    if(!$echoed){
                        echo   '<input type="submit" name="pog'.$postIDs[$i].'" value="POG" id="pog'.$postIDs[$i].'">
                        </form></div>
                        <br>';
                    }
                }
            }catch(PDOException $e){
                print("Error: " . $sql . "<br>" . $e->getMessage());
            }
        ?>
        <!-- php for pogging posts on user profile page -->
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
    </div>
    
</body>
</html>