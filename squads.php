<?php
session_start();
include "connect.php";

if (isset($_SESSION["email"])) {
    $loginemail = $_SESSION['email'];



    $query = "SELECT Users.firstName, Users.lastName, Users.email, Role.Name as Role FROM `Users` inner join Role ON Users.roleID = Role.id WHERE Users.email= '$loginemail';";

    $allsquads = "SELECT Squad.Name, Squad.Desciption as 'desc', Users.firstName as 'Coach Name' 
            FROM Squad 
            LEFT JOIN Users ON Users.id = Squad.CoachID";
            
    $retrievecoachquery = "SELECT firstName FROM Users WHERE Users.roleID=3";
    //  $squards= "SELECT firstName, lastName  FROM `Users` ";   
    $row = mysqli_query($link, $query);
    $row2 = mysqli_query($link, $allsquads);
    $row3 = mysqli_query($link, $retrievecoachquery);
    

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


include "createnewsquad.php";


?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome
        <?= $firstname ?>
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
    if ($role != "Admin") {
        echo("<style>
            #addbtn,
            #editbtn,
            #deletebtn, 
            .addedit {
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
    <h2>Squads</h2>

    <!-- create squad trigger modal -->
    <p class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary" id="addbtn" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Create New Squad
        </button>
    </p>

    <!-- ---------------------InsertModal ---------------------------->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Squad</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="squadname" class="form-label">Squad Name</label>
                            <input class="form-control" id="squadname" placeholder="Squad Name" name="squadname" required>
                        </div>
                        <div class="mb-3">
                            <label for="squaddescription" class="form-label">Squad Description</label>
                            <textarea class="form-control" id="squaddescription" rows="3" placeholder="Squad Description" name="description" required></textarea>
                        </div>
                       
                        <div class="mb-3">
                                <label for="coach" class="form-label">Coach</label>
                                <select id="coach" class="form-select form-select-md mb-3" name="coach" aria-label=".form-select-md example" required>
                                    <?php while ($result3 = mysqli_fetch_assoc($row3)) { ?>
                                        <option value="<?php echo $result3['firstName']; ?>"><?php echo $result3['firstName']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create Squad</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table >
        <thead>
            <tr>
            <th>Squad Name</th>
            <th>Description</th>
            <th>Coach</th>
            <th class="addedit">Edit</th>
            <th class="addedit">Delete</th>
            </tr>
        </thead>
        <tbody>
     <?php while ($result2 = mysqli_fetch_assoc($row2)) { ?>
        <tr>
        <td>
                    <a href="viewsquadmembers.php?squadname=<?php echo $result2['Name']; ?>">
                        <?php echo $result2['Name']; ?>
                    </a>
                </td>
                <td><?php echo $result2['desc']; ?></td>
                <td><?php echo $result2['Coach Name'] !== null ? $result2['Coach Name'] : 'N/A'; ?></td>
                <?php $loopsquad = $result2['Name']; ?>
                <td>
                    <a id="editbtn" class="btn btn-success" onclick="updateFormAction('<?php echo $loopsquad; ?>')" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                        Edit Squad
                    </a>
                </td>
                <td>
                    <a id="deletebtn" class="btn btn-danger" href="#" data-link="removefromsquad.php?squadname=<?php echo $loopsquad; ?>" data-bs-toggle="modal" data-bs-target="#deleteSquadModal">
                        Delete Squad
                    </a>
                </td>
        </tr>
    <?php } ?>
</tbody>

    </table>



    <!-- Delete Squad Modal -->
    <div class="modal fade" id="deleteSquadModal" tabindex="-1" aria-labelledby="deleteSquadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSquadModalLabel">Are you sure you want to delete this squad?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" id="delete-squad-confirmation-btn" href="#">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ---------------------Update Modal ---------------------------->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Squad</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editin" method="POST" action="updatesquad.php">
                        <input type="hidden" id="oldsquadname" name="squadname" value="<?php echo isset($loopsquad) ? $loopsquad : ''; ?>">
                        <div class="mb-3">
                            <label for="squadname" class="form-label"> Updated Squad Name</label>
                            <input class="form-control" id="squadupdatename" placeholder="Squad Name" name="name" required>
                        </div>  
                        <div class="mb-3">
                            <label for="squaddescription" class="form-label">Updated Squad Description</label>
                            <textarea required class="form-control" id="squadupdatedescription" rows="3" placeholder="Squad Description" name="description"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Squad</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>
<!-- ---------------------delete Modal ---------------------------->
    <div class="modal" tabindex="-1" id="exampleModal3">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Squad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this squad?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Delete</button>
                </div>
            </div>
        </div>
    </div>
</section>


        <script>
            //set link of button inside modal to match table link
            function updateFormAction(squadName) {
                $('#editin').attr('action', 'updatesquad.php?squadname=' + encodeURIComponent(squadName));
                $('#oldsquadname').val(squadName);
            }

            //tells the delete modal where to get the link
            $('#deleteSquadModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var link = button.data('link');
                var deleteBtn = $(this).find('#delete-squad-confirmation-btn');
                deleteBtn.attr('href', link);
            });

           

        </script>



</body>

</html>