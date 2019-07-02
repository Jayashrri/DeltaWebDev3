<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: formbuilder.html");
}

if(!isset($_SESSION['CurrentURL'])){
    header("Location: formbuilder.html");
}

include("config.php");
$link=new mysqli($server,$dbun,$dbpw,"FormBuilder");
$url=$_SESSION['CurrentURL'];
$result=$link->query("SELECT * FROM FormList WHERE FormURL='$url'");
while($row=mysqli_fetch_assoc($result)){
    $formname=$row['FormName'];
    $formdesc=$row['FormDesc'];
    $permissions=$row['FormPermissions'];
    $owner=$row['FormOwner'];
}
if($permissions=="Creator Only"){
    $showresponse=0;
}
else{
    $showresponse=1;
}

if(isset($_POST['submit'])){
    $sql="SELECT * FROM UserDets WHERE UN='$owner'";
    $result=$link->query($sql);
    $row=mysqli_fetch_assoc($result);
    $givenun=$_POST['username'];
    $givenpw=$_POST['psw'];
    if($givenun==$row['UN'] && $givenpw==$row['PW']){
        $showresponse=1;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $formname; ?></title>
        <link rel="stylesheet" href="responsestyle.css">
        <script src="responsedisplay.js" async></script>
    </head>
    <body>
        <h1 class="pagehead"><?php echo $formname; ?></h1>
        <h4 class="nexthead">Responses</h4>
        <div id="notallowed" <?php if ($showresponse==1){ echo 'style="display:none;"'; } ?>>
            <b>You are not allowed to view the responses to this form unless you're the owner.</b><br>
            <hr>
            <button type="button" id="login" onclick="document.getElementById('loginform').style.display='block'">Login</button>
        </div>

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

        <div class="formtable" <?php if ($showresponse==0){ echo 'style="display:none;"'; } ?>>
            <table>
                <tr>
                <?php
                    $headings=array();
                    $result=$link->query("SHOW COLUMNS FROM $url");
                    while($row=mysqli_fetch_assoc($result)){
                        echo "<th>".$row['Field']."</th>";
                        array_push($headings,$row['Field']);
                    }
                ?>
                </tr>
                <?php
                    $result=$link->query("SELECT * FROM $url");
                    while($row=mysqli_fetch_assoc($result)){
                        echo "<tr>";
                        foreach($headings as $title){
                            echo "<td>".$row["$title"]."</td>";
                        }
                        echo "</tr>";
                    }
                ?>
            </table>
            <br>
            <button type="button" id="backtoform" onclick="window.location='formpage.php'">Go Back To Form</button>
        </div>
    </body>
</html>