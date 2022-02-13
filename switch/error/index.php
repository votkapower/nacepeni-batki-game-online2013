<?php
$text=
"
В ".date('d M Y - H:i')."ч. Сесията <b>".$_SESSION['u']['username']."</b> попадна в 404 страницата.
Опитваше да влезе \"<b>".$_SERVER['REQUEST_URI']."</b>\";
###

";
	$file = "reports/404.txt";
	$fo = fopen($file, "a+");
	fwrite($fo,$text);
	fclose($fo);
?>
<h2>ГРЕШКА 404</h2>