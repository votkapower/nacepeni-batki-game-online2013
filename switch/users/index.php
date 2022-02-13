<?php
// -----------------------[..Потребители.. ]---------------------------
echo "
 <h3 class='headline'>Само батки ..</h3>
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
$users =mysqli_query($conn,"SELECT 
`cities`.`name` as `town`,
`display-name`,`level`,`city`,`exp`,`money` FROM `users`
LEFT JOIN `cities` ON `cities`.id=`users`.`city`
ORDER BY `users`.`id` ASC
");
while($e = mysqli_fetch_assoc($users))
{
$i++;

		
	echo "<tr>
		<td>".$i."</td>
		<td style='text-align:left;'><b>".($star ?: '')." ".$e['display-name']."</b></td>
		<td>".$e['level']." левел</td>
		<td>".$e['money']." лв.</td>
		<td>".$e['exp']." ехр</td>
		<td>".$e['town']."</td>
	</tr>";
}
Echo "</table>";

 echo
"
 &nbsp; <a href='./?p=map'>&laquo; към картата</a>
";