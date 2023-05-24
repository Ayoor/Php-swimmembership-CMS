<?php
include "connect.php";

if ($_POST) {
    $eventname = $_POST["eventname"];
    $venue = $_POST['venue'];
    $desc = $_POST['eventdescription'];
    $eventdate = $_POST['startdate'];
    $fee = $_POST['fee'];

    $query = "SELECT eventName FROM `Gala` WHERE eventName = ?";
    
    // Prepare the select statement
    $stmt = mysqli_prepare($link, $query);
    
    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "s", $eventname);
    
    // Execute the prepared statement
    mysqli_stmt_execute($stmt);
    
    // Get the result
    mysqli_stmt_store_result($stmt);
    
    // Check if the event already exists
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "Event already exists";
        exit();
    } else {
        // Prepare the insert statement
        $insertQuery = "INSERT INTO `Gala`(`id`, `eventName`, `venue`, `date`, `fee`, `description`) 
                        VALUES (NULL, ?, ?, ?, ?, ?)";
    
        // Create a prepared statement
        $stmt = mysqli_prepare($link, $insertQuery);
    
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "sssss", $eventname, $venue, $eventdate, $fee, $desc);
    
        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $issuccess = true;
            header("Location:galaevents.php");
            exit();
        } else {
            echo "An error occurred while adding the event";
            exit();
        }
    }
}
?>
