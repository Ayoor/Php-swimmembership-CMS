<?php
 session_start();
if($_SESSION['email']){
    unset($_SESSION['email']);
}

 include "connect.php";

if($_POST){  
  
    $loginemail= $_POST['loginemail']; 
    $loginpassword= $_POST['loginpassword'];
   
  $query = "SELECT email, firstName, password FROM `Users` WHERE email = '$loginemail'";
 
      $row= mysqli_query($link, $query);
      $result= mysqli_fetch_array($row); //get result in an array  
   
            if($result["email"]== "NULL"|| $result["email"]== "" ){
              echo "User not found, Signup to have an account <br>";  
                  
              }
              
             
              
              if( !(password_verify($loginpassword, $result["password"]))){ 
                  echo "Incorrect Password";

              }

              
             
              if($result["email"] && password_verify($loginpassword, $result["password"])){
                 
                  $_SESSION['email']= $result["email"];
                  
              header("Location: squads.php");
              
            //   https://ayodele.me/swimmembership/squads.php
            
       
}
}
?>










<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>

     <!--Bootstrap 5 -->
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script> 
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
     
     <!-- css -->
     <link rel="stylesheet" href="signin.css">
     
     <!-- google font -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com">
 <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet" >
 
 <!-- font-awesome --> 
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 
 <!-- jquery -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body>
  <div class="wrapper">
    <div class="container">
      <h4 class="display-4">Sign in!</h4>
   
    <form  class="loginform" method= "post" >
  
        <div class="mb-3">
            <label for="loginemail" class="form-label">Email address</label>
            <input class="form-control" id="loginemail" placeholder="Your Email" name="loginemail" required>
          </div>

                    
          <div class="mb-3">
            <label for="" class="form-label">Password</label>
            <input type="password" class="form-control" id="loginpassword" placeholder="Password" name="loginpassword"required>
          </div>  
    
                 <button id="submitbtn">Submit</button>
    
     <p style="margin-top: 15px; ">Dont have an Account? <a href="signup.php" style="color:hotpink" id="signup" >Signup</a></p>
    <p id="forgotpassword"><a href="" style="color:hotpink"> Forgot Password?</a></p>
    </form>
</div>
</div>



</body>
</html>