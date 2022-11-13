<?php
function getdb(){
	$servername = "localhost";
	$username = "root";
	$password = "DAFConn3ct2018!";
	$db = "rfms_reading";
	try {
		$con = mysqli_connect($servername, $username, $password, $db);
		 //echo "Connected successfully"; 
		}
	catch(exception $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
    return $con;
}
?>