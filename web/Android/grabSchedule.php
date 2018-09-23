<?php
require_once('db_connect.php');

$con = new DB_CONNECT();
$objDB = mysql_select_db("c2facultytracker");

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
                $ts = $ts - $offset * 86400;    // Gets Mondays timestamp
                $startDay--;                    // Fixes the start day's offset
                $ts = $ts + $startDay * 86400;  // Gets timestamp for current column

        
                $newdate = date('Y-m-d', $ts);
            ////if (isset($_GET["uid"])) {
               // $uid = '2';//$_GET['uid'];
                $strSQL = "SELECT event.Title, event.Start, event.End, event.Location, event.Description, event.eventID
                                FROM event
                                WHERE event.Date = '$newdate' AND event.staffID = '$uid'
                                ORDER BY event.Start";
                $Query = mysql_query($strSQL);
                $intNumField = mysql_num_fields($Query);
    
 ////   if (!empty($Query)) {
            // check for empty result
            if (mysql_num_rows($Query) > 0) {
                $response["events"] = array();

                while($row = mysql_fetch_array($Query))
                {
                    $events = array();
                //for($i=0;$i<$intNumField;$i++){
 
                 //   $event[mysql_field_name($Query,$i)] = $obResult[$i];
                //}
                
                //switch ($row["start"]){
                //    case "05:00:00": $events["Start"]
                //}
                
                    $events["Title"] = $row["Title"];
                    $events["Start"] = date('h:i:s a', strtotime($row["Start"]));
                    $events["End"] = date('h:i:s a', strtotime($row["End"]));
                    $events["Location"] = $row["Location"];
                    $events["Description"] = $row["Description"];
                    $events["EventID"] = $row["EventID"];

                    array_push($response["events"],$events);
                }
 
 
                // success
                $response["success"] = 1;
 
                // user node
                //$response["event"] = array();
 
    //          array_push($response["event"], $event);
            
                // echoing JSON response
                echo json_encode($response);
  ////      } else {
            // no event found
  ////          $response["success"] = 0;
  ////          $response["message"] = "No event found";
 
            // echo no users JSON
  ////          echo json_encode($response);
  ////      }
            } else {
                // no event found
                $response["success"] = 0;
                $response["message"] = "No event found";
 
                // echo no users JSON
                echo json_encode($response);
            }
    }
////} else {
    // required field is missing
////    $response["success"] = 0;
////    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
////    echo json_encode($response);
////    }
//while($result = mysql_fetch_array($result))
// {
// $arrCol = array();
// for($i=0;$i<$intNumField;$i++)
// {
// $arrCol[mysql_field_name($objQuery,$i)] = $obResult[$i];
// }
// array_push($resultArray,$arrCol);
// }
$uid = $_POST['uid'];
if($_POST['day'] == "Monday") $day = '1';
else if($_POST['day'] == "Tuesday") $day = '2';
else if($_POST['day'] == "Wednesday") $day = '3';
else if($_POST['day'] == "Thursday") $day = '4';
else $day = '5';

dayEvents($day,$uid);
//dayEvents("2",$uid);
//dayEvents("3",$uid);
//dayEvents("4",$uid);
//dayEvents("5",$uid);


mysql_close($con);

//echo json_encode($resultArray);
?>
