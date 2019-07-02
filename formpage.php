<?php
    session_start();

    $confirmsubmit=0;
    if(!isset($_SESSION['CurrentURL'])){
        header("Location: formbuilder.html");
    }

    include("config.php");
    $link=new mysqli($server,$dbun,$dbpw,"FormBuilder");
    $url=$_SESSION['CurrentURL'];
    $tablename=$url."Input";
    $result=$link->query("SELECT * FROM FormList WHERE FormURL='$url'");
    while($row=mysqli_fetch_assoc($result)){
        $formname=$row['FormName'];
        $formdesc=$row['FormDesc'];
    }

    if(isset($_POST['submit'])){
        $result=$link->query("SELECT MAX(NUM) FROM $url");
        $row=mysqli_fetch_assoc($result);
        $newnum=$row['MAX(NUM)'];
        $newnum++;
        $sql="INSERT INTO $url VALUES ('$newnum',";

        $result=$link->query("SELECT InpName FROM $tablename");
        $x=mysqli_num_rows($result);
        while(($row=mysqli_fetch_assoc($result)) && $x>1){
            $fn=$row['InpName'];
            $insert=$_POST[$fn];
            $sql.="'$insert',";
        }
        $row=mysqli_fetch_assoc($result);
        $sql.="'$insert')";
        $link->query($sql);
        $confirmsubmit=1;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $formname; ?></title>
        <link rel="stylesheet" href="formstyle.css">
    </head>
    <body>
        <script>
            function viewresponse() {
                window.location="showresponse.php";
            }
        </script>

        <h1><?php echo $formname; ?></h1>
        <div class="container">
            <h4><?php echo $formdesc; ?></h4>
            <button type='button' onclick='viewresponse();'>View Responses</button>
            <hr>

            <?php if ($confirmsubmit==1){
                echo "<div id='confirm'>Your response has been submitted successfully!</div>";
                }
            ?>
            <form method="post" action="">
                <?php
                if($confirmsubmit==0){
                    $result=$link->query("SELECT * FROM $tablename");
                    $count=1;
                    while($row=mysqli_fetch_assoc($result)){
                        $field=$row['Heading'];
                        $type=$row['InpType'];
                        $fieldname=$row['InpName'];
                        $fieldval=$row['InpValue'];
                        echo "<label for='$fieldname'><b>".$field."</b></label>";
                        if($type=="Number"){
                            echo "<input type='number' placeholder='Enter Response' name='$fieldname' value='$fieldval' required><br>";
                        }
                        elseif($type=="Text"){
                            echo "<input type='text' placeholder='Enter Response' name='$fieldname' value='$fieldval' required><br>";
                        }
                        elseif($type=="Radio"){
                            echo "<input type='radio' placeholder='Enter Response' name='$fieldname' value='$fieldval' required><br>";
                        }
                        elseif($type=="Checkbox"){
                            echo "<input type='checkbox' placeholder='Enter Response' name='$fieldname' value='$fieldval' required><br>";
                        }

                        $count++;
                    }
                
                echo "<button type='submit' name='submit'>Submit</button>";
                }
                ?>
            </form>
        </div>
    </body>
</html>