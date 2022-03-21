<?php
    echo '<div id="replies'.$commentIDs[$j].'"" class="replies" style="display:none">';
                                
    $sql = $conn->prepare("SELECT * FROM Replies WHERE Comment_ID = $commentIDs[$j] ORDER BY Reply_ID DESC"); //fetching data for all Comments
    $sql->execute();
    $result = $sql->fetchAll();
    $replyIDs = array_column($result, 'Reply_ID');
    $replyCommentIDs = array_column($result, 'Comment_ID');
    $replierIDs = array_column($result, 'Replier_ID');
    $replyContexts = array_column($result, 'Context');
    $replyTimes = array_column($result, 'Reply_time');

    //if there are no replies for the specific comment, show No replies yet. 
    if(count($replierIDs) == 0){
        echo '<div id="noReplies">No replies yet.</div>';
    }else{
        for($m = 0; $m < count($replyIDs); $m++){
            if($replyCommentIDs[$m] == $commentIDs[$j]){ //check if the reply belongs to the current comment
                $sql = $conn->prepare("SELECT Username, Profile_pic FROM Users WHERE ID = '$replierIDs[$m]'");
                $sql->execute();
                $result = $sql->fetchAll();

                $replierName = array_column($result, 'Username')[0];
                $replierPic = array_column($result, 'Profile_pic');

                echo '<div class="reply"><div id="replyHeader">
                    <a href="display.php?id='.$replierIDs[$m].'"><img id="replierPic" style="width: auto; height: 30px" src="data:image/jpg;base64,'.base64_encode($replierPic[0]).'" />
                    <div id="replierName">'.$replierName.'</a></div>
                </div>
                <div id="replyBody">
                    <div id="replyContext">'.$replyContexts[$m].'</div>
                    <div id="replyTime">'.$replyTimes[$m].'</div>
                </div>';

                //only show edit and remove reply if the current logged in user is the one leaving the reply
                if($id == $replierIDs[$m]){
                    echo 
                    '<div id="replyActions">
                        <button onclick="showEditReply('.$replyIDs[$m].')" name="editReply" id="editReply">Edit</button>
                        <form method="post" id="deleteReplyHandler'.$replyIDs[$m].'" style="width:100%">
                            <input type="text" name="reply-id" value="'.$replyIDs[$m].'" style = "display:none;">
                            <input type="submit" name="removeReply'.$replyIDs[$m].'" id="removeReply" value="Remove" >
                        </form>
                    </div>';
                }

                echo '<form method="post" id="editReplyForm'.$replyIDs[$m].'" style="display:none; align-item: center; flex-wrap: wrap; background-color: #2b2d42; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
                    <input type="text" name="reply-id" value="'.$replyIDs[$m].'" style = "display:none">
                    <textarea name="r-context'.$replyIDs[$m].'" id="r-context'.$replyIDs[$m].'" row="1" class="commentTxt" style="margin: 10px"></textarea>
                    <input type="submit" name="editReply'.$replyIDs[$m].'" id="editReply'.$replyIDs[$m].'" value="Edit" class="commentBtn" style="margin: 10px; margin-top: 0; width: 100%; padding: 5px">
                </form>';
                echo '</div>';
            }
        }
    }
?>