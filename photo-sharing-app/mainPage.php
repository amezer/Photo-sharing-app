<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
</head>
<body>
    <form action="viewPosts.php">
        <input type="submit" value="View Posts">
    </form>
    
    <button onclick="showForm()">Post Contents</button>

    <form id="post-content-form" action="postContents.php" method="POST" enctype="multipart/form-data" style="display:none">
        <textarea name="p-content" id="p-content" cols="30" rows="10"></textarea>
        <input type="file" name="p-image" id="p-image" accept="image/*" onchange="loadFile(event)">
        <img id="img-preview" style="width: 400px; height: auto">
        <input type="submit" value="Post">
    </form>

    <script>
        function showForm(){
            document.getElementById("post-content-form").style.display = "block";
        }

        var loadFile = function(event) {
            var preview = document.getElementById('img-preview');
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.onload = function() {
            URL.revokeObjectURL(preview.src) // free memory
            }
        };
    </script>

    <form action="logOut.php">
        <input type="submit" value="Log Out">
    </form>
</body>
</html>