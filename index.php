<?php 
session_start();

//Include the config file
include "includes/config.php";

//Define variables and initialize with empty values

$f_name_err = $l_name_err = $email_err = $username_err = $pwd_err = $u_name_err = $pass_err = "";


//Processing form data when the form is submitted
if (($_SERVER["REQUEST_METHOD"] == "POST")) {

    //Signup Auth
    if (isset($_POST["submit_signup"])) {
        //Set first name
        $input_f_name = trim($_POST["first_name"]);
        $f_name = $input_f_name;

        //Set last name
        $input_l_name = trim($_POST["last_name"]);
        $l_name = $input_l_name;

        //Set email
        $input_email = trim($_POST["email"]);
        if (empty($input_email)) {
            $email_err = "";
        } else {
            $sql = "SELECT * FROM users WHERE email='$input_email'";
            $result = mysqli_query($link, $sql);
            $resultCheck = mysqli_num_rows($result);

            if ($resultCheck > 0) {
                $email_err = "This email is already taken";
            } else {
                $email = $input_email;
            }
        }

        //Set username
        $input_username = trim($_POST["username"]);
        if (empty($input_username)) {
            $username_err = "";
        } else {
            $sql = "SELECT * FROM users WHERE username='$input_username'";
            $result = mysqli_query($link, $sql);
            $resultCheck = mysqli_num_rows($result);

            if ($resultCheck > 0) {
                $username_err = "This username is already taken";
            } else {
                $username = $input_username;
            }
        }
        
        //Set password
        $input_pwd = trim($_POST["password"]);
        if (empty($input_pwd)) {
            $pwd_err = "";
        } else {
            //hashing the password
            $hashedPwd = password_hash($input_pwd, PASSWORD_DEFAULT);
            $pwd = $hashedPwd;
        }

        //Check input errors before inserting into the database
        if (empty($f_name_err) && empty($l_name_err) && empty($email_err) && empty($username_err) && empty($pwd_err)) {
            
            // Prepare an insert statement
            $sql = "INSERT INTO users (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($link, $sql)) {
                //Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssss", $param_f_name, $param_l_name, $param_email, $param_username, $param_pwd);

                // Set parameters
                $param_f_name = $f_name;
                $param_l_name = $l_name;
                $param_email = $email;
                $param_username = $username;
                $param_pwd = $pwd;

                //Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    header("location: dashboard/");
                    exit();
                } else {
                    echo "<p>Something went wrong</p>";
                }
            }

            //Close statement
            mysqli_stmt_close($stmt);

        } else {
            echo "Try again";
        }

        //Close connection
        mysqli_close($link);
    }





    //Login Auth
    if (isset($_PUT["submit_login"])) {
        
        //validate username
        $input_u_name = trim($_POST["u_name"]);
        if (empty($input_u_name)) {
            $u_name_err = "";
        } else {
            $sql = "SELECT * FROM table WHERE username='$input_u_name' || email='$input_u_name' ";
            $result = mysqli_query($link, $sql);
            $resultCheck = mysqli_num_rows($result);

            if ($resultCheck < 1) {
                $u_name_err = "This user does not exist";
            } else {
                $u_name = $input_u_name;
            }
        }
        
        //validate password
        $input_pass = trim($_POST["pass"]);
        if (empty($input_pass)) {
            $pass_err = "";
        }  else {
            $pass = $input_pass;
        }

        //Check input errors before login
        if (empty($u_name_err) && empty($pass_err)) {
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                $hashedPwdCheck = password_verify($pass, $row['password']);
                if ($hashedPwdCheck == false) {
                    $pass_err = "Incorrect password";
                } elseif ($hashedPwdCheck == true) {
                    $_SESSION["app_user"] = $row['username'];
                    $_SESSION["app_email"] = $row['email'];
                    $_SESSION['expire'] = time() + (45 * 60);

                    $result = mysqli_query($link, $sql);

                    // Redirect to dashboard
                    
                }
            } else {
                // Redirect to error page or same page
                header("location: index.php?error");
                exit();
            }

            // Close statement
            //mysqli_stmt_close($stmt);
        } 

        // Close connection
        mysqli_close($link);
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta http-equiv="refresh" content="3"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Auth</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div id="form">
        <div class="container">
            <h1 class="text-center mt-5"><span class="typed-text"></span><span class="cursor">&nbsp;</span> with your friends!</h1>
            <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-md-8 col-md-offset-2">
                <div id="userform">
                    <!-- Tab navigation -->
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li class="active"><a href="#signup"  role="tab" data-toggle="tab">Sign up</a></li>
                    <li><a href="#login"  role="tab" data-toggle="tab">Log in</a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- The Signup form -->
                        <div class="tab-pane fade active in" id="signup">
                            <h2 class="text-uppercase text-center"> Create an account</h2>
                            <form id="signup" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group <?php echo (!empty($f_name_err)) ? 'has-error' : '' ?>">
                                            <label>First Name<span class="req">*</span> </label>
                                            <input type="text" class="form-control" name="first_name" value="<?php echo $f_name; ?>" id="first_name" required data-validation-required-message="Please enter your name." autocomplete="off">
                                            <p class="help-block text-danger"><?php echo $f_name_err; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                    <div class="form-group <?php echo (!empty($l_name_err)) ? 'has-error' : '' ?>">
                                        <label> Last Name<span class="req">*</span> </label>
                                        <input type="text" class="form-control" name="last_name" value="<?php echo $l_name; ?>" id="last_name" required data-validation-required-message="Please enter your name." autocomplete="off">
                                        <p class="help-block text-danger"><?php echo $f_name_err; ?></p>
                                    </div>
                                    </div>
                                </div>

                                <!-- Email and username input -->
                                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : '' ?>">
                                    <label> Email<span class="req">*</span> </label>
                                    <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" id="email" required data-validation-required-message="Please enter your email address." autocomplete="off">
                                    <p class="help-block text-danger"><?php echo $email_err; ?></p>
                                </div>
                                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : '' ?>">
                                    <label> Username<span class="req">*</span> </label>
                                    <input type="text" class="form-control" name="username" value="<?php echo $username; ?>" id="username" required data-validation-required-message="Please enter your phone number." autocomplete="off">
                                    <p class="help-block text-danger"><?php echo $username_err; ?></p>
                                </div>

                                <!-- Password input -->
                                <div class="form-group <?php echo (!empty($pwd_err)) ? 'has-error' : '' ?>">
                                    <label> Password<span class="req">*</span> </label>
                                    <input type="password" class="form-control" name="password" value="<?php echo $pwd; ?>" id="password" required data-validation-required-message="Please enter your password" autocomplete="off">
                                    <p class="help-block text-danger"><?php echo $pwd_err; ?></p>
                                </div>

                                <!-- Submit button -->
                                <div class="mrgn-30-top">
                                    <button type="submit" name="submit_signup" class="btn btn-larger btn-block">
                                    Sign up
                                    </button>
                                </div>
                            </form>
                        </div>


                        <!-- The login form -->
                        <div class="tab-pane fade in" id="login">
                            <h2 class="text-uppercase text-center"> Let's gooo!</h2>
                            <form id="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <div class="form-group <?php echo (!empty($u_name_err)) ? 'has-error' : '' ?>">
                                    <label> Username / Email<span class="req">*</span> </label>
                                    <input type="text" class="form-control" name="u_name" value="<?php echo $u_name; ?>" id="email" required data-validation-required-message="Please enter your email address." autocomplete="off">
                                    <p class="help-block text-danger"><?php echo $u_name_err; ?></p>
                                </div>

                                <div class="form-group <?php echo (!empty($pass_err)) ? 'has-error' : '' ?>">
                                    <label> Password<span class="req">*</span> </label>
                                    <input type="password" class="form-control" name="pass" value="<?php echo $pass; ?>" id="password" required data-validation-required-message="Please enter your password" autocomplete="off">
                                    <p class="help-block text-danger"><?php echo $pass_err; ?></p>
                                </div>
                                <div class="mrgn-30-top">
                                    <button type="submit" name="submit_login" class="btn btn-larger btn-block">
                                    Log in
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container --> 
    </div>

    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="assets/js/new.js"></script>
</body>
</html>