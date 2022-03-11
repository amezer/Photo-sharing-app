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
    <?php require("title.php"); ?>    <!-- require navi file -->
    <div class="content">
        <?php
            //getting all php functions needed and config db connection
            require("commentFuncs.php");
            require("config.php");

            $sql = $conn->prepare("SELECT * FROM Posts ORDER BY Post_ID DESC"); //fetching data for all posts
            $sql->execute();
            $result = $sql->fetchAll();

            $postIDs = array_column($result, 'Post_ID');
            $userIDs = array_column($result, 'User_ID');
            $content = array_column($result, 'Content');
            $postPic = array_column($result, 'Picture');
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

            $sql = $conn->prepare("SELECT * FROM Comments ORDER BY Comment_ID DESC"); //fetching data for all Comments
            $sql->execute();
            $result = $sql->fetchAll();
            $commentIDs = array_column($result, 'Comment_ID');
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
                    require("showPost.php");
                }
            }

        ?>
        <?php require("pogActions.php"); ?> <!-- require functions for pog/unpog -->
    </div>
</body>
</html>