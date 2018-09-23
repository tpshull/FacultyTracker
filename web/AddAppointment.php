<?php
session_start();
//Checks to see if the user is logged into the system
if (!isset($_SESSION['basic_is_logged_in']) 
    || $_SESSION['basic_is_logged_in'] !== true) {
//Redirects the user to the login page
    ob_start();
    include("FLogin.php");
    ob_flush();
exit;
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Appointment</title>
<?php
function checkRepeat($date, $Stime)
{
mysql_real_escape_string($date);
mysql_real_escape_string($time);
$testqry = "SELECT event.eventID 
FROM event 
INNER JOIN staff
ON event.staffID = staff.staffID
WHERE '$date' = event.Date AND '$Stime' >= event.Start AND 'Stime' <= event.End 
OR 'Etime' >= event.Start AND 'Etime' <= event.End";
// Deletes a previous event if event occurring at same time
$testqryresult = mysql_query($testqry);
$del = "DELETE FROM event 
WHERE eventID = '$testqryresult[eventID]'";
mysql_query($del) or die(mysql_error());
}
?>

</head>


<?php
include('connect.php');
//This selects the database
$selected_db = mysql_select_db($databaseName, $con);
//Variables for the data entered into the form


if ($_SERVER["REQUEST_METHOD"] == "POST") {
$event = test_input($_POST['Title']);
$type = test_input($_POST['Type']);
$date = test_input($_POST['When']);
$rpt = test_input($_POST['Repeat']);
$endDate = test_input($_POST['EndDate']);
$Stime = test_input($_POST['STime']);
$Etime = test_input($_POST['ETime']);
$location = test_input($_POST['Location']);
$descrip = test_input($_POST['Description']);
$radioValue = test_input($_POST['radiobutton']);
$adhoc = test_input($_POST['adhoc']);
$uID = test_input($_SESSION['staffID']);
}
//Help to prevent Cross-Site Scripting attacks
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if(isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit = true;
    $eventID = mysql_real_escape_string($_GET['edit']);
} else {
    $edit = false;
}
//To prevent script hacking
mysql_real_escape_string($descrip);

//Converts the Start and End date into unix time stamps
$unixStart = strtotime($date);
$unixEnd = strtotime($endDate);

//Prints out what was entered into the form
echo "The Appointment Information:";
//Line Break in php
echo nl2br("\n");
echo nl2br("\n");
echo "Title of the event is : " . $event;
echo nl2br("\n");
echo "The event occurs at : " . $date;
echo nl2br("\n");
echo "The start time of the event is:  " . $Stime;
echo nl2br("\n");
echo "The end time of the event is: " . $Etime;
echo nl2br("\n");
echo "The event ends at: " . $endDate;
echo nl2br("\n");
echo "The location of the event is : " .$location;
echo nl2br("\n");
echo '<a href="week.php">Return To Schedule</a>';
//Code for Reoccuring Events
if ($adhoc!='1')
{
checkRepeat($date, $Stime);
}
//While the start date is less than or equal to the end date
if ($rpt == '1')
{
// This inserts the data into the database
        if($edit) {
        $sql = "UPDATE `event` SET `staffID` = '$uID', `Type` = '$type', `Title` = '$event', `Date` = '$date', `Start` = '$Stime', `End` = '$Etime', `Location` = '$location', `Description` = '$descrip' WHERE `eventID` = '$eventID'"; 
        } else {
$sql = "INSERT INTO event(staffID, Type, Title, Date, Start, End, Location, Description) 
VALUES ('$uID', '$type', '$event', '$date', '$Stime', '$Etime', '$location', '$descrip')";
            
        }
while($unixStart <= $unixEnd)
{
//Code to add the next week to the event (7 days, 24 hours, 60 minutes, 60 seconds) 
$nextWeek = $unixStart + (7 * 24 * 60 * 60);
//Query to add the information into the database
            if($edit) {
                $sql = "UPDATE `event` SET `staffID` = '$uID', `Type` = '$type', `Title` = '$event', `Date` = '$date', `Start` = '$Stime', `End` = '$Etime', `Location` = '$location', `Description` = '$descrip' WHERE `eventID` = '$eventID'";
            } else {
$query = "INSERT INTO event(staffID, Type, Title, Date, Start, End, Location, Description) 
VALUES ('$uID', '$type', '$event', '$date', '$Stime', '$Etime', '$location', '$descrip')";
            }
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
else if ($rpt == '2')
{
// This inserts the data into the database
        if($edit) {
        $sql = "UPDATE `event` SET `staffID` = '$uID', `Type` = '$type', `Title` = '$event', `Date` = '$date', `Start` = '$Stime', `End` = '$Etime', `Location` = '$location', `Description` = '$descrip' WHERE `eventID` = '$eventID'"; 
        } else {
$sql = "INSERT INTO event(staffID, Type, Title, Date, Start, End, Location, Description) 
VALUES ('$uID', '$type', '$event', '$date', '$Stime', '$Etime', '$location', '$descrip')";
            
        }
while($unixStart <= $unixEnd)
{
//Code to add the next week to the event (7 days, 24 hours, 60 minutes, 60 seconds) 
$nextDay = $unixStart + (24 * 60 * 60);
//Query to add the information into the database
            if($edit) {
                $sql = "UPDATE `event` SET `staffID` = '$uID', `Type` = '$type', `Title` = '$event', `Date` = '$date', `Start` = '$Stime', `End` = '$Etime', `Location` = '$location', `Description` = '$descrip' WHERE `eventID` = '$eventID'";
            } else {
$query = "INSERT INTO event(staffID, Type, Title, Date, Start, End, Location, Description) 
VALUES ('$uID', '$type', '$event', '$date', '$Stime', '$Etime', '$location', '$descrip')";
            }
// Inserts the entry into the database
mysql_query($query) or die(mysql_error());
// Adds 7 to the day
$date = date("Y-m-d", $nextDay);
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
            if($edit) {
                $sql = "UPDATE `event` SET `staffID` = '$uID', `Type` = '$type', `Title` = '$event', `Date` = '$date', `Start` = '$Stime', `End` = '$Etime', `Location` = '$location', `Description` = '$descrip', `Ahoc` = '$adhoc' WHERE `eventID` = '$eventID'";
            } else {
$sql = "INSERT INTO event(staffID, Type, Title, Date, Start, End, Location, Description, Ahoc) 
VALUES ('$uID', '$type', '$event', '$date', '$Stime', '$Etime', '$location', '$descrip', '$adhoc')";
            }
mysql_query($sql) or die(mysql_error());
}
else
{
// This inserts the data into the database
            if($edit) {
                $sql = "UPDATE `event` SET `staffID` = '$uID', `Type` = '$type', `Title` = '$event', `Date` = '$date', `Start` = '$Stime', `End` = '$Etime', `Location` = '$location', `Description` = '$descrip' WHERE `eventID` = '$eventID'";
            } else {
$sql = "INSERT INTO event(staffID, Type, Title, Date, Start, End, Location, Description) 
VALUES ('$uID', '$type', '$event', '$date', '$Stime', '$Etime', '$location', '$descrip')";
            }
mysql_query($sql) or die(mysql_error());
}
}
?>
<script type="text/javascript">
window.opener.location.href="week.php";
self.close();
</script>
</body>
</html>