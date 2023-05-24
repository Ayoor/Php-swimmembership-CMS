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
                WHERE Users.email = '$loginemail'";

    $loginquery = "SELECT Users.firstName, Users.lastName, Users.email, Role.Name as Role FROM `Users` inner join Role ON Users.roleID = Role.id WHERE Users.email= '$loginemail';";




    $row = mysqli_query($link, $query);
    $result = mysqli_fetch_array($row);
    
    $row3 = mysqli_query($link, $loginquery);
    $result3 = mysqli_fetch_array($row3);


    $role = $result3["Role"];
    $_SESSION["role"] = $role;
    $firstname = $result3["firstName"];
    $lastname = $result3["lastName"];
}

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: signin.php");
    //   echo "next page";
    exit;
}



?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details
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
        <?php include "alertbox.php"; ?>
        <h2>Personal Details</h2> 

        <form method="post" action="updatedetails.php">
    <div class="mb-3">
        <label for="firstname" class="form-label">First Name</label>
        <input class="form-control" id="firstname" placeholder="First Name" name="firstname" value="<?= $result['firstname'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="lastname" class="form-label">Last Name</label>
        <input class="form-control" id="lastname" placeholder="Last Name" name="lastname" value="<?= $result['lastname'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input class="form-control" id="email" placeholder="Email" name="email" value="<?= $result['email'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input class="form-control" id="address" placeholder="Full Address" name="address" value="<?= $result['address'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="postcode" class="form-label">Post Code</label>
        <input class="form-control" id="postcode" placeholder="Post Code" name="postcode" value="<?= $result['postcode'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <input class="form-control" id="role" placeholder="<?= $result['Role'] ?>" name="role" disabled value="<?= $result['Role'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="squad" class="form-label">Squad</label>
        <input class="form-control" id="squad" placeholder="<?= $result['Squad'] ?>" name="squad" disabled value="<?= $result['Squad'] ?>" required>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary" id="savebtn" data-bs-toggle="modal" data-bs-target="#updatedetailsModal">
            Update Details
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
                <button type="submit" class="btn btn-danger" id="update-confirmation-btn">Update</button>
            </div>
        </div>
    </div>
</div>


        

        <script>
            // Set the form action when the modal is shown
    $('#updatedetailsModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var link = button.data('link');
        var saveBtn = $(this).find('#update-confirmation-btn');
        saveBtn.click(function () {
            $('form').attr('action', link); // Set the form action to the provided link
            $('form').submit(); // Submit the form
        });
    });
</script>



</body>

</html>