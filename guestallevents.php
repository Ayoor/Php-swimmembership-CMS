<?php
session_start();
$issuccess = false;
include "connect.php";

$loginemail = $_SESSION['email'];

$query = "SELECT * FROM Gala";
$query2 = "SELECT Users.firstName, Users.lastName, Users.email, Role.Name as Role FROM `Users` inner join Role ON Users.roleID = Role.id WHERE Users.email = ?";
$allevents = "SELECT eventName FROM Gala";

$stmt1 = mysqli_prepare($link, $query);
mysqli_stmt_execute($stmt1);
$row = mysqli_stmt_get_result($stmt1);

$stmt2 = mysqli_prepare($link, $allevents);
mysqli_stmt_execute($stmt2);
$row2 = mysqli_stmt_get_result($stmt2);

$stmt3 = mysqli_prepare($link, $query2);
mysqli_stmt_bind_param($stmt3, "s", $loginemail);
mysqli_stmt_execute($stmt3);
$row3 = mysqli_stmt_get_result($stmt3);

$result3 = mysqli_fetch_array($row3);

$role = $result3["Role"];
$_SESSION["role"] = $role;
$firstname = $result3["firstName"];
$lastname = $result3["lastName"];

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
    <title>Gala Events
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
    if (!($role == "Admin")) {
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

        <a href="signin.php">Sign in</a>

    </nav>


    <!--------------------Main Panel -------------------------->
    

        <h2>Gala Events</h2>

        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Description</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Registration</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($result = mysqli_fetch_assoc($row)) {
                ?>
                    <tr>
                        <td><a href="guestgalaperformance.php?gala=<?php echo $result['eventName']; ?>"> <?php echo $result['eventName']; ?>
                            </a></td>
                        <td><?php echo $result['description']; ?></td>
                        <td><?php echo $result['venue']; ?></td>
                        <td><?php echo $result['date']; ?></td>
                        <td><?php echo $result['fee']; ?></td>
                        <?php $loopgala = $result['eventName']; ?>

                        <td>
                            <a id="deletebtn" class="btn btn-danger" href="#" data-link="deletegala.php?event=<?php echo $loopgala; ?>" data-bs-toggle="modal" data-bs-target="#deletegalaModal">
                                Delete gala
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>


</body>

</html>