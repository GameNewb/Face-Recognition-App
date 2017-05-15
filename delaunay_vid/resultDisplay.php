<?php
/* programming_project_6.php
 * Kyle del Castillo
 * Saira Montermoso
 * Luis Rios
 * CS 160
 * Web-based display results
 */

//checks the arguments passed
//to execute: php resultDisplay.php <videoID>
if ($argc != 4) {
	exit("Usage: php <file.php> arg1\n");
} else {
	if (is_numeric($argv[1]) && intval($argv[1]) > 0) {
		$videoID = intval($argv[1]); // use videoId to query the desired info from databases
        $videoPath = $argv[2];
        $videoName = $argv[3];
		printf("arg1 = %d\n", $videoID); // comment out or remove for later --->> SAIRA
	} else {
		exit("Argument value must be a non-negative integer\n");
	}
}
//$username = $_SESSION['username'];

/* database query to get the metadata of input video ID
 * Retrieves the number of frames, width (pixels) of each frame (in pixels), height (pixels) of each frame (in pixels)
 */

/* connects to the database */
// --->> THIS PART NEEDS TO BE CHANGED ask KYLE -SAIRA <<---
$con = mysqli_connect("127.0.0.1", "root", "", "accounts");

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

/* after getting the input video metadata, retrieve the facial datapoints and pupil data for each frame
 * associated with the videoID
 */

// selects all the frameID and OFdata associated with videoID
$fdquery = "SELECT frameID, OFdata FROM openface WHERE videoID='".$videoID."'";

$fdresult = mysqli_query($con, $fdquery);

// gets the result of the database query
if(mysqli_num_rows($fdresult) > 0) {
	while($nrow = mysqli_fetch_assoc($fdresult)) {
		$frameID = $nrow['frameID'];
		$frameIDS[] = $frameID;
		$facialPoints[$frameID] = json_decode($nrow['OFdata']); // might produce a problem --->> saira
	}
}

unset($frameID);

// selects all frameID and associated Pupil data of the video
$epquery = "SELECT frameID, FTLeyeX, FTLeyeY, FTReyeX, FTReyeY FROM pupil WHERE videoID='".$videoID."'";

$epresult = mysqli_query($con, $epquery);

// gets the result of the database query
if(mysqli_num_rows($epresult) > 0) {
	while($mrow = mysqli_fetch_assoc($epresult)) {
		$id = $mrow['frameID'];
		$pupil[$id] = array($mrow['FTLeyeX'], $mrow['FTLeyeY'], $mrow['FTReyeX'], $mrow['FTReyeY']); 
	}
}

// calls delaunay_triangle.py in a loop
// pass the argument as json data
$newVidPath = ' "'.$videoPath . $videoName. '" ';
$videoname = '"'.$videoName.'"';

foreach($frameIDS as $frameID) {
	$data = array("videoID"=>$videoID, "frameID"=>$frameID, "facialPoints"=>$facialPoints[$frameID], "pupilData"=>$pupil[$frameID]);
	// this line calls the delaunay_triangle.py written by Luis
	//$output = shell_exec('python /opt/lampp/htdocs/Face-Recognition-App/exec/delaunay_triangles.py ' . escapeshellarg(json_encode($data)) . ' "'.$videoPath.'" ' . '"'.$videoName.'"' );
    $script = "python /opt/lampp/htdocs/Face-Recognition-App/exec/delaunay_triangle.py " . $videoID . ' ' . $frameID . ' "'.$videoPath.'" ' .  '"'.$videoName.'"';
    
    $outpu = shell_exec($script);
    //$output = exec('python /opt/lampp/htdocs/Face-Recognition-App/exec/delaunay_triangle.py ' . $videoID . $frameID . $newVidPath . $videoname);
}

// calls the frame_stitch.py to produce the output video
if ($output == 0) {
	$vidOutput = shell_exec('python /opt/lampp/htdocs/Face-Recognition-App/exec/frame_stitch.py ' . $videoID . ' "'.$videoPath.'" ' . '"'.$videoName.'"');
} else {
	echo "Error: Drawing delaunay triangle failed.\n";
}


//closing database connection
mysqli_close($con);

//unsetting variables
unset($frameIDS);
unset($pupil);
unset($facialPoints);
unset($data);

?>


 