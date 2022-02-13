<?php
session_start();
require_once '../includes/conf.php';
require_once '../includes/functions.php';
if($_SESSION['u']['username'] != 'voTkaPoweR' || $_SESSION['u']['type'] != 'admin')
{
 header("Location: /");
 exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<link href='<?php echo $DEFAULT_URL;?>/control/style/root.css' rel='stylesheet'  type='text/css'>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script type='text/javascript' src='<?php echo $DEFAULT_URL;?>javascript/jquery-1.11.1.min.js'></script>
	<script type='text/javascript'>
		$(function () {
			// refresh .. 
			var time = 5*2000;
			setTimeout(function () {
				var url = window.location.href.split("?m=");
				url = url[1];
				if(url == "index" || !url)
				{
					 window.location= '';
				}
				
			}, time);
		
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
		<?php
			include "includes/user-info.php";
		?>
		<div class='clear'></div>
	</div>
	<div id='search_p'>
		<?php include "includes/mini-search.php";?>
	</div>
	<div id='main-menu'>
		<?php
			include "includes/main-menu.php";
		?>
	</div>
</div>

<div id='right-col'>
	<div id='header'>
		<?php include "includes/header-shortcuts.php"; ?>
		<div class='clear'></div>
	</div>
	<?php
		$page = $_GET['m'];
		$file = "switch/".$page."/index.php";
		if(file_exists($file))
		{
			include $file;
		}
		else
			{
			  include "switch/index/index.php";
			}
	?>
</div>
<div class='clear'></div>

</body>
</html>