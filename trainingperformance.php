<?php
session_start();
include "connect.php";


    if (isset($_SESSION["email"])) {
        $loginemail = $_SESSION['email'];
        $trainingname = $_GET['training'];


        //   $query = "SELECT firstName, lastName FROM `Users` WHERE email = '$loginemail'";

        $logindetailsquery = "SELECT  Users.firstName, Users.lastName, Users.email, Role.Name as Role FROM `Users` inner join Role ON Users.roleID = Role.id WHERE Users.email= '$loginemail';";

        $trainingdataquery = "SELECT trainingPerformance.id, Users.firstName AS Swimmer, Strokes.Name AS stroke, trainingPerformance.date, trainingPerformance.distance AS Distance, trainingPerformance.time as Timee
    FROM trainingPerformance
    JOIN Users ON trainingPerformance.userId = Users.id
    JOIN Strokes ON trainingPerformance.strokeId = Strokes.id
    WHERE trainingPerformance.trainingId = (SELECT Training.id FROM Training WHERE Training.name = '$trainingname')";

    

        $swimmwerslistquery = "SELECT Users.firstName AS Swimmer FROM Training
                                JOIN Users ON Training.squadID = Users.squadID
                                  WHERE Training.id = (SELECT Training.id FROM Training WHERE Training.name = '$trainingname');";

                               

        $strokesquery =  "SELECT Strokes.Name from Strokes";


        //  $squards= "SELECT firstName, lastName  FROM `Users` ";   
        $row = mysqli_query($link, $logindetailsquery);
        $row2 = mysqli_query($link, $trainingdataquery);
        $row3 = mysqli_query($link, $swimmwerslistquery);
        $row4 = mysqli_query($link, $strokesquery);

        $result = mysqli_fetch_array($row); //get result in an array  
        //   $result2 = mysqli_fetch_array($row2);


        $role = $result["Role"];
        $_SESSION["role"] = $role;
        $firstname = $result["firstName"];
        $lastname = $result["lastName"];
    }

    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        header("Location: signin.php");
        //   echo "next page";
        exit;
    }


    include "createtrainingrecord.php";


    ?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Details
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
    <?php include "noperm.php"; ?>

</head>

<body>

    <?php
    if (!($role != "Admin" || "Coach")) {
        echo ("<style>
            #addbtn,
            #editbtn,
            #deletebtn {
                display: none;
            }
        </style>");
    }
    ?>

    ?>


    <!--------------------------------------top navbar------------------------------------>

    <nav class="nav-options">

        <a>Signed in as
            <?= $firstname ?>
        </a>
        <a href="signin.php">Sign out</a>

    </nav>

    <?php include "sidebar.php" ?>

    <section class="panel">
        <?php include "alertbox.php"; ?>
        <h2>Training Performances</h2>

        <!-- create squad trigger modal -->
        <p class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" id="addbtn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Add new Data
            </button>
        </p>

        <!-- ---------------------InsertModal ---------------------------->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">New Entry</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="mb-3">
                                <label for="squadlist" class="form-label">Select a Swimmer</label>
                                <select id="squadlist" class="form-select form-select-md mb-3" name="swimmer" aria-label=".form-select-lg example" required>
                                    <?php while ($result3 = mysqli_fetch_assoc($row3)) { ?>
                                        <option value="<?php echo $result3['Swimmer']; ?>"><?php echo $result3['Swimmer']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="stroke" class="form-label">Stroke</label>
                                <select id="stroke" class="form-select form-select-md mb-3" name="stroke" aria-label=".form-select-md example" required>
                                    <?php while ($result4 = mysqli_fetch_assoc($row4)) { ?>
                                        <option value="<?php echo $result4['Name']; ?>"><?php echo $result4['Name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="time" class="form-label">Time</label>
                                <input type="text" pattern="\d{2}:\d{2}\.\d{3}" placeholder="00:00.000"  name="time" title="Please enter a time in the format mm:ss.sss" required/>


                            </div>

                            <div class="mb-3">
                                <label for="coach" class="form-label">Distance</label>
                                <input class="form-control" id="distance"  placeholder="100" min="100"  name="distance" type="number" required>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input class="form-control" id="date"  name="date" type="date" required>
                            </div>
                    </div>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Upload</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Swimmer</th>
                    <th>Time</th>
                    <th>Stroke</th>
                    <th>Distance</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($result2 = mysqli_fetch_assoc($row2)) {
                ?>
                    <tr>
                        <td> <?php echo $result2['Swimmer']; ?></td>
                        <td><?php echo $result2['Timee']; ?></td>
                        <td><?php echo $result2['stroke']; ?></td>
                        <td><?php echo $result2['Distance'].'m'; ?></td>
                        <td><?php echo $result2['date']; ?></td>
                        <?php $looprecordid = $result2['id']; ?>

                        <td>
                            <a id="deletebtn" class="btn btn-danger" href="#" data-link="deletetrainingdata.php?trainingname=<?=$trainingname?>&record=<?php echo $looprecordid; ?>" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>


        <!-- Delete training Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deletetrainingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deletetrainingModalLabel">Are you sure you want to delete this training?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <a class="btn btn-danger" id="delete-training-confirmation-btn" href="#">Delete</a>
        </div>
        </div>
    </div>
    </div>


     
    </section>


    <script>
       
     //tells the delete modal where to get the link
$('#deleteModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    var link = button.data('link');
    var deleteBtn = $(this).find('#delete-training-confirmation-btn'); 
    deleteBtn.attr('href', link);
});


    </script>



</body>

</html>