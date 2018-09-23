<?php require_once('Connections/con1.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_con1, $con1);
$query_Faculty = "SELECT * FROM staff";
$Faculty = mysql_query($query_Faculty, $con1) or die(mysql_error());
$row_Faculty = mysql_fetch_assoc($Faculty);
$totalRows_Faculty = mysql_num_rows($Faculty);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript">
var win = null;
function NewWindow(mypage,myname,w,h,scroll){
LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
settings =
'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'
win = window.open(mypage,myname,settings)
}
function showAgenda(value)
{
	document.getElementById('agenda').innerHTML = "<b>Currently<b>" + value;
}
<!--
function selections()
{
	-->
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Faculty Tracker</title>
</head>
<body>
<p><a href="http://localhost/login.php" onclick="NewWindow(this.href,'name','300','300','yes');return false">Login</a></p>
<table width="624" height="279" border="1" summary="Holds the faculty and there agenda for the day.">
  <tr>
    <th width="92" scope="col" bgcolor="gold"><font color="navy">Faculty</font></th>
    <th width="520" scope="col"  bgcolor="gold"><font color="navy">Agenda</font></center></th>
  </tr>
  <tr>
    <td height="211"><form>
      <select name="select" size=10 onchange="showAgenda(value)">
        <?php
do {  
?>
        <option value="<?php echo $row_Faculty['Name']?>"<?php if (!(strcmp($row_Faculty['Name'], $row_Faculty['Name']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Faculty['Name']?></option>
        <?php
} while ($row_Faculty = mysql_fetch_assoc($Faculty));
  $rows = mysql_num_rows($Faculty);
  if($rows > 0) {
      mysql_data_seek($Faculty, 0);
	  $row_Faculty = mysql_fetch_assoc($Faculty);
  }
?>
      </select>
    </form></td>
    <td id='agenda'><table width="520" height="183" border="1">
      <tr bgcolor="gold"><font color="#000080">
        <th width="68" height="24" scope="col">Monday</th>
        <th width="77" scope="col">Tuesday</th>
        <th width="80" scope="col">Wednesday</th>
        <th width="77" scope="col">Thursday</th>
        <th width="78" scope="col">Friday</th>
        </font>
      </tr>
      <tr>
        <td height="151">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2">Current Time: <right>
	<script type="text/javascript">
<!--

var m_names = new Array("January", "February", "March", 
"April", "May", "June", "July", "August", "September", 
"October", "November", "December");

var d = new Date();
var curr_date = d.getDate();
var curr_month = d.getMonth();
var curr_year = d.getFullYear();
document.write(m_names[curr_month] + " " + curr_date  
+ ", " + curr_year);

/* The last two lines above have 
to placed on a single line */

//-->
</script>

	<script type="text/javascript">
	<!--

var a_p = "";
var d = new Date();

var curr_hour = d.getHours();

if (curr_hour < 12)
   {
   a_p = "AM";
   }
	else
	{
   		a_p = "PM";
   	}
	if (curr_hour == 0)
	{
   		curr_hour = 12;
	}
	if (curr_hour > 12)
	{
		curr_hour = curr_hour - 12;
	}

var curr_min = d.getMinutes();

document.write(curr_hour + ":" + curr_min + " " + a_p);

//-->
</script></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Faculty);
?>
