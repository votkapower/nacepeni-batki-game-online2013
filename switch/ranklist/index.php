<?php
// -----------------------[..ОБЩА Ранклиста .. ]---------------------------
echo "
 <h3 class='headline' style='color:#A03939;'>Най-здравите батки на всички времена</h3>
 <table style='margin-left:00px;'>
	 <tr class='main'>
		<td width='1%'>#</td>
		<td width='30%'>Батка</td>
		<td width='10%'>Левел</td>
		<td width='10%'>Пари</td>
		<td width='10%'>Еxp</td>
		<td width='20%'>Град</td>
	 </tr>
";
$i=0;
// глобална ранк листа -> ТОП 3
$top3_g_q = mysqli_query($conn,"SELECT 
`cities`.`name` as `town`,
`display-name`,`level`,`city`,`exp`,`money` FROM `users`
LEFT JOIN `cities` ON `cities`.id=`users`.`city`
ORDER BY `users`.`exp` DESC
LIMIT 3");
while($e = mysqli_fetch_assoc($top3_g_q))
{
$i++;
// сложи звездичка на първия ..
if($i == 1){
	$star = "<img src='".$DEFAULT_URL."images/star.png' width='16' title='НАЙ-ЛУДИЯ ОТ ".$e['town']."'>";
}
else if($i == 2 || $i==3)
	{
		$star = "<img src='".$DEFAULT_URL."images/star_off.png' width='16' title='НАЙ-ЛУДИЯ ОТ ".$e['town']."'>";
	}
	else
		{
			$star ="";
		}
		
		
	echo "<tr>
		<td>".$i."</td>
		<td style='text-align:left;'><b>".$star." ".$e['display-name']."</b></td>
		<td>".$e['level']." левел</td>
		<td>".$e['money']." лв.</td>
		<td>".$e['exp']." ехр</td>
		<td>".$e['town']."</td>
	</tr>";
}
Echo "</table>";

// -----------------------[..ЛИКАЛНА Ранклиста .. ]---------------------------

if($_SESSION['logged'] == true)
{

		// Вземи града на потребителя
		   $getUserTown = mysqli_query($conn,"SELECT * FROM `cities` WHERE `id`='".$_SESSION['u']['city']."'");
		   $myTown = mysqli_fetch_assoc($getUserTown);
		   $session_town_name= $myTown['name'];
		   $session_town_id= $myTown['id'];
	echo "
	<br/>
	<br/>
	 <h3 class='headline'  style='color:#1F529B;'>Най-здравите батки в ".$session_town_name."</h3>
	 <table style='margin-left:00px;'>
		 <tr class='main'>
			<td width='1%'>#</td>
			<td width='30%'>Батка</td>
			<td width='10%'>Левел</td>
			<td width='10%'>Пари</td>
			<td width='10%'>Еxp</td>
			<td width='20%'>Град</td>
		 </tr>
	";

	$i=0;
	// ЛОКАЛНА ЛИСТА -> ТОП 3
	$top3_l_q = mysqli_query($conn,"SELECT 
	`cities`.`name` as `town`,
	`display-name`,`level`,`city`,`exp`,`money` FROM `users`
	LEFT JOIN `cities` ON `cities`.id=`users`.`city`
	WHERE `users`.`city`='$session_town_id'
	ORDER BY `users`.`exp` DESC
	LIMIT 40");
	while($e = mysqli_fetch_assoc($top3_l_q))
	{
	$i++;
	// сложи звездичка на първия 
	if($i == 1){
		$star = "<img src='".$DEFAULT_URL."images/star.png' width='16' title='НАЙ-ЛУДИЯ ОТ ".$e['town']."'>";
	}
	else if($i == 2 || $i==3)
		{
			$star = "<img src='".$DEFAULT_URL."images/star_off.png' width='16' title='НАЙ-ЛУДИЯ ОТ ".$e['town']."'>";
		}
		else
			{
				$star ="";
			}
		
		
		echo "<tr>
			<td>".$i."</td>
			<td style='text-align:left;'><b>".$star." ".$e['display-name']."</b></td>
			<td>".$e['level']." левел</td>
			<td>".$e['money']." лв.</td>
			<td>".$e['exp']." ехр</td>
			<td>".$e['town']."</td>
		</tr>";
	}


	Echo "</table>";

}



 echo"
 &nbsp; <a href='./?p=map'>&laquo; към картата</a>
";