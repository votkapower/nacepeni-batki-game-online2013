<?php
$_username = $_GET['u'];
$q = mysqli_query($conn, "SELECT * FROM `users` WHERE `username`='$_username'");
$r = mysqli_fetch_assoc($q);
?>
<b>Изтрий потребител <span style='color:darkred;'><?php echo $r['display-name'];?>[<?php echo $r['username'];?>]</span></b>
<br/>
<br/>

<form method='post'>
	<div style='display:block;float:right;padding:5px;background:#e8e8e8;'>
		<input type='hidden' name='username' value='<?php echo $r['username'];?>'>
		<button name='delete-user' type='submit' Style='color:darkgreen;'><b>Да, Изтрий го !</b></button> или <a style='color:darkred;' href='./?m=users&w=list'>се откажи</a>
	</div>
</form>
<?php
if(isset($_POST['delete-user']))
{
$username = $_POST['username'];
	if($username)
	{
		 mysqli_query($conn, "DELETE FROM  `users` WHERE  `username`='$username' LIMIT 1") or die(mysql_error());
		 echo ok("Потребителя е изтрит успешно !");
		 refresh(2,'./?m=users&w=list');
	}

}
?>