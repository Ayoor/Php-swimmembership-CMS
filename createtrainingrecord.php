<?php
include "connect.php";
if ($_POST){
    $training = $_GET["training"];
    
    // if ($role == "Admin"){
    $swimmer = mysqli_real_escape_string($link, $_POST["swimmer"]);
    $distance = mysqli_real_escape_string($link, $_POST['distance']);
    $time = mysqli_real_escape_string($link, $_POST['time']);
    $date = mysqli_real_escape_string($link, $_POST['date']);
    $stroke = mysqli_real_escape_string($link, $_POST['stroke']);
    
    // Get the current date
    $currentDate = new DateTime();
    // Create a DateTime object from the date string
    $selectedDate = DateTime::createFromFormat('Y-m-d', $date);
    
    if ($selectedDate > $currentDate) {
        echo "The selected date is in the future.";
    } else {
        // Get the training ID based on the training name
        $trainingIdQuery = "SELECT id FROM `Training` WHERE name = ?";
        
        // Prepare the select statement
        $stmt = mysqli_prepare($link, $trainingIdQuery);
        
        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "s", $training);
        
        // Execute the prepared statement
        mysqli_stmt_execute($stmt);
        
        // Get the result
        $trainingIdResult = mysqli_stmt_get_result($stmt);
        
        // Fetch the training ID
        $trainingIdRow = mysqli_fetch_assoc($trainingIdResult);
        $trainingId = $trainingIdRow['id'];
        
        // Insert the new training performance into the trainingPerformance table
        $insertQuery = "INSERT INTO `trainingPerformance` (`id`, `trainingId`, `userId`, `Distance`, `strokeId`, `time`, `date`)
                        VALUES (NULL, ?, 
                        (SELECT Users.id FROM Users WHERE Users.firstName = ? AND Users.squadID = 
                        (SELECT Training.squadID FROM Training WHERE Training.name = ?)), 
                        ?, 
                        (SELECT Strokes.id FROM Strokes WHERE Strokes.Name = ?), 
                        ?, 
                        ?)";
        
        // Prepare the insert statement
        $stmt = mysqli_prepare($link, $insertQuery);
        
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "ississ", $trainingId, $swimmer, $training, $distance, $stroke, $time, $date);
        
        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $issuccess = true;
            header("Location:trainingperformance.php?training=$training");
            exit();
        } else {
            echo "An error occurred while adding that record";
            exit();
        }
    }
}
?>
