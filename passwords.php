<?php
session_start();
$issuccess = false;
include "connect.php";

if (isset($_SESSION["email"])) {
    $loginemail = $_SESSION['email'];

    $query = "SELECT Users.firstname, Users.lastname, Users.email, Users.address, Users.postcode, Role.Name as Role, Squad.Name Squad
                FROM Users
                JOIN Role ON Users.roleID = Role.id
                JOIN Squad ON Users.squadID = Squad.id
                WHERE Users.email = ?";

    $loginquery = "SELECT Users.firstName, Users.lastName, Users.email, Role.Name as Role FROM `Users` inner join Role ON Users.roleID = Role.id WHERE Users.email= ?";

    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $loginemail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    $stmt2 = mysqli_prepare($link, $loginquery);
    mysqli_stmt_bind_param($stmt2, "s", $loginemail);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);
    $row2 = mysqli_fetch_array($result2);

    $role = $row2["Role"];
    $_SESSION["role"] = $role;
    $firstname = $row2["firstName"];
    $lastname = $row2["lastName"];
}

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: signin.php");
    exit;
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwords
    </title>

    <!--Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">

    <!--css -->
    <link rel="stylesheet" href="./main.css">

    <!--google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!--font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

</head>

<body>
    <?php
    if (($role == "Admin" || $role == "Coach")) {
        echo ("<style>
            #addbtn,
            #editbtn,
            #deletebtn {
                display: none;
            }
        </style>");
    }
    ?>
    <!--------------------------------------top navbar------------------------------------>

    <nav class="nav-options">

        <a>Signed in as
            <?= $firstname ?>
        </a>
        <a href="signin.php">Sign out</a>

    </nav>

    <?php include "sidebar.php" ?>


    <!--------------------Main Panel -------------------------->
    <section class="panel">
        
        <h2>Change Password</h2> 

        <form method="post" action="updatepassword.php" id="passwordForm">

<div class="mb-3">
    <label for="oldpassword" class="form-label">Old Password</label>
    <input class="form-control" type="password" id="oldpassword" name="oldpassword" required>
</div>

<div class="mb-3">
    <label for="password" class="form-label">New Password</label>
    <input class="form-control" type="password" id="password" name="password" required>
</div>

<div class="mb-3">
    <label for="confirmpassword" class="form-label">Confirm Password</label>
    <input class="form-control" type="password" id="confirmpassword" name="confirmpassword" required>
</div>

<div class="mb-3">
    <button type="button" class="btn btn-primary" id="savebtn" data-bs-toggle="modal" data-bs-target="#updatedetailsModal">
        Update Password
    </button>
</div>

</form>


<!-- Confirm Save Modal -->
<div class="modal fade" id="updatedetailsModal" tabindex="-1" aria-labelledby="updatedetailsModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="updatedetailsModalLabel">Are you sure?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="update-confirmation-btn">Update</button>
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function () {
    $('#update-confirmation-btn').click(function () {
        $('#passwordForm').submit(); // Submit the form
    });
});
</script>





</body>

</html>