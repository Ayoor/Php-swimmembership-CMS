<?php
session_start();

if ($_POST){
    
    include "connect.php";
   
   
    // calculate age
    function calculateAge($birthdate) {
    // Convert the birthdate to a DateTime object
    $birthDate = new DateTime($birthdate);
    
    // Get the current date as a DateTime object
    $currentDate = new DateTime();
    
    // Calculate the difference between the current date and birthdate
    $ageInterval = $currentDate->diff($birthDate);
    
    // Extract the years from the age interval
    $age = $ageInterval->y;
    
    return $age;
}


$birthdate = $_POST['dob']; 
$age = calculateAge($birthdate);

    
    if (!$_POST['roleid']){
        $_POST['roleid']= 1; 
    } 
    
    if($age >= 18){
    
    $newuser= array(
        'email' => $_POST['email'],
        'firstname' => $_POST['firstname'],
        'lastname' => $_POST['lastname'],
        'password'=> $_POST['password'],
         'telephone'=> $_POST['telephone'],
        'address'=> $_POST['address'],
         'postcode'=> $_POST['postcode'],
         'dob'=> $_POST['dob'],
         'age'=> $age,
         'gender'=> $_POST['gender'],
          'roleid'=> $_POST['roleid']
                );
  
    // print_r($newuser);
    // convert array values to string;
    
    extract($newuser);
    
    
    $retrivequery = "SELECT email FROM Users WHERE email = '$email'";
    $hashedpassword= password_hash($password, PASSWORD_DEFAULT);
    $insertquery = " INSERT INTO `Users` (`id`, `firstName`, `lastName`, `email`, `password`, `address`, `postCode`, `d_o_b`, `age`,`gender`, `roleID`, `squadID`) 
                                VALUES (NULL, '$firstname', '$lastname', '$email', '$hashedpassword', '$address', '$postcode', '$dob', '$age', '$gender', '$roleid', 1)";

             $row= mysqli_query($link, $retrivequery);
            $result= mysqli_fetch_array($row); //get result in an array


            if(!$result["email"]){
       
                  mysqli_query($link, $insertquery);
                $_SESSION['email']= $email;
                //   echo $_SESSION['email'];
              header("Location: squads.php");
            //   echo "good";
                              }
             
              else{
                  echo("Email already exists");
              }
    }
    else{
        echo "Sorry you are not Old enough to have an adult account, reach out to admin for further assistance";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Memebership</title>

     <!--Bootstrap 5 --> 
     <script src="https://cdn.jsdelivr.net/npm/@p22opperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
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
  <div class="wrapper2">
    <div class="container"> 
      <h4 class="display-4">Sign Up!</h4>
    <form method="post" class="loginform">
  
        <div class="mb-3">
            <label for="loginemail" class="form-label">Email address</label>
            <input class="form-control" id="loginemail" placeholder="Your Email" name="email" required>
          </div>

          <div class="mb-3">
            <label for="loginemail" class="form-label">First name</label>
            <input class="form-control" id="firstname" placeholder="First name" name="firstname" required>
          </div>

          <div class="mb-3">
            <label for="loginemail" class="form-label">Last Name</label>
            <input class="form-control" id="lastame" placeholder="Last Name" name="lastname" required>
          </div>
          
          <div class="mb-3">
            <label for="loginemail" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>

            <!--<div class="pwhints">-->
            <!--  <ul>-->
            <!--    <li id="pw-min-8chars">Minimum of 8 Characters.</li>-->
            <!--    <li id="pw-min-1char">Minimum of 1 number</li>-->
            <!--    <li id="pw-min-1capital">Minimum Capital Letter.</li>-->
            <!--    <li id="pw-min-1number">Minimum of 1 number.</li>-->
            <!--    <li id="pw-no-repeat">Minimum of 1 special character</li>-->
            <!--  </ul>  -->
            <!--    </div>-->
          </div>
          
          <div class="mb-3">
            <label for="loginemail" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirmpassword" placeholder="Confirm Password" name="confirmpassword" required>
                  </div>

          <div class="mb-3">
            <label for="loginemail" class="form-label">Telephone</label>
            <input type="tel" class="form-control" id="telephone" placeholder="e.g +4412345678" name="telephone" pattern="+[0-9]{2}[0-9]{11}" maxlength="13"
            required>

          </div>

          <div class="mb-3">
            <label for="loginemail" class="form-label">Address</label>
            <input class="form-control" id="address" placeholder="Address" name="address" required>
            <input class="form-control" id="postcode" placeholder="postcode"   name="postcode" style="margin-top: 5px;" required>
          </div>
          
          <div class="mb-3">
            <label for="" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="dob"  name="dob" required>
          </div>  
    
          <div class="mb-3">
            <label for=" " class="form-label">Gender</label>
            <select class="form-select" aria-label="Default select example" required name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="NG">Prefer not to say
                  
                </option>
              </select>
          </div>

                  
          
           
          <div id="parentsdiv" style= "color: rgb(17, 192, 76)">
            <span>Parents Only</span>
            <div>
                 <input type="radio" id="html" name="roleid" value="2">
                <label for="html" class="form-label">I am registering as Parent to Oversee My Child(ren)</label><br>
               
                <input type="radio" id="css" name="roleid" value="6">
                <label for="css" class="form-label">I am registering as a Swimmer and as a Parent</label><br>
 
         
          </div>
         </div>
            <div class="text-center"><input type="submit" id="submitbtn" value="Signup" name="signupform"></div>
     
    
     <p style="margin-top: 15px; ">Already have an Account? <a href="signin.php" style="color:hotpink" id="signup" >Sign in</a></p>
   
    </form>
</div>



  <script>
    email= $("#signupemail").val()
    password= $("#signuppword").val()
    passwordconfirm= $("#spasswordconfirm").val()

$(function() {
  // enforce password strength
  $('#password').keyup(function() {
    
    if(this.value.length<8) {
      console.log("Worked")
      $("#pw-min-8chars").removeClass().addClass("pwfail");
		}
		else {
      $("#pw-min-8chars").removeClass().addClass("pwpass");
		}
    
    
    if(!this.value.match(/[a-z]/)) {
      $("#pw-min-1char").removeClass().addClass("pwfail");
		}
		else {
      $("#pw-min-1char").removeClass().addClass("pwpass");
		}      

    
    if(!this.value.match(/[A-Z]/)) {
      $("#pw-min-1capital").removeClass().addClass("pwfail");
		}
		else {
      $("#pw-min-1capital").removeClass().addClass("pwpass");
		}  
    
    
    if(!this.value.match(/[0-9]/)) {
      $("#pw-min-1number").removeClass().addClass("pwfail");
		}
		else {
      $("#pw-min-1number").removeClass().addClass("pwpass");
		}  


    if(this.value.match(/[!@#$%^&*(),.?":{}|<>]/)) {
      $("#pw-no-repeat").removeClass().addClass("pwfail");
		}
		else {
      $("#pw-no-repeat").removeClass().addClass("pwpass");
		}     
    
    
    if(this.value.length==0) {
      $( ".pwhints ul li" ).each(function () {
        $(this).removeClass();
      });
		}
  });
});

function verifyform(){
 
  if(email==""|| !isEmail(email)){
      errormessage+= "<p>Please enter a valid Email</p>"
  }


if (password==""){
// alert (password= $("#signuppword").val())
    errormessage+= "<p>No password Entered</p>"
}


function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}
}

if(errormessage==""){
  $("#aSuccess").show()
  $("#aDanger").hide()
  
}
else{
  $("#aSuccess").hide()
  $("#aDanger").html(errormessage)
  $("#aDanger").show()
  
}



$(".signupform").submit(function(e){
//setinputvalues()
  verifyform()
  var confirmpassword= $("#confirmpassword").val()

  if (confirmpassword!= password){
      errormessage+= "<p> Password mismatch, Please ensure the password and confirm passwords are same </p>"
  }


  if(errormessage==""|| errormessage==null){
  $("#aSuccess").show()
  $("#aDanger").hide()
  return true;
}
else{
  $("#aSuccess").hide()
  $("#aDanger").html(errormessage)
  $("#aDanger").show()
  errormessage=""
  return false
}

})

$(".loginform").submit(function(e){
  //setinputvalues()
  verifyform()
  if(errormessage==""){
  $("#aSuccess").show()
  $("#aDanger").hide()
  return true;
}
else{
  $("#aSuccess").hide()
  $("#aDanger").html(errormessage)
  $("#aDanger").show()
  errormessage=""     
  
  return false
}

})


</script>
</body>
</html>