<?php
session_start();
include "connect.php";

if (isset($_SESSION["email"])) {
    $loginemail = $_SESSION['email'];
    $galaname = $_GET['gala'];

    $logindetailsquery = "SELECT Users.firstName, Users.lastName, Users.email, Role.Name as Role FROM `Users` inner join Role ON Users.roleID = Role.id WHERE Users.email= ?";
    $galadataquery = "SELECT galaPerformance.id, galaPerformance.Position, Users.firstName AS Swimmer, Strokes.Name AS stroke, galaPerformance.date, galaPerformance.distance AS Distance, galaPerformance.time as Timee, Squad.name AS squad
    FROM galaPerformance
    JOIN Users ON galaPerformance.userId = Users.id
    JOIN Strokes ON galaPerformance.strokeId = Strokes.id
    JOIN Squad ON Users.squadID = Squad.id
    WHERE galaPerformance.galaId = (SELECT Gala.id FROM Gala WHERE Gala.eventName = ?)
    ORDER BY galaPerformance.Position";

    $swimmwerslistquery = "SELECT Users.firstName AS Swimmer
    FROM Users
    WHERE Users.roleID IN (1, 5, 6)";

    $strokesquery =  "SELECT Strokes.Name from Strokes";

    $stmt1 = mysqli_prepare($link, $logindetailsquery);
    mysqli_stmt_bind_param($stmt1, "s", $loginemail);
    mysqli_stmt_execute($stmt1);
    $row = mysqli_stmt_get_result($stmt1);

    $stmt2 = mysqli_prepare($link, $galadataquery);
    mysqli_stmt_bind_param($stmt2, "s", $galaname);
    mysqli_stmt_execute($stmt2);
    $row2 = mysqli_stmt_get_result($stmt2);

    $row3 = mysqli_query($link, $swimmwerslistquery);
    $row4 = mysqli_query($link, $strokesquery);

    $result = mysqli_fetch_array($row);
    $role = $result["Role"];
    $_SESSION["role"] = $role;
    $firstname = $result["firstName"];
    $lastname = $result["lastName"];
}

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: signin.php");
    exit;
}

include "creategalarecord.php";
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gala Details
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
    if (($role != "Admin" || $role !="Coach")) {
        echo ("<style>
            #addbtn,
            .editbtn,
            .deletebtn {
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

    <section class="panel">
        <?php include "alertbox.php"; ?>
        <h2><?=$galaname ?> Performances</h2>

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
                                <input type="text" pattern="\d{2}:\d{2}\.\d{3}" placeholder="00:00.000" name="time" title="Please enter a time in the format mm:ss.sss" required/>
                            </div>

                            <div class="mb-3">
                                <label for="distance" class="form-label">Distance</label>
                                <input class="form-control" id="distance" min="0"  placeholder="100" step="100" name="distance" type="number" required>
                            </div>

                            <div class="mb-3">
                                <label for="position" class="form-label">Position</label>
                                <input class="form-control" id="postition" name="position"  min="1" name="position" type="number" required>
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
                    <th>Squad</th>
                    <th>Position/Medal</th>
                    <th>Distance</th>
                    <th>Date</th>
                </tr>
            </thead>
           <tbody>
    <?php
    while ($result2 = mysqli_fetch_assoc($row2)) {
        $position = $result2['Position'];
        $medal = '';
        
        if ($position == 1) {
            $medal = 'Gold';
        } elseif ($position == 2) {
            $medal = 'Silver';
        } elseif ($position == 3) {
            $medal = 'Bronze';
        }
        else{
            $medal = $result2['Position'];
        }
    ?>
        <tr>
            <td><?php echo $result2['Swimmer']; ?></td>
            <td><?php echo $result2['Timee']; ?></td>
            <td><?php echo $result2['stroke']; ?></td>
            <td>
                <a href="viewsquadmembers.php?squadname=<?php echo $result2['squad']; ?>">
                    <?php echo $result2['squad']; ?>
                </a>
            </td>
            <td><?php echo $medal; ?></td>
            <td><?php echo $result2['Distance'].'m'; ?></td>
            <td><?php echo $result2['date']; ?></td>
            <?php $looprecordid = $result2['id']; ?>

            <td>
                <a class="deletebtn" class="btn btn-danger" href="#" data-link="deletegaladata.php?galaname=<?=$galaname?>&record=<?php echo $looprecordid; ?>" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    Delete
                </a>
            </td>
        </tr>
    <?php } ?>
</tbody>


        </table>


        <!-- Delete gala Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deletegalaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deletegalaModalLabel">Are you sure you want to delete this gala?</h5>
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