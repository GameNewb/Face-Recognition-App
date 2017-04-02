<?php
    /* The actual resetting of the password. Used in conjunction with forgot.php (reset link) and reset.php (accessing forgot.php) */
    require 'database.php';
    session_start();

    // Check if form submitted with method="post"
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        // Ensure that the password matches
        if($_POST['newpassword'] == $_POST['confirmpassword'])
        {
            // Encrypt new password
            $new_password = password_hash($_POST['newpassword'], PASSWORD_BCRYPT);
            
            // Obtain the users e-mail and hash ($_POST) from reset.php
            $email = $mysqli->escape_string($_POST['email']);
            $hash = $mysqli->escape_string($_POST['hash']);
            
            $sql = "UPDATE users SET password='$new_password', hash='$hash' WHERE email='$email'";
            
            if($mysqli->query($sql))
            {
                $_SESSION['message'] = "Password reset successful. Please login with your new password.";
                header("location: success.php");
            }
        }
        else // New password and confirmation new password do not match
        {
            $_SESSION['message'] = "Passwords do not match. Please try again.";
            header("location: error.php");
        }
    }
?>