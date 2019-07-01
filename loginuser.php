<?php
include("database.php");

function redirect() {
    ob_start();
    header('Location: userpage.php');
    ob_end_flush();
    die();
}

function loginuser() {
    include("config.php");
    $link=new mysqli($server,$dbun,$dbpass,"FormBuilder");
    $un=$_POST['username'];
    $pw=$_POST['psw'];
    global $dispmessage;
    $sql="SELECT UN,PW FROM UserDets WHERE UN='$un' AND PW='$pw'";
    $check=$link->query($sql);
    if($check->num_rows==1){
        $_SESSION['username']=$un;
        redirect();
    }
    else{
        $dispmessage="Invalid Credentials";
    }
    $link->commit();
    $link->close();
}

function signupuser() {
    include("config.php");
    $link=new mysqli($server,$dbun,$dbpw,"FormBuilder");
    $un=$_POST['username'];
    $pw=$_POST['psw'];
    $pwr=$_POST['psw-repeat'];
    global $dispmessage;
    $sql="SELECT UN FROM UserDets WHERE UN='$un'";
    $check=$link->query($sql);
    if($check->num_rows==0){
        if($pw==$pwr){
            $sql="INSERT INTO UserDets VALUES ('$un','$pw')";
            $link->query($sql);
            $dispmessage="Signup Successful";
        }
        else{
            $dispmessage="Passwords Do Not Match";
        }
    }
    else{
        $dispmessage="Username Already Exists";
    }
    $link->commit();
    $link->close();
}

session_start();
$dispmessage="";
switch($_POST['form']){
    case 'L':
        loginuser();
        break;
    case 'S':
        signupuser();
        break;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Form Builder</title>
        <link rel="stylesheet" href="homepage.css">
    </head>
    <body>
        <h4 class="pagehead"><?php echo $dispmessage; ?></h4>
        <script>
            function backtohome(){
                window.location="formbuilder.html";
            }
        </script>

        <button class="firstbutton" type="button" onclick="backtohome();">Go Back To Home</button>
    </body>
</html>