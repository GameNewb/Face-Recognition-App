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
            <div id="videoPanel">
                <?php
                    // Connect to the database and get the appropriate video
                    $db = mysqli_connect("localhost", "root", "", "accounts");
                    $video = $_GET['video'];
                
                    // Select the appropriate video to obtain the video format/extension type
                    $sql = "SELECT videoURL FROM videos WHERE username='$username' AND videoName='$video'"; 
                    $result = mysqli_query($db, $sql);
                
                    // Fetch the right url and take the format of the video
                    while($row = mysqli_fetch_array($result)) {  
                        $videoURL = $row['videoURL'];
                        $type = explode('.', $videoURL);
                        $type = end($type);
                    }
                ?>
                <h2><?php echo $video; ?></h2><br>
                
                <!-- Set video player -->
                <video id="my-video" class="video-js" controls preload="auto" width="750" height="380"
                poster="MY_VIDEO_POSTER.jpg" data-setup="{}">
                    
                <!-- Check the video format/type and set variables accordingly-->
                <?php 
                    
                    if($type == "mp4")
                    {
                        $format = "video/mp4";
                    }
                    elseif($type == "mpeg")
                    {
                        $format = "video/mpeg";
                    }
                    elseif($type == "flv")
                    {
                        $format = "video/x-flv";
                    }
                    elseif($type == "mov") 
                    {
                        $format = "video/quicktime";
                    }
                    elseif($type = "wmv") // Windows Media Video
                    {
                        $format = "video/x-ms-wmv";
                    }
                    elseif($type = "ogg")
                    {
                        $format = "video/ogg";
                    }
                    
                    
                ?>
                
                <source src="videos/<?php echo $username; echo "/"; echo $video; ?>" type="<?php echo $format; ?>">
                <!-- Fallback in case the users browsers doesn't support HTML5 videos -->
                Your browser does not support HTML5 video.

                </video>
            </div>
            <div class="logout">
                <!-- Logout button -->
                <a href="profile.php"><button class="button button-block" name="profile"/>Return to Profile</button></a>
            </div>
        </div>
        
        <script src="http://vjs.zencdn.net/5.19.2/video.js"></script>
    </body>
    
</html>