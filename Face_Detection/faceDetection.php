<?php
/* programming_project_4.php
 * Kyle del Castillo
 * Saira Montermoso
 * Luis Rios
 * CS 160
 * Facial Recognition
 */

/* NOTE: TO GET THE FPS of a VIDEO
 */
//ffmpeg -i test_vid2.mp4 2>&1 | sed -n "s/.*, \(.*\) fp.*/\1/p"
 /* TO GET THE RESOLUTION
 */
// ffmpeg -i test_vid2.mp4 2>&1 | sed -n "s/.*, \(.*\) \[SAR .*\]*/\1/p"

/* This program uses the OpenFace library from https://github.com/TadasBaltrusaitis/OpenFace.git
 * The FaceLandmarkImg which was edited to obtain the desired output without creating a file is used in this program.
 */
session_start();
// --->> This program should be run as php faceDetection.php <videoID> <videoNameOnly[0]> <username><<---
if ($argc != 4) {
	exit("Usage: php <file.php> vidID vidName username\n");
} else {
	if (is_numeric($argv[1]) && intval($argv[1]) > 0) {
		$videoID = intval($argv[1]); // use videoId to query the desired info from databases
        $videoName = $argv[2];
        $username = $argv[3];
		printf("arg1 = %d\n", $videoID); // comment out or remove for later --->> SAIRA
	} else {
		exit("Argument value must be a non-negative integer\n");
	}
}

// gets the username

//$username = $_SESSION['username'];
//printf("VideoID = %d\n", $videoID);  // Only for debugging, comment out for later --->> SAIRA

/* database query to get the metadata of input video ID
 * Retrieves the number of frames, width (pixels) of each frame (in pixels), height (pixels) of each frame (in pixels)
 */

/* connects to the database */
$con = mysqli_connect("127.0.0.1", "root", "", "accounts");

/* Checks the connection */
if (!$con) {
	die("Connection failed: " . mysqli_connect_error() . "\n");
}

/* select statement to get the data needed from the database */
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
 * call openFace FaceLandmarkImg
 */

// read all the frames associated with the video
$img_path = "/opt/lampp/htdocs/Face-Recognition-App/Login Page/videos/" . $username . "/" . $videoName . " Frames";
$imagefiles = glob($img_path . "/*.png");

$pointsDirectory = "/opt/lampp/htdocs/Face-Recognition-App/Login Page/videos/" . $username . "/" . $videoName . " Frames/" . "Points";

// Create points directory inside the video folder
if(!is_dir($pointsDirectory))
{
    $oldmask = umask(0);
    mkdir($pointsDirectory, 0777, true);
    umask($oldmask);
}

// --->> RUN THIS INSIDE A LOOP -saira <<---
foreach($imagefiles as $image) {
	// extracts the frame ID
	$frame = explode("/", $image);
	$frame_name = explode(".", $frame[9]);
	$frameID = $frame_name[1];
	// calls the modified FaceLandmarkImg from OpenFace
	// The modified FaceLandmarkImg 
    $landmark = "FaceLandmarkImg -f " . '"'.$image.'" '. "-of ./output.txt";
	exec($landmark, $output); 
    
    //Create a point file for each frame output
    $pointsFile = $pointsDirectory . "/" . $videoID . "." . $frameID . ".points";
    //$createFile = fopen($pointsFile, "w");
    //$writeFile = fwrite($creatFile, $output);
    
    foreach($output as $pt) {
        // Put the points into file and its directory
        file_put_contents($pointsFile, print($pt, true), FILE_APPEND | LOCK_EX);
    }
    
    
    if(!empty($output)) {
        // --->> WRITE TO THE DATABASE THE FACE DATA -SAIRA <<---
        $json_output = json_encode($output);
        $json_output = mysqli_real_escape_string($con, $json_output);
        $req = "INSERT INTO openface (videoID, frameID, OFdata) VALUES ('".$videoID."', '".$frameID."', '".$json_output."')"; 
        $res = mysqli_query($con, $req);
    
        if ($res) {
    	    echo "Record created successfully ";
        } else {
    	    echo "Error: " . mysqli_error($con) . "\n";
        }
    }

    unset($output); 

}

//closing database connection
mysqli_close($con);

?>
