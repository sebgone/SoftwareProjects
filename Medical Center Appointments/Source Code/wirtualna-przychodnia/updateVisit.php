<?php
	 
	require_once "connectDB.php";
	
	$connection = new mysqli($host, $db_user, $db_password, $db_name);
	
	if($connection->connect_errno)
	{
		echo "Error: ".$connection->connect_errno;
		exit();
	}
	$query = "DELETE FROM wizyty WHERE czas < NOW()";
	$result = mysqli_query($connection,$query);
	
	$connection->close();
	
?>