<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="content">
        <form action="logInHandler.php" method="post">
            <input type="text" name="username" id="username" placeholder="Enter Username">
            <input type="text" name="email" id="email" placeholder="Enter Email">
            <input type="password" name="password" id="password" placeholder="Enter Password">
            <input type="submit" value="Log In">
        </form>
    </div>
</body>
</html>