<?php
session_start();
 $issuccess = false;
include "connect.php";
if (isset($_SESSION["email"])) {
    $loginemail = $_SESSION['email'];



$query = "SELECT Training.*, Squad.name AS squadname
FROM Training
INNER JOIN Squad ON Training.squadID = Squad.id;";

$query2= "SELECT Users.firstName, Users.lastName, Users.email, Role.Name as Role FROM `Users` inner join Role ON Users.roleID = Role.id WHERE Users.email= '$loginemail';";

    $alltraining = "SELECT training.Name, training.Desciption as 'desc', Users.firstName as 'Coach Name' 
            FROM training 
            LEFT JOIN Users ON Users.id = training.CoachID";
            
    
    //  $squards= "SELECT firstName, lastName  FROM `Users` ";   
    $row = mysqli_query($link, $query);
    $row2 = mysqli_query($link, $alltraining);
    $row3 = mysqli_query($link, $query2);


    
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

//fetch squads
$squadlistquery = "SELECT name FROM Squad";                         
                   
                    
$squadlistrow = mysqli_query($link, $squadlistquery);
$squadlistrow2 = mysqli_query($link, $squadlistquery);
$squadlistrow3 = mysqli_query($link, $query);


//create training
include "createnewtraining.php";


?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training
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
        <h2>Membership Trainings</h2>

        <!-- create training trigger modal -->
        <p class="d-flex justify-content-end">
            <button id="addbtn" type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#exampleModal" style=" margin-right:20px ">
                Create New Training
            </button>
            
            <a id="editbtn" class="btn btn-success" onclick="updateFormAction('<?php echo $looptraining; ?>')" data-bs-toggle="modal" data-bs-target="#updateModal">
                    Edit training
                </a>
        </p>
        
        




        <!-- ---------------------InsertModal ---------------------------->

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Training</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">

                            <div class="mb-3">
                                <label for="trainingname" class="form-label">Training Name</label>
                                <input class="form-control" id="trainingname" placeholder="Training Name" name="trainingname" required>
                            </div>
                            <div class="mb-3">
                            <label for="squadlist"  class="form-label">Select Squad</label>
                            <select id="squadlist" class="form-select form-select-lg mb-3" name="squad" aria-label=".form-select-lg example" required>
                            
                                <?php 
                                while ($squadlistresult = mysqli_fetch_assoc($squadlistrow)) {
                                ?>
                            
                                <option value="<?php echo $squadlistresult['name']?>"><?php echo $squadlistresult['name'] ?></option>
                                 <?php } ?>    
                            
                                </select>
                            </div>
                             

                            <div class="mb-3">
                                <label for="trainingdescription" class="form-label">Training Description</label>
                                <textarea class="form-control" id="trainingdescription" rows="3" placeholder="training Description" name="description" required></textarea >
                            </div>
        
       
          <label for="coach" class="form-label" >Training Date and Time</label>
          <select class="form-select" name="trainingday" aria-label="Default select example" required>
 
          <option selected value="Mondays">Mondays</option>
  <option value="Tuesdays">Tuesdays</option>
  <option value="Wednesdays">Wednesdays</option>
  <option value="Thursdays">Thursdays</option>
  <option value="Fridays">Fridays</option>
  <option value="Saturdays">Saturdays</option>
</select>

<div class="timediv d-flex justify-content-around">
<div class="form-check">
  <input class="form-check-input" type="radio" name="trainingtime" value="3pm-4pm" id="flexRadioDefault1" checked required>
  <label class="form-check-label" for="flexRadioDefault1">
    3pm-4pm
  </label>
</div>

<div class="form-check">
  <input class="form-check-input" type="radio" name="trainingtime" value="4pm-5pm" id="flexRadioDefault2"  required>
  <label class="form-check-label" for="flexRadioDefault2">
    4pm-5pm
  </label>
</div>
        
<div class="form-check">
  <input class="form-check-input" type="radio" value="5pm-6pm" name="trainingtime" id="flexRadioDefault2" required>
  <label class="form-check-label" for="flexRadioDefault2">
    5pm-6pm
  </label>
</div>
</div>
</div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Create training</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>
    
   
   
         
    
    <table >
        <thead>
            <tr>
                <th>Training Name</th>
                <th>Description</th>
                <th>Squad</th>
                <th>Training Day</th>
                <th>Training Time</th>
            </tr>
        </thead>
        <tbody>
    <?php
    while ($result = mysqli_fetch_assoc($row)) {
    ?>
        <tr>
            <td>
            <a href="trainingperformance.php?training=<?php echo $result['name']; ?>"> <?php echo $result['name']; ?> </a>
                </td>
            <td><?php echo $result['Desciption']; ?></td>
            <td><a href="viewsquadmembers.php?squadname=<?php echo $result['squadname']; ?>"><?php echo $result['squadname']; ?></a></td>
            <td><?php echo $result['trainingDays']; ?></td>
            <td><?php echo $result['trainingTime']; ?></td>
            <?php $looptraining = $result['name']; ?>
           
            <td>
                <a id="deletebtn" class="btn btn-danger" href="#" data-link="deletetraining.php?trainingname=<?php echo $looptraining; ?>" data-bs-toggle="modal" data-bs-target="#deletetrainingModal">
                    Delete training
                </a>
            </td>
        </tr>
    <?php } ?>
</tbody>

    </table>

    <!-- Delete training Modal -->
    <div class="modal fade" id="deletetrainingModal" tabindex="-1" aria-labelledby="deletetrainingModalLabel" aria-hidden="true">
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


    <!-- ---------------------Update Modal ---------------------------->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Training</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="updatetraining.php">

                    <div class="mb-3">
                        <label for="trainingname" class="form-label">Current Training Name</label>
                        <select id="squadlist" class="form-select form-select-lg mb-3" name="oldtrainingname" aria-label=".form-select-lg example" required>
                            <?php while ($traininglistresult = mysqli_fetch_assoc($squadlistrow3)) { ?>
                                <option value="<?php echo $traininglistresult['name'] ?>"><?php echo $traininglistresult['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="trainingname" class="form-label">New Training Name</label>
                        <input class="form-control" id="trainingname" placeholder="Training Name" name="trainingname" required>
                    </div>

                    <div class="mb-3">
                        <label for="squadlist" class="form-label">New Select Squad</label>
                        <select id="squadlist2" class="form-select form-select-lg mb-3" name="squad" aria-label=".form-select-lg example" required>
                            <?php while ($squadlistresult = mysqli_fetch_assoc($squadlistrow2)) { ?>
                                <option value="<?php echo $squadlistresult['name'] ?>"><?php echo $squadlistresult['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="trainingdescription" class="form-label">New Training Description</label>
                        <textarea class="form-control" id="trainingdescription" rows="3" placeholder="training Description" name="description" required></textarea>
                    </div>

                    <label for="coach" class="form-label">Training Date and Time</label>
                    <select class="form-select" name="trainingday" aria-label="Default select example" required>
                        <option selected value="Mondays">Mondays</option>
                        <option value="Tuesdays">Tuesdays</option>
                        <option value="Wednesdays">Wednesdays</option>
                        <option value="Thursdays">Thursdays</option>
                        <option value="Fridays">Fridays</option>
                        <option value="Saturdays">Saturdays</option>
                    </select>

                    <div class="timediv d-flex justify-content-around">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="trainingtime" value="3pm-4pm" id="flexRadioDefault1
                    <label class="form-check-label" for="flexRadioDefault1">
    3pm-4pm
  </label>
</div>

<div class="form-check">
  <input class="form-check-input" type="radio" name="trainingtime" value="4pm-5pm" id="flexRadioDefault2"  required>
  <label class="form-check-label" for="flexRadioDefault2">
    4pm-5pm
  </label>
</div>
        
<div class="form-check">
  <input class="form-check-input" type="radio" value="5pm-6pm" name="trainingtime" id="flexRadioDefault2" required>
  <label class="form-check-label" for="flexRadioDefault2">
    5pm-6pm
  </label>
</div>
</div>
</div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update training</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </form>
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