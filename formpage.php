<?php
    session_start();
    $confirmsubmit=0;

    $link=new mysqli("localhost","root","pass123","FormBuilder");
    $url="t_0";
    $result=$link->query("SELECT * FROM FormList WHERE FormURL='$url'");
    while($row=mysqli_fetch_assoc($result)){
        $formname=$row['FormName'];
        $formdesc=$row['FormDesc'];
    }

    if(isset($_POST['submit'])){
        $result=$link->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$url'");
        $row=mysqli_fetch_assoc($result);
        $colnum=(int)$row['COUNT(*)'];
        $colnum--;
        $result=$link->query("SELECT MAX(NUM) FROM $url");
        $row=mysqli_fetch_assoc($result);
        $newnum=$row['MAX(NUM)'];
        $newnum++;
        $sql="INSERT INTO $url VALUES ('$newnum',";
        for($x=1;$x<$colnum;$x++){
            $insert=$_POST[$x];
            $sql.="'$insert',";
        }
        $insert=$_POST[$x];
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
        <h1><?php echo $formname; ?></h1>
        <div class="container">
            <h4><?php echo $formdesc; ?></h4>
            <hr>
            <?php if ($confirmsubmit==1){
                echo "<div id='confirm'>Your response has been submitted successfully!</div>";
                }
            ?>
            <form method="post" action="">
                <?php
                    $result=$link->query("SHOW COLUMNS FROM $url");
                    $count=1;
                    while($row=mysqli_fetch_assoc($result)){
                        $field=$row['Field'];
                        $type=$row['Type'];
                        if($field!="Num"){
                            echo "<label for='".$count."'><b>".$field."</b></label>";
                            if($type="int(11)"){
                                echo "<input type='number' placeholder='Enter Response' name='".$count."' required><br>";
                            }
                            elseif($type="varchar(100)"){
                                echo "<input type='text' placeholder='Enter Response' name='".$field."' required><br>";
                            }
                            $count++;
                        }
                    }
                ?>
                <button type="submit" name="submit">Submit</button>
            </form>
        </div>
    </body>
</html>