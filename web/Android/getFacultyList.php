<?php

require_once('db_connect.php');

$con = new DB_CONNECT();
 //$objConnect = mysql_connect("152.8.114.191","c2facultytracker","2015fTracker$");
 $objDB = mysql_select_db("c2facultytracker");
 
 $strSQL = "SELECT staff.name FROM staff";
 
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
 
echo json_encode($resultArray);
//mysql_close();
?>
