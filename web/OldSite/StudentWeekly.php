<?php
include('connect.php');

//Code to connect to the database
$con = mysql_connect($hostname, $dbusername, $dbpassword);
if (!$con)
{
	die('Unable to connect to MySQL' . mysql_error());
}

//This selects the database	
$selected_db = mysql_select_db($databaseName, $con);


//The Function to create the dropbox
function dropbox()
{
	$qry = "SELECT * FROM staff";
	//All the results of the query is stored in this variable
	$result = mysql_query($qry) or die(mysql_error());				
	//Goes through the results
	while($row = mysql_fetch_array($result))
	{
			echo "<option value='" . $row['staffID'] . "'>" . $row['name'] . "</option>";
	}
	
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="300" />
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon" type="image/gif" href="animated_favicon1.gif">
<link href="styles.css" rel="stylesheet" type="text/css" />
<?php 
if ($_POST["user"] != 0) 
{
	$sql = "Select * FROM staff where staffID=".$_POST["user"];
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);

	//Gets the Id for the staff member
	$uid = $row['staffID'];
	$name = $row['name'];
	$tname = "| " . $name;
}
?>
<title>Weekly Schedule <?php echo $tname; ?></title>
</head>

<body>
<style type="text/css">
div.week {
	padding-right: 7%;
}
table.weekly {
	border-width: 1px;
	border-spacing: 2px;
	border-style: solid;
	border-color: gold;
	border-collapse: collapse;
	background-color: rgb(213, 230, 242);
}
table.weekly th {
	border-width: 1px;
	padding: 1px;
	border-style: dotted;
	border-color: navy;
	background-color: #ffff7a;
	-moz-border-radius: 0px 0px 0px 0px;
} 
tr.days{
	background-color:#FFFF7A;
}
table.weekly td {
	border-width: 1px;
	padding: 1px;
	border-style: dashed;
	border-color: gold;
	background-color: white;
	-moz-border-radius: 0px 0px 0px 0px;
}
</style>
<div id="container">
<div id="header"><h1>Weekly Schedule</h1></div>
<div id='navigation'>
<p><h2><u>Navigation</u></h2></p>

<ul>
<li><a href="index.php">| Home </a></li>
</ul>

</div>

<form name="frmLoadUser" method="post" action="">
  <h3>Select Faculty:
    <select name="user">
      <option value="0">Select</option>
    <?php dropbox(); ?>
  </select>
  <input type="submit" name="load"  value="Display" />
 </h3>
</form>
 
</div>
 <div id='content'>
</div>
<hr />
<?php

	//Function that puts the days of the week into the schedule
	function dayEvents($startDay, $uid)
	{
		// Gets the current date
		$newdate = date('Y-m-d');
	  	//Time stamp
	  	$ts = strtotime($newdate);
	
	  	//Day of week
	  	$dow = date('w', $ts);
	  	$offset = $dow - 1;
		if($offset < 0) $offset = 6;
	  	
		// Calculate timestamp for appropriate column
	 	$ts = $ts - $offset * 86400;	// Gets Mondays timestamp
		$startDay--;			// Fixes the start day's offset
		$ts = $ts + $startDay * 86400;	// Gets timestamp for current column
	
		// Gets YYYY-mm-dd format of $day
		$newdate = date('Y-m-d', $ts);
		$query2 = "SELECT event.Title, event.Start, event.End, event.eventID 
				FROM event
				WHERE event.Date = '$newdate' AND event.staffID = '$uid'
				ORDER BY event.Start";
				
		//All the results of the query is stored in this variable
		$result = mysql_query($query2);
		//Goes through the results
		while($row = mysql_fetch_array($result))
		{
			echo "<tr valign=top>";
			//This puts the results in the table
			$url = "admin/descript.php";
			$url = $url . "?eventID=" . $row['eventID'];
			$url = "window.open('".$url."','','width=310,height=355,0,status=0,scrollbars=1,left=500,top=20')";
			echo "<td><b><a href='javascript:void();' onClick=".$url.">" . $row['Title'] . "</a></b><br/><small>". $row['Start'] . "-" . $row['End'] . "</small></td>";
			echo "</tr>";
		}
	}
?>


<h2>
    <p>Weekly Schedule <?php echo "for: ".$name; ?></p></h2>
    <div align="right" class="week">
    <table class="weekly" width="800" height="314" border="2" cellpadding="1">
      <tr>
        <th height="25" colspan="5" bgcolor="gold"><center><font color="navy"> Agenda </font></center></th>
      </tr>
      <tr align="center">
        <td height="27" width="160" bgcolor="gold"><b><font color="navy"> Monday</font></b></td>
        <td width="160" bgcolor="gold"><b><font color="navy">Tuesday</font></b></td>
        <td width="160" bgcolor="gold"><b><font color="navy">Wednesday</font></b></td>
        <td width="160" bgcolor="gold"><b><font color="navy">Thursday</font></b></td>
        <td width="160" bgcolor="gold"><b><font color="navy">Friday</font></b></td>
      </tr>
      <tr align="center">
      <?php
	  
	  $date = date('Y-m-d');
	  //Time stamp
	  $ts = strtotime($date);
	  //Day of week
	  $dow = date('w', $ts);
	  $offset = $dow - 1;
	  
	  if ($offset<0)
	  {
		  $offset = 6;
	  }
	  //Calculate timestamp for Monday
	  $ts = $ts - $offset * 86400;
	  
	  $dayEvent = array();
	  for($i=0; $i<5; $ts+=86400, $i++)
	  {
		  echo "<td height='31'>" . date('m/d/Y', $ts) . "</td>";
	  }
	  
	  ?>
      </tr>
      <tr valign="top">
        <td height="250">
        <table class="eventSel" width="155"><?php dayEvents("1",$uid);?></table></td>
	    <td><table class="eventSel" width="155"><?php dayEvents("2",$uid);?></table></td>
        <td><table class="eventSel" width="155"><?php dayEvents("3",$uid);?></table></td>
        <td><table class="eventSel" width="155"><?php dayEvents("4",$uid);?></table></td>
        <td valign="top"><table class="eventSel" width="155"><?php dayEvents("5",$uid);?></table></td>
      </tr>
      <tr>
      <td height="27" colspan="5" valign="middle">
      </td>
      </tr>
    </table>
</div>
<div class="extra">
<p>Brought to you by <b>”R.T.G.”</b> from Senior Project, Spring '15</p>
</div>
</body>
</html>