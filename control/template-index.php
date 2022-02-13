<?php
session_start();
require_once '../includes/conf.php';
require_once '../includes/functions.php';
?>
<!DOCTYPE html>
<html>
<head>
	<link href='./style/root.css' rel='stylesheet'  type='text/css'>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script type='text/javascript' src='../javascript/jquery-1.11.1.min.js'></script>
	<script type='text/javascript'>
		$(function () {
			$("#left-col, #right-col").css("height", window.innerHeight);
			
			$("#main-menu > ul > li ").click(function () {
				var cls = $(this).attr('class');
				var child = $(this).find('ul.sub-menu').length;
				if(child == 1)
				{
					if(cls == 'current')
					{
						$(this).find('ul.sub-menu').slideUp();
						$(this).attr('class','');
					}
					if(cls != 'current')
					{
						$(this).find('ul.sub-menu').slideDown();
						$(this).attr('class','current');
					}
				}
			});
			
		});
	</script>
	<title>Админ панел</title>
</head>
<body>

<div id='left-col'>
	<div id='welcome-p'>
		<div class='img' style="background:url('http://votkapower.eu/uploads/images/slider/forum-avatar.jpg');"></div>
		<div class='right'>
				Добре дошъл,
				<b>Димитър Папапзов</b>
		</div>
		<div class='clear'></div>
	</div>
	<div id='search_p'>
		<input type='text' name='search' placeholder='Име, място, ключова дума' /><button>Тест</button>
	</div>
	<div id='main-menu'>
		<ul>
			<li>Табло за управление</li>
			<li>
				Компонент
					<ul class='sub-menu'>
						<li>sad</li>
						<li>sdadad</li>
						<li>sda411</li>
					</ul>
			</li>
		</ul>
	</div>
</div>

<div id='right-col'>
	<div id='header'>
		<div class='p'>
			Поща
			<div class='badge'>3</div>
		</div>
		
		<div class='p'>
			Оналйн потребители
			<div class='badge'>45</div>
		</div>
		
		<div class='p'>
			Лотария
			<div class='badge'>4</div>
		</div>
		
		<div class='p'>
			Тото 6/49
			<div class='badge'>16</div>
		</div>
		
		
		<div class='clear'></div>
	</div>
	<div class='box'>
		<img src='../images/admin07.png' width='64' />
		<div class='text'><b>16</b> общо потребителя</div>
	</div>
	
	<div class='box-small'>
		<img src='../images/bar-chart634.png' width='64' />
		<div class='text'><b>16</b>  общо посещения</div>
	</div>
	
	<div class='box-small'>
	<img src='../images/favorites58.png' width='64' />
		<div class='text'><b>votkapower</b> Най-много посещения</div>
	</div>
	
	<div class='box-big'>
		<div class='title'>Игра:</div>
		<div style='float:left;padding-right:5px;border-right:1px solid #ccc;margin-right:10px;'>
			<b>Най-добрите играчи:</b>
			<table style='max-width:400px;'>
				<tr>
					<td>#</td>
					<td>Потребител</td>
					<td>Левел</td>
					<td>EXP</td>
					<td>Пари</td>
					<td>Град</td>
				</tr>
				<tr>
					<td>1</td>
					<td>Потребител</td>
					<td>Левел</td>
					<td>EXP</td>
					<td>Пари</td>
					<td>Град</td>
				</tr>
			</table>
			</div>
	
		<div style='float:right;border:0px solid #ccc;'>
			<b>Най-добрите играчи от най-добрия град:</b>
			<table style='max-width:400px;'>
				<tr>
					<td>#</td>
					<td>Потребител</td>
					<td>Левел</td>
					<td>EXP</td>
					<td>Пари</td>
					<td>Град</td>
				</tr>
				<tr>
					<td>1</td>
					<td>Потребител</td>
					<td>Левел</td>
					<td>EXP</td>
					<td>Пари</td>
					<td>Град -</td>
				</tr>
			</table>
			</div>
	
	</div>
	
	<div class='box'>
		<img src='../images/Error6451231.png' width='64'/>
		<div class='text'><b>0</b> съобщения за бъг</div>
	</div>
	<div class='box'>
		<img src='../images/Error6451231.png' width='64'/>
		<div class='text'><b>0</b> опита за хак</div>
	</div>
	<div class='box'>
		<img src='../images/Error6451231.png' width='64'/>
		<div class='text'><b>0</b> попадания в 404 стр.</div>
	</div>
	
	<div class='clear'></div>
</div>
<div class='clear'></div>

</body>
</html>