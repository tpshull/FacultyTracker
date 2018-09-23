<?php

require_once('db_connect.php');
$con = new DB_CONNECT();
	//Sets Current Time
	$current = time();
	$here = getdate();
	
	//The querty to get the staff names, events, locations, start and end times from the database
	//to check if the events are taking place right now
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
        $intNumField = mysql_num_fields($result);

    
        if (mysql_num_rows($result) > 0) {
            
                $response["events"] = array();
	
            //Goes through the results	
            while($row = mysql_fetch_array($result))
            {					
                $events = array();					
                $events["name"] = $row['Name'];
                
                            if($row['Ahoc'] == 1)
                            {
                                    
                                    $events["Title"] = $row['Title'];
                                    $events["Location"] = $row['Location'];
                                    $events["End"] = date("g:i a", strtotime($row['End']));
                            }
                            else
                            {
                                    $events["Title"] = $row['Title'];
                                    $events["Location"] = $row['Location'];
                                    $events["End"] = date("g:i a", strtotime($row['End']));
                            }
                            
                array_push($response["events"],$events);

                            
            }
        $response["success"] = 1;
        echo json_encode($response);

        }
        
        else{
            $response["success"] = 0;
            echo json_encode($response);
	}
?>