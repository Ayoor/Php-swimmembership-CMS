<?php
include "connect.php";
session_start();
if ($_POST) {
    $role = $_SESSION['role'];
    if ($role == "Admin") {
        $oldeventname = mysqli_real_escape_string($link, $_POST["oldeventname"]);
        $eventname = mysqli_real_escape_string($link, $_POST["eventname"]);
        $venue = mysqli_real_escape_string($link, $_POST['venue']);
        $desc = mysqli_real_escape_string($link, $_POST['eventdescription']);
        $eventdate = mysqli_real_escape_string($link, $_POST['startdate']);
        $fee = mysqli_real_escape_string($link, $_POST['fee']);

        $checkquery = "SELECT Gala.eventName FROM Gala WHERE Gala.eventName = '$eventname'";
        $checkresult = mysqli_query($link, $checkquery);
        
        if($fee==0){
            $fee= "Free";
        }
        else{
            $feeString = strval($fee);
        $fee = "£".$fee;
        }

        if ($checkresult && mysqli_num_rows($checkresult) > 0) {
            
            echo "Event already exists. Please choose a different name.";
            exit();
            
        } else {
            $updatequery = "UPDATE `Gala`
                SET `eventName` = '$eventname', `venue` = '$venue', `date` = '$eventdate',
                    `fee` = '$fee', `description` = '$desc' WHERE `Gala`.`eventName` = '$oldeventname';";


            if (mysqli_query($link, $updatequery)) {
                $issuccess = true;
                header("Location:galaevents.php");
            } else {
                echo "Failed to update the Event. Please try again later.";
            }
        }
    } else {
        echo "<script>alert('You don\'t have permission to do that sorry!')</script>";
    }
}
?>
