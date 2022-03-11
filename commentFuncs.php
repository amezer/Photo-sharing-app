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
                if(document.getElementById("replyForm"+id).style.display === "flex"){
                    document.getElementById("replyForm"+id).style.display = "none";
                }
            }
        }

        function showReplyComment(id){
            var tmp = document.getElementById("replyForm"+id);
            if(tmp.style.display === "flex"){
                tmp.style.display = "none";
            }else{
                tmp.style.display = "flex";
                if(document.getElementById("editCommentForm"+id).style.display === "flex"){
                    document.getElementById("editCommentForm"+id).style.display = "none";
                }
            }
        }

        function showAllReplies(id){
            var tmp = document.getElementById("replies"+id);
            if(tmp.style.display === "flex"){
                tmp.style.display = "none";
            }else{
                tmp.style.display = "flex";
            }
            
        }        
    </script>
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
            $dbServername = "localhost";
            $dbUsername = "root";
            $dbPassword = "Z3(sz83Nva-nnYR9";

            $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "DELETE FROM Comments WHERE Comment_ID = '$commentID'";
            //also need to delete the replies
            $conn -> exec($sql);
            header("refresh: 0;");
        }

        if(isset($_POST['replyComment'.$_POST['Rcomment-id']])) {
            addReply($_POST['Rcomment-id'], $_POST['r-context'.$_POST['Rcomment-id']]);
            unset($_POST['replyForm'.$_POST['Rcomment-id']]);
        }

        function addReply($commentID, $context){
            session_start();
            $id = $_SESSION['id'];
            $dbServername = "localhost";
            $dbUsername = "root";
            $dbPassword = "Z3(sz83Nva-nnYR9";

            $conn = new PDO("mysql:host=$dbServername;dbname=photo_sharing_app", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO `Replies` (`Comment_ID`, `Replier_ID` ,`Context`) VALUES ('$commentID', '$id', '$context')";
            $conn -> exec($sql);
            header("refresh: 0;");
        }

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