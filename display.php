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
    <?php require("title.php"); ?>    <!-- require navi file -->
    <div class="content">
        <?php
            //getting all php functions needed and 
            require("commentFuncs.php");
            require("config.php");

            $user_id = $_GET['id'];
            session_start();

            if($user_id == $_SESSION['id']){
                header("Location: myPage.php");
            }
            
            //get information for all followings from db
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

            //fetch the searched user's information from db
            $sql = $conn->prepare("SELECT * FROM Users WHERE ID = $user_id");
            $sql->execute();
            $result = $sql->fetchAll();
            $name = array_column($result, 'Username');
            $email = array_column($result, 'Email');
            $bio = array_column($result, 'Bio');
            $profilePic = array_column($result, 'Profile_pic');

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

            //print out the user's information
            echo '<div class="profile" style="margin-top: 20px">';
            echo "<b>User: </b>".$name[0]."<br>";
            echo "<b>Email: </b>".$email[0]."<br>";
            echo '<img style="width: 100px; height: auto; margin-top: 10px" src="data:image/jpg;base64,'.base64_encode($profilePic[0]).'" /><br>';
            echo "<b>Bio: </b>".$bio[0]."<br>";
            echo '</div>';

            //display follow/unfollow if the profile page doesn't belong to the current user
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

            echo '<div class="border"><p>User Posts</p></div>';
            for($i = 0; $i < count($content); $i++){
                require("showPost.php");
            }
        ?>
        <!-- php for pogging posts on user profile page -->
        <?php
         require("pogActions.php");
        ?>
    </div>
    
</body>
</html>