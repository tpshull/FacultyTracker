<?php
require_once('db_connect.php');
$con = new DB_CONNECT();

$db=mysql_select_db("c2facultytracker");

function checkRepeat($date, $Stime)
{
		$testqry = "SELECT event.eventID 
                                    FROM event 
                                    INNER JOIN staff
                                    ON event.staffID = staff.staffID
                                    WHERE '$date' = event.Date AND '$Stime' >= event.Start AND 'Stime' <= event.End 
                                    OR 'Etime' >= event.Start AND 'Etime' <= event.End";
                                    
                


		// Deletes a previous event if event occuring at same time
		$testqryresult = mysql_query($testqry);
		
		$del = "DELETE FROM event 
				WHERE eventID = '$testqryresult[eventID]'";
		mysql_query($del) or die(mysql_error());
}

//Variables for the data entered into the form
$event = $_POST['Title'];
$type = $_POST['Type'];
$date = $_POST['StartDate'];
$rpt = $_POST['Repeat'];
$endDate = $_POST['EndDate'];
$Stime = $_POST['STime'];
$Etime = $_POST['ETime'];
$location = $_POST['Location'];
$descrip = $_POST['Description'];
//$radioValue = $_POST['radiobutton'];
$adhoc = $_POST['adhoc'];
$uID = $_POST['staffID'];

//To prevent script hacking
mysql_real_escape_string($descrip);

//Converts the Start and End date into unix time stamps
$unixStart = strtotime($date);
$unixEnd = strtotime($endDate);
	
	//Code for Reoccuring Events
	if ($adhoc!='1')
	{
		checkRepeat($date, $Stime);
	}
	//While the start date is less than or equal to the end date
	if ($rpt == '1')
	{
		// This inserts the data into the database
		$sql = "INSERT INTO event(staffID, Type, Title, Date, Start, End, Location, Description) 
								VALUES ('$uID', '$type', '$event', '$date', '$Stime', '$Etime', '$location', '$descrip')";
		while($unixStart <= $unixEnd)
		{
			//Code to add the next week to the event (7 days, 24 hours, 60 minutes, 60 seconds) 
			$nextWeek = $unixStart + (7 * 24 * 60 * 60);
		
			//Query to add the information into the database
			$query = "INSERT INTO event(staffID, Type, Title, Date, Start, End, Location, Description) 
				VALUES ('$uID', '$type', '$event', '$date', '$Stime', '$Etime', '$location', '$descrip')";

			// Inserts the entry into the database
			mysql_query($query) or die(mysql_error());
			
			// Adds 7 to the day
			$date = date("Y-m-d", $nextWeek);			
	
			//Updates the unix start variable
			$unixStart = strtotime($date);
			
			// Checks to see if the new date also has an event occuring simultaneously
			checkRepeat($date, $Stime);
		}						
	}
	else
	{
			//Overrides current schedule to add this one time event
		if ($adhoc =='1')
		{
			$sql = "INSERT INTO event(staffID, Type, Title, Date, Start, End, Location, Description, Ahoc) 
									VALUES ('$uID', '$type', '$event', '$date', '$Stime', '$Etime', '$location', '$descrip', '$adhoc')";
			mysql_query($sql) or die(mysql_error());	
		}
		else
		{
		// This inserts the data into the database
		$sql = "INSERT INTO event(staffID, Type, Title, Date, Start, End, Location, Description) 
								VALUES ('$uID', '$type', '$event', '$date', '$Stime', '$Etime', '$location', '$descrip')";
		mysql_query($sql) or die(mysql_error());	
		}
	}	
?>
