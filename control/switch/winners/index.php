<div class='box-big'>
<?php
$w = $_GET['w'];
if(!$w || $w == 'list')
{
	include "sub/list.php";
}
if($w == 'view')
{
	include "sub/view.php";
}
if($w == 'delete')
{
	include "sub/delete.php";
}
?>	
</div>
