<?php
session_start();
include "connect.php";

$loginemail = $_SESSION['email'];

if ($_GET) {
    $squads = $_GET['squadname'];
    $squadmembers = str_replace(" ", "", $squads);
    $squadquery = "SELECT Users.firstName, Users.email, Users.gender, Users.age
                  FROM Users
                  JOIN Squad ON Users.squadID = Squad.id
                  WHERE Squad.Name = ?";

    $query = "SELECT Users.firstName, Users.lastName, Users.email, Role.Name as Role
              FROM `Users`
              INNER JOIN Role ON Users.roleID = Role.id
              WHERE Users.email = ?";

    $stmt1 = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt1, "s", $loginemail);
    mysqli_stmt_execute($stmt1);
    $result1 = mysqli_stmt_get_result($stmt1);
    $row = mysqli_fetch_array($result1);

    $stmt2 = mysqli_prepare($link, $squadquery);
    mysqli_stmt_bind_param($stmt2, "s", $squadmembers);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);

    $role = $row["Role"];
    $_SESSION["role"] = $role;
    $firstname = $row["firstName"];
    $lastname = $row["lastName"];
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$squads ?> Squad Members
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
    if (!($role == "Admin"|| $role=="Coach")) {
        echo("<style>
           
            #editbtn{
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

        <?php include "alertbox.php"; ?>
        <h2><?=$squads ?> Squad Members</h2>

     

    




    
    
<table style="width:600px; line-height:40px;">
    <thead>
        <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Gender</th>
        <th>Age</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($result2 = mysqli_fetch_assoc($row2)) {
        ?>
               <tr>
            <td><?php echo $result2['firstName']; ?></td>
            <td><?php echo $result2['email']; ?></td>
            <td><?php echo $result2['gender']; ?></td>
            <td><?php echo $result2['age']; ?></td>
            

        </tr>
        <?php } ?>
    </tbody>
    </table>
      
    

    

    
           
        </body>

</html>