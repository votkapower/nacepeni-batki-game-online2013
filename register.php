<?php
session_start();
ob_start();
require_once 'includes/conf.php';
require_once 'includes/functions.php';
if($_SESSION['logged'] == true)
{
 header("Location: ./");
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
	<title>Регистрация / Нацепени Батки</title>
</head>
<body>
<div id='wrapper' class='login-register-bg'>
	<div class='poup-styled-box'>

		<form method='post'>
		<h2>Регистрация</h2>
			Потребител: <br/>
			<input name='username' /> <br/> <br/>
			Парола: <br/>
			<input name='password' type='password' /> <br/> <br/>
			Имейл: <br/>
			<input name='email' /> <br/> <br/>
			Име за показване: <br/>
			<input name='displayName' /> <br/> <br/>
			
			Избери пол: <br/>
			<label  style='background:#FFF;padding:5px;'><input type='radio' style='width:20px;' name='gener' value='1' /> Момче </label>
			<label  style='background:#FFF;padding:5px;'><input type='radio' style='width:20px;' name='gener' value='2' /> Момиче </label>
			<br/> <br/>
			
			
			Избери град: <br/>
			<select name='town'>
				<?php
					$q = mysqli_query($conn,"SELECT * FROM `cities` ORDER BY `name` ASC");
					while($r = mysqli_fetch_assoc($q))
					{
						echo "<option value='".$r['id']."'>".$r['name']."</option>";
					}
				?>
			</select>
			<br/> <br/>
			
			<button name='regi-me' type='submit'>Регистрация</button>  или си <a href='login.php'>влез в профила</a>.
		</form>
		<br/>
		<?php
			if(isset($_POST['regi-me']))
			{
				$username = trim(htmlspecialchars($_POST['username']));
				$password = (trim(htmlspecialchars($_POST['password'])));
				$email = trim(htmlspecialchars($_POST['email']));
				$displayName = trim(htmlspecialchars($_POST['displayName']));
				$gener = trim(htmlspecialchars($_POST['gener']));
				$city_id = trim(htmlspecialchars($_POST['town']));
				
				$check =mysqli_query($conn,"SELECT * FROM `users` WHERE `username`='$username'");
				if(!preg_match("/^[a-z0-9-_]+$/i", $username) || strlen($username) < 3)
				{
					echo error("Потребителското име може да съдържа само латински букви, числа, тире и долна чета (<b>a-z, 0-9, - и _</b>) !");
				}
				else if(mysqli_num_rows($check) >= 1)
				{
					echo error("Потребителското име вече е заето, избери си друго !");
				}
				else if(strlen($password) < 3)
				{
					echo error("Паролата не трябва да е толкова кратка !");
				}
				else if(!preg_match("/[а-яА-Я0-9a-zA-Z-_]+/iu", $displayName))
				{
					echo error("Името за показване може да съдържа само: <b>a-z, 0-9, - и _</b> !");
				}
				else if((int)$gener > 2)
				{
					echo error("Не пипай сорс кода бе пръдльо .. !");
				}
				else
					{
						$time = time();
						mysqli_query($conn,"INSERT INTO `users` 
						(`username`,`password`,`money`,`exp`,`level`,`display-name`,`city`,`energy`,`confirmed`,`reg_date`,`timestamp`,`gener`,`email`)
						VALUES
						('$username','".md5($password)."','0','0','1','$displayName','$city_id','100','false','$time','$time','$gener','$email')
						");
							// set cookie .. 
									 $randomHash = md5($username.'-valid');
									 $_24 = time() + (3*86400); 
									 setcookie("hvld-k", $randomHash, $_24,"/");
									 
							 // set email .. 
								$from = 'Нацепени Батки[БЕТА]';
								$header = "From: $from\n".'content-type: text/html; charset=utf-8' . "\r\n";
								$url  = $DEFAULT_CEO_URL;
								$msg = trim("
 Здравей, <br/>
	 Днес ти си направи регистрация в нашата онлайн игра \"Нацепени Батки\". За да може да си влезеш в профила трябва да потвърдиш регистрацията си. Потвръждението става като посетиш линка отдоло и това е.  <br/>
	 <br/>
	 Линк: <a style='color:green;' href='".$url."confirm-acc?acc=".base64_encode($username)."&key=".$randomHash."'>".$url."confirm-acc?acc=".base64_encode($username)."&key=".$randomHash."</a>
	 <br/>
	 <br/>
		<small>
			<i style='color:darkred;'>Потвърждението трябва да стане от компютъра, от който си се регистирал!</i>
		</small>
	 <br/>
	 <br/>
	 Приятен ден/вечер,<br/>
	 Димитър Папазов [voTkaPoweR] - автор на играта.<br/>
								");
								 mail($email, "Потвръждение на регистрацията.",$msg, $header);
					 
						echo ok("ГОТОВО! Пратихме ти писмо за активация на <b>".$email."</b>. Изпълни инструкциите и тогава ще можеш да си влезеш в профила ! ");
					}
			}
		?>
	</div>
</div>

</body>
</html>