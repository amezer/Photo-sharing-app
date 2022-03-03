<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div id = "title">
        <p>
            <a href="myPage.php">My Page</a>
        </p>
    </div>

    <div class = "content">
        <?php
            session_start();
            echo '<div class="words" style="font-size: 30px">Welcome, <div id="username">'.$_SESSION['username'].'</div></div>';
        ?>
        <form action="viewPosts.php">
            <input type="submit" value="View Posts">
        </form>

        <button onclick="showPostForm()">Post Contents</button>

        <form id="post-content-form" action="postContents.php" method="POST" enctype="multipart/form-data" style="display:none">
            <textarea name="p-content" id="p-content" cols="30" rows="10"></textarea>
            <input type="file" name="p-image" id="p-image" accept="image/*" onchange="loadFile(event)">
            <img id="img-preview" style="width: 400px; height: auto">
            <input type="submit" value="Post">
        </form>

        <script>
            function showPostForm(){
                document.getElementById("post-content-form").style.display = "block";
            }

            var loadFile = function(event) {
                var preview = document.getElementById('img-preview');
                preview.src = URL.createObjectURL(event.target.files[0]);
                preview.onload = function() {
                URL.revokeObjectURL(preview.src) // free memory
                }
            };

            function showSearchForm(){
                document.getElementById("search-form").style.display = "block";
            }
        </script>

        <br>
        
        <button onclick="showSearchForm()">Search User</button>

        <form action="searchRst.php" method="post" id = "search-form" style="display:none">
            <input type="text" name="searchUsername" id="searchUsername" placeholder="Enter Username">
            <br>
            <input type="submit" value="Search" id="searchBtn">
        </form>

        <form action="logOut.php">
            <input type="submit" value="Log Out">
        </form>
    </div>
</body>
</html>