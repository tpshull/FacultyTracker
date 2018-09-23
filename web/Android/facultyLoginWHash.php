<?php
require_once('db_connect.php');
$con = new DB_CONNECT();

$username=strtolower($_POST["username"]);
$password=strtolower($_POST["password"]);

//Attempt to prevent mysql injection
mysql_real_escape_string($username);
mysql_real_escape_string($password);

if(!empty($_POST)) {
    if(empty($_POST['username']) || empty($_POST['password'])){
        
        //Create some data that will be the JSON response
	$response["success"] = 0;
	$response["message"] = "One or both of the fields are empty.";

	die(json_encode($response));
    }

    $query = " SELECT * FROM staff WHERE username = '$username'";

    $sql1=mysql_query($query); 
    $row = mysql_fetch_array($sql1); 

    if (!empty($row)) { 
        if($password==$row['password'] or md5($password)==$row['password']){
        
            $response["success"] = 1; 
            $response["message"] = "You have been sucessfully logged in";
            $response["name"]=$row["name"];
            $response["uid"]=$row["staffID"];
        
            die(json_encode($response)); 
        }
    }else{
	$response["success"] = 0; 
	$response["message"] = "invalid username or password "; 

	die(json_encode($response)); 
    }

}

//mysql_close();
?>
