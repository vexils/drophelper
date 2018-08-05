<?php
    
    include "common.php";
    
    unset($_SESSION['responseMessage']);
    
    function beginDrop($dbh){
        $inputs = ['date'=>date('Y-m-d H:i:s'),
                'usersInvited'=>$_POST['invited'],
                'length'=>$_POST['length'],
                'delay'=>$_POST['delay'],
                'bagValue'=>$_POST['bagvalue']
                ];
    
        foreach($inputs as $key => $value){
            if(!isset($value) || empty($value)){
                $_SESSION['responseMessage'] = "Error! The " . $key . " field is invalid.";
                return false;
            }
            if($key != 'date' && !is_numeric($value)){
                $_SESSION['responseMessage'] = "Error! The " . $key . " field is not a number.";
                return false;
            } else{
                if($value < 0 || $value > 10000){
                    $_SESSION['responseMessage'] = "Error! The " . $key . " field is incorrect.";
                    return false;
                }
            }
        }
        
        $id = openssl_random_pseudo_bytes(4) . openssl_random_pseudo_bytes(4);
        $id = bin2hex($id);
        $inputs['id'] = $id;
        $inputs['status'] = null;
        
        $stmt = $dbh->prepare("INSERT INTO sessions(id,status,date,usersInvited,length,delay,bagValue) VALUES(:id,:status,:date,:usersInvited,:length,:delay,:bagValue)");
        $stmt->execute($inputs);
        
        if($stmt){
            $_SESSION['responseMessage'] = "The drop was successfully started. Redirecting";
            ?>
            
            <script>
                window.location.href = 'drop.php?id=<?php echo $id ?>';
            </script>
            
            <?php
            return true;
        } else{
            $_SESSION['responseMessage'] = "Error! The query failed!";
            return false;
        }
        
    }
    
    if(isset($_POST['submit'])){
        beginDrop($dbh);
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
        
        if(true){ //verifySession($_POST['password'])
            if(!empty($_SESSION['responseMessage'])){
                ?>
                <div class="responseMessage">
                    <?php echo $_SESSION['responseMessage']; ?>
                </div>
                <?php
            }
            ?>
                <div id="beginDrop">
                    Begin a New Drop
                    <div class="content">
                        <form method="POST">
                            <div class="formBlock">
                                <label for="invited">Users Invited:</label>
                                <input type="number" name="invited" value="20">
                            </div><div class="formBlock">
                                <label for="length">Drop Length (Minutes):</label>
                                <input type="number" name="length" value="30">
                            </div><div class="formBlock">
                                <label for="delay">Drop Delay (Ms):</label>
                                <input type="number" name="delay" value="935">
                            </div><div class="formBlock">
                                <label for="bagvalue">Bag Value (USD):</label>
                                <input type="number" name="bagvalue" value="2500">
                            </div>
                            <button class="button" name="submit">Submit</button>
                        </form>
                    </div>
                </div>
            <?php
        } else{
            ?>
            
            <script type="text/javascript">
                window.location.href = 'login.php';
            </script>
            
            <?php
        }
    ?>
</body>
</html>