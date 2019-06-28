<?php
    session_start();

    function redirect() {
        ob_start();
        header('Location: createform.php');
        ob_end_flush();
        die();
    }

    function generateURL() {
        $link=new mysqli("localhost","root","pass123","FormBuilder");
        $result=$link->query("SELECT COUNT(*) AS total FROM FormList");
        $data=mysqli_fetch_assoc($result);
        $count=(int)$data['total'];
        $url=base_convert($count,10,32);
        $final="t_".$url;
        return $final;
    }
    
    function newform(){
        $link=new mysqli("localhost","root","pass123","FormBuilder");
        $username=$_SESSION['username'];
        $formname=$_POST['formname'];
        $formdesc=$_POST['formdesc'];
        $formurl=generateURL();
        $_SESSION['formurl']=$formurl;
        $sql="INSERT INTO FormList VALUES ('$formname', '$formurl', '$username', '$formdesc')";
        $link->query($sql);
        $link->commit();
        $sql="CREATE TABLE $formurl (Num int)";
        $link->query($sql);
        $link->commit();
        $link->close();
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
                    $link=new mysqli("localhost","root","pass123","FormBuilder");
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
                <button type="submit" name="submit">Create Form</button>
            </div>
            </form>
        </div>
    </body>
</html>