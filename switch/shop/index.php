<?php
// Погрижи се за потребител, който не е логнат
if($_SESSION['logged'] != true)
{
	 header("Location: login.php");
	 exit;
}
// -----------------------[..КУПИ ГО !! .. ]---------------------------
if(isset($_GET['pid']))
{
	$product_id = (int)$_GET['pid'];
	$user = $_SESSION['u']['username'];
	$butTheProduct =  buy_product($conn,$user,$product_id);
	 if($butTheProduct == 'ok')
	 {
		refresh(0,"./?p=shop");
	 }
	 else
		{
			echo $butTheProduct;
			refresh(3,'./?p=shop');
		}
	
}
/// ------------ МОИТЕ ПРОДУКТИИ


echo "
 <h3 class='headline'>Купени продукти</h3>
 <table style='margin-left:00px;'>
	 <tr class='main'>
		<td width='1%'>#</td>
		<td width='50%'>Продукт</td>
		<td width='10%'>+ EXP</td>
		<td width='30%'>Време за употреба</td>
	 </tr>
";
$i=0;
$myCurrentLevel = user_info($conn,$_SESSION['u']['username'], 'level');
$myMoney = user_info($conn,$_SESSION['u']['username'], 'money');
$getMyproducts = mysqli_query($conn,"SELECT * FROM `bought_products` 
LEFT JOIN  `shop` 
ON `shop`.`id`=`bought_products`.`product_id`
WHERE  `bought_products`.`username`='".$_SESSION['u']['username']."' AND `shop`.`neaded-lvl` <= $myCurrentLevel 
ORDER BY `bought_products`.`end-time` ASC
") or die(mysql_error());
if(mysqli_num_rows($getMyproducts) == 0){  echo "<tr><td>-</td><td>-</td><td>-</td><td>-</td></tr>";}
while($p = mysqli_fetch_assoc($getMyproducts))
{
$i++;
$days_remaining = round(($p['end-time'] - time()) / 86400);
$days_procentage = 100 - (($days_remaining /$p['days-for-use']) * 100) ;
	echo "<tr>
		<td>".$i."</td>
		<td>".$p['title']."</td>
		<td>+ ".$p['exp-boost']." exp</td>
		<td>".$p['days-for-use']." (Остават ".$days_remaining." дена) дни.
			<div class='progressbar'>
				<div style='width:".$days_procentage."%;'></div>
			</div>
		</td>
	</tr>";
}

Echo "</table>";



// -----------------------[..МАГАЗИНА .. ]---------------------------
echo "
 <h3 class='headline'>Магазин</h3>
 <table style='margin-left:00px;'>
	 <tr class='main'>
		<td width='1%'>#</td>
		<td width='50%'>Продукт</td>
		<td width='10%'>+ EXP</td>
		<td width='10%'>Цена</td>
		<td width='10%'>Време за употреба</td>
		<td width='10%'>Опции</td>
	 </tr>
";
$i=0;
$myCurrentLevel = user_info($conn,$_SESSION['u']['username'], 'level');
$myMoney = user_info($conn,$_SESSION['u']['username'], 'money');
$getAllproducts = mysqli_query($conn,"SELECT * FROM `shop` WHERE `neaded-lvl` <= $myCurrentLevel  ORDER BY `exp-boost`,`price` DESC");
while($e = mysqli_fetch_assoc($getAllproducts))
{
$i++;
if($myMoney >= $e['price'])
{
$showBuy = "<a href='./?p=shop&pid=".$e['id']."'>Купи !</a>";
}
else
	{
		$showBuy = "Още <b>".($e['price'] - $myMoney )."</b> лв.";
	}

	echo "<tr>
		<td>".$i."</td>
		<td>".$e['title']."</td>
		<td>+ ".$e['exp-boost']." exp</td>
		<td> ".$e['price']." лв</td>
		<td>".$e['days-for-use']." дни.</td>
		<td>".$showBuy ."</td>
	</tr>";
}

Echo "</table>
 &nbsp; <a href='./?p=map'>&laquo; към картата</a>
";