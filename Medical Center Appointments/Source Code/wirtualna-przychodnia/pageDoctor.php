<?php
	
	session_start();
	
	include 'updateVisit.php';
	
	if (!isset($_SESSION['loggedD']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		require_once "connectDB.php";
	    $connection = new mysqli($host, $db_user, $db_password, $db_name);	
		$iddoctor = $_SESSION['iddoctor'];
	}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<title>Doktor</title>
	<meta charset="utf-8"/>
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
						<div id="title"><b>Panel Doktora</b></div>
						<div id="zegar"></div> 
						<div style="clear: both;"></div>
					</div>
					
					<hr>
					
					<p><b>Dane:</b></p> 
					<table class="DaneTable">
					<thead>
					  <tr>
						<th>id lekarza</th>
						<th>Imię</th>
						<th>Nazwisko</th>
						<th>profesja</th>
					  </tr>
					</thead>
					<tbody>
					  <tr>
						<td><?php echo $_SESSION['iddoctor'];?></td>
						<td><?php echo $_SESSION['namedoctor'];?></td>
						<td><?php echo $_SESSION['lastnamedoctor'];?></td>
						<td><?php echo $_SESSION['profession'];?></td>
					  </tr>
					</tbody>
					</table>
					
					<hr>
					
					<p><b>kalendarz wizyt: </b></p>
					<form action="pageDoctor.php" method="post">
						<button class="buttonConfirm" type="submit" name="now">dziś</button>
						<button class="buttonConfirm" type="submit" name="all">wszystko</button>
						<button class="buttonConfirm" type="submit" name="call">kalendarz</button>
						<input style="background-color: white;" type="date" name="date">
					</form>
					<br>
				</div>
				
				<div class="PatientPanelRight">
					<?php
						echo '<h2 style="margin-top: 0px;">harmonogram wizyt:</h2>';
						if(isset($_POST['all']))
						{
							$query = "Select wizyty.idwizyty, wizyty.czas, pacjenci.idpacjenta, pacjenci.imie, pacjenci.nazwisko FROM pacjenci, wizyty WHERE pacjenci.idpacjenta = wizyty.idpacjent AND wizyty.idlekarz = '$iddoctor'";
							$state = "all";
							
						}
						if(isset($_POST['now']))
						{
							$today = date("c", strtotime("today"));
							$tomorrow = date("c", strtotime("tomorrow"));
							$query = "Select wizyty.idwizyty, wizyty.czas, pacjenci.idpacjenta, pacjenci.imie, pacjenci.nazwisko FROM pacjenci, wizyty WHERE pacjenci.idpacjenta = wizyty.idpacjent AND wizyty.idlekarz = '$iddoctor' AND (wizyty.czas>'$today' and wizyty.czas<'$tomorrow')";
							$state = "now";
						}
						if(isset($_POST['call']))
						{
							$stringDay = strtotime($_POST['date']);
							$time = date("Y-m-d", $stringDay); 
							$nextday = date("Y-m-d",strtotime('+1 day',$stringDay));
							$query = "Select wizyty.idwizyty, wizyty.czas, pacjenci.idpacjenta, pacjenci.imie, pacjenci.nazwisko FROM pacjenci, wizyty WHERE pacjenci.idpacjenta = wizyty.idpacjent AND wizyty.idlekarz = '$iddoctor' AND (wizyty.czas>'$time' and wizyty.czas<'$nextday')";
							$state = "call";
						}
						
						if(isset($_POST['all']) or isset($_POST['now']) or isset($_POST['call']))
						{
							$result = mysqli_query($connection,$query);
							$row = $result->fetch_all(MYSQLI_ASSOC);
						
						    switch($state)
							{
								case "all":
									echo "wszystko";
									break;
								case "now":
									echo "dziś";
									break;
								case "call":
									echo $time;
									break;
							}
							echo '<table class="DaneTable">';
							echo "<thead><tr> <th>id wizyty</th> <th>termin wizyty</th> <th>id pacjenta</th> <th>imie</th> <th>nazwisko</th> </tr></thead> <tbody>";
							foreach($row as $r)
							{
								echo "<tr> <td>{$r['idwizyty']}</td> <td>{$r['czas']}</td> <td>{$r['idpacjenta']}</td> <td>{$r['imie']}</td> <td>{$r['nazwisko']}</td> </tr>";
							}	
							echo "</tbody></table>";
							$result->free_result();
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