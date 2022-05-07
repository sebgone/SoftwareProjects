<?php

	session_start();
	
	if($_SESSION['setup']==false)
	{
		header("Location: index.php");
        exit();	
	}
	
	if(isset($_GET["doctor"]))
	{
		$iddoctor = $_GET["doctor"];
		$idpatient = $_GET["patient"];
		$time = $_GET["time"];
		
		require_once "connectDB.php";
	    $connection = new mysqli($host, $db_user, $db_password, $db_name);
	}
	else
	{
		header("Location: index.php");
        exit();	
	}
	
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<title>Pacjent podsumowanie</title>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatibile" content="IE=edge,cgrome=1" />
	<link rel="stylesheet" href="summary.css" />
	
</head>
<body> 
	<div class="container">
		<a class="return" href="appointment.php?iddoctor=<?php echo $iddoctor;?>">&#8592 powrót</a>
		
		<main>
            <article>
				
				<h2>Podsumowanie wizyty</h2>
				<h3>Lekarz:</h3>
				
				<div class="doctor">
				<?php
					$query = "SELECT imie,nazwisko,profesja FROM lekarze WHERE idlekarza='$iddoctor'";
					$result = mysqli_query($connection,$query);
					$row = $result->fetch_assoc();
					foreach($row as $r)
					{
						echo "&nbsp $r  " ;
					}	
					$result->free_result();
				?>
				
				<hr>
				<h3 style="color:black; font-size:18px;">Termin wizyty:</h3>
				<?php
					echo date("Y-m-d H:i",$time);
				?>
				</div>
				
				<form method="post">
					<button type="submit" name="ok" class="buttonConfirm">Potwierdź</button>
				</form>
				
				<?php
					if(isset($_POST["ok"]))
					{
						$t = date("c", $time);
						
						$query = "INSERT INTO wizyty VALUES (NULL, '$t', '$iddoctor', '$idpatient' )";
						mysqli_query($connection,$query);
						
						header("Location: loginPatient.php");
					}
				?>
			</article>
        </main>
    </div>
</body>
</html>
<?php
	$connection->close();
?>