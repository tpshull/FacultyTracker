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

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Faculty Tracker</title>
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
// <link href="/web/styles.css" type="text/css" />
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<body onLoad="goforit()">
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Agenda</li>
    <li class="TabbedPanelsTab" tabindex="0">Day</li>
    <li class="TabbedPanelsTab" tabindex="0">Week</li>
    <li class="TabbedPanelsTab" tabindex="0">Month</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">Content 1</div>
    <div class="TabbedPanelsContent">Content 2</div>
    <div class="TabbedPanelsContent">
    <style type="text/css">
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
	background-color: gold;
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
table.weekly a{text-decoration:none; color:#000000;}
</style>

<?php
//The connection to the database
include('connect.php');

	$selected_db = mysql_select_db($databaseName, $con);

	
		if ($_POST['check'] != 0) 
		{
			if(isset($_POST['check']))
			{
				foreach($_POST['check'] as $eventID)
				{
					mysql_query("DELETE FROM event WHERE eventID = '$eventID'") or die(mysql_error());
				}
			}
		}

		
	
	
	function dayEvents($startDay)
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
				WHERE event.Date = '$newdate' AND event.staffID = '$_SESSION[staffID]'
				ORDER BY event.Start";
				
		//All the results of the query is stored in this variable
		$result = mysql_query($query2);
		//Goes through the results
		while($row = mysql_fetch_array($result))
		{
			echo "<tr>";
			//This puts the results in the table
			echo "<td><input type='checkbox' name='check[]' value='" . $row['eventID'] . "'></td>";
			echo "<td><b>" . $row['Title'] . "</b><br/><small>". $row['Start'] . "-" . $row['End'] . "</small></td>";			
			echo "</tr>";
		}
	}	

?>
<h2>
    <p>Weekly Schedule<hr/></p>
    <center>
    <table class="weekly" width="800" height="344" border="2" cellpadding="1">
      <tr>
        <th height="25" colspan="5" bgcolor="gold"><center><font color="navy"> Agenda </center></th>
      </tr>
      <tr class="days" align="center">
        <td height="27" width="160" bgcolor="gold"><b><font color="navy"> Monday</td>
        <td width="160" bgcolor="gold"><b><font color="navy">Tuesday</td>
        <td width="160" bgcolor="gold"><b><font color="navy">Wednesday</td>
        <td width="160" bgcolor="gold"><b><font color="navy">Thursday</td>
        <td width="160" bgcolor="gold"><b><font color="navy">Friday</td>
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
	  
	  echo "</tr>";
	  ?>
		
        <script type="text/javascript">
		function modify()
		{
			alert("You made it this far");
			document.modify.del.disabled="false";
			document.modify.edit.disabled="false";
		}
		</script>
	<form name="modify" method="post" action="week.php">
      <tr valign="top">
        <td height="250">
        <table class="eventSel" width="155"><?php dayEvents("1");?></table></td>
	    <td><table class="eventSel" width="158"><?php dayEvents("2");?></table></td>
        <td><table class="eventSel" width="158"><?php dayEvents("3");?></table></td>
        <td><table class="eventSel" width="158"><?php dayEvents("4");?></table></td>
        <td><table class="eventSel" width="158"><?php dayEvents("5");?></table></td>
      </tr>
      <tr>
      <td height="27" colspan="5" valign="middle">
        <input name="del" type='submit' value="Delete" />
      </form>
      </td>
    </table>
  </div>
</div>
    <p><script type="text/javascript"> 
/*
Live Date Script- 
Â© Dynamic Drive (www.dynamicdrive.com)
For full source code, installation instructions, 100's more DHTML scripts, and Terms Of Use,
visit http://www.dynamicdrive.com
*/
 
 
var dayarray=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday")
var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
 
function getthedate(){
var mydate=new Date()
var year=mydate.getYear()
if (year < 1000)
year+=1900
var day=mydate.getDay()
var month=mydate.getMonth()
var daym=mydate.getDate()
if (daym<10)
daym="0"+daym
var hours=mydate.getHours()
var minutes=mydate.getMinutes()
var seconds=mydate.getSeconds()
var dn="AM"
if (hours>=12)
dn="PM"
if (hours>12){
hours=hours-12
}
if (hours==0)
hours=12
if (minutes<=9)
minutes="0"+minutes
if (seconds<=9)
seconds="0"+seconds
//change font size here
var cdate="<small><font color='000000' face='Arial'><b>"+dayarray[day]+", "+montharray[month]+" "+daym+", "+year+" "+hours+":"+minutes+":"+seconds+" "+dn
+"</b></font></small>"
if (document.all)
document.all.clock.innerHTML=cdate
else if (document.getElementById)
document.getElementById("clock").innerHTML=cdate
else
document.write(cdate)
}
if (!document.all&&!document.getElementById)
getthedate()
function goforit(){
if (document.all||document.getElementById)
setInterval("getthedate()",1000)
}
 
</script>
  </center>
</h2>
  <span id="clock"></span>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//-->
  </script>
</body>
</html>