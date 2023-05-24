<?php
session_start();
include "connect.php";
$role = $_SESSION["role"];

    if (isset($_GET["squadname"])) {
   
  if ($role == "Admin"){
    $squad = $_GET["squadname"];
        try {
      $retrivequery = "SELECT * FROM Squad WHERE Name = '$squad'";
      $deletequery = "DELETE FROM Squad WHERE `Name`= '$squad'";
      $row = mysqli_query($link, $retrivequery);
      $result = mysqli_fetch_array($row);

      if ($result) {
        mysqli_query($link, $deletequery);
        header("Location: squads.php");
      }            
    } catch (Exception $e) {
      echo "An exception was caught: " . $e->getMessage();
    }
  } else {
    echo "You don't have permission to do that sorry!";
  }
}
?>