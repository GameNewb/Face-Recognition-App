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
videoID INTEGER NOT NULL PRIMARY KEY,
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
videoID INTEGER NOT NULL PRIMARY KEY,
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
videoID INTEGER NOT NULL PRIMARY KEY,
frameID INTEGER NOT NULL,
OFdata1 POINT,
OFdata2 POINT,
OFdata3 POINT,
OFdata4 POINT,
OFdata5 POINT,
OFdata6 POINT,
OFdata7 POINT,
OFdata8 POINT,
OFdata9 POINT,
OFdata10 POINT,
OFdata11 POINT,
OFdata12 POINT,
OFdata13 POINT,
OFdata14 POINT,
OFdata15 POINT,
OFdata16 POINT,
OFdata17 POINT,
OFdata18 POINT,
OFdata19 POINT,
OFdata20 POINT,
OFdata21 POINT,
OFdata22 POINT,
OFdata23 POINT,
OFdata24 POINT,
OFdata25 POINT,
OFdata26 POINT,
OFdata27 POINT,
OFdata28 POINT,
OFdata29 POINT,
OFdata30 POINT,
OFdata31 POINT,
OFdata32 POINT,
OFdata33 POINT,
OFdata34 POINT,
OFdata35 POINT,
OFdata36 POINT,
OFdata37 POINT,
OFdata38 POINT,
OFdata39 POINT,
OFdata40 POINT,
OFdata41 POINT,
OFdata42 POINT,
OFdata43 POINT,
OFdata44 POINT,
OFdata45 POINT,
OFdata46 POINT,
OFdata47 POINT,
OFdata48 POINT,
OFdata49 POINT,
OFdata50 POINT,
OFdata51 POINT,
OFdata52 POINT,
OFdata53 POINT,
OFdata54 POINT,
OFdata55 POINT,
OFdata56 POINT,
OFdata57 POINT,
OFdata58 POINT,
OFdata59 POINT,
OFdata60 POINT,
OFdata61 POINT,
OFdata62 POINT,
OFdata63 POINT,
OFdata64 POINT,
OFdata65 POINT,
OFdata66 POINT,
OFdata67 POINT,
OFdata68 POINT)";


if ($mysqli->query($openface) === TRUE) {
	//echo "Table OPENFACE created successfully\n";
} else {
	echo "Error: OpenFace\n" . $mysqli->error;
}


/* closes the database connection */
//$con->close();

