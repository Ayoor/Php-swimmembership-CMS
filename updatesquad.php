<?php
session_start();
include "connect.php";

if (isset($_GET["squadname"])) {
    $role = $_SESSION["role"];
    $currentsquadname = $_GET["squadname"];

    // Check if $currentsquadname exists in the database
    $retrievequery = "SELECT Name FROM Squad WHERE Name = ?";
    $stmt = mysqli_prepare($link, $retrievequery);
    mysqli_stmt_bind_param($stmt, "s", $currentsquadname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result || mysqli_num_rows($result) == 0) {
        header("Location: squads.php");
        exit();
    }
}

try {
    $name = $_POST['name'];
    $desc = $_POST['description'];

    // Check if $name already exists in the database and is not the same as $currentsquadname
    $checkquery = "SELECT Name FROM Squad WHERE Name = ?";
    $stmt = mysqli_prepare($link, $checkquery);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if ($row["Name"] !== $currentsquadname) {
            $errormessage = 'Squad created successfully!';
            header("Location: squads.php");
            exit();
        }
    }

    $updatequery = "UPDATE `Squad` SET `Name` = ?, `Desciption` = ? WHERE Name = ?";
    $stmt = mysqli_prepare($link, $updatequery);
    mysqli_stmt_bind_param($stmt, "sss", $name, $desc, $currentsquadname);
    mysqli_stmt_execute($stmt);

    echo "<script>alert('Squad details successfully updated :)')</script>";
    header("Location: squads.php");
} catch (Exception $e) {
    error_log("An exception was caught: " . $e->getMessage());
    header("Location: squads.php?error=An error occurred while updating squad details.");
    exit();
}
?>
