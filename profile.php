<?php 
    /* Display user information and messages */
    session_start();

    // Check if user is logged in using the session variables
    if($_SESSION['logged_in'] != 1)
    {
        $_SESSION['message'] = "You must log in before viewing your profile page.";
        header("location: error.php");
    }
    else
    {   
        // Make it easier to read code
        $first_name = $_SESSION['first_name'];
        $last_name = $_SESSION['last_name'];
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $active = $_SESSION['active'];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profile Page</title>
    <?php include 'css/css.html'; ?>
</head>
    <body>
        <div class="banner">
            <a href="/">App Name</a>
        </div>
        <div class="form">
            <h1>Welcome  <?= $first_name.' '.$last_name ?></h1>
            
            <p>
            <?php
    
                // NEEDS TO BE FIXED - DISPLAYING ERROR MESSAGE WHEN USER LOGS INCORRECTLY FOR THE FIRST TIME
                // Display message about account verification link once
                if(isset($_SESSION['message']))
                {
                    echo $_SESSION['message'];
                    
                    // Don't display the same message upon page refresh
                    unset($_SESSION['message']);
                }
            ?>
            </p>
            
            <?php
                // Remind user that the account is not active yet
                if(!$active)
                {
                    echo
                        '<div class="info">
                        Account is unverified. Please confirm your e-mail by clicking on the e-mail link provided. 
                        </div>';
                }
            ?>
            
            <h2><?php echo $first_name.' '.$last_name; ?></h2>
            <p><?= $email ?></p>
            
            <a href="logout.php"><button class="button button-block" name="logout"/>Log Out</button></a>
        </div>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src="js/index.js"></script>
    </body>
    
</html>