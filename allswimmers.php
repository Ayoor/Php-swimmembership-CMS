<?php
session_start();
$issuccess = false;
include "connect.php";

if (isset($_SESSION["email"])) {
    $loginemail = $_SESSION['email'];

    $query = "SELECT
    Users.firstName AS firstname,
    Users.lastName,
    Users.gender,
    Users.age,
    Squad.Name AS squadname,
    Coach.firstName AS coach,
    MIN(galaPerformance.time) AS galaTime,
    MIN(trainingPerformance.time) AS trainingTime,
    (
        SELECT MIN(time)
        FROM trainingPerformance
        WHERE userID = Users.id
    ) AS trainingbesttime,
    (
        SELECT MIN(time)
        FROM galaPerformance
        WHERE userID = Users.id
    ) AS galabesttime,
    galaStrokes.Name AS galaStroke,
    trainingStrokes.Name AS trainingStroke
    FROM
    Users
    LEFT JOIN
    Squad ON Squad.id = Users.squadID
    LEFT JOIN
    Users AS Coach ON Coach.id = Squad.CoachID
    LEFT JOIN
    galaPerformance ON galaPerformance.userID = Users.id
    LEFT JOIN
    trainingPerformance ON trainingPerformance.userID = Users.id AND trainingPerformance.strokeId = galaPerformance.strokeId
    LEFT JOIN
    Strokes AS galaStrokes ON galaStrokes.id = galaPerformance.strokeId
    LEFT JOIN
    Strokes AS trainingStrokes ON trainingStrokes.id = trainingPerformance.strokeId
    WHERE
    Users.roleID IN (1, 5, 6)
    GROUP BY
    Users.id;";

    $query2 = "SELECT Users.firstName, Users.lastName, Users.email, Role.Name as Role FROM `Users` inner join Role ON Users.roleID = Role.id WHERE Users.email= ?";

    $allevents = "SELECT eventName from Gala";

    $stmt = mysqli_prepare($link, $query2);
    mysqli_stmt_bind_param($stmt, "s", $loginemail);
    mysqli_stmt_execute($stmt);
    $result3 = mysqli_stmt_get_result($stmt);

    $row = mysqli_query($link, $query);
    $row2 = mysqli_query($link, $allevents);

    $role = "";
    $firstname = "";
    $lastname = "";

    if ($result3) {
        $result3 = mysqli_fetch_array($result3);
        $role = $result3["Role"];
        $_SESSION["role"] = $role;
        $firstname = $result3["firstName"];
        $lastname = $result3["lastName"];
    }

    }

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: guestallswimmers.php");
    exit;
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swimmers
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
        
        <h2>CRSC Swimmers</h2>
<br>

<!--  -->
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Squad</th>
                    <th>Coach</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Training Best Time</th>
                    <th>Stroke</th>
                    <th>Competion Best Time</th>
                    <th>Stroke</th>
                </tr>
            </thead>
           <tbody>
    <?php
    while ($result = mysqli_fetch_assoc($row)) {
    ?>
        <tr>
            <td><?php echo $result['firstname']; ?></td>
            <td><?php echo $result['lastName']; ?></td>
            <td><a href="viewsquadmembers.php?squadname=<?php echo $result['squadname']; ?>"><?php echo $result['squadname']; ?></a></td>
            <td><?php echo $result['Coach'] !== null ? $result['Coach'] : 'N/A'; ?></td>
            <td><?php echo $result['gender']; ?></td>
            <td><?php echo $result['age']; ?></td>
            <td><?php echo $result['trainingbesttime'] !== null ? $result['trainingbesttime'] : 'N/A'; ?></td>
            <td><?php echo $result['trainingStroke'] !== null ? $result['trainingStroke'] : 'N/A'; ?></td>
            <td><?php echo $result['galabesttime'] !== null ? $result['galaTime'] : 'N/A'; ?></td>
            <td><?php echo $result['galaStroke'] !== null ? $result['galaStroke'] : 'N/A'; ?></td>
        </tr>
    <?php } ?>
</tbody>


        </table>
        
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