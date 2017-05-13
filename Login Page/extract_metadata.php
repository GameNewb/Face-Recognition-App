<?php
    /* GET APPROPRIATE VIDEO */
    session_start();
    $db = mysqli_connect("127.0.0.1", "root", "", "accounts");
    //$username = $_SESSION['username'];
    
    $username = $argv[1];
    $videoLocation = $argv[2];
    $vidID = $argv[3];

    /* EXTRACT NUMBER OF FRAMES */
    $numberOfFrames = "ffprobe -v error -count_frames -select_streams v:0 -show_entries stream=nb_read_frames -of default=nokey=1:noprint_wrappers=1 " . '"'.$videoLocation.'"';
    $nframes = shell_exec($numberOfFrames);

    /* EXTRACT FPS */
    $fps = "ffprobe -v error -select_streams v -of default=noprint_wrappers=1:nokey=1 -show_entries stream=r_frame_rate " . '"'.$videoLocation.'"';

    $scriptOutput = explode('/', shell_exec($fps)); //Get the actual number (e.g. r)
    $totalCount = (int)$scriptOutput[0]; //Get the first part of the output
    $averageCount = (int)$scriptOutput[1]; //Get the second part
    $realFPS = round(($totalCount / (float) $averageCount), 2); //Get the real fps by dividing the ffprobe output

    /* EXTRACT HEIGHT */
    $height = "ffprobe -v error -select_streams v -of default=noprint_wrappers=1:nokey=1 -show_entries stream=height " . '"'.$videoLocation.'"';
    $Yheight = shell_exec($height);

    /* EXTRACT WIDTH */
    $width = "ffprobe -v error -select_streams v -of default=noprint_wrappers=1:nokey=1 -show_entries stream=width " . '"'.$videoLocation.'"';
    $Xwidth = shell_exec($width);

    // Update the metadata
    $updateMetadata = "UPDATE videos SET nframes='$nframes', fps='$realFPS', Xwidth='$Xwidth', Yheight='$Yheight' WHERE username='$username' AND videoID='$vidID'";
    $db->query($updateMetadata);

    $db->close();
?>