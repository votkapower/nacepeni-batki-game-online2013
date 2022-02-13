<?php
$_id = $_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM `admin-post` WHERE `id`='$_id'");
$r = mysqli_fetch_assoc($q);
?>
<b>Изтрий съобщение <span style='color:darkred;'><?php echo $r['subj'];?>[<?php echo $r['from'];?>]</span></b>
<br/>
<br/>

<form method='post'>
	<div style='display:block;float:right;padding:5px;background:#e8e8e8;'>
		<input type='hidden' name='post' value='<?php echo $r['id'];?>'>
		<button name='delete-msg' type='submit' Style='color:darkgreen;'><b>Да, Изтрий го !</b></button>
		или <a style='color:darkred;' href='./?m=post&w=list'>се откажи</a>
	</div>
</form>
<?php
if(isset($_POST['delete-msg']))
{
$post = $_POST['post'];
	if($post)
	{
		 mysqli_query($conn, "DELETE FROM  `admin-post` WHERE `id`='$post' LIMIT 1") or die(mysql_error());
		 echo ok("Съобщението е  изтриито успешно !");
		 refresh(2,'./?m=post&w=list');
	}

}
?>