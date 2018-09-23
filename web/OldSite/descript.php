<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Desrciption</title>
<style type="text/css">
<!--
body {
	background-image: url(http://facultytracker.ncat.edu/img/ncatBg.jpg);
	background-repeat: no-repeat;
}
-->
</style>
</head>
<h1>Description</h1>
<hr />

<?php 

//The connection to the database
include('connect.php');

	$selected_db = mysql_select_db($databaseName, $con);
	
function results()
{
	$event = $_GET['eventID'];
	$qry = "SELECT * FROM event WHERE eventID = '$event'";
	//All the results of the query is stored in this variable
	$result = mysql_query($qry2);
	//Goes through the results
	while($row = mysql_fetch_array($result))
	{
		echo "<b>Title: </b>" . $row['Title'];
		echo "<br/>\n";
		echo "<b>Time: </b>" . $row['Start'] . " - " . $row['End'];
		echo "<br/><br/>";
		echo "<b>Location: </b>" . $row['Location'];
		echo "<br>";
		echo "<b>Decription: </b><br><textarea>" . $row[Description] . "</textarea>";
	}
	

<body>
<h1>Description</h1>
<hr>
<br />
<?php results(); ?>
<center>
<a href="javascript:window.close()">Close Window</a>
</center>
</body>
</html>