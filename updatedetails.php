<?php
include "connect.php";
session_start();
if (isset($_POST) && isset($_SESSION['email'])) {
    $role = $_SESSION['role'];
    $oldemail = $_SESSION['email'];
    if ($role == "Admin" || $role == "Coach") {
        $firstname = mysqli_real_escape_string($link, $_POST["firstname"]);
        $lastname = mysqli_real_escape_string($link, $_POST["lastname"]);
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $address = mysqli_real_escape_string($link, $_POST['address']);
        $postcode = mysqli_real_escape_string($link, $_POST['postcode']);
        

        $checkquery = "SELECT Users.email FROM Users  WHERE Users.email = '$oldemail';";
        $checkresult = mysqli_query($link, $checkquery);

      
            $updatequery = "UPDATE `Users` SET `firstName` = '$firstname', `lastName` = '$lastname', `email` = '$email', `address` = '$address', `postcode` = '$postcode'
                        WHERE `Users`.`email` = '$oldemail';";
                         
            mysqli_query($link, $updatequery);
            if (mysqli_query($link, $updatequery)) {
                $_SESSION['email']= $email;
                $issuccess = true;
                header("Location:profile.php");
            } else {
                echo "Failed to update the details. Please try again later.";
            }
        
    } else {
        echo "<script>alert('You don\'t have permission to do that sorry!')</script>";
    }
}
?>
