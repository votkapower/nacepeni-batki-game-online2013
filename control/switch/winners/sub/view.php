<?php
$_id = $_GET['id'];
	$q = mysqli_query($conn, "SELECT * FROM `admin-post` WHERE `id`='$_id'");
	$r = mysqli_fetch_assoc($q);
	mysqli_query($conn, "UPDATE `admin-post` SET `new`='fasle' WHERE `id`='$_id'");
?>
<b>Преглед ан съобщение</b>
<br/>
<br/>
<style type='text/css'>
div.b{ overflow:hidden; font-size:16px; padding:5px; font-weight:normal;}
div.b div{ width:70px; float:left; font-weight:bold;color:#207EDB;}
</style>
<Div class='b'><div>Тема:</div> <?php echo $r['subj'];?></div>
<Div class='b'><div>От:</div> <?php echo $r['from'];?></div>
<Div class='b'><div>Кога:</div> <?php echo date("d M Y - H:iч.",$r['timestamp']);?></div>
<Div class='b'><div>Съобщение:</div> <br/><br/> <?php echo nl2br($r['text']);?></div>
<div style='margin:10px;'><a href='./?m=post'>&laquo; Назад</a></div>