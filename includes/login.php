<?php
if($_SESSION['logged'] != true)
{
	echo "Инфо: Ако нямаш профил се регистрирай, отнема само 15 сек.";
}
else
	{
	  $my_displayName = trim(htmlspecialchars(user_info($conn, $_SESSION['u']['username'],'display-name')));
	  echo "<div id='logged-user-pannel'>
		Здравей, <b>".$my_displayName."</b> <a href='./?p=settings'>Настройки</a> <a href='./logout.php' class='logout'>Изход</a>
	  </div>";
	}