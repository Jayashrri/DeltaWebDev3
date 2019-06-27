<?php
include("database.php");

function redirect() {
    ob_start();
    header('Location: userpage.php');
    ob_end_flush();
    die();
}

function loginuser() {
    $link=new mysqli("localhost","root","pass123","FormBuilder");
    $un=$_POST['username'];
    $pw=$_POST['psw'];
    $sql="SELECT UN,PW FROM UserDets WHERE UN='$un' AND PW='$pw'";
    $check=$link->query($sql);
    if($check->num_rows==1){
        $_SESSION['username']=$un;
        redirect();
    }
    else{
        echo "Invalid Credentials";
    }
    $link->commit();
    $link->close();
}

function signupuser() {
    $link=new mysqli("localhost","root","pass123","FormBuilder");
    $un=$_POST['username'];
    $pw=$_POST['psw'];
    $pwr=$_POST['psw-repeat'];
    $sql="SELECT UN FROM UserDets WHERE UN='$un'";
    $check=$link->query($sql);
    if($check->num_rows==0){
        if($pw==$pwr){
            $sql="INSERT INTO UserDets VALUES ('$un','$pw')";
            $link->query($sql);
            echo "Signup Successful";
        }
        else{
            echo "Passwords Do Not Match";
        }
    }
    else{
        echo "Username Already Exists";
    }
    $link->commit();
    $link->close();
}

session_start();
switch($_POST['form']){
    case 'L':
        loginuser();
        break;
    case 'S':
        signupuser();
        break;
}
?>