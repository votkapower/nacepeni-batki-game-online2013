<b>Добави потребител</b>
<br/>
<br/>
<style type='text/css'>
form input{ padding:3px; width:300px; float:right;}
form select { padding:3px; float:right;}
form label{display:block; overflow:hidden; margin:5px;padding:5px;background:#fcfcfc;}
form label:hover{background:#EEEEEE;}
</style>
<form method='post'>
	<label>Потребителско име <input name='username' /></label>
	<label>Име за показване <input name='display-name' /></label>
	<label>Парола <input name='password' /></label> 
	<label>Имейл <input name='email' /></label> 
	<label>Пол 
			<select name='gener'>
				<option value='1'>Момче</option>
				<option value='2'>Момиче</option>
			</select>
	</label>
 	<label>Град 
			<select name='city'>
				<?php
					$wq = mysqli_query($conn, "SELECT * FROM `cities` ORDER BY `name` ASC");
					while($r = mysqli_fetch_assoc($wq))
					{
						echo "<option value='".$r['id']."'>".$r['name']."</option>";
					}
				?>
			</select>
	</label> 
	<label>Левел <input name='level' value='1'/></label> 
	<label>Пари <input name='money' value='0'/></label> 
	<label>Еxp <input name='exp' value='0'/></label> 
	<label>Енергия 	 <input name='energy' value='100'/></label> 
	<label>Ключ 	 <input name='confirm-key' value='<?php echo md5(time().'-valid');?>'/></label> 
	<label>Потвърден
		<select name='confirmed'>
				<option value='true'>ДА</option>
				<option value='false'>все още не ..</option>
			</select>
	</label> 
	<button name='add-user' type='submit'>Добави потребителя</button>
</form>
<?php
if(isset($_POST['add-user']))
{
 $username = trim(htmlspecialchars($_POST['username']));
 $display_name = trim(htmlspecialchars($_POST['display-name']));
 $password = md5(trim(htmlspecialchars($_POST['password'])));
 $email = trim(htmlspecialchars($_POST['email']));
 $gener = trim(htmlspecialchars($_POST['gener']));
 $level = trim(htmlspecialchars($_POST['level']));
 $exp = trim(htmlspecialchars($_POST['exp']));
 $energy = trim(htmlspecialchars($_POST['energy']));
 $confirm_key = trim(htmlspecialchars($_POST['confirm-key']));
 $confirmed = trim(htmlspecialchars($_POST['confirmed']));
 $money = trim(htmlspecialchars($_POST['money']));
 $city = trim(htmlspecialchars($_POST['city']));
 $time = time();
 
 $q = mysqli_num_rows(mysqli_query($conn, "SELECT `username`  FROM `users` WHERE `username`='$username'"));
 if($q == 0)
 {
	 // username 	password 	money 	exp 	charecter 	level 	display-name 	city 	energy 	gener 	confirmed 	reg_date 	timestamp 	confirm-key
	 mysqli_query($conn, "INSERT INTO `users` 
	 (`username`,`password`,`money`,`exp`,`level`,`display-name`,`city`,`energy`,`gener`,`confirmed`,`reg_date`,`timestamp`,`confirm-key`)
	 VALUES
	 ('$username','$password','$money','$exp','$level','$display_name','$city','$energy','$gener','$confirmed','$time','$time','$confirm_key')");
	 echo ok("Потребителя е успешно добавен !");
 }
 else
	{
		 echo error("Вече има такъв потребител :/");
	}
 
	
} 