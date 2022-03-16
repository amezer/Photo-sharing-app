<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pogger's Home Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php require("title.php"); ?>
    <div class="content">
        <?php
            //getting all php functions needed and config db connection
            require("commentFuncs.php"); 
            require("config.php");

            $sql = $conn->prepare("SELECT * FROM Users WHERE ID = $current_userID");
            $sql->execute();

            $result = $sql->fetchAll();

            $name = array_column($result, 'Username');
            $email = array_column($result, 'Email');
            $bio = array_column($result, 'Bio');
            $profilePic = array_column($result, 'Profile_pic');
            
            echo '<div class="profile">';
            echo 'User: '.$name[0].'<br>';
            echo 'Email: '.$email[0].'<br>';
            echo '<img style="width: 100px; height: auto" src="data:image/jpg;base64,'.base64_encode($profilePic[0]).'" /><br>';
            echo 'Bio: '.$bio[0].'<br>';
            echo '</div>';

            //show actions that users can commit
            echo '<div class="edits"> 
                    <a href="seeFollowers.php">Manage Followers</a>
                    <a href="editProfile.php">Edit Profile</a>
                    <a href="seeLikedPosts.php">Liked Posts</a>
                    </div>';

            //echos a border to mark user's posts
            echo '<div class="border"><p>User Posts</p></div>';

            $sql = $conn->prepare("SELECT * FROM Posts WHERE User_ID = $current_userID ORDER BY Post_ID DESC");
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

            for($i = 0; $i < count($content); $i++){ //iterate through the posts and print out the posts
                $postUsername = $_SESSION['username'];
                require("showPost.php");
            }
        ?>
        <!-- include pog/unpog actions -->
        <?php require("pogActions.php"); ?> 
    </div>
</body>
</html>