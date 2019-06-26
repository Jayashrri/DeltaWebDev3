<?php
session_start();

function redirect() {
    ob_start();
    header('Location: userpage.php');
    ob_end_flush();
    die();
}

function addfield(){
    $link=new mysqli("localhost","root","pass123","FormBuilder");
    $fieldhead=$_POST['fieldhead'];
    $radioval=$_POST['fieldtype'];
    $formname=$_SESSION['formname'];
    $sql="";
    switch($radioval){
        case "Text":
            $sql="ALTER TABLE $formname ADD COLUMN $fieldhead varchar(100)";
            break;
        case "Number":
            $sql="ALTER TABLE $formname ADD COLUMN $fieldhead int";
            break;
    }
    $link->query($sql);
    $link->commit();
    $link->close();
}

if(isset($_POST['submit'])){
    switch($_POST['form']){
        case 'M':
            addfield();
            break;
        case 'F':
            redirect();
            break;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Form Builder</title>
        <link rel="stylesheet" href="userstyle.css">
    </head>
    <body>
        <div id="makeform">
            <form id="repeatform" class="modal-content" method="post" action="">
                <label for="fieldtype"><b>Field Type</b></label>
                <input type="radio" name="fieldtype" value="Text" checked="checked">Text
                <input type="radio" name="fieldtype" value="Number">Number<br>
                <label for="fieldhead"><b>Field Heading</b></label>
                <input type="text" placeholder="Enter Heading" name="fieldhead" required>
                <input type="hidden" name="form" value="M">
                <button type="submit" name="submit">Add</button>
            </form>
            <form action="" method="post">
                <input type="hidden" name="form" value="F">
                <button type="submit" name="submit">Finish</button>
            </form>
        </div>
    </body>
</html>