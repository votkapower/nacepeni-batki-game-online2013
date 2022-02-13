<?php
// Бройки .. 

// ------------------------------------
$total_users_count = _cnt("*","`users`");
// ------------------------------------
$total_visits_count =  _get(array(
	'count',
	'SUM(count)'
),'`visits-stats`');
$total_visits_count = $total_visits_count[0];
// ------------------------------------
$most_visits_from_website = _get(array(
	'domain'
),'`visits-stats`','`count` >= 1 ORDER BY `count` DESC LIMIT 1');
$most_visits_from_website = $most_visits_from_website['domain'];
?>	
	<div class='box'>
		<img src='<?php echo $DEFAULT_URL;?>images/admin07.png' width='64' />
		<div class='text'><b><?php echo $total_users_count;?></b> общо потребителя</div>
	</div>
	
	<div class='box-small'>
		<img src='<?php echo $DEFAULT_URL;?>images/bar-chart634.png' width='64' />
		<div class='text'><b><?php echo $total_visits_count;?></b>  общо посещения</div>
	</div>
	
	<div class='box-small'>
	<img src='<?php echo $DEFAULT_URL;?>images/favorites58.png' width='64' />
		<div class='text'><b  style='font-size:12px;'><?php echo $most_visits_from_website;?></b> Най-много посещ. от</div>
	</div>
	
	<div class='box-big'>
		<div style='float:left;padding-right:10px;border-right:1px solid #ccc;margin-right:0px;'>
			<b>Най-добрите играчи:</b>
			<table style='max-width:395px;width:355px;'>
				<tr>
					<td>#</td>
					<td>Потребител</td>
					<td>Левел</td>
					<td>EXP</td>
					<td>Град</td>
				</tr>
				<?php
				$i=0;
					$slq_f = mysqli_query($conn, "SELECT *	FROM `users` LEFT JOIN `cities` ON `cities`.`id` = `users`.`city` ORDER BY `users`.`exp` DESC LIMIT 5");
					while($r = mysqli_fetch_array($slq_f ))
					{
					$i++;
					if($i%2==0){ $color = '#fff'; }else{$color='#eee';}
						echo '<tr style="background:'.$color.';">
							<td>'.$i.'</td>
							<td>'.$r['display-name'].'</td>
							<td>'.$r['level'].'</td>
							<td>'.$r['exp'].'</td>
							<td>'.$r['name'].'</td>
							</tr>';
					}
				?>
			</table>
			</div>
	
		<div style='float:right;border:0px solid #ccc;'>
			<b>Топ 5 играча от топ 5 града:</b>
			<table style='max-width:395px;width:360px;'>
				<tr>
					<td>#</td>
					<td>Потребител</td>
					<td>Левел</td>
					<td>EXP</td>
					<td>Град</td>
				</tr>
				<?php
					$i=0;
					$slq_q = mysqli_query($conn, "SELECT * FROM `users` LEFT JOIN `cities` ON `cities`.`id`=`users`.`city` GROUP BY `users`.`city` ORDER BY `users`.`exp`  DESC LIMIT 5");
					while($r = mysqli_fetch_array($slq_q ))
					{
					$i++;
					if($i%2==0){ $color = '#fff'; }else{$color='#eee';}
						echo '<tr style="background:'.$color.';">
							<td>'.$i.'</td>
							<td>'.$r['display-name'].'</td>
							<td>'.$r['level'].'</td>
							<td>'.$r['exp'].'</td>
							<td>'.$r['name'].'</td>
							</tr>';
					}
				?>
			</table>
			</div>
	
	</div>
<?php
// Бройки 2.. 

// ------------------------------------
// Колко докладвания за бъг има ?
// ------------------------------------
$total_report_bugs_count = _cnt("*","`admin-post`",'`type`="report"');


// ------------------------------------
// Колко докладвания за бъг има ?
// ------------------------------------
$content = file_get_contents($DEFAULT_URL."reports/404.txt");
$total_404errors_count = count(explode("###",$content))-1;

// ------------------------------------

?>	
	<div class='box'>
		<img src='<?php echo $DEFAULT_URL;?>images/Error6451231.png' width='64'/>
		<div class='text'><b><?php echo $total_report_bugs_count; ?></b> съобщения за бъг</div>
	</div>
	<div class='box'>
		<img src='<?php echo $DEFAULT_URL;?>images/Error6451231.png' width='64'/>
		<div class='text'><b><?php echo $total_report_bugs_count; ?></b> опита за хак</div>
	</div>
	
	<div class='box' style='cursor:pointer;' onclick="document.location='http://batki.votkapower.eu/control/?m=reports'">
		<img src='<?php echo $DEFAULT_URL;?>images/Error6451231.png' width='64'/>
		<div class='text'><b><?php echo $total_404errors_count; ?></b> попадания в 404 стр.</div>
	</div>
	
	<div class='clear'></div>