<form method='post' style='float:right;'>
	<input type='text' name='search-users' style='width:300px;' placeholder='Потребителско име, exp или левел..'> <button type='submit' name='do-filter-users'>Търси</button>
</form>
			<b>Онлайн Потребители</b>
			<table style='width:740px;'>
				<tr>
					<td>#</td>
					<td>Потребител</td>
					<td>Левел</td>
					<td>EXP</td>
					<td>Град</td>
					<td>Статус</td>
				</tr>
				<?php
				// SEARCH
					if(isset($_POST['do-filter-users']))
					{
						$word = strtolower(trim(htmlspecialchars($_POST['search-users'])));
						if(strlen($word)>0)
						{
							$where = '
							AND (`username` LIKE "%'.$word.'%" OR
							`display-name` LIKE "%'.$word.'%" OR
							`level` LIKE "%'.$word.'%" OR
							`exp` LIKE "%'.$word.'%" )
							';
						}
						else{ $where = "";}
					//	echo $where;
					}
					
				// ---- 
				$i=0;
				$_2min = time() - (2*60);
					$slq_f = mysqli_query($conn, "SELECT *	FROM `users` LEFT JOIN `cities` ON `cities`.`id` = `users`.`city` WHERE `timestamp` > ".$_2min." ".$where." ORDER BY `users`.`exp` DESC ");
					while($r = mysqli_fetch_array($slq_f ))
					{
					$i++;
					if($i%2==0){ $color = '#fff'; }else{$color='#eee';}
						echo '<tr style="background:'.$color.';">
							<td>'.$i.'</td>
							<td><b>'.$r['username'].'</b> <br/><small>('.$r['display-name'].')</small></td>
							<td>'.$r['level'].'</td>
							<td>'.$r['exp'].'</td>
							<td>'.$r['name'].'</td>
							<td><b style="color:darkgreen;">онлайн</b></td>
							</tr>';
					}
				?>
			</table>