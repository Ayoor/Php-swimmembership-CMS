<?php
session_start();
include "connect.php";

if (isset($_SESSION["email"])) {
    $loginemail = $_SESSION['email'];
    
    if (isset($_GET['squadname'])) {
        $squads = $_GET['squadname'];
        $squadmembers = str_replace(" ", "", $squads);
        $squadquery = "SELECT Users.firstName, Users.email, Users.gender, Users.age
                      FROM Users JOIN Squad ON Users.squadID = Squad.id
                      WHERE Squad.Name = '$squadmembers'";

        $query = "SELECT Users.firstName, Users.lastName, Users.email, Role.Name as Role FROM `Users` inner join Role ON Users.roleID = Role.id WHERE Users.email= '$loginemail';";

        $row = mysqli_query($link, $query);
        $row2 = mysqli_query($link, $squadquery);

        $result = mysqli_fetch_array($row); // Get result in an array

        $role = $result["Role"];
        $_SESSION["role"] = $role;
        $firstname = $result["firstName"];
        $lastname = $result["lastName"];
    }
}

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: signin.php");
    exit;
}

include "addswimmertosquad.php";
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

?>

    <!--------------------------------------top navbar------------------------------------>

    <nav class="nav-options">

        <a>Welcome
            <?= $firstname ?>
        </a>
        <a href="signin.php">Sign out</a>

    </nav>

    <?php include "sidebar.php" ?>

    <!--------------------Main Panel -------------------------->
    <section class="panel">
        <?php include "alertbox.php"; ?>
        <h2><?=$squads ?> Squad Members</h2>

      <section id="details">

      <!-- create squad trigger modal -->
      <p class="d-flex justify-content-end">
            <button id="editbtn" type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#exampleModal">
                Move Swimmer to Squad
            </button>
        </p>




        <!-- ---------------------Add Squad Member Modal ---------------------------->

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Move Swimmer to Squad</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">

                            <div class="mb-3">
                                <label for="squadname" class="form-label">Swimmer Email</label>
                                <input class="form-control" id="newswimmer" placeholder="Swimmer Email" name="newswimmer" required>
                            </div>
                        </div>
      
                        <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Move</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>
    
    
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
      
    

    
</section>
    </section>
           
        </body>

</html>