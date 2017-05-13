<?php
namespace project\cs160\configs;

require_once "Config.php";

/* Kyle del Castillo
 * Saira Montermoso
 * Luis Rios
 * Tien Tran
 * CS 160 Software Programming
 */

/* The following php program creates the initial database "cs160"
 * and creates the initial tables described in database schema
 * for Programming Project 1.
 */

/* creates mysqli connection */
$mysqli = new \mysqli(HOST, USER, PWD);

/* checks database connection */
if ($mysqli->connect_error) {
	die("Connection error: " . $mysqli->connect_error ."\n");
}

/* creates initial database "accounts" ==> change to cs160 if necessary */
$qry = "CREATE DATABASE IF NOT EXISTS accounts";

if ($mysqli->query($qry) === TRUE) {
	//echo "Created cs160 database\n";
} else { 
	echo "Error: \n" . $mysqli->error;
}

/* if datase created successfully, selects the created database */
$mysqli->select_db(DB);

/* create tables described in the database schema */
/* creates user profile information table */
/* should add this for later after hash =>> llogin TIMESTAMP,ipadd CHAR(40) NOT NULL */
$user = "CREATE TABLE IF NOT EXISTS users (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
first_name VARCHAR(50) NOT NULL,
last_name VARCHAR(50) NOT NULL,
username VARCHAR(25) NOT NULL,
email VARCHAR(50) NOT NULL,
avatar VARCHAR(200) NOT NULL,
password VARCHAR(100) NOT NULL,
hash VARCHAR(32) NOT NULL,
active tinyint(1) NOT NULL)";


if ($mysqli->query($user) === TRUE) {
	//echo "Table USER created successfully\n";
} else {
	echo "Error: \n" . $mysqli->error;
}

/* creates input video metadata table */
$video = "CREATE TABLE IF NOT EXISTS videos (
username VARCHAR(25) NOT NULL,
videoName VARCHAR(100) NOT NULL,
videoID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
videoURL VARCHAR(255) NOT NULL,
thumbnail VARCHAR(200) NOT NULL,
nframes INTEGER NOT NULL,
Xwidth INTEGER NOT NULL,
Yheight INTEGER NOT NULL,
fps REAL NOT NULL)";

if ($mysqli->query($video) === TRUE) {
	//echo "Table METADATA created successfully\n";
} else {
	echo "Error: \n" . $mysqli->error;
}

/* creates Head(skull) position data */
$head = "CREATE TABLE IF NOT EXISTS head (
videoID INTEGER NOT NULL,
frameID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
yaw REAL,
pitch REAL,
roll REAL)";

if ($mysqli->query($head) === TRUE) {
	//echo "Table HEAD created successfully\n";
} else {
	echo "Error: \n" . $mysqli->error;
}

/* creates pupil data table */
/* removed columns for pupils detected using OpenFace --->> OFLeye POINT, OFReye POINT Saira */
$pupil = "CREATE TABLE IF NOT EXISTS pupil (
videoID INTEGER NOT NULL,
frameID INTEGER NOT NULL, 
FTLeye POINT,
FTReye POINT)";

if ($mysqli->query($pupil) === TRUE) {
	//echo "Table PUPIL created successfully\n";
} else {
	echo "Error: \n" . $mysqli->error;
}

/* creates OpenFace data */
/* edited the OFdata# columns from POINT NOT NULL to POINT --->> Saira */
$openface = "CREATE TABLE IF NOT EXISTS openface (
videoID INTEGER NOT NULL,
frameID INTEGER NOT NULL,
OFdata VARCHAR(10000))";


if ($mysqli->query($openface) === TRUE) {
	//echo "Table OPENFACE created successfully\n";
} else {
	echo "Error: OpenFace\n" . $mysqli->error;
}


/* closes the database connection */
//$con->close();

