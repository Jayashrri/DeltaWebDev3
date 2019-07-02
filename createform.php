<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: formbuilder.html");
}

if(!isset($_SESSION['formurl'])){
    header("Location: formbuilder.html");
}

function redirect() {
    ob_start();
    header('Location: userpage.php');
    ob_end_flush();
    die();
}

function addfield(){
    include("config.php");
    $link=new mysqli($server,$dbun,$dbpw,"FormBuilder");
    $fieldhead=$_POST['fieldhead'];
    $radioval=$_POST['fieldtype'];
    $fieldname=$_POST['fieldname'];
    $fieldvalue=$_POST['fieldvalue'];

    $formurl=$_SESSION['formurl'];
    $tablename=$formurl."Input";

    $sql="INSERT INTO $tablename VALUES ('$fieldhead','$radioval','$fieldname','$fieldvalue')";
    $link->query($sql);
    
    if($radioval=="Number"){
        $sql="ALTER TABLE $formurl ADD COLUMN `$fieldhead` int";
    }
    else {
        $sql="ALTER TABLE $formurl ADD COLUMN `$fieldhead` varchar(100)";
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
        <script src="formfield.js" async></script>
    </head>
    <body>
        <div id="makeform" class="modal">
            <form id="repeatform" class="modal-content" method="post" action="">
            <div class="container">
                <h1>Insert Input Fields</h1>
                <p>Please add the desired input fields for the form, where the Name, Type, and Value values hold the same meaning as their corresponding HTML input attributes do.</p>
                <hr>
                <div id="selecttype">
                    <label for="fieldtype"><b>Field Type</b></label><br>
                    <input type="radio" name="fieldtype" id="t1" value="Text" required>Text
                    <input type="radio" name="fieldtype" id="t2" value="Radio">Radio
                    <input type="radio" name="fieldtype" id="t3" value="Checkbox">Checkbox
                    <input type="radio" name="fieldtype" id="t4" value="Number">Number<br>
                </div>
                <div id="forsingle">
                    <label for="fieldhead"><b>Field Heading</b></label>
                    <input type="text" placeholder="Enter Heading" name="fieldhead" required>

                    <label for="fieldname"><b>Identifying Name</b></label>
                    <input type="text" placeholder="Enter Name" name="fieldname" id="singlename" required>
                </div>
                <div id="formultiple">
                    <label for="fieldhead"><b>Field Heading</b></label>
                    <input type="text" placeholder="Enter Heading" name="fieldhead" required>

                    <label for="fieldname"><b>Identifying Name</b></label>
                    <input type="text" placeholder="Enter Name" name="fieldname" id="multiname" required>

                    <label for="fieldvalue"><b>Field Value</b></label>
                    <input type="text" placeholder="Enter Value" name="fieldvalue">
                </div>
                <div id="lockbuttons">
                    <button type="button" id="nextbtn">Next</button>
                </div>
                <input type="hidden" name="form" value="M">
                <button type="submit" id="submitbtn" name="submit">Add</button>
            </form>
            <form action="" method="post">
                <input type="hidden" name="form" value="F">
                <button type="submit" name="submit">Finish</button>
            </form>
        </div>
    </body>
</html>