<?php
    /* Forgot password link. Reset the user password by sending the request to reset.php */
    require 'database.php';
    session_start();
    
    // Check if form submitted with method="post"
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $email = $mysqli->escape_string($_POST['email']);
        $email_result = $mysqli->query("SELECT * FROM users WHERE email='$email'");
        
        // User does not exist
        if($email_result->num_rows == 0)
        {
            $_SESSION['message'] = "User with that e-mail does not exist. Please try again.";
            header("location: error.php");
        }
        else
        {
            $user = $email_result->fetch_assoc(); // Obtain array of user data
            
            $email = $user['email'];
            $hash = $user['hash'];
            $first_name = $user['first_name'];
            
            // Session message to display for success.php
            $_SESSION['message'] = "<p>Please check your e-mail <span>$email</span>"
            . " for a confirmation link to complete your password reset.</p>";
            
            // Send confirmation link to reset.php
            $to      = $email;
            $subject = 'Password Reset Link';
            $message_body = '
            Hi '.$first_name.',
            
            You have requested a password reset link.
            
            Please click the link provided below to reset your password:
            
            http://localhost/Login%20Page/reset.php?email='.$email.'&hash='.$hash;
            
            mail($to, $subject, $message_body);
            
            header("location: success.php");
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Reset Password</title>
        <script src="https://use.fontawesome.com/3feed0bc38.js"></script>
        <?php include 'css/css.html'; ?>
    </head>
    
    <body>
        <div class="banner">
            <a href="/">App Name</a>
        </div>
        <div class="form">
            <h1>Reset Your Password</h1>
            
            <form action="forgot.php" method="post">
                <div class="field-wrap">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    <label>
                        E-mail Address<span class="req">*</span>
                    </label>
                    <input type="email" required autocomplete="off" name="email"/>
                </div>
                <button class="button button-block"/>Reset</button>
            </form>
            
        </div>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src="js/index.js"></script>
    </body>
</html>