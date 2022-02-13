<?php
$_username = $_GET['u'];
$q = mysqli_query($conn, "SELECT * FROM `users` WHERE `username`='$_username'");
$r = mysqli_fetch_assoc($q);
?>
<b>Редактирай потребител <span style='color:darkred;'><?php echo $r['display-name'];?>[<?php echo $r['username'];?>]</span></b>
<br/>
<br/>
<style type='text/css'>
form input{ padding:3px; width:300px; float:right;}
form select { padding:3px; float:right;}
form label{display:block; overflow:hidden; margin:5px;padding:5px;background:#fcfcfc;}
form label:hover{background:#EEEEEE;}
</style>
<form method='post'>
	<label>Потребителско име <input name='username' value='<?php echo $r['username'];?>'/></label>
	<label>Име за показване <input name='display-name' value='<?php echo $r['display-name'];?>'/></label>
	<label>Имейл <input name='email' value='<?php echo $r['email'];?>'/></label> 
	<label>Пол 
			<select name='gener'>
				<option value='1' <?php if($r['gener'] == '1'){echo 'selected';}?>>Момче</option>
				<option value='2'<?php if($r['gener'] == '2'){echo 'selected';}?>>Момиче</option>
			</select>
	</label>
 	<label>Град 
			<select name='city'>
				<?php
					$wq = mysqli_query($conn, "SELECT * FROM `cities` ORDER BY `name` ASC");
					while($c = mysqli_fetch_assoc($wq))
					{
						if($r['city'] == $c['id']){$sel= 'selected';}else{$sel='';}
						echo "<option value='".$c['id']."' ".$sel." >".$c['name']."</option>";
					}
				?>
			</select>
	</label> 
	<label>Левел <input name='level' value='<?php echo $r['level'];?>'/></label> 
	<label>Пари <input name='money' value='<?php echo $r['money'];?>'/></label> 
	<label>Еxp <input name='exp' value='<?php echo $r['exp'];?>'/></label> 
	<label>Енергия 	 <input name='energy' value='<?php echo $r['energy'];?>'/></label> 
	<label>Ключ 	 <input name='confirm-key' value='<?php echo $r['confirm-key'];?>'/></label> 
	<label>Потвърден
		<select name='confirmed'>
				<option value='true' <?php if($r['confirmed'] =='true'){echo 'selected';}?>>ДА</option>
				<option value='false' <?php if($r['confirmed'] =='false'){echo 'selected';}?>>все още не ..</option>
			</select>
	</label> 
	<button name='add-user' type='submit'>Редактирай потребителя</button>
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
 if($q == 1)
 {
	 // username 	password 	money 	exp 	charecter 	level 	display-name 	city 	energy 	gener 	confirmed 	reg_date 	timestamp 	confirm-key
	 mysqli_query($conn, "UPDATE `users` SET
	
			`money`='$money',
			`exp`='$exp',
			`level`='$level',
			`display-name`='$display_name',
			`city`='$city',
			`energy`='$energy',
			`gener`='$gener',
			`confirmed`='$confirmed',
			`reg_date`='$time',
			`timestamp`='$time',
			`confirm-key`='$confirm_key'
	
	 WHERE 
	  `username`='$username'
	") or die(mysql_error());
	 echo ok("Потребителя е успешно редактран !");
	 refresh(0);
 }
 else
	{
		 echo error("Няма такъв потребител :/");
	}
 
	
}
?>