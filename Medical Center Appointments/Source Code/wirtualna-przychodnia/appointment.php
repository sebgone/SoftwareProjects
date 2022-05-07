<?php
	
	session_start();
	
	if($_SESSION['setup']==false)
	{
		header("Location: index.php");
        exit();	
	}
	
	if (isset($_GET['iddoctor']))
	{
		$iddoctor = $_GET['iddoctor'];
	}
	else{ exit(); }
	
	require_once "connectDB.php";
	$connection = new mysqli($host, $db_user, $db_password, $db_name);
	
	function CheckVisit($id,$date,$conn)
	{
		$d = date("c",$date);
		$query = "SELECT * FROM wizyty WHERE idlekarz='$id' AND czas='$d'";
		$result = mysqli_query($conn,$query);
		if($result->num_rows > 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<title>Pacjent wizyty</title>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatibile" content="IE=edge,cgrome=1" />
	<link rel="stylesheet" href="summary.css" />
	
	<script type="text/javascript" src="timer.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="jquery.scrollTo.min.js"></script>
	
	
	<script>
		
	jQuery(function($)
	{
		//zresetuj scrolla
		$.scrollTo(0);
			
		$('#btn1').click(function() { $.scrollTo($('#link'), 1000); });
	    $('.scrollup').click(function() { $.scrollTo($('.container'), 1000); });
	}
	);
		

	$(window).scroll(function()
	{
		if($(this).scrollTop()>200) $('.scrollup').fadeIn();
		else $('.scrollup').fadeOut();		
	}
	); 
	
	
	</script>
	
</head>
<body  onload="odliczanie();">
    <div class="container">
	<a href="#" class="scrollup"></a>
		<a class="return" href="pagePatient.php">&#8592 powrót</a>
        <main>
            <article>
				<div class="panel">
					<h3>Wybierz termin wizyty:</h3>
					<div id="title"><b>Dzisiaj:</b></div>
					<div id="zegar"></div> 
					<div style="clear: both;"></div>
				</div>
				
				<div class="doctor">
				<p style="color:black; font-size: 25px;"><b>Lekarz:</b></p>
				<?php
					$query = "SELECT imie,nazwisko,profesja FROM lekarze WHERE idlekarza='$iddoctor'";
					$result = mysqli_query($connection,$query);
					$row = $result->fetch_assoc();
					foreach($row as $r)
					{
						echo "&nbsp $r  ";
					}	
					$result->free_result();
				?>
				</div>
				
				<hr>
				<form method="post">
					<p>Dzień wizyty:</p>
					<input type="date" name="day" autocomplete="off">
					<button id="btn" class="buttonShow" type="submit" name="show">pokaż dostępność</button>
				</form>
				
				<?php
				if( isset($_POST['show']) )
				{
					$stringDay = strtotime($_POST['day']);    //2020-11-14 00:00:00
					$day = date("w",$stringDay);
			
					if( ($stringDay >= strtotime("today")) and $day != 6 and $day != 0 )
					{
						$chosenDay = date('Y-m-d',$stringDay);
echo<<<END
	
	<table class="DaneTable">
		<thead>
			<tr>
				<th colspan="2"><a id="btn1">$chosenDay</a></th>
			</tr>
			<tr>
				<th>godzina</th>
				<th>status</th>
			</tr>
		</thead>
			<tbody>
END;
						$start = strtotime("+8 hours",$stringDay); //start with 8:00
						$i = 1;
						$idpatient = $_SESSION['idpatient'];
						while($i<18)
						{
							$i = $i+1;
							$status = CheckVisit($iddoctor, $start, $connection);
							if($status==true)
							{
								echo "<tr><td>".date('H:i',$start).'</td><td><a href="visitSummary.php?doctor='.$iddoctor.'&patient='.$idpatient.'&time='.$start.'">wolny</a></td></tr>';
								$start = strtotime('+30 minutes',$start);
							}
							else
							{
								echo "<tr><td>".date('H:i',$start).'</td><td><span style="color:red;">zajęty</span></td></tr>';
								$start = strtotime('+30 minutes',$start);
							}
							
						}	
						
echo<<<END
			</tbody>
		</table>

END;

                        echo '<a id="link" href="#"></a>';
							
					}
					else
					{
						echo '<span style="color: red; font-size: 14px;">nieprawidłowa data, wybierz inny termin </span> <br>' ;
					}
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