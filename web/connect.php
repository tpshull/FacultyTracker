<?php
//Variables for Username, Password, Hostname

//Code for Our Server
$dbusername = 'c2facultytracker';
$dbpassword = '2015fTracker$';

//Variables for my local database on localhost
$hostname = 'localhost';

$databaseName = 'c2facultytracker';

//Code to connect to the database
$con = mysqli_connect($hostname, $dbusername, $dbpassword, $databaseName);
	
//If it couldnt connect to the database, the error message will tell us why	
if (!$con)
{
	die('Unable to connect to MySQL' . mysqli_error());
}

else
{
	//This selects the database	
	$selected_db = mysqli_select_db($databaseName, $con);
}

?>
