<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php require("title.php"); ?> <!-- require navi file -->
    <div class="content">
        <?php
            $name = $_POST['searchUsername'];

            require("config.php");

            $stmt = $conn -> prepare("SELECT * FROM Users WHERE Username LIKE ?"); //find similar usernames

            $name = "%$name%";

            $stmt -> bindParam(1, $name);

            $stmt -> execute();

            $row = $stmt -> fetch();
            
            for($i = 0; $i < count($row['Username']); $i++){
                echo '<a href = "display.php?id='.$row['ID'].'" style="display: flex; align-item: center">'.'<img style="width: auto; height: 100px; margin-top: 0" src="data:image/jpg;base64,'.base64_encode($row['Profile_pic']).'" /><div style="font-size: 40px; padding: 30px">'.$row['Username'].'</div></a>';
            }
        ?>
    </div>
</body>
</html>