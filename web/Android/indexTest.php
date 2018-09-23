<?php

 $objConnect = mysql_connect("152.8.114.191","c2facultytracker","2015fTracker$");
 $objDB = mysql_select_db("c2facultytracker");

	$query1 = "SELECT staff.Name, event.Title, event.Location, event.Start, event.End, event.Ahoc
				FROM staff
				INNER JOIN event
				ON staff.staffID = event.staffID
				WHERE CURDATE() = event.Date
				AND CURTIME() BETWEEN event.Start AND event.End
				ORDER BY event.Ahoc desc";
				
	//All the results of the query is stored in this variable
	$result = mysql_query($query1);

//Have a query to get all the events that are going on now that are adhoc and not adhoc
	//Check to see if the staff member has an adhoc event going on
	//If he does then print out that result
	//If he doesnt then print out the regular event
		
	$oldStaffID = 0;
	
	//Goes through the results	
	while($row = mysql_fetch_array($result))
	{										
			//This puts the results in the table
			echo $row['Name'];
			if($row['Ahoc'] == 1)
			{
				echo $row['Title'].$row['Location'];
			}
			else
			{
				echo $row['Title'].$row['Location'];
			}
	}
	//The Function to create the dropbox
	function dropbox()
	{
		$qry = "SELECT * FROM staff";
		//All the results of the query is stored in this variable
		$result = mysql_query($qry) or die(mysql_error());			
		//Goes through the results
		while($row = mysql_fetch_array($result))
		{
			echo $row['staffID'] . $row['name'];
		}
	
	}

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
			//This puts the results in the table
			$url = "descript.php";
			$url = $url . $row['eventID'];
			echo $row['Title'] . $row['Start'] . $row['End'];
		}
	}
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
		  echo date('m/d/Y', $ts);
	  }

	dayEvents("1",$uid);
	dayEvents("2",$uid);
	dayEvents("3",$uid);
	dayEvents("4",$uid);
	dayEvents("5",$uid);
mysql_close($objConnect);
?>
