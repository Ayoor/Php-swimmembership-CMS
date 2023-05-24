<?php
include "connect.php";
session_start();
if (isset($_SESSION["email"])) {
  $loginemail = $_SESSION['email'];
  $logindetailsquery = "SELECT  Users.firstName, Users.lastName, Users.email, Role.Name as Role FROM `Users` inner join Role ON Users.roleID = Role.id WHERE Users.email= '$loginemail';";
  $row = mysqli_query($link, $logindetailsquery);
  $result = mysqli_fetch_array($row);
  $firstname = $result["firstName"];
}
?>







<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Welcome to College Road</title>

  <!--Bootstrap 5 -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">

  <!--css -->
  <link rel="stylesheet" href="style.css">

  <!--google font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!--font-awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
  <section id="landing-page">
  <!-- top bar navigation -->
  

    <nav class="navigation">

      <a id="brand" href="#"><h2>CRSC</h2></a>

      <div id="engagement">
        <a href="allswimmers.php">Records</a>
        <a href="galaevents.php">Gala</a>
      </div>
      <?php if ($result["firstName"]) { ?>
        <div id="signindiv">
          <span> Signed in as <?= $firstname ?></span>
          <span><a href="signout.php">Sign Out</a></span>
        </div>
      <?php } else { ?>

        <div id="signindiv">
          <a href="signin.php">Sign in</a>
          <a href="signup.php">Join Membership</a>
        </div>

      <?php } ?>
    </nav>
 
<section id="pagebody" class="d-flex align-content-around">

<div class="jumbotron text-center ">
        <h1 class="display-2" style="font-weight: 500;">Welcome to College Road <br>Swim Club</h1><br>
        <p id="welcometext">Dive into Excellence, Swim with Passion!!</p>
        <div class="buttondiv">
          <a href="signup.php" class="downloadbutton">Sign up</a>
        <a href="signin.php" class="signup">Sign in</a>
        </div>
        
    </div>
</section>
  </section>
</body>

</html>