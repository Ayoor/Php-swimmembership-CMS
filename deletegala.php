<?php
session_start();
include "connect.php";
$role = $_SESSION["role"];

if (isset($_GET["event"])) {
    if ($role == "Admin") {
        $event = mysqli_real_escape_string($link, $_GET["event"]);
        
        $retrieveQuery = "SELECT * FROM Gala WHERE `Gala`.`eventName` = ?";
        $deleteQuery = "DELETE FROM Gala WHERE `Gala`.`eventName` = ?";
        
        // Prepare the retrieve statement
        $stmt = mysqli_prepare($link, $retrieveQuery);
        
        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "s", $event);
        
        // Execute the prepared statement
        mysqli_stmt_execute($stmt);
        
        // Get the result
        $result = mysqli_stmt_get_result($stmt);
        
        // Fetch a row from the result
        $row = mysqli_fetch_array($result);
        
        if ($row) {
            // Prepare the delete statement
            $stmt = mysqli_prepare($link, $deleteQuery);
            
            // Bind the parameter
            mysqli_stmt_bind_param($stmt, "s", $event);
            
            // Execute the prepared statement
            mysqli_stmt_execute($stmt);
            
            header("Location: galaevents.php");
            exit();
        }
    } else {
        echo "You don't have permission to do that sorry!";
        exit();
    }
}
?>
