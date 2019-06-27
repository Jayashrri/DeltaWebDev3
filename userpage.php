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
        $count=$link->query("SELECT COUNT(*) FROM FormList");
        $count++;
        $url=base_convert(number_format($count),10,32);
        return $url;
    }
    
    function newform(){
        $link=new mysqli("localhost","root","pass123","FormBuilder");
        $username=$_SESSION['username'];
        $formname=$_POST['formname'];
        $formdesc=$_POST['formdesc'];
        $formurl=generateURL().".php";
        $_SESSION['formname']=$formname;
        $sql="INSERT INTO FormList VALUES ('$formname', '$formurl', '$username', '$formdesc')";
        $link->query($sql);
        $link->commit();
        $sql="CREATE TABLE $formname (Num int)";
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
        <h3 id="greeting">Welcome, <?php
                        echo $_SESSION['username'];
                    ?></h3>

        <button onclick="document.getElementById('createform').style.display='block'">Create Form</button>
        <div id="createform" class="modal">
            <span onclick="document.getElementById('createform').style.display='none'"
            class="close" title="Close">&times;</span>
            <form class="modal-content" method="post" action="">
                <label for="formname"><b>Form Name</b></label>
                <input type="text" placeholder="Enter Form Name" name="formname" required="required">
                <label for="formdesc"><b>Form Name</b></label>
                <textarea rows="5" cols="30" name="formdesc"></textarea>
                <button type="submit" name="submit">Create Form</button>
            </form>
        </div>
    </body>
</html>