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

    if(count($replierIDs) == 0){
        echo '<div id="noReplies">No replies yet.</div>';
    }else{
        for($m = 0; $m < count($replyIDs); $m++){
            if($replyCommentIDs[$m] == $commentIDs[$j]){
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
                </div></div>';
            }
        }
    }
?>