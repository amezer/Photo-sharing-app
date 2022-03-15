<?php
    $echoed = false;
    $id = $_SESSION['id'];
    echo
    '<div class="post">
        <div id="header">
        <div class="post-user"> Posted by: '.$postUsername.'</div>
        <div class="dropDown">
        <div class="threeDots" onclick="showDropDown('.$postIDs[$i].')">
            <img id="drop-down-icon" src="https://static.thenounproject.com/png/979351-200.png">
        </div></div>
        <ol class="dropDownLike" id="dropDownLike'.$postIDs[$i].'" style="display: none">';

        $sql = $conn->prepare("SELECT ID, Username FROM `Users` INNER JOIN POGs ON ID = POGs.User_ID WHERE POGs.Post_ID = '$postIDs[$i]';");
        $sql->execute();
        $result = $sql->fetchAll();
    
        $likedUsernames = array_column($result, 'Username');
        $likedUserIDs = array_column($result, 'ID');

        for($x = 0; $x < count($likedUsernames); $x++){
            echo '<li><a href="display.php?id='.$likedUserIDs[$x].'">'.$likedUsernames[$x].'</a></li>';
        }

    echo
        '</ol></div>
        <div class="post-time">'.$postDate[$i].'</div>
        <div class="post-img">
            <img style="width: 400px; height: auto" src="data:image/jpg;base64,'.base64_encode($postPic[$i]).'" />
        </div>
        <div class="post-content">'.$content[$i].'</div>
        <form method="post">
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
                    <button onclick="showReplyComment('.$commentIDs[$j].')" name="replyComment" id="replyComment">Reply</button>
                    <button onclick="showAllReplies('.$commentIDs[$j].')" name="showReplies" id="showReplies">Show Replies</button>
                    <button onclick="showEditComment('.$commentIDs[$j].')" name="editComment" id="editComment">Edit</button>
                    <form method="post" id="deleteCommentHandler'.$commentIDs[$j].'" style="width:100%">
                        <input type="text" name="comment-id" value="'.$commentIDs[$j].'" style = "display:none;">
                        <input type="submit" name="removeComment'.$commentIDs[$j].'" id="removeComment" value="Remove">
                    </form></div>
                    ';
            }else{
                echo '<div id="commentActions">
                    <button onclick="showReplyComment('.$commentIDs[$j].')" name="replyComment" id="replyComment">Reply</button>
                    <button onclick="showAllReplies('.$commentIDs[$j].')" name="showReplies" id="showReplies">Show Replies</button>
                    </div>';
            }

            echo '<form method="post" id="editCommentForm'.$commentIDs[$j].'" style="display:none; align-item: center; background-color: #2b2d42; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
                <input type="text" name="comment-id" value="'.$commentIDs[$j].'" style = "display:none">
                <textarea name="c-context'.$commentIDs[$j].'" id="c-context'.$commentIDs[$j].'" row="1" class="commentTxt" style="margin: 10px"></textarea>
                <input type="submit" name="editComment'.$commentIDs[$j].'" id="editComment'.$commentIDs[$j].'" value="Edit" class="commentBtn" style="margin: 10px">
            </form>';

            echo '<form method="post" id="replyForm'.$commentIDs[$j].'" class = "actionForms">
                <input type="text" name="Rcomment-id" value="'.$commentIDs[$j].'" style = "display:none">
                <textarea name="r-context'.$commentIDs[$j].'" id="r-context'.$commentIDs[$j].'" row="1" class="commentTxt" style="margin: 10px"></textarea>
                <input type="submit" name="replyComment'.$commentIDs[$j].'" id="replyComment'.$commentIDs[$j].'" value="Reply" class="commentBtn" style="margin: 10px">
            </form>';

            require("replies.php");

            echo '</div></div><br>';
        }
        
    }

    echo '<form method="post" id="commentForm">
        <input type="text" name="post-id" value="'.$postIDs[$i].'" style = "display:none">
        <textarea name="c-context'.$postIDs[$i].'" id="c-context'.$postIDs[$i].'" row="1" class="commentTxt"></textarea>
        <input type="submit" name="comment'.$postIDs[$i].'" id="comment'.$postIDs[$i].'" value="comment" class="commentBtn">
    </form>';

    echo '</div></div><br>';
?>

<script>
    function showDropDown(id){
        var list = document.getElementById("dropDownLike"+id);
        if (list.style.display == "none"){
            list.style.display = "block";
        }else{
            list.style.display = "none";
        }
    }
</script>

