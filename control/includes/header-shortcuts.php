<?php
// ---------------------------------
// Онлайн потребители
// ---------------------------------
$_1min = time() - (1*60);
$total_users_online_count = _cnt($conn,"*","`users`","`timestamp` > ".$_1min."");

// ---------------------------------
// Последно спечелили от лотарията ..
// ---------------------------------
$total_users_lotary_won_count = _cnt($conn,"*","`admin-toto-lotary-stats`","`what`='lotary'");

// ---------------------------------
// Последно спечелили от ТОТО 6/49 ..
// ---------------------------------
$total_users_toto_won_count = _cnt($conn,"*","`admin-toto-lotary-stats`","`what`='toto'");

// ---------------------------------
// Последно спечелили от Пощата..
// ---------------------------------
$total_new_post_count = _cnt($conn,"*","`admin-post`","`type`='msg' AND `new`='true'");
?>
	<a href='./?m=post'>
		<div class='p'>
			Поща
			<div class='badge'><?php echo $total_new_post_count;?></div>
		</div>
	</a>
		
	<a href='./?m=users&w=online'>
		<div class='p'>
			Оналйн потребители
			<div class='badge'><?php echo $total_users_online_count;?></div>
		</div>
	</a>
	
	<a href='./?m=winners&w=list&t=lotary'>	
		<div class='p'>
			Лотария
			<div class='badge'><?php echo $total_users_lotary_won_count;?></div>
		</div>
	</a>
		
	<a href='./?m=winners&w=list&t=toto'>
		<div class='p'>
			Тото 6/49
			<div class='badge'><?php echo $total_users_toto_won_count;?></div>
		</div>
	</a>
		
		