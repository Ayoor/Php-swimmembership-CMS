<?php
include "connect.php";
session_start();
if ($_POST) {
    $role = $_SESSION['role'];
    if ($role == "Admin" || $role == "Coach") {
        $oldtrainingname = $_POST["oldtrainingname"];
        $newtrainingname = $_POST["trainingname"];
        $squadname = $_POST['squad'];
        $desc = $_POST['description'];
        $trainingday = $_POST['trainingday'];
        $trainingtime = $_POST['trainingtime'];

        $checkquery = "SELECT name FROM Training WHERE name = ?";
        $stmt = mysqli_prepare($link, $checkquery);
        mysqli_stmt_bind_param($stmt, "s", $newtrainingname);
        mysqli_stmt_execute($stmt);
        $checkresult = mysqli_stmt_get_result($stmt);

        if ($checkresult && mysqli_num_rows($checkresult) > 0) {
            // Training already exists, show error message or redirect as needed
            echo "Training already exists. Please choose a different name.";
        } else {
            $updatequery = "UPDATE `Training` SET `name` = ?, `Desciption` = ?, `squadID` =
                            (SELECT squadID FROM Squad WHERE Squad.Name = ?), `trainingDays` = ?, `trainingTime` = ? WHERE `Training`.`Name` = ?";
            $stmt = mysqli_prepare($link, $updatequery);
            mysqli_stmt_bind_param($stmt, "ssssss", $newtrainingname, $desc, $squadname, $trainingday, $trainingtime, $oldtrainingname);
            if (mysqli_stmt_execute($stmt)) {
                $issuccess = true;
                header("Location:training.php");
            } else {
                echo "Failed to update the training. Please try again later.";
            }
        }
    } else {
        echo "<script>alert('You don\'t have permission to do that sorry!')</script>";
    }
}
?>
