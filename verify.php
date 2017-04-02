<?php
    /* Verifies registered user e-mail from register.php */
    require 'database.php';
    session_start();

    // Make sure e-mail and hash variables aren't empty
    if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash']))
    {
        $email = $mysqli->escape_string($_GET['email']);
        $hash = $mysqli->escape_string($_GET['hash']);
        
        // Select user with matching e-mail and hash who hasn't verified their account yet
        $email_result = $mysqli->query("SELECT * FROM users WHERE email='$email' AND hash='$hash' AND active='0'");
        
        if($email_result->num_rows == 0)
        {
            $_SESSION['message'] = "Account has already been activated or the URL is invalid.";
            header("location: error.php");
        }
        else
        {
            $_SESSION['message'] = "Your account has been activated!";
            
            // Set the user status to active
            $mysqli->query("UPDATE users SET active='1' WHERE email='$email'") or die($mysqli->error);
            $_SESSION['active'] = 1;
            
            header("location: success.php");
        }
    }
    else
    {
        $_SESSION['message'] = "Invalid parameters provided for account verification!";
        header("location: error.php");
    }
?>