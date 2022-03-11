<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Following/Followers</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php require("title.php"); ?>    <!-- require navi file -->
    <div class="content">
        <?php
            require("config.php");
            $sql = $conn->prepare("SELECT * FROM Follows");
            $sql -> execute();
            $result = $sql -> fetchAll();

            $id = array_column($result, 'User_ID');
            $followers = array_column($result, 'Follower_ID');

            echo '<table name="followersTable" class="followTable">
                    <tr>
                        <th colspan="2">Followers</th>
                    </tr>';

                for ($i = 0; $i < count($id); $i++){
                    if ($id[$i] == $current_userID){
                        $sql = $conn->prepare("SELECT * FROM Users WHERE ID = $followers[$i]");
                        $sql->execute();

                        $result = $sql->fetchAll();

                        $name = array_column($result, 'Username');
                        $email = array_column($result, 'Email');
                        $bio = array_column($result, 'Bio');
                        $profilePic = array_column($result, 'Profile_pic');

                        echo 
                        '<tr>
                            <td><img style="width: 100px; height: auto" src="data:image/jpg;base64,'.base64_encode($profilePic[0]).'" />'.$name[0].'</td>
                            <td><a href="removeFollow.php?type=0&id='.$followers[$i].'">Remove</td>
                        </tr>';
                    }
                }

            echo '</table>';

            echo '<table name="followingTable" class="followTable">
                    <tr>
                        <th colspan="2">Following</th>
                    </tr>';

                for ($i = 0; $i < count($id); $i++){
                    if ($followers[$i] == $current_userID){
                        $sql = $conn->prepare("SELECT * FROM Users WHERE ID = $id[$i]");
                        $sql->execute();

                        $result = $sql->fetchAll();

                        $name = array_column($result, 'Username');
                        $email = array_column($result, 'Email');
                        $bio = array_column($result, 'Bio');
                        $profilePic = array_column($result, 'Profile_pic');

                        echo 
                        '<tr>
                            <td><img style="width: 100px; height: auto" src="data:image/jpg;base64,'.base64_encode($profilePic[0]).'" />'.$name[0].'</td>
                            <td><a href="removeFollow.php?type=1&id='.$id[$i].'">Remove</td>
                        </tr>';
                    }
                }
                
            echo '</table>';
        ?>
    </div>
</body>
</html>