<div class='box-big'>
<?php
$w = $_GET['w'];
if($w == 'list' || !$w)
{
	include "sub/list.php";
}
if($w == 'online')
{
	include "sub/online.php";
}
if($w == 'add')
{
	include "sub/add.php";
}
if($w == 'edit')
{
	include "sub/edit.php";
}
if($w == 'delete')
{
	include "sub/delete.php";
}
?>	
</div>
