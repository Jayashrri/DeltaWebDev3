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

$fieldhead="";
$fieldname="";
$radioval="";

function addfield(){
    include("config.php");
    $link=new mysqli($server,$dbun,$dbpw,"FormBuilder");

    global $fieldhead;
    global $fieldname;
    global $radioval;
    $fieldhead=$_POST['fieldhead'];
    $radioval=$_POST['fieldtype'];
    $fieldname=$_POST['fieldname'];
    $fieldvalue=$_POST['fieldvalue'];

    $formurl=$_SESSION['formurl'];
    $tablename=$formurl."_Input";

    if($radioval=="Checkbox"){
        $fieldname.="[]";
    }

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
            <form id="repeatform" name="repeatform" class="modal-content" method="post" action="">
            <div class="container">
                <h1>Insert Input Fields</h1>
                <p>Please add the desired input fields for the form. Please ensure that the identifying name does not contain any spaces.</p>
                <hr>
                <div id="selecttype">
                    <label for="fieldtype"><b>Field Type</b></label><br>
                    <input type="radio" name="fieldtype" value="Text" required>Text
                    <input type="radio" name="fieldtype" value="Radio">Radio
                    <input type="radio" name="fieldtype" value="Checkbox">Checkbox
                    <input type="radio" name="fieldtype" value="Number">Number<br>

                    <div id="forsingle">
                        <label for="fieldhead"><b>Field Heading</b></label>
                        <input type="text" placeholder="Enter Heading" id="fieldhead" name="fieldhead" required 
                            <?php 
                                if($radioval=="Radio" || $radioval=="Checkbox"){  
                                    echo "value='$fieldhead' disabled";      
                                } 
                            ?>
                        >

                        <label for="fieldname"><b>Identifying Name</b></label>
                        <input type="text" placeholder="Enter Name" name="fieldname" id="fieldname" required
                            <?php 
                                if($radioval=="Radio" || $radioval=="Checkbox"){  
                                    echo "value='$fieldname' disabled";      
                                } 
                            ?>
                        >
                    </div>
                </div>
                <div id="formultiple">
                    <label for="fieldvalue"><b>Field Value</b></label>
                    <input type="text" placeholder="Enter Value" name="fieldvalue">
                </div>
                <input type="hidden" name="form" value="M">
                <button type="button" id="resetbtn">Reset</button>
                <button type="submit" id="submitbtn" name="submit">Add</button>
            </form>
            <form action="" method="post">
                <input type="hidden" name="form" value="F">
                <button type="submit" name="submit">Finish</button>
            </form>
        </div>
    </body>
</html>