<form method='post' style='float:right;'>
	<input type='text' name='search-post' style='width:300px;' placeholder='заглавие'> <button type='submit' name='do-filter-post'>Търси</button>
</form>
			<b>Поща <?php if($_GET['type'] == 'bugs'){ echo "- Бъгове";}?></b>
			<table style='width:740px;'>
				<tr>
					<td width='1%'>#</td>
					<td>Тема</td>
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
				$_type = $_GET['type'];
				if(!$_type){ $_type = "msg";}
					$slq_f = mysqli_query($conn, "SELECT *	FROM `admin-post` WHERE `type`= '$_type' ".$where." ORDER BY `timestamp`,`new` DESC ");
					while($r = mysqli_fetch_array($slq_f ))
					{
					$i++;
					if($i%2==0){ $color = '#fff'; }else{$color='#eee';}
					if($r['new']=='true'){ $new = '<span class="new-badge">НОВО</span>';}else{$new='';}
						echo '<tr style="background:'.$color.';">
							<td>'.$i.'</td>
							<td>'.$new.'<b>'.$r['subj'].'</b> <br/><small>('.$r['from'].')</small></td>
							<td>'.$r['type'].'</td>
							<td>'.date("d M Y H:i",$r['timestamp']).'</td>
							<td><a href="./?m=post&w=view&id='.$r["id"].'" style="color:darkgreen;">Преглед</a> </td>
							</tr>';
					}
				?>
			</table>