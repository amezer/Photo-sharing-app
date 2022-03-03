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
    <div id = "title">
        <p>
            <a href="mainPage.php">Main Page</a>
            <a href="viewPosts.php">View Posts</a>
            <a href="myPage.php">My Page</a>
        </p>
    </div>
    <div class="content">
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
                    
                    //$executed = 0;

                    for($i = 0; $i < count($dbPostID); $i++){
                        if($postID == $dbPostID[$i] && $id == $dbUserID[$i]){
                            $sql = "DELETE FROM POGs WHERE Post_ID = $postID AND User_ID = $id";
                            $conn -> exec($sql);
                            //$executed = 1;
                            //echo '<script> document.getElementById("pog'.$postID.'").value = "POG";</script>';
                            break;
                        }
                    // }
                    // if($executed == 0){
                    //     $sql = "INSERT INTO POGs (Post_ID, User_ID) VALUES ($postID, $id)";
                    //     $conn -> exec($sql);
                    //     echo '<script> document.getElementById("pog'.$postID.'").value = "UNPOG";</script>';
                    // }
                    // $executed = 0;
                    }
                }
            ?>
        <?php
            session_start();

            $dbServername = "localhost";
            $dbUsername = "root";
            $dbPassword = "Z3(sz83Nva-nnYR9";

            try{
                $current_userID = $_SESSION['id'];
                $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
                        if($postIDs[$i] == $dbPostID[$j] && $current_userID == $dbUserID[$j]){
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
                            $poggedCount++;
                        }
                    }
                }

                if($poggedCount == 0){
                    echo 'No posts Pogged, sadge';
                }

            }catch(PDOException $e){
                print("Error: " . $sql . "<br>" . $e->getMessage());
            }
        ?>
    </div>
</body>
</html>