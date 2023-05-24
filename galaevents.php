<?php
session_start();
$issuccess = false;
include "connect.php";

if (isset($_SESSION["email"])) {
    $loginemail = $_SESSION['email'];
    $query = "SELECT * FROM Gala";
    $query2 = "SELECT Users.firstName, Users.lastName, Users.email, Role.Name as Role FROM `Users` INNER JOIN Role ON Users.roleID = Role.id WHERE Users.email = ?;";
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
}

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: guestallevents.php");
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

    <?php if ($issuccess == true) {
        echo ('
        <div class="alert alert-success alert-dismissible fade show d-flex justify-content-center" role="alert">
          New Squad Created
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
    }
    ?>
    <!--------------------Main Panel -------------------------->
    <section class="panel">
        
        <h2>Gala Events</h2>

        <!-- create gala trigger modal -->
        <p class="d-flex justify-content-end">
            <button id="addbtn" type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#exampleModal" style=" margin-right:20px ">
                Create New Event
            </button>

            <a id="editbtn" class="btn btn-success" onclick="updateFormAction('<?php echo $loopgala; ?>')" data-bs-toggle="modal" data-bs-target="#updateModal">
                Edit Event
            </a>
        </p>






        <!-- ---------------------InsertModal ---------------------------->

       <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Event</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="createnewgala.php">

                    <div class="mb-3">
                        <label for="eventname" class="form-label">Event Name</label>
                        <input class="form-control" id="eventname" placeholder="Event Name" name="eventname" required>
                    </div>

                    <div class="mb-3">
                        <label for="eventdescription" class="form-label">Event Description</label>
                        <textarea class="form-control" id="eventdescription" rows="3" placeholder="Event Description" name="eventdescription" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="venue" class="form-label">Event Venue</label>
                        <input class="form-control" id="venue" placeholder="Venue" name="venue" required>
                    </div>

                    <div class="mb-3">
                        <label for="startdate" class="form-label">Commencement</label>
                        <input class="form-control" id="startdate" type="date" placeholder="Start Date" name="startdate" required>
                    </div>

                    <div class="mb-3">
                        <label for="regfee" class="form-label">Registration Fee</label>
                        <input class="form-control" type="number" id="regfee" min="0.00"  step="0.01" placeholder="50" name="fee" required>
                       
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Create Event</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


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
                        <td><a href="galaperformance.php?gala=<?php echo $result['eventName']; ?>"> <?php echo $result['eventName']; ?>
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
       

      
        <!-- Delete gala Modal -->
        <div class="modal fade" id="deletegalaModal" tabindex="-1" aria-labelledby="deletegalaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deletegalaModalLabel">Are you sure you want to delete this Event?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a class="btn btn-danger" id="delete-gala-confirmation-btn" href="#">Delete</a>
                    </div>
                </div>
            </div>
        </div>


      <!--------------------Update Modal--------------------->
      
   <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Event</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="updateevents.php">

                    <div class="mb-3">
                        <label for="trainingname" class="form-label">Select Event</label>
                        <select id="squadlist" class="form-select form-select-lg mb-3" name="oldeventname" aria-label=".form-select-lg example" required>
                            <?php while ($eventlistresult = mysqli_fetch_assoc($row2)) { ?>
                                <option value="<?php echo $eventlistresult['eventName']; ?>"><?php echo $eventlistresult['eventName']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                                        <div class="mb-3">
                        <label for="eventname" class="form-label">New Event Name</label>
                        <input class="form-control" id="eventname" placeholder="Event Name" name="eventname" required>
                    </div>

                    <div class="mb-3">
                        <label for="eventdescription" class="form-label">Event Description</label>
                        <textarea class="form-control" id="eventdescription" rows="3" placeholder="Event Description" name="eventdescription" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="venue" class="form-label">Event Venue</label>
                        <input class="form-control" id="venue" placeholder="Venue" name="venue" required>
                    </div>

                    <div class="mb-3">
                        <label for="startdate" class="form-label">Commencement</label>
                        <input class="form-control" id="startdate" type="date" placeholder="Start Date" name="startdate" required>
                    </div>

                    <div class="mb-3">
                        <label for="regfee" class="form-label">Registration Fee</label>
                        <input class="form-control" type="number" id="regfee" min="0.00" max="10000.00" step="0.01" placeholder="50" name="fee" required>
                       
                    </div>
                   

                   

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update Event</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>


        <script>
            //set link of button inside modal to match table link


            //tells the delete modal where to get the link
            $('#deletegalaModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var link = button.data('link');
                var deleteBtn = $(this).find('#delete-gala-confirmation-btn');
                deleteBtn.attr('href', link);
            });
        </script>



</body>

</html>