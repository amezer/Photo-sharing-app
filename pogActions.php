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
                //change the display of the like button and reset the like list
                echo '<script> 
                    document.getElementById("pog'.$postID.'").value = "POG";
                    var list = document.getElementById("dropDownLike'.$postID.'");
                    list.innerHTML = "";
                </script>';
                //fetch the list again to avoid lag
                $sql = $conn->prepare("SELECT ID, Username FROM `Users` INNER JOIN POGs ON ID = POGs.User_ID WHERE POGs.Post_ID = '$postID';");
                $sql->execute();
                $result = $sql->fetchAll();
            
                $likedUsernames = array_column($result, 'Username');
                $likedUserIDs = array_column($result, 'ID');
                //check if anyone liked the post
                if(count($likedUsernames) == 0){
                    echo '<script>
                        list.innerHTML = "No one POGGED the post...SADGE";
                    </script>';
                }else{
                    for($x = 0; $x < count($likedUsernames); $x++){
                        echo '<script>
                            var li = document.createElement("li");
                            li.innerHTML = "<a href=\"display.php?id='.$likedUserIDs[$x].'\">'.$likedUsernames[$x].'</a>";
                            list.appendChild(li);
                        </script>';
                    }
                }
                break;
            }
        }
        if($executed == 0){
            $sql = "INSERT INTO POGs (Post_ID, User_ID) VALUES ($postID, $id)";
            $conn -> exec($sql);
            //change the display of the like button and reset the like list
            echo '<script> 
                document.getElementById("pog'.$postID.'").value = "UNPOG";
                var list = document.getElementById("dropDownLike'.$postID.'");
                list.innerHTML = "";
            </script>';
            //fetch the list again to avoid lag
            $sql = $conn->prepare("SELECT ID, Username FROM `Users` INNER JOIN POGs ON ID = POGs.User_ID WHERE POGs.Post_ID = '$postID';");
            $sql->execute();
            $result = $sql->fetchAll();
        
            $likedUsernames = array_column($result, 'Username');
            $likedUserIDs = array_column($result, 'ID');

            for($x = 0; $x < count($likedUsernames); $x++){
                echo '<script>
                        var li = document.createElement("li");
                        li.innerHTML = "<a href=\"display.php?id='.$likedUserIDs[$x].'\">'.$likedUsernames[$x].'</a>";
                        list.appendChild(li);
                    </script>';
            }
        }
        $executed = 0;
        header("refresh: 0;");
    }
?>