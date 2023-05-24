<?php
include "connect.php";
session_start();
if (isset($_POST) && isset($_SESSION['email'])) {
    $role = $_SESSION['role'];
    $email = $_SESSION['email'];
        
        $oldpassword = mysqli_real_escape_string($link, $_POST["oldpassword"]);
        $password = mysqli_real_escape_string($link, $_POST["password"]);
        $confirmpassword = mysqli_real_escape_string($link, $_POST["confirmpassword"]);
       
        if($password==$confirmpassword){

        $hashedpassword= password_hash($oldpassword, PASSWORD_DEFAULT);

        $checkquery = "SELECT Users.password FROM `Users` WHERE Users.email = '$email'";
        $checkresult = mysqli_query($link, $checkquery);
        
        
      
        if ($checkresult && mysqli_num_rows($checkresult) > 0) {
                 $result = mysqli_fetch_assoc($checkresult);
  
    if (password_verify($oldpassword, $result["password"])) {
        $newhashedpassword= password_hash($password, PASSWORD_DEFAULT);
        
        $updatequery = "UPDATE `Users` SET `password` = '$newhashedpassword'
                            WHERE `Users`.`email` = '$email';";
                echo $updatequery;         
            mysqli_query($link, $updatequery);

            if (mysqli_query($link, $updatequery)) {
                 $issuccess = true;
                header("Location:profile.php");
            } else {
                echo "Failed to update the details. Please try again later.";
            }
        }
        else{
            echo "incorrect password";
        }
        }
        }else{
             echo "Please enter matching Passwords.";
        } 
    
}
?>
