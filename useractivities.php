<?php

if (isset($_POST['loginform'])) {

    $loginemail = $_POST['loginemail'];
    $loginpassword = $_POST['loginpassword'];

    $query = "SELECT Email, Password FROM Test WHERE Email = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $loginemail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    if ($row == null) {
        echo "User not found. Please try to sign up.";
    } else {
        $passwordmatch = password_verify($loginpassword, $row["Password"]);
        if (!$passwordmatch) {
            echo "Incorrect password.";
        } else {
            echo "Login successful.";
            setcookie("useremail", $row["Email"], time() + 60 * 30);
            header("Location: welcome.php");
            exit();
        }
    }
}

?>
