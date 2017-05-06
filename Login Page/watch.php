<?php 
    /* Display user information and messages */
    session_start();

    // Make it easier to read code
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $active = $_SESSION['active'];
    $msg = "";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profile Page</title>
    <?php include 'css/css.html'; ?>
    <link href="http://vjs.zencdn.net/5.19.2/video-js.css" rel="stylesheet">
</head>
    <body>
        <div class="banner">
            <a href="/">App Name</a>
        </div>
        
        <div id="viewVideoBox">
            <?php
                $db = mysqli_connect("localhost", "root", "", "accounts");
                $video = $_GET['video'];
                echo $video;
            ?>
            <video id="my-video" class="video-js" controls preload="auto" width="640" height="320"
            poster="MY_VIDEO_POSTER.jpg" data-setup="{}">
            <source src='videos/<?php echo $video; ?>' type='video/mp4'>
            </video>

            <script src="http://vjs.zencdn.net/5.19.2/video.js"></script>
            <!-- Logout button -->
            <a href="profile.php"><button class="button button-block" name="profile"/>Return to Profile</button></a>
        </div>
        
            
        
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src="js/index.js"></script>
    </body>
    
</html>