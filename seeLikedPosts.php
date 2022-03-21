<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liked Posts</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php require("title.php"); ?>
    <div class="content">
        <?php
            require("pogActions.php");
            require("config.php");

            $sql = $conn->prepare("SELECT * FROM Posts ORDER BY Post_ID DESC");
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

            $poggedCount = 0;

            for ($i = 0; $i < count($postIDs); $i++){
                for ($j = 0; $j < count($dbPostID); $j++){
                    if($postIDs[$i] == $dbPostID[$j] && $current_userID == $dbUserID[$j]){ //see if there is a match between pog db and the current userID + postID
                        echo
                        '<div class="post">
                            <div class="post-time">'.$postDate[$i].'</div>
                            <div class="post-img">
                                <img style="width: 400px; height: auto" src="data:image/jpg;base64,'.base64_encode($postPic[$i]).'" />
                            </div>
                            <div class="post-content">'.$contents[$i].'</div>
                            <div>
                            <form method="post" action = "seeLikedPosts.php">
                                <input type="text" name="post-id" value="'.$postIDs[$i].'" style = "display:none">
                                <input type="submit" name="pog'.$postIDs[$i].'" value="UNPOG" id="pog'.$postIDs[$i].'">
                            </form></div></div>
                        <br>';
                        $poggedCount++; //count pogs
                    }
                }
            }
            //show no pogs if no pogs
            if($poggedCount == 0){
                echo 'No posts Pogged, sadge';
            }
        ?>
    </div>
</body>
</html>