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
}

if(isset($_POST['submit'])){
    header("Location: FormPages/".$url.".php");
    die();
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $formname; ?></title>
        <link rel="stylesheet" href="formstyle.css">
    </head>
    <body>
        <h1><?php echo $formname; ?></h1>
        <h4 class="nexthead">Responses</h4>
        <div class="formtable">
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
            <form method="post" action="">
                <button type="submit" name="submit">Go Back To Form</button>
            </form>
        </div>
    </body>
</html>