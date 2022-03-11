<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <script>
        var loadFile = function(event) {
            var preview = document.getElementById('img-preview');
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.onload = function() {
            URL.revokeObjectURL(preview.src) // free memory
            }
        };
    </script>
    <?php require("title.php"); ?>    <!-- require navi file -->
    <div class="content">
        <?php
            session_start();
            //fetch old information from session to prefill form
            $oldUsername = $_SESSION['username'];
            $oldProfilePic = $_SESSION['img'];
            $oldBio = $_SESSION['bio'];
            echo 
            '<form action="editProfileHandler.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="username" id="username" placeholder="Enter Username" value="'.$oldUsername.'">
                <br>
                <input type="file" name="p-image" id="p-image" accept="image/*" onchange="loadFile(event)">
                <br>
                <img id="img-preview" style="width: 400px; height: auto">
                <br>
                <textarea name="bio" id="bio" cols="30" rows="10" >'.$oldBio.'</textarea>
                <br>
                <input type="submit" value="Change">
            </form>';
        ?>
    </div>
</body>
</html>