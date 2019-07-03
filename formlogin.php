<?php
session_start();
include("config.php");

if(!isset($_SESSION['CurrentURL'])){
    header("Location: formbuilder.html");
}

$link=new mysqli($server,$dbun,$dbpw,"FormBuilder");
$url=$_SESSION['CurrentURL'];
$tablename=$url."_Input";
$result=$link->query("SELECT * FROM FormList WHERE FormURL='$url'");
while($row=mysqli_fetch_assoc($result)){
    $formname=$row['FormName'];
    $resplimit=$row['RespLimit'];
}

$dispmessage="";

if(isset($_POST['submit'])){
    if($resplimit!=0){
        $UN=$_POST['username'];
        $PW=$_POST['psw'];
        $result=$link->query("SELECT * FROM UserDets WHERE UN='$UN' AND PW='$PW'");
        if(mysqli_num_rows($result)==1){
            $result=$link->query("SELECT * FROM $url WHERE UN='$UN'");
            $submitted=mysqli_num_rows($result);
            if($submitted<$resplimit){
                $_SESSION['limit']=0;
                $_SESSION['currentuser']=$UN;
            }
            else{
                $_SESSION['limit']=1;
            }
            header("Location: formpage.php");
            die();

        }
        else{
            $dispmessage="Invalid Credentials";
        }
    }
    else{
        $UN=$_POST['username'];
        $_SESSION['limit']=0;
        $_SESSION['currentuser']=$UN;
        header("Location: formpage.php");
        die();
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $formname; ?></title>
        <link rel="stylesheet" href="responsestyle.css">
    </head>
    <body>
        <?php if(!empty($dispmessage)){ echo "<h4 class='nexthead'>$dispmessage</h4>"; }?></h4>
        <h4 class='nexthead'>Please login to continue.</h4>
        <button type="button" id="login" onclick="document.getElementById('loginform').style.display='block'">Login</button>

        <div id="loginform" class="modal">
            <span onclick="document.getElementById('loginform').style.display='none'"
            class="close" title="Close">&times;</span>
            <form class="modal-content" action="" method="POST">
                <div class="container">
                    <h1>Log In</h1>
                    <hr>
                    <label for="username"><b>Username</b></label>
                    <input type="text" placeholder="Enter Username" name="username" required>
        
                    <label for="psw"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="psw" required>
        
                    <div class="container">
                        <button type="button" onclick="document.getElementById('loginform').style.display='none'" class="cancelbtn">Cancel</button>
                        <button type="submit" name="submit">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
