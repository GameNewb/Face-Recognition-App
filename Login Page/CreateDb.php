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
username VARCHAR(25) NOT NULL PRIMARY KEY,
videoID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
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
$head = "CREATE TABLE IF NOT EXISTS HEAD (
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
$pupil = "CREATE TABLE IF NOT EXISTS PUPIL (
videoID INTEGER NOT NULL PRIMARY KEY,
frameID INTEGER NOT NULL, 
OFLeye POINT,
OFReye POINT,
FTLeye POINT,
FTReye POINT)";

if ($mysqli->query($pupil) === TRUE) {
	//echo "Table PUPIL created successfully\n";
} else {
	echo "Error: \n" . $mysqli->error;
}

/* creates OpenFace data */
$openface = "CREATE TABLE IF NOT EXISTS OPENFACE (
videoID INTEGER NOT NULL PRIMARY KEY,
frameID INTEGER NOT NULL,
OFdata1 POINT NOT NULL,
OFdata2 POINT NOT NULL,
OFdata3 POINT NOT NULL,
OFdata4 POINT NOT NULL,
OFdata5 POINT NOT NULL,
OFdata6 POINT NOT NULL,
OFdata7 POINT NOT NULL,
OFdata8 POINT NOT NULL,
OFdata9 POINT NOT NULL,
OFdata10 POINT NOT NULL,
OFdata11 POINT NOT NULL,
OFdata12 POINT NOT NULL,
OFdata13 POINT NOT NULL,
OFdata14 POINT NOT NULL,
OFdata15 POINT NOT NULL,
OFdata16 POINT NOT NULL,
OFdata17 POINT NOT NULL,
OFdata18 POINT NOT NULL,
OFdata19 POINT NOT NULL,
OFdata20 POINT NOT NULL,
OFdata21 POINT NOT NULL,
OFdata22 POINT NOT NULL,
OFdata23 POINT NOT NULL,
OFdata24 POINT NOT NULL,
OFdata25 POINT NOT NULL,
OFdata26 POINT NOT NULL,
OFdata27 POINT NOT NULL,
OFdata28 POINT NOT NULL,
OFdata29 POINT NOT NULL,
OFdata30 POINT NOT NULL,
OFdata31 POINT NOT NULL,
OFdata32 POINT NOT NULL,
OFdata33 POINT NOT NULL,
OFdata34 POINT NOT NULL,
OFdata35 POINT NOT NULL,
OFdata36 POINT NOT NULL,
OFdata37 POINT NOT NULL,
OFdata38 POINT NOT NULL,
OFdata39 POINT NOT NULL,
OFdata40 POINT NOT NULL,
OFdata41 POINT NOT NULL,
OFdata42 POINT NOT NULL,
OFdata43 POINT NOT NULL,
OFdata44 POINT NOT NULL,
OFdata45 POINT NOT NULL,
OFdata46 POINT NOT NULL,
OFdata47 POINT NOT NULL,
OFdata48 POINT NOT NULL,
OFdata49 POINT NOT NULL,
OFdata50 POINT NOT NULL,
OFdata51 POINT NOT NULL,
OFdata52 POINT NOT NULL,
OFdata53 POINT NOT NULL,
OFdata54 POINT NOT NULL,
OFdata55 POINT NOT NULL,
OFdata56 POINT NOT NULL,
OFdata57 POINT NOT NULL,
OFdata58 POINT NOT NULL,
OFdata59 POINT NOT NULL,
OFdata60 POINT NOT NULL,
OFdata61 POINT NOT NULL,
OFdata62 POINT NOT NULL,
OFdata63 POINT NOT NULL,
OFdata64 POINT NOT NULL,
OFdata65 POINT NOT NULL,
OFdata66 POINT NOT NULL,
OFdata67 POINT NOT NULL,
OFdata68 POINT NOT NULL)";


if ($mysqli->query($openface) === TRUE) {
	//echo "Table OPENFACE created successfully\n";
} else {
	echo "Error: OpenFace\n" . $mysqli->error;
}


/* closes the database connection */
//$con->close();

