<?php
    session_start();

    function endswith($string) {
        $strlen = strlen($string);
        $testlen = strlen("[]");
        if ($testlen > $strlen) return false;
        return substr_compare($string, "[]", $strlen - $testlen, $testlen) === 0;
    }

    $confirmsubmit=0;
    $timeout=0;
    $blockresp=1;
    if(!isset($_SESSION['CurrentURL'])){
        header("Location: formbuilder.html");
    }

    include("config.php");
    $link=new mysqli($server,$dbun,$dbpw,"FormBuilder");
    $url=$_SESSION['CurrentURL'];
    $tablename=$url."_Input";
    $result=$link->query("SELECT * FROM FormList WHERE FormURL='$url'");
    while($row=mysqli_fetch_assoc($result)){
        $formname=$row['FormName'];
        $formdesc=$row['FormDesc'];
        $formtimeout=$row['FormEnd'];
        $resplimit=$row['RespLimit'];
    }

    $currenttime=date("Y-m-d H:i:s");
    if(!empty($formtimeout) && $currenttime>$formtimeout){
        $timeout=1;
    }

    if(isset($_POST['submit'])){
        $UN=$_SESSION['currentuser'];
        $sql="INSERT INTO $url VALUES ('$UN',";

        $result=$link->query("SELECT InpName FROM $tablename");
        $x=mysqli_num_rows($result);
        $lastname="";
        while($row=mysqli_fetch_assoc($result)){
            $insert="";
            $fn=$row['InpName'];
            if(endswith($fn)){
                $fn=rtrim($fn,"[]");
                $insert=implode(",",$_POST[$fn]);
            }
            else{
                $insert=$_POST[$fn];
            }
            if($fn!=$lastname){
                $sql.="'$insert',";
            }
            $lastname=$fn;
        }
        $sql=rtrim($sql,',');
        $sql.=");";
        $link->query($sql);
        $confirmsubmit=1;
    }

    if(isset($_SESSION['limit'])){
        $blockresp=$_SESSION['limit'];
        unset($_SESSION['limit']);
    }
    else{
        if($confirmsubmit==0){
            header("Location: formlogin.php");
        }
        else{
            $blockresp=0;
        }
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
        <div class="container">
            <h4><?php echo $formdesc; ?></h4>

            <script>
                function viewresponse(){
                    window.location="showresponse.php";
                }
            </script>

            <button type='button' onclick='viewresponse();'>View Responses</button>
            <hr>

            <?php if ($confirmsubmit==1){
                echo "<div id='confirm'>Your response has been submitted successfully!</div>";
                }
                if($timeout==1){
                    echo "<div id='expired'>This form's deadline is over.</div>";
                }
                elseif($blockresp==1){
                    echo "<div id='expired'>You have already submitted maximum possible number of responses.</div>";
                }

            ?>
            <form method="post" action="">
                <?php
                if($confirmsubmit==0 && $timeout==0 && $blockresp==0){
                    $result=$link->query("SELECT * FROM $tablename");
                    $count=1;
                    $prevhead="";
                    while($row=mysqli_fetch_assoc($result)){
                        $field=$row['Heading'];
                        $type=$row['InpType'];
                        $fieldname=$row['InpName'];
                        $fieldval=$row['InpValue'];
                        if($field!=$prevhead){
                            echo "<label for='$fieldname'><b>".$field."</b></label><br>";
                        }
                        if($type=="Number"){
                            echo "<input type='number' placeholder='Enter Response' name='$fieldname' value='$fieldval' required><br>";
                        }
                        elseif($type=="Text"){
                            echo "<input type='text' placeholder='Enter Response' name='$fieldname' value='$fieldval' required><br>";
                        }
                        elseif($type=="Radio"){
                            echo "<input type='radio' name='$fieldname' value='$fieldval' required>$fieldval<br>";
                        }
                        elseif($type=="Checkbox"){
                            echo "<input type='checkbox' name='$fieldname' value='$fieldval' required>$fieldval<br>";
                        }
                        $prevhead=$field;
                        $count++;
                    }
                
                echo "<button type='submit' name='submit'>Submit</button>";
                }
                ?>
            </form>
        </div>
    </body>
</html>