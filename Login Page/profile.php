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
        $msg = "";
        
        // Upload button is pressed
        if(isset($_POST['upload'])) {
            $target = "avatars/".basename($_FILES['image']['name']);
            
            $db = mysqli_connect("localhost", "root", "", "accounts");
            
            // Get data
            $image = $_FILES['image']['name'];
            //$sql = "INSERT INTO users (avatar) VALUES ('$image')";
            $sql2 = "UPDATE users SET avatar = '".$_FILES['image']['name']."' WHERE username = '".$_SESSION['username']."'";
            mysqli_query($db, $sql2);
            
            if(move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
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
            <a href="/">App Name</a>
        </div>
        <div class="profile">
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
            
            <div class="user">
                <div class="headerpanel">
                    <h2 id="infopanel"><u>User Information</u></h2>
                </div>
                <div class="userpanel">
                    <div class="avatar">
                        <?php
                            $db = mysqli_connect("localhost", "root", "", "accounts");
                            $sql = "SELECT * FROM users";
                            $result = mysqli_query($db, $sql);
                            while($row = mysqli_fetch_array($result)) {
                                echo "<div id='img_div'>";
                                    echo "<img src='avatars/".$row['avatar']."' height='150' width='200'>";
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
            
            <div class="video">
                <div class="videosheaderpanel">
                    <h2 id="videosheader"><u>My Videos</u></h2>
                </div>
                
                <div id="my_videos">
                    <div id="uploadvideo">
                        <div id="allvideos">
                              This is a test
                        </div>
                        <div id="fileupload">
                            <form action="upload_file.php" method="post" enctype="multipart/form-data">
                            <input type="file" name="file" id="file" /> 
                        
                        </div>
                        <div id="submitupload">
                            <input type="submit" name="upload" value="Upload Video"/>
                            </form>
                        </div>
                     
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            
            <div class="clear">
            </div>
            
            
            <a href="logout.php"><button class="button button-block" name="logout"/>Log Out</butto></a>
        
        </div>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src="js/index.js"></script>
    </body>
    
</html>