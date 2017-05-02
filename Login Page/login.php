<?php
    /* User login process, checks if user exists and password is correct */

    // Escape email to protect against SQL injections
    $email = $mysqli->escape_string($_POST['email']);
    $email_result = $mysqli->query("SELECT * FROM users WHERE email='$email'");

    $username = $mysqli->escape_string($_POST['username']);
    $username_result = $mysqli->query("SELECT * FROM users WHERE username='$username'");

    if($email_result->num_rows == 0) // User doesn't exist
    {
        $_SESSION['message'] = "User with that email doesn't exists!";
        
        // Send raw http header a.k.a redirect user to error page
        header("location: error.php");
    }
    else // User exists
    {
        $user = $email_result->fetch_assoc(); // Fetch user string
        $user2 = $username_result->fetch_assoc();
        
        // Allow login with either username or password
        if (password_verify($_POST['password'], $user['password']) ||
            password_verify($_POST['password'], $user2['password']))
        {
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['active'] = $user['active'];
            
            // User is logged in
            $_SESSION['logged_in'] = true; 
            
            // Send raw http header a.k.a redirect user to profile page
            header("location: profile.php"); 
        }
        else
        {
            $_SESSION['message'] = "Incorrect credentials. Try again.";
            header("location: error.php");
        }
    
    }
