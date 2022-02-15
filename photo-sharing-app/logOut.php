<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        session_destroy();
        echo "Logged out. Redirecting to index after 3 seconds...";
        header( "refresh:3;url=index.php");
    ?>
</body>
</html>