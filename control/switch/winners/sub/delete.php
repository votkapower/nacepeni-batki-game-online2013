<?php
$_id = $_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM `admin-toto-lotary-stats` WHERE `id`='$_id'");
$r = mysqli_fetch_assoc($q);
?>
<b>Изтрий Победител <span style='color:darkred;'><?php echo $r['username'];?>[<?php echo $r['chisla'];?>]</span></b>
<br/>
<br/>

<form method='post'>
	<div style='display:block;float:right;padding:5px;background:#e8e8e8;'>
		<input type='hidden' name='post' value='<?php echo $r['id'];?>'>
		<button name='delete-msg' type='submit' Style='color:darkgreen;'><b>Да, Изтрий го !</b></button>
		или <a style='color:darkred;' href='./?m=winners&w=list'>се откажи</a>
	</div>
</form>
<?php
if(isset($_POST['delete-msg']))
{
$post = $_POST['post'];
	if($post)
	{
		 mysqli_query($conn, "DELETE FROM  `admin-toto-lotary-stats` WHERE `id`='$post' LIMIT 1") or die(mysql_error());
		 echo ok("Съобщението е  изтриито успешно !");
		 refresh(2,'./?m=winners&w=list');
	}

}
?>