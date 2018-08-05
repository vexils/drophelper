<?php

include "common.php";

$locations = ['Los Santos International Airport','Vespucci Beach','the Vanilla Unicorn Strip Club','Maze Bank Tower','the Playboy Mansion'];
$id = $_GET['id'];
$timer = false;

$stmt = $dbh->prepare("SELECT status FROM sessions WHERE id=?");
$stmt->execute(array($id));
$stmt = $stmt->fetch(PDO::FETCH_ASSOC);
$stage = $stmt['status'];
switch($stage){
    case null:
        $location = $locations[array_rand($locations)];
        $textboxContent = 'Welcome to the drop! Here are some rules that will help me drop as much as possible:
1. Do not send me social club messages. Unfriend me on social club once the drop ends. 
2. Do not say anything in game chat. 
3. Do not spend or bank the money made from the drop in the drop lobby.
4. Do not invite anyone else to the drop, or share my social club name with other users.
5. Please be polite and respectful with me and other users in the drop. 

Once you have read the rules, we are ready to begin! Please follow these instructions for a smooth drop:
1. Add me on Social Club. My username is PapaPlsNoHitMe. 
2. Join my online session.
3. Head over to ' . $location . ' and the drop will begin. 
4. Once the drop ends, please switch to a new lobby and remove me as a friend from Social Club.

Please read and follow all of these instructions. It makes things much easier for me. If you have any questions, do not ask in game chat or social club. Leave your messages in this chatroom. 

Thanks!';
        $button = '<a href="?id=' . $id . '" class="button button2">Next Step</a>';
        $topText = "You have started a new drop.";
        
        $stmt = $dbh->prepare("UPDATE sessions SET status=? WHERE id=?");
        $stmt->execute(array(1,$id));
        
        break;
    case 1:
        $stmt = $dbh->prepare("SELECT * FROM sessions WHERE id=?");
        $stmt->execute(array($id));
        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);
        $time = $stmt['length'];
        $textboxContent = 'Due to popular demand, I am instituting a strict ' . $time . ' minute time limit on my drop sessions. This will allow more people to partake in drops.';
        $button = '<a href="?id='.$id.'" class="button button2">Next Step</a>';
        $topText = null;
        
        $stmt = $dbh->prepare("UPDATE sessions SET status=? WHERE id=?");
        $stmt->execute(array(2,$id));
        
        break;
    case 2:
        $textboxContent = "If you just joined this room, please read all of the rules. Don't skim them, take a minute and read them. *PLEASE PLEASE PLEASE* unadd me on social club and leave the session when the drop is over.";
        $button = '<a href="?id='.$id.'" class="button button2">Begin Timer</a>';
        $topText = null;
        
        $stmt = $dbh->prepare("UPDATE sessions SET status=? WHERE id=?");
        $stmt->execute(array(3,$id));
        
        break;
    case 3:
        $date = date('Y-m-d H:i:s');
        $stmt = $dbh->prepare("UPDATE sessions SET date=? WHERE id=?");
        $stmt->execute([$date,$id]);
        $topText = null;
        
        $stmt = $dbh->prepare("UPDATE sessions SET status=? WHERE id=?");
        $stmt->execute(array(4,$id));
        ?>
        
        <script>
            window.location.href = '?id=<?php echo $id ?>';
        </script>
        
        <?php
        
        break;
    case 4:
        $timer = true;
        $textboxContent = "";
        $button = '<a href="enddrop.php?id='.$id.'" class="button button2" id="dropLink">End Drop</a>';
        $topText = null;
        
        break;
    case 5:
        $topText = "This drop has ended.";
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
<body onload="resizeArea()">
    <?php
        $stmt = $dbh->prepare("SELECT * FROM sessions WHERE id=:id");
        $stmt->execute(array('id'=>$id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row != null){ 
            if(true){ //verifySession($_POST['password'])
                ?>
                <div id="drop">
                    <div class="content">
                        <div class="title"><?php echo $topText; ?></div>
                        <ul>
                            <li>Invited:<br> <?php echo $row['usersInvited'] ?> Players</li>
                            <li>Length:<br> <?php echo $row['length'] ?>mins</li>
                            <li>Delay:<br> <?php echo $row['delay'] ?>Ms</li>
                            <li>Bag Value:<br> $<?php echo $row['bagValue'] ?></li>
                        </ul>
                        <p id="countDown"></p>
                        <textarea id="copyPasta"><?php echo $textboxContent; ?></textarea>
                        <?php echo $button ?>
                    </div>
                </div>
                
                <?php
            } else{
                
            }
            ?>
                
            <?php
        } else{
            ?>
            <div class="centerTitle">Invalid Drop ID.</div>
            <div class="centerSubtitle">Redirecting you...</div>
            <script>
            
                setTimeout(function(){
                    window.location.href = 'index.php';
                }, 3000);
            </script>
            
            <?php
        }
    ?>
    <script>
    
        var resizeArea = function(){
            var textArea = document.getElementById("copyPasta");
            textArea.style.height = "";
            textArea.style.height = textArea.scrollHeight + 1 + "px";
        }
                
        var beginTimer = function(){
            var length = "<?php echo $row['length'] ?>";
            var string = "This drop will last " + length + " minutes. The timer begins now.";
            document.getElementById("countDown").style.display = "block";
            document.getElementById("copyPasta").innerHTML = string;
            var sqlDate = "<?php echo $row['date'] ?>";
            var startTime = Date.parse(sqlDate);
            
            var startDate = new Date(startTime).getTime();
            var countDownDate = new Date(startDate + length*60000);
            
            // Update the count down every 1 second
            var x = setInterval(function() {
            
                // Get todays date and time
                var now = new Date().getTime();
                
                var distance = countDownDate - now;
                
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // Output the result in an element with id="demo"
                if(hours != 0){
                    var timeLeft = hours + "h " + minutes + "m " + seconds + "s ";
                } else{
                    var timeLeft = minutes + "m " + seconds + "s ";
                }
                document.getElementById("countDown").innerHTML = timeLeft;
                
                // If the count down is over, write some text 
                if (distance < 0) {
                    var audio = new Audio('guccigang.mp3');
                    audio.play();
                    document.getElementById("countDown").innerHTML = "The drop has ended";
                    clearInterval(x);
                }
                
                if("<?php echo $row['status'] ?>" == 5){
                    clearInterval(x);
                    document.getElementById("countDown").innerHTML = "The drop has ended.";
                }
                
            }, 1000);
        }
    </script>
    <?php
        if($timer){
            ?>
            <script type="text/javascript">
                beginTimer();
            </script>
            <?php
        }
    ?>
</body>
</html>