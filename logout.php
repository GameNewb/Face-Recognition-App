<?php
    /* Log out the session. Unset and remove the current session variables */
    session_start();
    session_unset();
    session_destroy();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Logged Out</title>
        <?php include 'css/css.html'; ?>
    </head>
    <body>
        <div class="banner">
            <a href="/">App Name</a>
        </div>
        <div class="form">
            <h1>Thank you for visiting.</h1>
            
            <p><?= 'Thank you for using our services. Your session has ended.'; ?></p>
            
            <a href="index.php"><button class="button button-block"/>Home</button></a>
        </div>
    
    </body>
</html>