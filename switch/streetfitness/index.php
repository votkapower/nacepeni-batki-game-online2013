<?php
// Погрижи се за потребител, който не е логнат
if($_SESSION['logged'] != true)
{
	 header("Location: login.php");
	 exit;
}

if(user_info($conn,$_SESSION['u']['username'],'level') < 10)
{
	 header("Location: ./?p=map");
	 exit;
}

// -----------------------[..ТРЕНИРАЙЙЙ !! .. ]---------------------------
if(isset($_GET['sfid']))
{
	$finess_ured_id = (int)$_GET['sfid'];
	$user = $_SESSION['u']['username'];
	$training_msg =  do_training($conn,$user,$finess_ured_id,'streetfitness');
	
	 if($training_msg == 'ok')
	 {
		refresh(0,"./?p=streetfitness");
	 }
	 else
		{
			echo $training_msg;
			#refresh(3,'./?p=streetfitness');
		}
	
}
/// ------------ КАКВО БЛУСКАМ СЕГА ?


echo "
 <h3 class='headline'>Сега тренирам ..</h3>
 <table style='margin-left:00px;'>
	 <tr class='main'>
		<td width='1%'>#</td>
		<td width='50%'>Уред / упражнение</td>
		<td width='10%'>+ EXP</td>
		<td width='30%'>Време за трениране</td>
	 </tr>
";
$i=0;
$myCurrentLevel = user_info($conn,$_SESSION['u']['username'], 'level');
$myMoney = user_info($conn,$_SESSION['u']['username'], 'money');
$getMyproducts = mysqli_query($conn,"SELECT * FROM `training` 
 LEFT JOIN  `streetfitness` 
	ON `streetfitness`.`id`=`training`.`fitness_id`
	WHERE   `training`.`type`='streetfitness' AND `training`.`username`='".$_SESSION['u']['username']."'  AND `streetfitness`.`need-lvl` <= $myCurrentLevel 

ORDER BY `training`.`end-time` ASC
") or die(mysql_error());
if(mysqli_num_rows($getMyproducts) == 0){  echo "<tr><td>-</td><td>-</td><td>-</td><td>-</td></tr>";}
while($p = mysqli_fetch_assoc($getMyproducts))
{
$i++;
$min_remaining = round(($p['end-time'] - time()) / 60);
$min_procentage = 100 - (($min_remaining /$p['work-in-minutes']) * 100) ;
	echo "<tr>
		<td>".$i."</td>
		<td>".$p['title']."</td>
		<td>+ ".$p['exp-boost']." exp</td>
		<td>".$p['work-in-minutes']." (Остават ".$min_remaining." мин.) мин.
			<div class='progressbar'>
				<div style='width:".$min_procentage."%;'></div>
			</div>
		</td>
	</tr>";
}

Echo "</table>";



// -----------------------[..МАГАЗИНА .. ]---------------------------
echo "
 <h3 class='headline'>Стрийт Фитнес / Лостове</h3>
 <table style='margin-left:00px;'>
	 <tr class='main'>
		<td width='1%'>#</td>
		<td width='50%'>Упражнение</td>
		<td width='10%'>+ EXP</td>
		<td width='10%'>Цена</td>
		<td width='10%'>Време за трениране</td>
		<td width='10%'>Опции</td>
	 </tr>
";
$i=0;
$myCurrentLevel = user_info($conn,$_SESSION['u']['username'], 'level');
$myMoney = user_info($conn,$_SESSION['u']['username'], 'money');
$myEnergy = user_info($conn,$_SESSION['u']['username'], 'energy');
$getAllproducts = mysqli_query($conn,"SELECT * FROM `streetfitness`  WHERE `need-lvl` <= $myCurrentLevel  ORDER BY `exp-boost`,`price` DESC");
while($s = mysqli_fetch_assoc($getAllproducts))
{
$i++;

if(is_user_treining($conn,$_SESSION['u']['username'], $s['id'],'streetfitness')) // ako sega go trenira ?
{
	$showBuy = "сега го тренираш";
}
else if($myEnergy < $s['need-energy'] )
{
	$showBuy = "нямаш енергия";
}
else  if($myMoney >= $s['price'])
{
$showBuy = "<a href='./?p=streetfitness&sfid=".$s['id']."' title='Силата е в тебе ! Както и парите !'>Давай !</a>";
}
else
	{
		$showBuy = "Още <b>".($s['price'] - $myMoney )."</b> лв.";
	}

	echo "<tr>
		<td>".$i."</td>
		<td>".$s['title']."</td>
		<td>+ ".$s['exp-boost']." exp</td>
		<td> ".$s['price']." лв</td>
		<td>".$s['work-in-minutes']." мин.</td>
		<td>".$showBuy ."</td>
	</tr>";
}

Echo "</table>
 &nbsp; <a href='./?p=map'>&laquo; към картата</a>
";