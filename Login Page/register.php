<?php
    /*
        Registration process. Put user input into the accounts database
        and sends a confirmation e-mail to ensure that the user is real.
    */

    // Session variables to be used on profile.php
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['first_name'] = $_POST['firstname'];
    $_SESSION['last_name'] = $_POST['lastname'];

    // Protect $_POST variables against SQL injections
    $first_name = $mysqli->escape_string($_POST['firstname']);
    $last_name = $mysqli->escape_string($_POST['lastname']);
    $username = $mysqli->escape_string($_POST['username']);
    $email = $mysqli->escape_string($_POST['email']);
    
    // Protect user password by encrypting and hashing - SALTING
    /* Encrypt password using BCRYPT (CRYPT_BLOWFISH algorithm) - takes in the raw password, which then generates a random string from it */
    $password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
    $hash = $mysqli->escape_string( md5(rand(0, 1000)) ); // md5 hashing
    
    // Check if user with input username already exists
    $username_result = $mysqli->query("SELECT * FROM users where username='$username'") or die($mysqli->error());
    
    // Check if user with input email already exists
    $email_result = $mysqli->query("SELECT * FROM users WHERE email='$email'") or die($mysqli->error());

    // Ensure user e-mail or username exists
    if($email_result->num_rows > 0)
    {
        $_SESSION['message'] = 'User with this e-mail already exists. Please login instead.';
        header("location: error.php");
    }
    elseif($username_result->num_rows > 0)
    {
        $_SESSION['message'] = 'User with this username already exists. Please login instead.';
        header("location: error.php");
    }
    else // E-mail doesn't exist in the database, create it
    {
        $sql = "INSERT INTO users (first_name, last_name, username, email, password, hash) "
                . "VALUES ('$first_name', '$last_name', '$username', '$email', '$password', '$hash')";
        
        // Add user to database
        if($mysqli->query($sql))
        {
            $_SESSION['active'] = 0; // 0 until user activates their account with verify.php
            $_SESSION['logged_in'] = true; // User is logged in
            $_SESSION['message'] = 
                
                "Confirmation link has been sent to $email, please verify your account by clicking on the link in the e-mail.";
            
            // Send registration confirmation link (verify.php)
            $to      = $email;
            $subject = 'Account Verification';
            $message_body = '
                Hi '.$first_name.',
                
                Thank you for signing up to our website! 
                
                Please click the validation link below to activate your account:
                
                http://localhost/Face-Recognition-App/Login%20Page/verify.php?email='.$email.'&hash='.$hash; 
            
                mail($to, $subject, $message_body);
            
                // Redirect user
                header("location: account_verification.php");
        }
        else
        {
            $_SESSION['message'] = 'Registration failed!';
            header("location: error.php");
        }
    }
    