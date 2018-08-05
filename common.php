<?php
    session_start();
    
    date_default_timezone_set('America/New_York');
    
    /*DB Config*/
    $host = "localhost";
    $name = "vexbot";
    $dbuser = "xilcho";
    $dbpassword = "";
    
    try{
        
        $dbh = new PDO("mysql:host={$host};dbname={$name}", $dbuser, $dbpassword);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
    } catch(PDOException $e){
        
        echo "Database Connection Failed: " . $e->getMessage();
    
    }
    
    function verifySession($password){
        if($password = "bigB00tyroom3"){
            return true;
        } else{
            return false;
        }
    }
?>