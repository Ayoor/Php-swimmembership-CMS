<?php
include "connect.php";

if ($_POST) {
    $gala = $_GET["gala"];

    // if ($role == "Admin"){
    $swimmer = $_POST["swimmer"];
    $distance = $_POST['distance'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $stroke = $_POST['stroke'];
    $position = $_POST['position'];

    $currentDate = new DateTime(); // Get the current date
    $selectedDate = DateTime::createFromFormat('Y-m-d', $date); // Create a DateTime object from the date string

    if ($selectedDate > $currentDate) {
        echo "The selected date is in the future.";
    } else {
        // Prepare the insert statement
        $insertQuery = "INSERT INTO `galaPerformance` (`id`, `galaId`, `userId`, `Distance`, `strokeId`, `time`, `Position`, `date`)
                        VALUES (NULL, 
                        (SELECT Gala.id FROM Gala WHERE Gala.eventName = ?), 
                        (SELECT Users.id FROM Users WHERE Users.firstName = ? AND Users.squadID = 
                        (SELECT Users.squadID FROM Users WHERE Users.firstName = ?)), 
                        ?, 
                        (SELECT Strokes.id FROM Strokes WHERE Strokes.Name = ?), 
                        ?, 
                        ?,
                        ?)";

        // Create a prepared statement
        $stmt = mysqli_prepare($link, $insertQuery);

        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "ssssssss", $gala, $swimmer, $swimmer, $distance, $stroke, $time, $position, $date);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $issuccess = true;
            header("Location:galaperformance.php?gala=$gala");
            exit();
        } else {
            echo "An error occurred while adding that record";
            exit();
        }
    }
}
?>
