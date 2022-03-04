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
        if(isset($_POST['comment'.$_POST['post-id']])) {
            addComment($_POST['post-id'], $_POST['c-context'.$_POST['post-id']]);
            unset($_POST['comment'.$_POST['post-id']]);
        }

        function addComment($postID, $context){
            session_start();
            $id = $_SESSION['id'];
            $dbServername = "localhost";
            $dbUsername = "root";
            $dbPassword = "Z3(sz83Nva-nnYR9";

            $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = $conn->prepare("INSERT INTO `Comments`(`Post_ID`, `Commenter_ID`, `Context`) VALUES ('$postID','$id','$context')");
            $sql->execute();
            header("refresh: 0;");
        }
    ?>

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
            $userPics = array_column($result, 'Profile_pic');

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

            $sql = $conn->prepare("SELECT * FROM Comments"); //fetching data for all Comments
            $sql->execute();
            $result = $sql->fetchAll();
            $commenterIDs = array_column($result, 'Commenter_ID');
            $commentingPostIDs = array_column($result, 'Post_ID');
            $commentContexts = array_column($result, 'Context');
            $commentTimes = array_column($result, 'Comment_time');

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
                        <form method="post" action = "viewPosts.php">
                            <input type="text" name="post-id" value="'.$postIDs[$i].'" style = "display:none">';
                    
                    for($l = 0; $l < count($dbPostID); $l++){
                        if($postIDs[$i] == $dbPostID[$l] && $id == $dbUserID[$l]){
                            echo   '<input type="submit" name="pog'.$postIDs[$i].'" value="UNPOG" id="pog'.$postIDs[$i].'">
                                    </form>';
                            $echoed = true;
                        }
                    }
                    if(!$echoed){
                        echo   '<input type="submit" name="pog'.$postIDs[$i].'" value="POG" id="pog'.$postIDs[$i].'">
                        </form>';
                    }
                    
                    echo '<div class="border"><p>Comments</p></div>';


                    for($j = 0; $j < count($commenterIDs); $j++){
                        $commenterName = "defaultName";
                        $commenterPic = $_SESSION['img'];
                        $canEcho = false;
                        for($k = 0; $k < count($userdbIDs); $k++){ //get the commenter's username
                            if($commenterIDs[$j] == $userdbIDs[$k] && $commentingPostIDs[$j] == $postIDs[$i]){
                                $commenterName = $usernames[$k];
                                $commenterPic = $userPics[$k];
                                $canEcho = true;
                                break;
                            }
                        }

                        if($canEcho){
                            echo '
                            <div class="comment">
                            <div id="commentUserInfo">
                                <img id="commenterPic" style="width: auto; height: 30px" src="data:image/jpg;base64,'.base64_encode($commenterPic).'" />
                                <div id="commenterName">'.$commenterName.'</div>
                            </div>
                            <div id="commentBody">
                                <div id="commentContext">'.$commentContexts[$j].'</div>
                                <div id="commentTime">'.$commentTimes[$j].'</div>
                            </div>
                            </div><br>';
                        }
                        
                    }

                    echo '<form method="post" action="viewPosts.php" id="commentForm">
                        <input type="text" name="post-id" value="'.$postIDs[$i].'" style = "display:none">
                        <textarea name="c-context'.$postIDs[$i].'" id="c-context'.$postIDs[$i].'" row="1" class="commentTxt"></textarea>
                        <input type="submit" name="comment'.$postIDs[$i].'" id="comment'.$postIDs[$i].'" value="comment" class="commentBtn">
                    </form>';
                    echo '</div><br>';
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