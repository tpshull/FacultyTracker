<?php
 $objConnect = mysql_connect("152.8.114.191","c2facultytracker","2015fTracker$");
 $objDB = mysql_select_db("c2facultytracker");
 $strSQL = "SELECT * FROM staff WHERE 1 ";
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
 
mysql_close($objConnect);
 
echo json_encode($resultArray);
?>
