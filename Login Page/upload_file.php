<?php
session_start();
$username = $_SESSION['username'];
$allowedExts = array("mp3", "mp4", "wma", "avi");
$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$db = mysqli_connect("localhost", "root", "", "accounts");

# Allowable extensions
if ((($_FILES["file"]["type"] == "video/mp4")
     || ($_FILES["file"]["type"] == "video/mpeg")
     || ($_FILES["file"]["type"] == "video/mpg")
     || ($_FILES["file"]["type"] == "video/flv")
     || ($_FILES["file"]["type"] == "video/mov")
     || ($_FILES["file"]["type"] == "video/avi"))
    && ($_FILES["file"]["size"] < 50000000) #50mbs of video space each
    && in_array($extension, $allowedExts))
    {
        $invalidFile = false;
        if ($_FILES["file"]["error"] > 0)
        {
            $uploadSuccessful = false;
        }
        else
        {
            $fileExists = file_exists("videos/" . $_FILES["file"]["name"]);
            if($fileExists)
            {
                $uploadSuccessful = false;
            }
            else
            {
                $uploadSuccessful = true;
            }
        }
    } // End if
else
  {
    $invalidFile = true;
  }
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upload Page</title>
    <?php include 'css/css.html'; ?>
</head>
    <body>
        <div class="banner">
            <a href="/">App Name</a>
        </div>
        <div class="uploadsection">
            <div class="upload_info">
                <h2 id="upload_header"><u>Upload Information:</u></h2>
                <br/>
                <div class="upload_panel">
                    <p id=upload_text>
                        <?php 
                        if(!$invalidFile)
                        {
                            // If upload is successful and file does not exists, upload
                            if($uploadSuccessful && !$fileExists) 
                            {
                                $username = $_SESSION['username'];
                                $videoname = $_FILES['file']['name'];
                                $type = explode('.', $videoname);
                                $type = end($type);
                                $random_name = rand();
                                
                                // Display the video details to the user
                                echo "Upload: " . $_FILES["file"]["name"] . "<br />";
                                echo "Type: " . $_FILES["file"]["type"] . "<br />";
                                echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                                echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
                                
                                // Target location for user folder
                                $target = "videos/".$username.'/'.basename($_FILES['file']['name']);
                                $location = "videos/" . $username . "/";
                                
                                //Create directory if it doesn't exist for that user
                                if(!file_exists($location))
                                {
                                    mkdir("videos/". $username, 0777, true);
                                    // Move file to local folder and query to database
                                    move_uploaded_file($_FILES["file"]["tmp_name"], $target);
                                    $uploadToDatabase = "INSERT INTO videos (username, videoName, videoURL) VALUES ('$username', '$videoname', 'videos/$random_name.$type')";
                                    $db->query($uploadToDatabase);
                                }
                                else // Just move appropriate videos to user folder
                                {
                                    move_uploaded_file($_FILES["file"]["tmp_name"], $target);
                                    $uploadToDatabase = "INSERT INTO videos (username, videoName, videoURL) VALUES ('$username', '$videoname', 'videos/$random_name.$type')";
                                    $db->query($uploadToDatabase);
                                }
                               
                                echo "Stored in: " . "videos/" . $username.'/'. $_FILES["file"]["name"];
                            }
                            elseif($fileExists) // If file exists, return error message
                            {
                                echo "Upload: " . $_FILES["file"]["name"] . "<br />";
                                echo "Type: " . $_FILES["file"]["type"] . "<br />";
                                echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                                echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

                                echo $_FILES["file"]["name"] . " already exists. ";
                            }
                            else
                            {
                                echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
                            }
                        }
                        else
                        {
                            echo "Invalid file. Video Format not supported.";
                        }
                       
                        ?>
                    </p>
                    
                </div> <!-- Upload Panel -->
                
            </div> 
            <!-- Redirect user back to profile page after uploading -->
            <h4>Redirecting to profile page in 10 seconds...</h4> 
            <?php
                header( "refresh: 10; url=profile.php" );
            ?>
            <a href="profile.php"><button class="button button-block" name="profile"/>Return to Profile</button></a>
        </div> <!-- Upload Box Form -->
    </body>
    
</html>
