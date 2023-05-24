<?php
session_start();
include "connect.php";

if (isset($_SESSION["email"])) {
    $loginemail = $_SESSION['email'];

    $query = "SELECT Users.*, Squad.Name AS squadname, Training.trainingDays, Training.trainingTime
              FROM Ward
              INNER JOIN Users ON Ward.Userid = Users.id
              INNER JOIN Squad ON Users.squadID = Squad.id
              INNER JOIN Training ON Squad.id = Training.squadID
              WHERE Ward.parentid = (SELECT Users.id FROM Users WHERE Users.email = '$loginemail')";

    $userdetailsquery = "SELECT Users.firstName, Users.lastName, Users.email, Role.Name as Role
                         FROM `Users` INNER JOIN Role ON Users.roleID = Role.id
                         WHERE Users.email = '$loginemail'";

    $row = mysqli_query($link, $query);
    $row3 = mysqli_query($link, $userdetailsquery);

    $result3 = mysqli_fetch_array($row3);

    $role = $result3["Role"];
    $_SESSION["role"] = $role;
    $firstname = $result3["firstName"];
    $lastname = $result3["lastName"];
}

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: signin.php");
    exit;
}

// Fetch squads
$squadlistquery = "SELECT name FROM Squad";
$squadlistrow = mysqli_query($link, $squadlistquery);

?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wards
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
    if (!($role == "Admin"|| $role=="Parent"|| $role=="Parent & Member")) {
        echo("<style>
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
        <?php include "alertbox.php"; ?>
        <h2>Your Wards</h2>

        <!-- create training trigger modal -->
        <p class="d-flex justify-content-end">
            <a id="addbtn" href="addnewward.php" class="btn btn-primary " style=" margin-right:20px ">
                Add Ward
            </a>
            
            <a id="editbtn" class="btn btn-success" href="editward.php?<?php echo $loopward; ?>">
                    Edit Ward Details
                </a>
        </p>
        
        




        
   
         
    
    <table >
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Age</th>
                <th>Squad</th>
                 <th>Training Days</th>
                <th>Training Time</th>
            </tr>
        </thead>
        <tbody>
    <?php
    while ($result = mysqli_fetch_assoc($row)) {
    ?>
        <tr>
            <td>
                    <?php echo $result['firstName']; ?>
                </td>
            <td><?php echo $result['lastName']; ?></td>
            <td><?php echo $result['age']; ?></td>
            <td><?php echo $result['squadname'];?></td>
            <td><?php echo $result['trainingdays']; ?></td>
            <td><?php echo $result['trainingtime']; ?></td>
            <?php $loopward = $result['email']; ?>
           
            <td>
                <a id="deletebtn" class="btn btn-danger" href="#" data-link="deleteward.php?email=<?php echo $loopward; ?>" data-bs-toggle="modal" data-bs-target="#deletetrainingModal">
                    Delete Ward
                </a>
            </td>
        </tr>
    <?php } ?>
</tbody>

    </table>
    <p id="eg" style="color: white !important; "></p>

    <!-- Delete training Modal -->
    <div class="modal fade" id="deletetrainingModal" tabindex="-1" aria-labelledby="deletetrainingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deletetrainingModalLabel">Are you sure?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <a class="btn btn-danger" id="delete-training-confirmation-btn" href="#">Delete</a>
        </div>
        </div>
    </div>
    </div>


    


        <script>
            //set link of button inside modal to match table link
            

            //tells the delete modal where to get the link
            $('#deletetrainingModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var link = button.data('link');
                var deleteBtn = $(this).find('#delete-training-confirmation-btn');
                deleteBtn.attr('href', link);
            });

           

        </script>



</body>

</html>