<?php
session_start();
ob_start();
require_once 'includes/conf.php';
require_once 'includes/functions.php';

if($_SESSION['logged'] != true)
{
	header("Location: ./login.php");
	exit;
}


/*
//	Визитации - VISITS
*/
// запиши то каде идва потребителя, само ако не е от този домейн де .. ;д 
$referer = isset($_SERVER) ? $_SERVER['HTTP_REFERER'] : '';
if( strlen($referer) > 10)
{
	record_referer($conn,$referer);
} 
// ------------------


//+++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Глобални фунцкии за изпълнение
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 1. Изчисване на всички РАБОТИ които са приключени
	clear_finished_work($conn);
// 2. Изчисване на всички ПОЧИНАЛИ потребители..
    //removeRestingUsers(); -> има друга фукция дето го прави това .. 
// 3. Изчисване на всички консумирани продукти..
    remove_finished_products($conn);
// 4. Изчисване на всички преключени тренировки..
    remove_finished_trainings($conn);
// 4. Изчисване на всички изминали ПОДАРЪЦ..
    clear_gift_ended($conn);

	
// ГЕНЕРИРАЙ TITLE PREFIX
$title_prefix = '';
$p = isset($_GET['p']) ? $_GET['p'] : '';
if($p ==  'map') {
	 $title_prefix = 'Карта / ';
}
else if($p ==  'users') {
	 $title_prefix = 'Батки / ';
}
else if($p ==  'ranklist') {
	 $title_prefix = 'Само най-яките батки / ';
}
else if($p ==  'gifts') {
	 $title_prefix = 'Подаръци / ';
}
else if($p ==  'fitness') {
	 $title_prefix = 'Фитнес / ';
}
else if($p ==  'streetfitness') {
	 $title_prefix = 'Лостове / ';
}
else if($p ==  'shop') {
	 $title_prefix = 'Магазин / ';
}
else if($p ==  'toto') {
	 $title_prefix = 'ТОТО 6/49 / ';
}
else if($p ==  'lotary') {
	 $title_prefix = 'Лотария / ';
}
else if($p ==  'work') {
	 $title_prefix = 'Работа / ';
}
else if($p ==  'settings') {
	 $title_prefix = 'Настройки / ';
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name='keywords' content="nacepeni batki igra, игра с фитнес, димитър папазов нацепени батки игра, играта нацепени батки на votkapower, votkapower, fitness nacepeni baDki"/>
	<meta name='description' content="Нацепени Батки - развий своя герой и стани най-яката батка ! Играта е създадена от Димитър Папазов [voTkaPoweR]"/>
	<link href='<?php echo $DEFAULT_URL;?>style/root.css' rel='stylesheet'  type='text/css'>
	<title><?php echo $title_prefix; ?>Нацепени Батки</title>
	<script type='text/javascript' src='<?php echo $DEFAULT_URL;?>javascript/jquery-1.11.1.min.js'></script>
	<script type='text/javascript'>
	 $(function () {
			refresh(1);
			// toto
				$(".blank-toto-number > input[type=checkbox].check").each(function () {
					$(this).on("click", function (){
						
						 var checked = $(this).is(":checked");
						 if(checked != true )
						 {
							$(this).parent().css({
								"color":'#666666',
								"background":"#FFF"
							});
							
						 }
						 else
							{
							 // checked !!!
								$(this).parent().css({
									"color":'#FFFFF2',
									"background":"#99CCFF"
								});
							// колко да избере още ?
							var total = 6;
							 var selected = $(".blank-toto-number > input[type=checkbox].check:checked").length;
							 var lefted = total - selected;
							 if(lefted < 0)
							 {
								lefted  =  lefted * (-1);
								$("#to-select-numbers-left").html("Трябва да премахнеш " + lefted +" числа ..");
								$("#do-toto").css("display","none");
							 }
							 else if(lefted == 0)
							 {
								$("#to-select-numbers-left").html("Супер! Вече може да си провериш късмета  :)");
								$("#do-toto").fadeIn();
							 }
							 else 
								{
									$("#to-select-numbers-left").html("Трябва да избереш още " + lefted +" числа ..");
									$("#do-toto").css("display","none");
								}
							 
							}
					});
				});
			// end toto
	 });
		function refresh(on_mins)
		{
			var mins = ((on_mins * 1000) * 10);
			var url = document.URL.split("?p=");
			if(url[1] == "map" || url[1] == "gifts" || url[1] == "work" ||  url[1] == "shop" ||   url[1] == "fitness"  )
			{
				setTimeout(function () {
					document.location='';
				}, mins);
			}
		}
		
	</script>
</head>
<body>
 

<div id='top-part'>
	<h1>Нацепени Батки</h1> 
	<div id='login'>
		<?php include "includes/login.php";  ?>
	</div>
	<div class='clear'></div>
</div>

<div id='wrapper'>
	<div id='menu'>
		<ul>
			<?php include "includes/menu.php"; ?>
		</ul>
		<div class='clear'></div>
	</div>
	<div id='left-col'>
		<?php
			// Динамични страници .. 
			 $page = isset($_GET['p']) ? trim(htmlspecialchars($_GET['p'])) : false; // страницата ?
			 $page_inc = "switch/".$page."/index.php"; // пътя до нея ?
			 if(file_exists($page_inc)) // има ли таква ?
			 {
				include $page_inc; // щом има - покажи я
			 }
			 else // иначе ..
				{
					if(!$page) // провери дали има нещо зададено в УРЛ
					{
					 include "switch/index/index.php"; // ако няма значи е иНДЕКСА
					}
					else // иначе .. значи е ГРЕШЕН УРЛ
						{
						 include "switch/error/index.php"; // изкарай грешка
						}
				}
				
			// Край на динамичните страници
		?>
	</div>
	<div id='right-col'>
		<div class='panel'>
			<div class='title'>Потребителски панел</div>
			<div class='content'>
				<?php include "includes/login-side.php"; ?>
			</div>
		</div>
		<div class='panel'>
			<div class='title'>Статистика</div>
			<div class='content'>
				<?php include "includes/stats.php"; ?>
			</div>
		</div>
	</div>
	<div class='clear'></div>
</div>
<div class='clear'></div>
<br/>
<div id='footer'>Код и дизайн: <a href='https://votkapower.eu/' title='Посети сайта ми :)'>Димитър Папазов [voTkaPoweR]</a></div>
<br/>
<br/>

</body>
</html>