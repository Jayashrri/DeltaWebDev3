<?php
    session_start();

    function redirect() {
        ob_start();
        header('Location: createform.php');
        ob_end_flush();
        die();
    }

    function createpage($url) {
        $file="FormPages/".$url.".php";
        $filelink=fopen($file,'w');
        $inserttext="<?php
                session_start();
                unset(\$_SESSION['CurrentURL']);
                \$_SESSION['CurrentURL']='$url';
                \$newpage='/formpage.php';
                header('Location: '.\$newpage);
                die();
                ?>
            ";
        fwrite($filelink,$inserttext);
        fclose($filelink);
    }

    function generateURL() {
        include("config.php");
        $link=new mysqli($server,$dbun,$dbpw,"FormBuilder");
        $result=$link->query("SELECT COUNT(*) AS total FROM FormList");
        $data=mysqli_fetch_assoc($result);
        $count=(int)$data['total'];
        $url=base_convert($count,10,32);
        $final="t_".$url;
        return $final;
    }
    
    function newform(){
        include("config.php");
        $link=new mysqli($server,$dbun,$dbpw,"FormBuilder");
        $username=$_SESSION['username'];
        $formname=$_POST['formname'];
        if(!isset($_POST['formdesc']) || empty($_POST['formdesc'])){
            $formdesc=NULL;
        }
        else{
            $formdesc=$_POST['formdesc'];
        }

        if(!isset($_POST['formdesc']) || empty($_POST['formdesc'])){
            $formdesc=NULL;
        }
        else{
            $formdesc=$_POST['formdesc'];
        }

        if(!isset($_POST['formdate']) || empty($_POST['formdate'])){
            $formdate=NULL;
        }
        else{
            $formdate=$_POST['formdate'];
        }

        if(!isset($_POST['formtime']) || empty($_POST['formtime'])){
            $formtime=NULL;
        }
        else{
            $formtime=$_POST['formtime'];
        }
        $response=$_POST['response'];

        if(!isset($_POST['resplimit']) || empty($_POST['resplimit'])){
            $resplimit=NULL;
        }
        else{
            $resplimit=$_POST['resplimit'];
        }

        if(isset($formdate) && isset($formtime)){
            $combined=date('Y-m-d H:i:s', strtotime("$formdate $formtime"));
        }
        else{
            $combined=NULL;
        }

        $formurl=generateURL();
        $_SESSION['formurl']=$formurl;
        
        $link->commit();
        $sql="CREATE TABLE $formurl (Num int)";
        $link->query($sql);
        $tablename=$formurl."_Input";
        $sql="CREATE TABLE $tablename (
            Heading varchar(100),
            InpType varchar(20),
            InpName varchar(100),
            InpValue varchar(100)
        )";
        $link->query($sql);
        $link->commit();
        $sql="INSERT INTO FormList VALUES ('$formname', '$formurl', '$username', '$formdesc', '$response', '$combined', '$resplimit')";
        $link->query($sql);
        $link->close();
        createpage($formurl);
    }

    if(!isset($_SESSION['username'])){
        header("Location: formbuilder.html");
    }

    if(isset($_POST['submit'])){
        newform();
        redirect();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Form Builder</title>
        <link rel="stylesheet" href="userstyle.css">
    </head>
    <body>
        <h1 id="pagehead">Form Builder</h1>
        <h3 class="nexthead">Welcome, <?php
                        echo $_SESSION['username'];
                    ?>
        </h3>
        <h4 class="nexthead">Created Forms</h4>
        <div id="formtable">
            <table>
                <tr>
                    <th>Form Name</th>
                    <th>Form Description</th>
                    <th>Form URL</th>
                </tr>

                <?php 
                    $username=$_SESSION['username'];
                    include("config.php");
                    $link=new mysqli($server,$dbun,$dbpw,"FormBuilder");
                    $result=$link->query("SELECT * FROM FormList WHERE FormOwner='$username'");
                    while($row=mysqli_fetch_assoc($result)){
                        echo "<tr>";
                        echo "<td>".$row['FormName']."</td>";
                        echo "<td>".$row['FormDesc']."</td>";
                        echo "<td>".$row['FormURL'].".php</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
        </div>
        <button class="firstbutton" onclick="document.getElementById('createform').style.display='block'">Create Form</button>
        <button class="firstbutton" onclick="window.location='formbuilder.html';">Logout</button>
        <div id="createform" class="modal">
            <span onclick="document.getElementById('createform').style.display='none'"
            class="close" title="Close">&times;</span>
            <form class="modal-content" method="post" action="">
            <div class="container">
                <h1>Create Form</h1><hr>
                <label for="formname"><b>Form Name</b></label>
                <input type="text" placeholder="Enter Form Name" name="formname" required="required"><br>
                
                <label for="formdesc"><b>Form Description</b></label><br>
                <textarea rows="5" cols="30" name="formdesc"></textarea>

                <label for="response"><b>Permissions for Viewing Responses</b></label><br>
                <input type="radio" name="response" value="Creator Only" required>Creator Only<br>
                <input type="radio" name="response" value="Everyone">Everyone<br>

                <label for="formdate"><b>Form Timeout Date</b></label>
                <input type="date" name="formdate"><br>

                <label for="formtime"><b>Form Timeout Time</b></label>
                <input type="time" name="formtime"><br>

                <label for="resplimit"><b>Number of Responses Per User</b></label>
                <input type="number" name="resplimit">

                <button type="submit" name="submit">Create Form</button>
            </div>
            </form>
        </div>
    </body>
</html>