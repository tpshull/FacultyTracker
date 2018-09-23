<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_thicPC = "localhost";
$database_thicPC = "c2facultytracker";
$username_thicPC = "root";
$password_thicPC = "admin";
$thicPC = mysql_pconnect($hostname_thicPC, $username_thicPC, $password_thicPC) or trigger_error(mysql_error(),E_USER_ERROR); 
?>