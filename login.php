<?php
session_start();
ob_start();
require_once 'includes/conf.php';
require_once 'includes/functions.php';
if($_SESSION['logged'] == true)
{
 header("Location: ./?p=map");
 exit;
}
?>
<!DOCTYPE html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name='keywords' content="nacepeni batki igra, игра с фитнес, димитър папазов нацепени батки игра, играта нацепени батки на votkapower, votkapower, fitness nacepeni baDki"/>
	<meta name='description' content="Нацепени Батки - развий своя герой и стани най-яката батка ! Играта е създадена от Димитър Папазов [voTkaPoweR]"/>
	<link href='<?php echo $DEFAULT_URL;?>style/root.css' rel='stylesheet'  type='text/css'>
	<script type='text/javascript' src='<?php echo $DEFAULT_URL;?>javascript/jquery-1.11.1.min.js'></script>
	<title>Вход / Нацепени Батки</title>
</head>
<body>
<style type='text/css'>
.login-demo-image
{
	border:1px solid #ccc;
	padding:2px;
	background:#FFF;
	cursor:pointer;
	outline:0px;
}

.login-demo-image:hover{
	border-color:#2174D3;
	background:#2174D3;
	transition: 1s;
}

#preview-bg{
	background:rgba(0,0,0,.6);
	 margin:0 auto;
	padding:0;
	width:100%;
	height:100%;
	position:fixed;
	 display:none;
	  left:0;
	  overflow:auto;
 top:0;
  z-index:998;
}
#preview{
 width:700px;
 height:500px;
 padding:0;
 
  margin:0 auto;
 margin-top:100px;
 display:none;
}
</style>

<script>
	
		$(".login-demo-image").click(function () {
		
			var src= $(this).attr('src');
			var cont = "<img src='"+ src +"' style='margin:0;padding:0;width:100%;height:100%;'/>";
			//var w = window;
			
			$("#preview").html(cont);
			$("#preview").fadeIn(500);
			$("#preview-bg").fadeIn(500);
			
			/*var w = window.open("", "testwindow", "width=600, height=400");
			
			
			
			w.document.write("<title>Преглед на демо снимка</title>");
			w.document.write(cont);
			w.focus();*/
			
		});
		var ic = 0;
		$(document).click(function () {
			
			ic++;
			if(ic > 1)
			{
				if($("#preview").css('display') != 'none' ||
					$("#preview-bg").css('display')!= 'none'
				)
				{
					 $("#preview").fadeOut();
					 $("#preview-bg").fadeOut(500);
				}
				ic=0;
			}
		});

</script>
<div id='wrapper' class='login-register-bg' style='position:relative'>
<div style='width:420px;background:transparent;position:absolute;right:0;top:140px;'>
  <h3 style='margin-top:0px;'>Демо снимки:</h3>
	<img src='http://batki.votkapower.eu/images/demo-images/batki-index.png' class='login-demo-image' width='200'/>
	<img src='http://batki.votkapower.eu/images/demo-images/batki-klasaciq.png' class='login-demo-image' width='200'/>
	<img src='http://batki.votkapower.eu/images/demo-images/batki-rabota.png'  class='login-demo-image' width='200'/>
	<img src='http://batki.votkapower.eu/images/demo-images/batki-toto.png' class='login-demo-image' width='200'/>
</div>

	<div class='poup-styled-box'>

		<form method='post'>
		<h2>Вход в системата</h2>
			Потребител: <br/>
			<input name='username' /> <br/> <br/>
			Парола: <br/>
			<input name='password' type='password' /> <br/> <br/>
	
			<button name='login-me' type='submit'>Влизай</button> или <a href='register.php'>се регистрирай</a>.
		</form>
		<br/>
		<?php
		// При валидиран АКАУНТ
		if(isset($_GET['valid']))
		{
			if($_GET['valid'] == 'true')
			{
				echo ok("Готово! Акаунта вече е потвърден и може да се логнеш. Приятна игра, покажи, че си НАЦЕПЕНА БАТКА ! :)");
			}
		}
		// ПРИ ЛОГИН .. 
			if(isset($_POST['login-me']))
			{
				$username = trim(htmlspecialchars($_POST['username']));
				$password = md5(trim(htmlspecialchars($_POST['password'])));
				$time = time();
				
				$check = mysqli_query($conn, "SELECT * FROM `users` WHERE `username`='$username' AND `password`='$password'");
				if(mysqli_num_rows($check) == 0)
				{
				 echo error("Грешно Потребителско име или парола !");
				}
				else if(mysqli_num_rows($check) == 1)
				{
				  $r = mysqli_fetch_assoc($check);
				  if($r['confirmed'] =='false' && strlen($r['confirm-key']) < 20)
				  {
					echo error("Акаунта ти все още не е активиран. Трябва да отидеш на посочения от теб имейл адрес и да следваш стъпките !");
				  }
				  else
					  {
						  $_SESSION['logged']=true;
						  $_SESSION['u']=$r;
						  mysqli_query($conn,"UPDATE `users` SET `timestamp`='$time' WHERE `username`='".$r['username']."' LIMIT 1");
						  header("Location: ./");
						  exit;
					  }
				}
				else
					{
						echo error("К'во направи бе?! К'ви са тия магии, я се успокой малко .. ");
					}
				
			}
		?>
	</div>

</div>
<div id='preview-bg'><div id='preview'></div></div>

</body>
</html>