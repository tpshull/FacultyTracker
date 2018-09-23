<?php
$con=mysql_connect("152.8.114.191","c2facultytracker","2015fTracker$");
$objDB = mysql_select_db("c2facultytracker");
// Gets the current date
                $newdate = date('Y-m-d');
                //Time stamp
                $ts = strtotime($newdate);

                //Day of week
                $dow = date('w', $ts);
                $offset = $dow - 1;
                if($offset < 0) $offset = 6;

                // Calculate timestamp for appropriate column
                $ts = $ts - $offset * 86400;    // Gets Mondays timestamp
                $startDay--;                    // Fixes the start day's offset
                $ts = $ts + $startDay * 86400;  // Gets timestamp for current column
$newdate = date('Y-m-d', $ts);
$strSQL = "SELECT event.Title, event.Start, event.End, event.eventID
                                FROM event
                                WHERE event.Date = '$newdate' AND event.staffID = '10'
                                ORDER BY event.Start";
$objQuery = mysql_query($strSQL);
$intNumField = mysql_num_fields($objQuery);
$resultArray = array();
while($obResult = mysql_fetch_array($objQuery))
 {
 $arrCol = array();
 for($i=0;$i<$intNumField;$i++)
 {
 $arrCol[mysql_field_name($objQuery,$i)] = $obResult[$i];
 }
 array_push($resultArray,$arrCol);
 }

mysql_close($con);

echo json_encode($resultArray);
?>
