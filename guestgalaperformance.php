<?php
session_start();
include "connect.php";

$loginemail = $_SESSION['email'];
$galaname = $_GET['gala'];

$logindetailsquery = "SELECT Users.firstName, Users.lastName, Users.email, Role.Name as Role FROM `Users` INNER JOIN Role ON Users.roleID = Role.id WHERE Users.email = ?";
$galadataquery = "SELECT galaPerformance.id, galaPerformance.Position, Users.firstName AS Swimmer, Strokes.Name AS stroke, galaPerformance.date, galaPerformance.distance AS Distance, galaPerformance.time as Timee, Squad.name AS squad
        FROM galaPerformance
        JOIN Users ON galaPerformance.userId = Users.id
        JOIN Strokes ON galaPerformance.strokeId = Strokes.id
        JOIN Squad ON Users.squadID = Squad.id
        WHERE galaPerformance.galaId = (SELECT Gala.id FROM Gala WHERE Gala.eventName = ?)
        ORDER BY galaPerformance.Position";

$swimmerslistquery = "SELECT Users.firstName AS Swimmer
                                FROM Users
                                WHERE Users.roleID IN (1, 5, 6)";

$strokesquery =  "SELECT Strokes.Name from Strokes";

$stmt1 = mysqli_prepare($link, $logindetailsquery);
mysqli_stmt_bind_param($stmt1, "s", $loginemail);
mysqli_stmt_execute($stmt1);
$result1 = mysqli_stmt_get_result($stmt1);
$row1 = mysqli_fetch_array($result1);

$stmt2 = mysqli_prepare($link, $galadataquery);
mysqli_stmt_bind_param($stmt2, "s", $galaname);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);

$row3 = mysqli_query($link, $swimmerslistquery);
$row4 = mysqli_query($link, $strokesquery);

$role = $row1["Role"];
$_SESSION["role"] = $role;
$firstname = $row1["firstName"];
$lastname = $row1["lastName"];
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

    


    <!--------------------------------------top navbar------------------------------------>

    <nav class="nav-options">

        <a href="signin.php">Sign in</a>

    </nav>


  
        <h2><?=$galaname ?> Performances</h2>


        <table >
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

         
        </tr>
    <?php } ?>
</tbody>


        </table>


      

     
    


   



</body>

</html>