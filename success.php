<?php
    // Display messages
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Success</title>
    <?php include 'css/css.html'; ?>
</head>
    <body>
        <div class="banner">
            <a href="/">App Name</a>
        </div>
        <div class="form">
            <h1><?= 'Success'; ?></h1>
            <p>
            <?php
                if(isset($_SESSION['message']) AND !empty($_SESSION['message'])):
                    echo $_SESSION['message'];
                else:
                    header("location: index.php");
                endif;
            ?>
            </p>
            <a href="index.php"><button class="button button-block"/>Home</button></a>
        </div>
    </body>
</html>