<?php
	
	session_start();
	
	//if login.php was entered from link locate to index.php 
	if ( !(isset($_POST['loginP']) or isset($_POST['loginD'])) )
	{
		header('Location: index.php');
		exit();
	}

	require_once "connectDB.php";
	
	$connection = new mysqli($host, $db_user, $db_password, $db_name);
	
	//if connection goes wrong exit the script
	if($connection->connect_errno)
	{
		echo "Error: ".$connection->connect_errno;
		exit();
	}
	
	//if form PATIENT was submitted then check DB
	if( isset($_POST['loginP']) )
	{
		$login = $connection->real_escape_string($_POST['loginP']);
		$password = $connection->real_escape_string($_POST['passwordP']);
		
		$query = "SELECT * FROM pacjenci WHERE login='$login' AND haslo='$password'";
		$result = mysqli_query($connection,$query);
		
		if($result->num_rows == 1)
		{
			$_SESSION['loggedP'] = true;
			$row = $result->fetch_assoc();
			$_SESSION['idpatient'] = $row['idpacjenta'];
			$_SESSION['namepatient'] = $row['imie'];
			$_SESSION['lastnamepatient'] = $row['nazwisko'];
			$_SESSION['loginpatient'] = $row['login'];
			
			$result->free_result();
			unset($_SESSION['warning']);
			header('Location: pagePatient.php');
			
		} else {
			$_SESSION['warning'] = '<span style="color:red; font-size: 14px;">Nieprawidłowy login lub hasło!</span>';
			header('Location: loginPatient.php');
		}
	}
	
	//if form DOCTOR was submitted then check DB
	if( isset($_POST['loginD']) )
	{
		$login = $connection->real_escape_string($_POST['loginD']);
		$password = $connection->real_escape_string($_POST['passwordD']);
		
		$query = "SELECT * FROM lekarze WHERE login='$login' AND haslo='$password'";
		$result = mysqli_query($connection,$query);
		
		if($result->num_rows == 1)
		{
			$_SESSION['loggedD'] = true;
			$row = $result->fetch_assoc();
			$_SESSION['iddoctor'] = $row['idlekarza'];
			$_SESSION['namedoctor'] = $row['imie'];
			$_SESSION['lastnamedoctor'] = $row['nazwisko'];
			$_SESSION['profession'] = $row['profesja'];
			
			$result->free_result();
			unset($_SESSION['warning']);
			header('Location: pageDoctor.php');
			
		} else {
			$_SESSION['warning'] = '<span style="color:red; font-size: 14px;">Nieprawidłowy login lub hasło!</span>';
			header('Location: loginDoctor.php');
		}
	}
	
	$connection->close();
	
?>