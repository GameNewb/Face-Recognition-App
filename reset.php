<?php
    /* Password reset form. Allow user to reset the password by accessing the forgot link (forgot.php) */
    require 'database.php';
    session_start();

    // E-mail and hash must not be empty
    if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash']))
    {
        $email = $mysqli->escape_string($_GET['email']);
        $hash = $mysqli->escape_string($_GET['hash']);
        
        // Obtain email by matching the hash to the e-mail
        $email_result = $mysqli->query("SELECT * FROM users WHERE email='$email' AND hash='$hash'");
        
        if($email_result->num_rows == 0)
        {
            $_SESSION['message'] = "You have entered an invalid URL for the password reset.";
            header("location: error.php");
        }
    }
    else
    {
        $_SESSION['message'] = "Sorry, verification has failed. Please try again.";
        header("location: error.php");
    }
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Reset Password</title>
        <script src="https://use.fontawesome.com/3feed0bc38.js"></script>
        <?php include 'css/css.html'; ?>
    </head>
    
    <body>
        <div class="banner">
            <a href="/">App Name</a>
        </div>
        <div class="form">
            <h1>Enter Your New Password</h1>
            
            <form action="reset_password.php" method="post">
            
                <div class="field-wrap">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                    <label>
                        New Password<span class="req">*</span>
                    </label>
                    <input type="password" required name="newpassword" autocomplete="off"/>
                </div>
            
                <div class="field-wrap">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                    <label>
                        Confirm New Password<span class="req">*</span>
                    </label>
                    <input type="password" required name="confirmpassword" autocomplete="off"/>
                </div>
                
                <!-- Get the e-mail from the user -->
                <input type="hidden" name="email" value="<?= $email ?>">
                <input type="hidden" name="hash" value="<?= $hash ?>">
                
                <button class="button button-block"/>Reset</button>
            
            </form>
        </div>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src="js/index.js"></script>
    </body>
</html>