<?php
session_start();
include "connect.php";
$role = $_SESSION["role"];

if (isset($_GET["email"])) {
    if ($role == "Admin") {
        $wardemail = $_GET["email"];
        
        // Sanitize the email input
        $wardemail = mysqli_real_escape_string($link, $wardemail);
        
        // Retrieve query to check if the record exists
        $retrieveQuery = "SELECT * FROM Ward WHERE Userid = (SELECT id FROM Users WHERE email = '$wardemail')";
        
        // Delete query to remove the ward record
        $warddeleteQuery = "DELETE FROM Ward WHERE Userid = (SELECT id FROM Users WHERE email = '$wardemail')";

        // Delete query to remove the user record
        $deleteQuery = "DELETE FROM Users WHERE email = '$wardemail'";

        $result = mysqli_query($link, $retrieveQuery);
        $row = mysqli_fetch_array($result);
        
        if ($row) {
            mysqli_query($link, $warddeleteQuery);
            mysqli_query($link, $deleteQuery);
            header("Location: ward.php");
            exit();
        } else {
            echo "User record not found.";
            exit();
        }
    } else {
        echo "You don't have permission to do that. Sorry!";
        exit();
    }
}
?>
