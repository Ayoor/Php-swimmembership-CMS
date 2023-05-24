<?php
session_start();
include "connect.php";
$role = $_SESSION["role"];

if (isset($_GET["trainingname"])) {
    if ($role == "Admin" || $role == "Coach") {
        $training = mysqli_real_escape_string($link, $_GET["trainingname"]);

        $retrieveQuery = "SELECT * FROM Training WHERE `Training`.`name` = ?";
        $deleteQuery = "DELETE FROM Training WHERE `Training`.`name` = ?";

        // Prepare the retrieve statement
        $stmt = mysqli_prepare($link, $retrieveQuery);

        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "s", $training);

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
            mysqli_stmt_bind_param($stmt, "s", $training);

            // Execute the prepared statement
            mysqli_stmt_execute($stmt);

            header("Location: training.php");
            exit();
        } else {
            echo "Training does not exist";
            exit();
        }
    } else {
        echo "You don't have permission to do that sorry!";
        exit();
    }
}
?>
