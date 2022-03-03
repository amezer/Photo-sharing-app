<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="content" style="margin-top:0">
        <form action="signUpHandler.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="username" id="username" placeholder="Enter Username">
            <br>
            <input type="text" name="email" id="email" placeholder="Enter Email">
            <br>
            <input type="password" name="password" id="password" placeholder="Enter Password">
            <br>
            <input type="file" name="p-image" id="p-image" accept="image/*" onchange="loadFile(event)">
            <br>
            <img id="img-preview" style="width: 400px; height: auto">
            <br>
            <textarea name="bio" id="bio" cols="30" rows="10">Hi</textarea>
            <br>
            <input type="submit" value="Sign Up">
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
    </div>
</body>
</html>