<?php

	session_start();
	
	include 'updateVisit.php';
	
	if (!isset($_SESSION['loggedP']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		require_once "connectDB.php";
	    $connection = new mysqli($host, $db_user, $db_password, $db_name);
		$idpatient = $_SESSION['idpatient'];
		$_SESSION['setup'] = false;
	}
	
?>
<!DOCTYPE html>
<html lang="pl">
<head>

	<title>Pacjent</title>
	
    <meta charset="utf-8">
	<meta name="description" content="Wirtualna Przychodnia - wizyty lekarskie online">
    <meta name="keywords" content="Przychodnia, wizyty, online, lekarze, pacjenci">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<link rel="stylesheet" href="main.css" />
	<link rel="stylesheet" href="css/fontello.css" type="text/css" />
	<link href='http://fonts.googleapis.com/css?family=Lato|Josefin+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>
	<script type="text/javascript" src="timer.js"></script>

</head>
<body onload="odliczanie();">

	<header>
		<div class="mainlogo">
			<h1 class="logo"> Wirtualna Przychodnia </h1>
		</div>
	</header>

	<main>
		<section>
			<div class="container">
				<div class="PatientPanelLeft">
					<a class="return" href="logout.php">&#8592 Wyloguj</a>
					
					<div class="panel">
						<div id="title"><b>Panel Pacjenta</b></div>
						<div id="zegar"></div> 
						<div style="clear: both;"></div>
					</div>
					
					<hr>
					
					<p><b>Dane:</b></p>
					<table class="DaneTable">
					<thead>
					  <tr>
						<th>id pacjenta</th>
						<th>Imię</th>
						<th>Nazwisko</th>
						<th>login</th>
					  </tr>
					</thead>
					<tbody>
					  <tr>
						<td><?php echo $_SESSION['idpatient'];?></td>
						<td><?php echo $_SESSION['namepatient'];?></td>
						<td><?php echo $_SESSION['lastnamepatient'];?></td>
						<td><?php echo $_SESSION['loginpatient'];?></td>
					  </tr>
					</tbody>
					</table>
					
					<hr>

					<p><b>Status wizyty: </b></p>
					<table class="DaneTable">
					<thead>
					  <tr>
						<th>id wizyty</th>
						<th>Imię</th>
						<th>Nazwisko</th>
						<th>specjalność</th>
						<th>Data</th>
					  </tr>
					</thead>
					<tbody>
					  <tr>
					  <?php
						$query = "SELECT wizyty.idwizyty, lekarze.imie, lekarze.nazwisko, lekarze.profesja, wizyty.czas FROM lekarze, wizyty WHERE lekarze.idlekarza = wizyty.idlekarz AND wizyty.idpacjent ='$idpatient'";
						$result = mysqli_query($connection,$query);
						$row = $result->fetch_assoc();
						if($result->num_rows == 1)
						{
							foreach($row as $r)
							{
								echo " <td>$r</td> ";
							}	
							$result->free_result();
							$state = 1;
						} else {
							echo "brak wizyt <br>";
							$state = 0;
						}   
					   ?>
					  </tr>
					</tbody>
					</table>
				
					<form action="pagePatient.php" method="post">
						<button class="buttonLP" style="font-size:15px; margin-top:10px;" type="submit" name="btncall">
							<?php
								if($state == 0)
									echo "Zapisz się";
								else
									echo "Odwołaj"
							?>
						</button>
					</form>
					<br>
				</div>
				
				<div class="PatientPanelRight">
				
					<?php
						if(isset($_POST['btncall']))
						{
							if($state==0)
							{
								$_SESSION['setup'] = true;
								$query = "SELECT * FROM lekarze";
								$result = mysqli_query($connection,$query);
								$rows = $result->fetch_all(MYSQLI_ASSOC);
								
								echo '<h2 style="margin-top: 0px;">Wybierz specjalistę:</h2>';
								echo '<table class="DaneTable">';
								foreach($rows as $row)
								{
									echo '<tr> <td style="background-color: white;"><a style="color: black;" href="appointment.php?iddoctor='.$row['idlekarza'].'">wybierz</a></td>';
									echo "<td>{$row['idlekarza']}</td>";
									echo "<td>{$row['imie']}</td>";
									echo "<td>{$row['nazwisko']}</td>";
									echo "<td>{$row['profesja']}</td>";
									echo "</tr>";
								}
								echo "</table>";
								$result->free_result();
							}
							else
							{   
								echo "Czy napweno chcesz odwołać wizytę ?";
								echo '<form action="pagePatient.php" method="post">';
								echo '<button type="submit" name="yes" class="buttonConfirm" > Tak </button>';
								echo '<button type="submit" name="no" class="buttonConfirm"> Nie </button>';
								echo "</form>";
							}
						}
						if(isset($_POST['yes']))
						{
							$query = "DELETE FROM wizyty WHERE idpacjent='$idpatient'";
							mysqli_query($connection,$query);
							echo '<script>alert("Twoja wizyta została odwołana !")</script>';
							header("Refresh:0");
						}
					?>
				</div>
				<div style="clear:both;"></div>
				
			</div>
		</section>
	</main>
	
	<footer>
		<div class="contact">
			<div class="con">
				<i class="fas fa-phone-alt" style="font-size:30px;"></i><br>
				nr. tel:<br><br>
				123 456 789
			</div>
			
			<div class="con">
				<i class="far fa-envelope" style="font-size:30px;"></i><br>
				e-mail:<br><br>
				przychodnia@wp.pl 
			</div>
			
			<div class="con">
				<i class="fas fa-map-marker-alt" style="font-size:30px;"></i><br>
				Lokacjizacja:<br><br>
				Nowy Sącz 33-300 ul. Nieznana 0
			</div>
			
			<div class="info">
				Wirtualna przychodnia - wizyty lekarskie online. Serwis w sieci od 2020r. &copy; Wszelkie prawa zastrzerzone
			</div>
		</div>
	</footer>
	
</body>
</html>
<?php
	$connection->close();
?>