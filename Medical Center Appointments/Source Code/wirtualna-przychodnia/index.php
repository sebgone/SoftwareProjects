<?php

	session_start();
	
	if ((isset($_SESSION['loggedD'])) && ($_SESSION['loggedD']==true))
	{
		header('Location: pageDoctor.php');
		exit();
	}
	
	if ((isset($_SESSION['loggedP'])) && ($_SESSION['loggedP']==true))
	{
		header('Location: pagePatient.php');
		exit();
	}
	
?>
<!DOCTYPE html>
<html lang="pl">
<head>

	<title>Wirtualna Przychodnia</title>
	
    <meta charset="utf-8">
	<meta name="description" content="Wirtualna Przychodnia - wizyty lekarskie online">
    <meta name="keywords" content="Przychodnia, wizyty, online, lekarze, pacjenci">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<link rel="stylesheet" href="main.css" />
	<link rel="stylesheet" href="css/fontello.css" type="text/css" />
	<link href='http://fonts.googleapis.com/css?family=Lato|Josefin+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>

</head>
<body>

	<header>
		<div class="mainlogo">
			<h1 class="logo"> Wirtualna Przychodnia </h1>
		</div>
	</header>

	<main>
		<section>
			<div class="container">
			
				<div class="graphicLeft">
					<img id="mainsnake" src="source/snake.png" alt="main logo">
				</div>
				<div class="startPanelRight">
					<h1>Zaloguj się jako: </h1>
					
					<a href="loginDoctor.php" >
					<button type="button" class="buttonLP" >Lekarz</button>
					</a><br>
					
					<a href="loginPatient.php" >
					<button type="button" class="buttonLP">Pacjent</button>
					</a>
					
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