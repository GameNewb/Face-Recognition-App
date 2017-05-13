<?php 
    /* Display user information and messages */
    session_start();

    // Check if user is logged in using the session variables
    if($_SESSION['logged_in'] != 1)
    {
        $_SESSION['message'] = "You must log in before viewing your profile page.";
        header("location: error.php");
    }
    else // Else if user is active
    {   
        // Make it easier to read code
        $first_name = $_SESSION['first_name'];
        $last_name = $_SESSION['last_name'];
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $active = $_SESSION['active'];
        $msg = "";
        
        // Upload button is pressed
        if(isset($_POST['upload'])) {
            //$target = "avatars/". basename($_FILES['image']['name']);
            $target2 = "avatars/".$username.'/'.basename($_FILES['image']['name']);
            $db = mysqli_connect("localhost", "root", "", "accounts");
            
            // Get data
            $image = $_FILES['image']['name'];
            //$sql = "INSERT INTO users (avatar) VALUES ('$image')";
            $sql2 = "UPDATE users SET avatar = '".$_FILES['image']['name']."' WHERE username = '".$_SESSION['username']."'";
            mysqli_query($db, $sql2);
            
            // Move the file
            if(move_uploaded_file($_FILES['image']['tmp_name'], $target2)) {
                $msg = "Image uploaded successfully";
            }
            else{
                $msg = "An issue occurred while uploading the image.";
            }
        }
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
            <a href="/">Face Prop</a>
        </div>
        <div class="profile">
            <h1>Welcome  <?= $first_name.' '.$last_name ?></h1>

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
            
            <!-- The user information panel that contains the first name, last name, and email address of the user -->
            <div class="user">
                <div class="headerpanel">
                    <h2 id="infopanel"><u>User Information</u></h2>
                </div>
                <div class="userpanel">
                    
                    <!-- Avatar section of the user -->
                    <div class="avatar">
                        <?php
                            //Connect to database
                            $db = mysqli_connect("localhost", "root", "", "accounts");
                            $sql = "SELECT * FROM users WHERE username='$username'"; // Make sure to select the appropriate user from the database
                            $result = mysqli_query($db, $sql); // Query to database.
                            $default = __DIR__ . "/avatars/defaults/default.png";
                            
                            //Get appropriate user avatar
                            while($row = mysqli_fetch_array($result)) {
                                echo "<div id='img_div'>";
                                $location = "avatars/" . $username . "/";
                                
                                //Create directory if it doesn't exist for that user
                                if(!file_exists($location))
                                {
                                    $dir = "avatars/". $username;
                                    
                                    // For linux - create directory
                                    if (!is_dir($dir)) {
                                        // Create mask to allow program to create a directory
                                        $oldmask = umask(0);
                                        mkdir($dir, 0777, true);
                                        if(!copy($default, $dir . "/" . "default.png")) //Copy default avatar
                                        {
                                            echo "Failed to copy $default to $dir";
                                        }
                                        umask($oldmask);
                                    }
                                    
                                    // Set user default avatar until user uploads a new one
                                    $defaultAvatar = "UPDATE users SET avatar ='default.png' WHERE username = '".$_SESSION['username']."'";
                                    mysqli_query($db, $defaultAvatar);
                                    
                                    echo "<img src='avatars/".$username.'/'."default.png"."' height='150' width='200'>";
                                }
                                else // Just diplay to user mo
                                {
                                    echo "<img src='avatars/".$username.'/'.$row['avatar']."' height='150' width='200'>";
                                }

                                echo "</div>";
                            }
                        ?>
                        <form method="post" action="profile.php" enctype="multipart/form-data">
                            <input type="hidden" name="size" value="10">
                            <div class="fileinputs">
                                <input type="file" name="image">

                                <input type="submit" name="upload" value="Upload Image">

                            </div>

                        </form>
                    </div>
                    <p id="info">
                        <?php 
                        
                        $strfirst = ucwords($first_name);
                        $strlast = ucwords($last_name); 
                        echo nl2br("<u>First Name:</u> $strfirst 

                            <u>Last Name:</u> $strlast

                            <u>Username:</u> $username

                            <u>E-mail Address:</u> 
                            $email");

                        ?>
                    </p>
                </div>
            </div>
            
            <!-- The video panel that contains all the videos of the user -->
            <div class="video">
                <div class="videosheaderpanel">
                    <h2 id="videosheader"><u>My Videos</u></h2>
                    
                </div>
                
                <div id="my_videos">
                    <div id="uploadvideo">
                        <div id="allvideos">
                            <?php
                                $db = mysqli_connect("localhost", "root", "", "accounts");
                                $sql = "SELECT videoID, videoName, videoURL, thumbnail FROM videos WHERE username='$username'";
                                $result = mysqli_query($db, $sql);
                                while($row = mysqli_fetch_array($result)) {

                                    $videoID = $row['videoID'];
                                    $videoName = $row['videoName'];
                                    $videoURL = $row['videoURL'];
                                    $videoThumbnail = $row['thumbnail'];
                                    $vidNameOnly = explode('.', $videoName); //Get the name of the vid only
                                    $vidNameOnly[0] = $vidNameOnly[0] .= " Frames";
                                    $thumbnailLocation = "videos/$username/$vidNameOnly[0]/";

                                    echo '<div id="vidlinks">';
                                    echo "<a href='watch.php?video=$videoName' class='linkers'>
                                    <img src='$thumbnailLocation/thumbnail001.jpg' width='120' height='90' border='1'>
                                    $videoName</a>";
                                    echo '</div>';
                                }
                                echo '<div class="clear"></div>'; // Clear floating styles 
                            ?>
                        </div>
                        <div id="uploadtab">
                            <div id="fileuploadinfo">
                                <p id="file-extension-desc">File Extensions Accepted: 
                                    <br>mp4, flv (50 mb max file size)</p>
                            </div>
                            <div id="fileupload">
                                <form action="upload_file.php" method="post" enctype="multipart/form-data">
                                <input type="file" name="file" id="file" /> 

                            </div>
                            <div id="submitupload">
                                <input type="submit" name="upload" value="Upload Video"/>
                                </form>
                            </div>
                        </div> <!-- End div for upload tab -->
                    </div>
                    <div class="clear"></div> <!-- Clear the float styles -->
                </div>
            </div>
            
            <!-- Logout button -->
            <a href="logout.php"><button class="button button-block" name="logout"/>Log Out</button></a>
        
        </div>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src="js/index.js"></script>
    </body>
    
</html>