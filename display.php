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
                followUser($_POST['userID']);
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
                unfollowUser($_POST['userID']);
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

            if(isset($_POST['editComment'.$_POST['comment-id']])) {
                updateComment($_POST['comment-id'], $_POST['c-context'.$_POST['comment-id']]);
                unset($_POST['editComment'.$_POST['comment-id']]);
            }
    
            function updateComment($commentID, $context){
                session_start();
                $id = $_SESSION['id'];
                $dbServername = "localhost";
                $dbUsername = "root";
                $dbPassword = "Z3(sz83Nva-nnYR9";
    
                $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $sql = "UPDATE Comments SET Context = '$context' WHERE Comment_ID = '$commentID'";
                $conn -> exec($sql);
                header("refresh: 0;");
            }
    
            if(isset($_POST['removeComment'.$_POST['comment-id']])) {
                removeComment($_POST['comment-id']);
                unset($_POST['removeComment'.$_POST['comment-id']]);
            }
    
            function removeComment($commentID){
                session_start();
                $id = $_SESSION['id'];
                $dbServername = "localhost";
                $dbUsername = "root";
                $dbPassword = "Z3(sz83Nva-nnYR9";
    
                $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $sql = "DELETE FROM Comments WHERE Comment_ID = '$commentID'";
                $conn -> exec($sql);
                header("refresh: 0;");
            }
        ?>                  

        <script>
            function showComments(id){
                var tmp = document.getElementById("allComments"+id);
                if(tmp.style.display === "block"){
                    tmp.style.display = "none";
                }else{
                    tmp.style.display = "block";
                }
                
            }

            function showEditComment(id){
                var tmp = document.getElementById("editCommentForm"+id);
                if(tmp.style.display === "flex"){
                    tmp.style.display = "none";
                }else{
                    tmp.style.display = "flex";
                }
            }
        </script>

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

                $sql = $conn->prepare("SELECT * FROM Users WHERE ID = $user_id");
                $sql->execute();

                $result = $sql->fetchAll();

                echo '<div class="profile" style="margin-top: 20px">';
                $name = array_column($result, 'Username');
                $email = array_column($result, 'Email');
                $bio = array_column($result, 'Bio');
                $profilePic = array_column($result, 'Profile_pic');

                echo "<b>User: </b>".$name[0]."<br>";
                echo "<b>Email: </b>".$email[0]."<br>";
                echo '<img style="width: 100px; height: auto; margin-top: 10px" src="data:image/jpg;base64,'.base64_encode($profilePic[0]).'" /><br>';
                echo "<b>Bio: </b>".$bio[0]."<br>";

                echo '</div>';

                if($current_userID != $user_id && $isFollowing == 0){
                    echo '
                    <form method="post" action="display.php?id='.$user_id.'" align="center" style="margin-top:20px">
                        <input type="text" name="userID" value="'.$user_id.'" style = "display:none">
                        <input type="submit" name="followBtn" value="Follow" class="followBtn">
                    </form>';
                }else if($current_userID != $user_id){
                    echo '
                    <form method="post" action="display.php?id='.$user_id.'" align="center" style="margin-top:20px">
                        <input type="text" name="userID" value="'.$user_id.'" style = "display:none">
                        <input type="submit" name="unfollowBtn" value="Unfollow" class="followBtn">
                    </form>';
                }

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

                $sql = $conn->prepare("SELECT * FROM Comments ORDER BY Comment_ID DESC"); //fetching data for all Comments
                $sql->execute();
                $result = $sql->fetchAll();
                $commentIDs = array_column($result, 'Comment_ID');
                $commenterIDs = array_column($result, 'Commenter_ID');
                $commentingPostIDs = array_column($result, 'Post_ID');
                $commentContexts = array_column($result, 'Context');
                $commentTimes = array_column($result, 'Comment_time');

                $sql = $conn->prepare("SELECT * FROM Users"); //fetching user table columns
                $sql->execute();
                $result = $sql->fetchAll();

                $userdbIDs = array_column($result, 'ID');
                $usernames = array_column($result, 'Username');
                $userPics = array_column($result, 'Profile_pic');

                echo '<div class="border"><p>User Posts</p></div>';
                for($i = 0; $i < count($content); $i++){
                    $echoed = false;
                    echo
                    '<div class="post">
                        <div class="post-time">'.$postDate[$i].'</div>
                        <div class="post-img">
                            <img style="width: 400px; height: auto" src="data:image/jpg;base64,'.base64_encode($postPic[$i]).'" />
                        </div>
                        <div class="post-content">'.$content[$i].'</div>
                        <form method="post" action="display.php?id='.$user_id.'">
                            <input type="text" name="post-id" value="'.$postIDs[$i].'" style = "display:none">';
                    for($l = 0; $l < count($dbPostID); $l++){
                        if($user_id == $dbPostID[$l] && $current_userID == $dbUserID[$l]){
                            echo   '<input type="submit" name="pog'.$postIDs[$i].'" value="UNPOG" id="pog'.$postIDs[$i].'">
                                    </form>';
                            $echoed = true;
                        }
                    }
                    if(!$echoed){
                        echo   '<input type="submit" name="pog'.$postIDs[$i].'" value="POG" id="pog'.$postIDs[$i].'">
                        </form>';
                    }

                    $sql = $conn->prepare("SELECT Post_ID FROM Comments WHERE Post_ID = '$postIDs[$i]'");
                    $sql->execute();
                    $result = $sql->fetchAll();

                    $numOfComments = count(array_column($result, 'Post_ID'));

                    if($numOfComments > 1){
                        echo '<button class="showComment" onclick="showComments('.$postIDs[$i].')">'.$numOfComments.' Comments</button>';
                    }else if($numOfComments == 1){
                        echo '<button class="showComment" onclick="showComments('.$postIDs[$i].')">1 Comment</button>';
                    }else{
                        echo '<button class="showComment" onclick="showComments('.$postIDs[$i].')">Add Comments</button>';
                    }
                    
                    echo '<div id="allComments'.$postIDs[$i].'" style="display: none">';
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
                                <a href="display.php?id='.$commenterIDs[$j].'"><img id="commenterPic" style="width: auto; height: 30px" src="data:image/jpg;base64,'.base64_encode($commenterPic).'" />
                                <div id="commenterName">'.$commenterName.'</a></div>
                            </div>
                            <div id="commentBody">
                                <div id="commentContext">'.$commentContexts[$j].'</div>
                                <div id="commentTime">'.$commentTimes[$j].'</div>
                            </div>';

                            if ($current_userID == $commenterIDs[$j]){
                                echo '<div id="commentActions">
                                    <button onclick="showEditComment('.$commentIDs[$j].')" name="editComment" id="editComment">Edit</button>
                                    <form method="post" action="display.php?id='.$user_id.'" id="deleteCommentHandler'.$commentIDs[$j].'" style="width:100%">
                                        <input type="text" name="comment-id" value="'.$commentIDs[$j].'" style = "display:none;">
                                        <input type="submit" name="removeComment'.$commentIDs[$j].'" id="removeComment" value="Remove">
                                    </form></div>
                                    ';
                            }
                            
                            echo '<form method="post" action="display.php?id='.$user_id.'" id="editCommentForm'.$commentIDs[$j].'" style="display:none; align-item: center; background-color: #2b2d42; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
                                <input type="text" name="comment-id" value="'.$commentIDs[$j].'" style = "display:none">
                                <textarea name="c-context'.$commentIDs[$j].'" id="c-context'.$commentIDs[$j].'" row="1" class="commentTxt" style="margin: 10px"></textarea>
                                <input type="submit" name="editComment'.$commentIDs[$j].'" id="editComment'.$commentIDs[$j].'" value="Edit" class="commentBtn" style="margin: 10px">
                            </form>';

                            echo '</div><br>';
                        }
                        
                    }

                    echo '<form method="post" action="display.php?id='.$user_id.'" id="commentForm">
                        <input type="text" name="post-id" value="'.$postIDs[$i].'" style = "display:none">
                        <textarea name="c-context'.$postIDs[$i].'" id="c-context'.$postIDs[$i].'" row="1" class="commentTxt"></textarea>
                        <input type="submit" name="comment'.$postIDs[$i].'" id="comment'.$postIDs[$i].'" value="comment" class="commentBtn">
                    </form>';
                    echo '</div></div><br>';
                }
            }catch(PDOException $e){
                print("Error: " . $sql . "<br>" . $e->getMessage());
            }
        ?>

        <script>
            function showComments(id){
                var tmp = document.getElementById("allComments"+id);
                if(tmp.style.display === "block"){
                    tmp.style.display = "none";
                }else{
                    tmp.style.display = "block";
                }
                
            }
        </script>
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