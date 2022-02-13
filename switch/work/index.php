<?php
// Погрижи се за потребител, който не е логнат
if($_SESSION['logged'] != true)
{
	 header("Location: login.php");
	 exit;
}

//--------------- [ РАБОТИ !!]---------

if(isset($_GET['jid']))
{
$job_id = (int)$_GET['jid'];
	$work = set_work_for_user($conn,$_SESSION['u']['username'],$job_id);
	if($work == 'ok')
	{
		header("Location: ./?p=work");
		exit;
	}
	else
		{
			 echo $work;
			 refresh(2,'./?p=work');
		}
		
}

// -----------------[Покажи какво работя в момента]---------------------------
echo "
 <h3 class='headline'>Сега работиш ..</h3>
 <table style='margin-left:00px;'>
	 <tr class='main'>
		<td width='1%'>#</td>
		<td width='50%'>Работа</td>
		<td width='10%'>+ EXP</td>
		<td width='10%'>+ ЛВ</td>
		<td width='30%'>Изпълнена</td>
	 </tr>
";
$i=0;
$work_done_procent=0;
$getCurrenJobs = mysqli_query($conn,"SELECT * FROM `working` 
LEFT JOIN `jobs` 
ON `jobs`.`id`=`working`.`job_id`
WHERE `working`.`username`='".$_SESSION['u']['username']."' ORDER BY `working`.`start-time` ASC");
if(mysqli_num_rows( $getCurrenJobs) == 0){  echo "<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>";}
while($wor = mysqli_fetch_assoc($getCurrenJobs))
{
$minuter_remaining = round(($wor['end-time'] - time()) / 60 );
$work_done_procent =  100 - (( $minuter_remaining / $wor['work-time-minutes'] ) * 100);
$i++;
	echo "<tr>
		<td>".$i."</td>
		<td>".$wor['title']."</td>
		<td>+ ".$wor['exp-boost']." exp</td>
		<td>+ ".$wor['money-boost']." лв</td>
		<td>
		".$wor['work-time-minutes']." мин. (".$minuter_remaining." мин остават)
			<div class='progressbar'>
				<div style='width:".$work_done_procent."%;'></div>
			</div>
		</td>
	</tr>";
}
Echo "</table>
 <br/>
";


// ----------------------[Покажи КАКВО МОГА да работя]----------------------
echo "
 <h3 class='headline'>Работа</h3>
 <table style='margin-left:00px;'>
	 <tr class='main'>
		<td width='1%'>#</td>
		<td width='50%'>Работа</td>
		<td width='10%'>+ EXP</td>
		<td width='10%'>+ ЛВ</td>
		<td width='10%'>Време</td>
		<td width='10%'>Опции</td>
	 </tr>
";
$i=0;
$myCurrentLevel = user_info($conn,$_SESSION['u']['username'], 'level');
$myEnergy = user_info($conn,$_SESSION['u']['username'], 'energy');
$getAllJobs = mysqli_query($conn,"SELECT * FROM `jobs` WHERE `neaded-lvl` <= $myCurrentLevel  ORDER BY `money-boost` DESC");
while($job = mysqli_fetch_assoc($getAllJobs))
{
$i++;

if(is_user_working($conn,$_SESSION['u']['username'], $job['id'])) // ako go raboti sega ?
{
	$showDoJob = "сега го работиш";
}
else if($myEnergy < $job['need-energy'] )
{
	$showDoJob = "нямаш енергия";
}
else
	{
		$showDoJob= "<a href='./?p=work&jid=".$job['id']."'>Работи !</a>";
	}

	echo "<tr>
		<td>".$i."</td>
		<td>".$job['title']."</td>
		<td>+ ".$job['exp-boost']." exp</td>
		<td>+ ".$job['money-boost']." лв</td>
		<td>".$job['work-time-minutes']." мин.</td>
		<td>".$showDoJob."</td>
	</tr>";
}

Echo "</table>
 &nbsp; <a href='./?p=map'>&laquo; към картата</a>
";