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
    $formurl=$_SESSION['formurl'];
    $sql="";
    switch($radioval){
        case "Text":
            $sql="ALTER TABLE $formurl ADD COLUMN $fieldhead varchar(100)";
            break;
        case "Number":
            $sql="ALTER TABLE $formurl ADD COLUMN $fieldhead int";
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
        <div id="makeform" class="modal">
            <form id="repeatform" class="modal-content" method="post" action="">
            <div class="container">
                <h1>Insert Fields</h1>
                <hr>
                <label for="fieldtype"><b>Field Type</b></label><br>
                <input type="radio" name="fieldtype" value="Text" checked="checked">Text
                <input type="radio" name="fieldtype" value="Number">Number<br>
                <label for="fieldhead"><b>Field Heading</b></label>
                <input type="text" placeholder="Enter Heading" name="fieldhead" required>
                <input type="hidden" name="form" value="M">
                <button type="submit" name="submit">Add</button>
            </div>
            </form>
            <form action="" method="post">
                <input type="hidden" name="form" value="F">
                <button type="submit" name="submit">Finish</button>
            </form>
        </div>
    </body>
</html>