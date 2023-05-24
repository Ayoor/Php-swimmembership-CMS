<?php
if ($_POST) {
    if ($role == "Admin" || $role == "Coach") {
        $newswimmer = $_POST['newswimmer'];

        try {
            $retrivequery = "SELECT * FROM `Users` WHERE Users.email = ?";
            $updatequery = "UPDATE `Users` SET `squadID` = (SELECT Squad.id FROM Squad WHERE Squad.Name = ?) WHERE `Users`.`email` = ?";

            $stmt = mysqli_prepare($link, $retrivequery);
            mysqli_stmt_bind_param($stmt, "s", $newswimmer);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_array($result);

            if ($row) {
                $stmt = mysqli_prepare($link, $updatequery);
                mysqli_stmt_bind_param($stmt, "sss", $squadmembers, $newswimmer, $newswimmer);
                mysqli_stmt_execute($stmt);
                echo "<script>alert('New Swimmer Added to Squad :) ')</script>";
                header("Refresh:0");
            } else {
                echo "<script>alert('$newswimmer does not exist or is not eligible to swim')</script>";
                exit();
            }
        } catch (Exception $e) {
            echo "An exception was caught: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('You don\'t have permission to do that, sorry!')</script>";
    }
}
?>
