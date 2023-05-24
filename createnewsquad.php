<?php
include "connect.php";

if ($_POST) {
    if ($role == "Admin") {
        $squadname = mysqli_real_escape_string($link, $_POST['squadname']);
        $desc = mysqli_real_escape_string($link, $_POST['description']);
        $coach = mysqli_real_escape_string($link, $_POST['coach']);

        // Check if the coach exists in the Users table
        $retrieveQuery = "SELECT CoachID FROM Squad WHERE CoachID = (SELECT id FROM Users WHERE Users.firstName = ?)";
        
        // Prepare the select statement
        $stmt = mysqli_prepare($link, $retrieveQuery);
        
        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "s", $coach);
        
        // Execute the prepared statement
        mysqli_stmt_execute($stmt);
        
        // Get the result
        mysqli_stmt_store_result($stmt);
        
        // Check if the coach exists
        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo "The coach already exists in another squad";
            exit();
        } else {
            // Insert the new squad into the Squad table
            $insertQuery = "INSERT INTO `Squad` (`id`, `Name`, `Description`, `CoachID`) VALUES (NULL, ?, ?, (SELECT id FROM Users WHERE Users.firstName = ?))";
            
            // Prepare the insert statement
            $stmt = mysqli_prepare($link, $insertQuery);
            
            // Bind the parameters
            mysqli_stmt_bind_param($stmt, "sss", $squadname, $desc, $coach);
            
            // Execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('New Squad Added Successfully')</script>";
                header("Refresh:0");
                exit();
            } else {
                echo "An error occurred while adding the squad";
                exit();
            }
        }
    } else {
        echo "<script>alert('You don't have permission to do that, sorry!')</script>";
    }
}
?>
