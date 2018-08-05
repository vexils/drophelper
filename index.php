<?php

include "common.php";

if(isset($_POST['password'])){
    
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Vexbot 3000</title>
	
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
</head>
<body>
    <?php
        if(verifySession($_POST['password'])){
            ?>
                <div id="home">
                    <div class="content">
                        <div class="welcome">Welcome back Vexils!</div>
                        <ul>
                            <?php
                            
                                $stmt = $dbh->prepare("SELECT * FROM sessions WHERE status=?");
                                $stmt->execute(array(4));
                                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                                if($row != null){
                                    ?>
                                    <li><a href="drop.php?id=<?php echo $row['id'] ?>" class="button">Ongoing Drops</a></li>
                                    <?php
                                } else{
                                    ?>
                                    <li><a href="beginDrop.php" class="button">Begin Drop</a></li>
                                    <?php
                                }
                            
                            ?>
                            <li><a href="" class="button">Full Stats</a></li>
                        </ul>
                    </div>
                </div>
            <?php
        } else{
            ?>
            
                <div id="auth">
                    <form method='POST'>
                        <label>Please Enter Password:</label>
                        <input type="password" name="password">
                    </form>
                </div>
            
            <?php
        }
    ?>
</body>
</html>