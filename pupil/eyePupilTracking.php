<?php
/* programming_project_5.php
 * Kyle del Castillo
 * Saira Montermoso
 * Luis Rios
 * Tien Tran
 * CS 160
 * Facial Recognition
 */

/* Track eye pupils
 * This program uses a modified pupil tracking algorithm written by Tristan Hume.
 * Tristan Hume's pupil tracking algorithm is on github at
 * https://github.com/trishume/eyeLike
 */

/* Overview:
 * This program determines the location of the left and right eye pupils.
 * The location of the pupils are written to the database.
 */

/* Specifications:  
 * 1. This program accepts command line input through argc and argv.
 * It uses argv to pass in a non-negative integer (video ID),
 * which represents the unique identifier of an input video.
 * 2. This program queries database for the following metadata associated with 
 * the input video ID:
 * number of frames
 * width (pixels) of each frame (in pixels)
 * height (pixels) of each frame (in pixels)
 * 3. For each frame associated with the video ID in the image repository, 
 * the ff are performed:
 * Call the modified eye tracking function written by Mr. Hume.
 * If the location of the left eye is found, the Cartesian (x, y) coordinates 
 * for the left eye pupil is written to the database along with the current frame number and video id.
 * If the location of the right eye is found, the Cartesian (x, y) coordinates
 * for the right eye pupil is written to the database along with the current frame number and video id.
 */

/* Start of the code */

/* checks the argument passed to the php script
 * makes sure that only one argument is passed 
 * makes sure that the argument passed is a non-negative integer
 * does not filter if the argument passed is a float or double
 */

// --->> THIS PART IS NOT PERFECT BUT OK TO USE -SAIRA <<---
if ($argc != 2) {
	exit("Usage: php <file.php> arg1\n");
} else {
	if (is_numeric($argv[1]) && intval($argv[1]) > 0) {
		$videoID = intval($argv[1]); // use videoId to query the desired info from databases
		printf("arg1 = %d\n", $videoID); // comment out or remove for later --->> SAIRA
	} else {
		exit("Argument value must be a non-negative integer\n");
	}
}

// gets the username

$username = $_SESSION['username'];
//printf("VideoID = %d\n", $videoID);  // Only for debugging, comment out for later --->> SAIRA

/* database query to get the metadata of input video ID
 * Retrieves the number of frames, width (pixels) of each frame (in pixels), height (pixels) of each frame (in pixels)
 */

/* connects to the database */
// --->> THIS PART NEEDS TO BE CHANGED ask KYLE -SAIRA <<---
$con = mysqli_connect("localhost", "root", "", "accounts");

/* Checks the connection */
if (!$con) {
	die("Connection failed: " . mysqli_connect_error() . "\n");
}

/* select statement to get the data needed from the database */
// --->> THIS NEEDS TO BE EDITED -SAIRA <<---
$query = "SELECT nframes, Xwidth, Yheight FROM videos WHERE videoID='".$videoID."'";

$result = mysqli_query($con, $query);

// gets the data and save to appropriate variables
// not sure if this is really useful
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$nframes = $row["nframes"];
		$width = $row["Xwidth"];
		$height = $row["Yheight"];
	}
} else {
	echo "Data not found"; // else can be removed for later if not needed --->> SAIRA
}

/* after getting the input video metadata, retrieve the frames
 * call the eye tracking program by tristan hume
 */

// read all the frames associated with the video
// --->> NEED THE PATH TO WHERE THE IMAGES ARE STORED <<---
$img_path = "/opt/lampp/htdocs/Face-Recognition-App/Login Page/videos/" . $username . "/" . $videoID;
$imagefiles = glob($img_path . "/*.png");

// --->> RUN THIS INSIDE A LOOP -saira <<---
// THE EDITED CODE OF MR HUME WILL GIVE (-1, -1) output if both eyes is not detected --saira
foreach($imagefiles as $image) {
	// extracts the frame ID
	$frame = explode("/", $image);
	$frame_name = explode(".", $frame[9]);
	$frameID = $frame_name[0];
	// calls the modified pupil tracking of Mr. Hume
	exec("/opt/lampp/htdocs/Face-Recognition-App/modified_eyeLike/build/bin/eyeLike $image", $output); 
    $rightPupilx = (int)$output[0];
    $rightPupily = (int)$output[1];
    $leftPupilx = (int)$output[2];
    $leftPupily = (int)$output[3];

    unset($output); 

    // --->> WRITE TO THE DATABASE THE PUPIL DATA -SAIRA <<---
    $req = "INSERT INTO pupil (videoID, frameID, FTLeyeX, FTLeyeY, FTReyeX, FTReyeY) VALUES ('".$videoID."', '".$frameID."', '".$leftPupilx."', '".$leftPupily."', '".$rightPupilx."', '".$rightPupily."')"; 
    $res = mysqli_query($con, $req);
    
    if ($res) {
    	echo "Record created successfully ";
    } else {
    	echo "Error: " . mysqli_error($con) . "\n";
    }
}

//closing database connection
mysqli_close($con);

?>
