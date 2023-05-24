<?php
session_start();
include "connect.php";
$role = $_SESSION["role"];
$gala = $_GET['galaname'];

if (isset($_GET["record"])) {
    if ($role == "Admin") {
        $recordid = mysqli_real_escape_string($link, $_GET["record"]);

        $retrieveQuery = "SELECT * FROM galaPerformance WHERE galaPerformance.id = ?";
        $deleteQuery = "DELETE FROM galaPerformance WHERE galaPerformance.id = ?";

        // Prepare the retrieve statement
        $stmt = mysqli_prepare($link, $retrieveQuery);

        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "s", $recordid);

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
            mysqli_stmt_bind_param($stmt, "s", $recordid);

            // Execute the prepared statement
            mysqli_stmt_execute($stmt);

            header("Location: galaperformance.php?gala=$gala");
            exit();
        } else {
            echo "Couldn't delete that record, an error occurred.";
        }
    } else {
        echo "You don't have permission to do that sorry!";
        exit();
    }
}
?>
