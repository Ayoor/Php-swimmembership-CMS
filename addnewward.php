<?php
session_start();

if (isset($_SESSION["email"])) {

    if ($_POST) {
        $parentemail = $_SESSION["email"];
        include "connect.php";

        // calculate age
        function calculateAge($birthdate)
        {
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

        if ($age < 18) {
            $newWard = array(
                'email' => $_POST['email'],
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'password' => $_POST['password'],
                'telephone' => $_POST['telephone'],
                'address' => $_POST['address'],
                'postcode' => $_POST['postcode'],
                'dob' => $_POST['dob'],
                'age' => $age,
                'gender' => $_POST['gender'],
                'roleid' => $_POST['roleid']
            );

            extract($newWard);

            // Sanitize user input
            $email = mysqli_real_escape_string($link, $email);
            $firstname = mysqli_real_escape_string($link, $firstname);
            $lastname = mysqli_real_escape_string($link, $lastname);
            $address = mysqli_real_escape_string($link, $address);
            $postcode = mysqli_real_escape_string($link, $postcode);
            $dob = mysqli_real_escape_string($link, $dob);
            $gender = mysqli_real_escape_string($link, $gender);

            // Validate and filter user input
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            // Perform additional validation and filtering for other input fields

            if ($email === false) {
                echo "Invalid email address";
                exit();
            }

            // Check if email already exists
            $retrieveQuery = "SELECT email FROM Users WHERE email = '$email'";
            $result = mysqli_query($link, $retrieveQuery);
            $existingUser = mysqli_fetch_assoc($result);

            if (!$existingUser) {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Prepare and execute the INSERT statement using prepared statements
                $insertQuery = "INSERT INTO `Users` (`id`, `firstName`, `lastName`, `email`, `password`, `address`, `postCode`, `d_o_b`, `age`, `gender`, `roleID`, `squadID`) 
                            VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, 5, 1)";

                $stmt = mysqli_prepare($link, $insertQuery);
                mysqli_stmt_bind_param($stmt, 'ssssssssi', $firstname, $lastname, $email, $hashedPassword, $address, $postcode, $dob, $age, $gender);
                mysqli_stmt_execute($stmt);

                $affectedRows = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);

                if ($affectedRows > 0) {
                    // Retrieve the newly inserted user's ID
                    $userId = mysqli_insert_id($link);

                    // Retrieve the parent's ID
                    $parentQuery = "SELECT id FROM Users WHERE email = ?";
                    $parentStmt = mysqli_prepare($link, $parentQuery);
                    mysqli_stmt_bind_param($parentStmt, 's', $parentemail);
                    mysqli_stmt_execute($parentStmt);
                    mysqli_stmt_bind_result($parentStmt, $parentid);
                    mysqli_stmt_fetch($parentStmt);
                    mysqli_stmt_close($parentStmt);

                    // Insert the ward record
                    $wardinsertQuery = "INSERT INTO `Ward` (`id`, `Parentid`, `Userid`) VALUES (NULL, ?, ?)";
                    $wardinsertStmt = mysqli_prepare($link, $wardinsertQuery);
                    mysqli_stmt_bind_param($wardinsertStmt, 'ii', $parentid, $userId);
                    mysqli_stmt_execute($wardinsertStmt);
                    mysqli_stmt_close($wardinsertStmt);

                    header("Location: ward.php");
                    exit();
                } else {
                    echo "Failed to insert user";
                }
            } else {
                echo "Email already exists";
            }
        } else {
            echo "Please sign up for an adult account";
        }
    }
}

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: signin.php");
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward Registration</title>

    <!--Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@p22opperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">

    <!-- css -->
    <link rel="stylesheet" href="signin.css">

    <!-- google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
    <div class="wrapper2">
        <div class="container">
            <h4 class="display-4">Ward Registration</h4>
            
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

                  
                </div>

                <div class="mb-3">
                    <label for="loginemail" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmpassword" placeholder="Confirm Password" name="confirmpassword" required>
                </div>

                <div class="mb-3">
                    <label for="loginemail" class="form-label">Telephone</label>
                    <input type="tel" class="form-control" id="telephone" placeholder="e.g +4412345678" name="telephone" pattern="+[0-9]{2}[0-9]{11}" maxlength="13" required>

                </div>

                <div class="mb-3">
                    <label for="loginemail" class="form-label">Address</label>
                    <input class="form-control" id="address" placeholder="Address" name="address" required>
                    <input class="form-control" id="postcode" placeholder="postcode" name="postcode" style="margin-top: 5px;" required>
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
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

                <div class="text-center"><input type="submit" id="submitbtn" value="Signup" name="signupform"></div>


                <p style="margin-top: 15px; ">Already have an Account? <a href="signin.php" style="color:hotpink" id="signup">Sign in</a></p>

            </form>

        </div>


        <script>
            email = $("#signupemail").val()
            password = $("#signuppword").val()
            passwordconfirm = $("#spasswordconfirm").val()

            $(function() {
                // enforce password strength
                $('#password').keyup(function() {

                    if (this.value.length < 8) {
                        console.log("Worked")
                        $("#pw-min-8chars").removeClass().addClass("pwfail");
                    } else {
                        $("#pw-min-8chars").removeClass().addClass("pwpass");
                    }


                    if (!this.value.match(/[a-z]/)) {
                        $("#pw-min-1char").removeClass().addClass("pwfail");
                    } else {
                        $("#pw-min-1char").removeClass().addClass("pwpass");
                    }


                    if (!this.value.match(/[A-Z]/)) {
                        $("#pw-min-1capital").removeClass().addClass("pwfail");
                    } else {
                        $("#pw-min-1capital").removeClass().addClass("pwpass");
                    }


                    if (!this.value.match(/[0-9]/)) {
                        $("#pw-min-1number").removeClass().addClass("pwfail");
                    } else {
                        $("#pw-min-1number").removeClass().addClass("pwpass");
                    }


                    if (this.value.match(/[!@#$%^&*(),.?":{}|<>]/)) {
                        $("#pw-no-repeat").removeClass().addClass("pwfail");
                    } else {
                        $("#pw-no-repeat").removeClass().addClass("pwpass");
                    }


                    if (this.value.length == 0) {
                        $(".pwhints ul li").each(function() {
                            $(this).removeClass();
                        });
                    }
                });
            });

            function verifyform() {

                if (email == "" || !isEmail(email)) {
                    errormessage += "<p>Please enter a valid Email</p>"
                }


                if (password == "") {
                    // alert (password= $("#signuppword").val())
                    errormessage += "<p>No password Entered</p>"
                }


                function isEmail(email) {
                    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    return regex.test(email);
                }
            }

            if (errormessage == "") {
                $("#aSuccess").show()
                $("#aDanger").hide()

            } else {
                $("#aSuccess").hide()
                $("#aDanger").html(errormessage)
                $("#aDanger").show()

            }



            $(".signupform").submit(function(e) {
                //setinputvalues()
                verifyform()
                var confirmpassword = $("#confirmpassword").val()

                if (confirmpassword != password) {
                    errormessage += "<p> Password mismatch, Please ensure the password and confirm passwords are same </p>"
                }


                if (errormessage == "" || errormessage == null) {
                    $("#aSuccess").show()
                    $("#aDanger").hide()
                    return true;
                } else {
                    $("#aSuccess").hide()
                    $("#aDanger").html(errormessage)
                    $("#aDanger").show()
                    errormessage = ""
                    return false
                }

            })

            $(".loginform").submit(function(e) {
                //setinputvalues()
                verifyform()
                if (errormessage == "") {
                    $("#aSuccess").show()
                    $("#aDanger").hide()
                    return true;
                } else {
                    $("#aSuccess").hide()
                    $("#aDanger").html(errormessage)
                    $("#aDanger").show()
                    errormessage = ""

                    return false
                }

            })
        </script>
</body>

</html>