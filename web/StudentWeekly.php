<?php
include('connect.php');

//Code to connect to the database
$con = mysqli_connect($hostname, $dbusername, $dbpassword, $databaseName);
if (!$con)
{
	die('Unable to connect to MySQL' . mysqli_error());
}

//This selects the database	
$selected_db = mysqli_select_db($databaseName, $con);


//The Function to create the dropbox
function dropbox()
{
	$qry = "SELECT * FROM staff";
	//All the results of the query is stored in this variable
	$result = mysql_query($qry) or die(mysql_error());
	//Goes through the results
	while($row = mysql_fetch_array($result))
	{
			$staffIDs = mysql_real_escape_string($row['staffID']);
			$rname = mysql_real_escape_string($row['name']);
			echo "<option value='" . $staffIDs . "'>" . $rname . " </option>";
	}
	
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="300" />

<link href="calstyles.css" rel="stylesheet" type="text/css" />
<?php 
if ($_POST["user"] != 0) 
{
	$user = mysql_real_escape_string($_POST['user']);
	$sql = "Select * FROM staff where staffID=".$user;
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);

	$staffID = mysql_real_escape_string($row['staffID']);
	$name = mysql_real_escape_string($row['name']);
	
		
	//Gets the Id for the staff member
	$uid = $staffID;
	$name = $name;
	$tname = "| " . $name;
}
?>
<title>Weekly Schedule <?php echo $tname; ?></title>
</head>

<body>

<div class="wrapper" align= "center">
 <?php include("headnav.php") ?>;
    
    <div id="space">
    <p>
    
    
     </p>
    </div>
<div id="white">
<form name="frmLoadUser" method="post" action="">
  <h3>&nbsp;</h3>
  <h3>&nbsp;</h3>
  
  <h3>Select Faculty:
    <select name="user" id="user" onchange="this.form.submit()">
      <option value="0">Select</option>
      <?php dropbox(); ?>
    </select>
  </h3>
</form>
 



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
			$eventID = mysql_real_escape_string($row['eventID']);
			$title = mysql_real_escape_string($row['Title']);
			$start = mysql_real_escape_string($row['Start']);
			$end = mysql_real_escape_string($row['End']);
			
			
			echo "<tr valign=top>";
			//This puts the results in the table
			$url = "descript.php";
			$url = $url . "?eventID=" . $eventID;
			$url = "window.open('".$url."','','width=310,height=355,0,status=0,scrollbars=1,left=500,top=20')";
			echo "<td><b><a href='javascript:void();' onClick=".$url.">" . $title . "</a></b><br/><small>". date("g:i a", strtotime($start)) . "-" . date("g:i a", strtotime($end)) . "</small></td>";
			echo "</tr>";
		}
	}
?>


<h2>
    <p>Weekly Schedule <?php echo "for: ".$name; ?></p></h2>
  </div>
    <div align="center" class="week">
    <p> NOTICE! If you do not see a scheduled event, assume faculty member is not available.  </p>
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
    </table>
   
</div>
 	<?php include("footer.php") ?>
    </div>
</body>
</html>