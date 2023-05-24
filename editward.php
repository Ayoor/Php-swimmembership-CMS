<?php
include "connect.php";
session_start();
$loginemail = $_SESSION['email'];

if ($_POST) {
    $role = $_SESSION['role'];
    if ($role == "Admin" || $role == "Parent" || $role == "Parent & Member") {
        $currentname = mysqli_real_escape_string($link, $_POST["currentname"]);
        $firstname = mysqli_real_escape_string($link, $_POST["firstname"]);
        $lastname = mysqli_real_escape_string($link, $_POST['lastname']);
        $address = mysqli_real_escape_string($link, $_POST['address']);
        $telephone = mysqli_real_escape_string($link, $_POST['telephone']);
        $email = mysqli_real_escape_string($link, $_POST['email']);
       
        // Prepare the subquery to retrieve the ward ID
        $wardid = "(SELECT Users.id
        FROM Ward
        INNER JOIN Users ON Ward.Userid = Users.id
        INNER JOIN Squad ON Users.squadID = Squad.id
        INNER JOIN Training ON Squad.id = Training.squadID
        WHERE Ward.parentid = (SELECT Users.id FROM Users WHERE Users.email = '$loginemail') AND Users.firstName = '$currentname')";

        // Prepare the query to check if the new email already exists
        $checkemail =  "SELECT Users.email FROM Users WHERE Users.email = '$email'";
        
        $checkwardidresult = mysqli_query($link, $wardid);
        $checkwardemailresult = mysqli_query($link, $checkemail);

        if ($checkwardidresult && mysqli_num_rows($checkwardidresult) > 0) {
            if (!($checkwardemailresult && mysqli_num_rows($checkwardemailresult) > 0)) {
                // Update query with parameter binding
                $updatequery = "UPDATE `Users` SET Users.firstName = ?, Users.lastName = ?, Users.address = ?, Users.email = ?
                WHERE Users.id = ?";

                $stmt = mysqli_prepare($link, $updatequery);
                mysqli_stmt_bind_param($stmt, "ssssi", $firstname, $lastname, $address, $email, $wardid);

                if (mysqli_stmt_execute($stmt)) {
                    $issuccess = true;
                    header("Location: ward.php");
                } else {
                    echo "Failed to update the ward. Please try again later.";
                }
            } else {
                echo "Email already exists. Please try again later.";
            }
        }
    } else {
        echo "<script>alert('You don\'t have permission to do that sorry!')</script>";
    }
}

$retrivewardsquery = "SELECT Users.firstName FROM Ward
INNER JOIN Users ON Ward.Userid = Users.id
WHERE Ward.parentid = (SELECT Users.id FROM Users WHERE Users.email = ?)";

$stmt = mysqli_prepare($link, $retrivewardsquery);
mysqli_stmt_bind_param($stmt, "s", $loginemail);
mysqli_stmt_execute($stmt);

$wardsrow = mysqli_stmt_get_result($stmt);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Ward Details</title>

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
            <h4 class="display-4">Update Ward Details</h4>

            
            
            <form method="post" class="loginform">

                 <div class="mb-3">
                        <label for="trainingname" class="form-label">Select Ward</label>
                        <select id="squadlist" class="form-select form-select-lg mb-3" name="currentname" aria-label=".form-select-lg example"  required>
                            <?php while ($wardsresult = mysqli_fetch_array($wardsrow)) { ?>
                                <option value="<?php echo $wardsresult['firstName']; ?>"><?php echo $wardsresult['firstName']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                <div class="mb-3">
                    <label for="loginemail" class="form-label">Updated Email address</label>
                    <input class="form-control" id="loginemail" placeholder="Your Email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="loginemail" class="form-label">Updated First name</label>
                    <input class="form-control" id="firstname" placeholder="First name" name="firstname" required>
                </div>

                <div class="mb-3">
                    <label for="loginemail" class="form-label">Updated Last Name</label>
                    <input class="form-control" id="lastame" placeholder="Last Name" name="lastname" required>
                </div>


                <div class="mb-3">
                    <label for="loginemail" class="form-label">Updated Telephone</label>
                    <input type="tel" class="form-control" id="telephone" placeholder="e.g +4412345678" name="telephone" pattern="+[0-9]{2}[0-9]{11}" maxlength="13" required>

                </div>

                <div class="mb-3">
                    <label for="loginemail" class="form-label">Updated Address</label>
                    <input class="form-control" id="address" placeholder="Address" name="address" required>
                    <input class="form-control" id="postcode" placeholder="postcode" name="postcode" style="margin-top: 5px;" required>
                </div>

                <div class="text-center"><input type="submit" id="submitbtn" value="Update" name="signupform"></div>

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
