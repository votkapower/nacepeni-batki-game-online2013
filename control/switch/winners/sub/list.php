<form method='post' style='float:right;'>
	<input type='text' name='search-post' style='width:300px;' placeholder='заглавие'> <button type='submit' name='do-filter-post'>Търси</button>
</form>
			<b>Печеливши <?php if($_GET['t'] == 'toto'){ echo "от Тото 6/49";}else{echo " от Лотарията ";}?></b>
			<table style='width:740px;'>
				<tr>
					<td width='1%'>#</td>
					<td>Печеливш</td>
					<td>Тип</td>
					<td>Кога</td>
					<td width='15%'>Опции</td>
				</tr>
				<?php
				// SEARCH
					if(isset($_POST['do-filter-post']))
					{
						$word = strtolower(trim(htmlspecialchars($_POST['search-post'])));
						if(strlen($word)>0)
						{
							$where = 'AND (
							`subj` LIKE "%'.$word.'%" OR
							`from` LIKE "%'.$word.'%" 
							)
							';
						}
						else{ $where = "";}
					//	echo $where;
					}
					
				// ---- 
				$i=0;
				$_type = $_GET['t'];
					$slq_f = mysqli_query($conn,"SELECT *	FROM `admin-toto-lotary-stats` WHERE `what`='".$_type."' ".$where." ORDER BY `timestamp` DESC ") or die(mysql_error());
					while($r = mysqli_fetch_array($slq_f ))
					{
					$i++;
					if($i%2==0){ $color = '#fff'; }else{$color='#eee';}
					
						echo '<tr style="background:'.$color.';">
							<td>'.$i.'</td>
							<td><b>'.$r['username'].'</b> <br/><small>('.$r['chisla'].')</small></td>
							<td>'.$r['what'].'</td>
							<td>'.date("d M Y H:i",$r['timestamp']).'</td>
							<td><a href="./?m=winners&w=view&id='.$r["id"].'" style="color:darkgreen;">Преглед</a> </td>
							</tr>';
					}
				?>
			</table>