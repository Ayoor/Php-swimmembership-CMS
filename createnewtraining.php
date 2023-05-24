<?php
if ($_POST){
    if ($role == "Admin" || $role == "Coach"){
        $trainingname = mysqli_real_escape_string($link, $_POST["trainingname"]);
        $squadname = mysqli_real_escape_string($link, $_POST['squad']);
        $desc = mysqli_real_escape_string($link, $_POST['description']);
        $trainingday = mysqli_real_escape_string($link, $_POST['trainingday']);
        $trainingtime = mysqli_real_escape_string($link, $_POST['trainingtime']);
        
        // Get the squad ID based on the squad name
        $squadIdQuery = "SELECT id FROM `Squad` WHERE Name = ?";
        
        // Prepare the select statement
        $stmt = mysqli_prepare($link, $squadIdQuery);
        
        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "s", $squadname);
        
        // Execute the prepared statement
        mysqli_stmt_execute($stmt);
        
        // Get the result
        $squadIdResult = mysqli_stmt_get_result($stmt);
        
        // Fetch the squad ID
        $squadIdRow = mysqli_fetch_assoc($squadIdResult);
        $squadId = $squadIdRow['id'];
        
        // Insert the new training session into the Training table
        $insertQuery = "INSERT INTO `Training` (`id`, `name`, `Description`, `squadID`, `trainingDays`, `trainingTime`) 
                        VALUES (NULL, ?, ?, ?, ?, ?)";
        
        // Prepare the insert statement
        $stmt = mysqli_prepare($link, $insertQuery);
        
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "ssiss", $trainingname, $desc, $squadId, $trainingday, $trainingtime);
        
        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $issuccess = true;
            header("refresh:3");
            exit();
        } else {
            echo "An error occurred while adding the training session";
            exit();
        }
    } else {
        echo "<script>alert('You don\'t have permission to do that, sorry!')</script>";
    }
}
?>
