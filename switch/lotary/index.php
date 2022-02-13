<?php
if ($_SESSION['logged'] != true) {
        header("Location: login.php");
        exit;
    }
// -----------------------[..ЛОТАРИЯ .. ]---------------------------
echo "
 <h3 class='headline'>Лотария</h3>
 
 <div class='info'> 
		Хей, Батка ! Това е рулетката, нещо като колелото на късмета на Къци, само че преправено, тук се иска сериозен ХЪС за да играеш. Тук може да спечелиш до <b>1 500лв</b>, но можеш и да загубиш всичко! <br />
		 <br />
		Ето правилата: <br />
		 - Ако ти се падне <b>777</b> можеш да спечелиш до <b>1 500 лв</b>. <br />
		 - Ако ти се падне <b>666</b> губиш всичките си пари. <br />
		 <br />
		 Можеш да играеш постоянно, но въпроса е: Стиска ли ти да играеш ?
 </div>
 
";

if (isset($_POST['do-lotary'])) {
        $me = $_SESSION['u']['username'];
        $now = time();
        $_1 = rand(1, 10);
        $_2 = rand(1, 10);
        $_3 = rand(1, 10);
        // Вземай по 1 лев от потребителя
        mysqli_query($conn, "UPDATE `users` SET `money`= `money` - 1  WHERE `username`='" . $me . "' LIMIT 1"); //SQL

        if ($_1 == 7 && $_2 == 7 && $_3 == 7) {
                $randMoney = rand(100, 1500);
                mysqli_query($conn, "UPDATE `users` SET `money`= `money` + " . $randMoney . "  WHERE `username`='" . $me . "' LIMIT 1"); //SQL
                mysqli_query($conn, "INSERT INTO `admin-toto-lotary-stats`
			(`username`,`timestamp`,`what`)
			values
			('$me','$now','lotary')
		"); //SQL
                $success = "<div id='success-lotary-msg'>Ооо, ЧЕСТИТОО! Ти печелиш <b>" . number_format($randMoney, 0) . " лв.</b>, да си ги ползваш със здраве !</div>";
            }
        if ($_1 == 6 && $_2 == 6 && $_3 == 6) {
                mysqli_query($conn, "UPDATE `users` SET `money`='0' WHERE `username`='" . $me . "' LIMIT 1"); //SQL
                $success = "<div id='fuck-lotary-msg'>Ооо! Токущо загуби <b>всичките</b> си пари :( Кофти а, пробвай пак ?</div>";
            } else {
                $success = "<div id='tryagain-lotary-msg'>Нищо .. пробвай пак ?</div>";
            }
        echo
            "<script>" .
                "$(function () {" .
                "randomNumbersAnimation();" .
                "});" .
                "
			function randomNumbersAnimation()
			{
				var input = $('#machine-form .slot-machine-input');
				var i = 0;
				var a = 0;
				var ruchka = $('#machine-form #ruchka');
				 ruchka.css('visibility','hidden');
				var interval = setInterval(function (){
					i++;
					if(i==10){ i=0;}
					input.each(function () {
						$(this).val(i);
					});
					a++;
					if(a>=20)
					{
					 input[0].value = '" . $_1 . "';
					}
					if(a>=40)
					{
					 input[1].value = '" . $_2 . "';
					}
					if(a>=60)
					{
					 input[2].value = '" . $_3 . "'; 
					 clearInterval(interval);
					 //console.log('intervala e izchisten i chislata sa " . $_1 . " - " . $_2 . " -  " . $_3 . "');
					 ruchka.css('visibility','visible');
						setTimeout(function () {
							$(\"#success-lotary-msg\").fadeIn();
							$(\"#fuck-lotary-msg\").fadeIn();
							$(\"#tryagain-lotary-msg\").fadeIn();
						}, 300); 
					}
				}, 100);
			}
		" .
                "</script>";
    }

echo
    "<div style='background:#fafafa;padding:20px;padding-top:10px;'>" .
        "<div style='text-align:left;float:left;'>" .
        "<img src='" . $DEFAULT_URL . "images/slot-machine.png' width='128' title='ДЪРПАЙЙ РЪЧКАТАА!!' />" .
        "</div>" .
        "<h2 style='color:green;margin-bottom:5px;'>Давай, ако ти стиска .. </h2>" .
        "<br />" .
        "<div style='font-size:14px;font-family:Verdana;'>" .
        "<form id='machine-form' method='post'>" .
        "<input type='text' name='n' class='slot-machine-input' readonly placeholder='0' value='0' />" .
        "<input type='text' name='n' class='slot-machine-input' readonly placeholder='0' value='0' />" .
        "<input type='text' name='n' class='slot-machine-input' readonly placeholder='0' value='0' />" .
        "<button name='do-lotary' id='ruchka'>РЪЧКАТА</button> " . $success . " " .
        "</form>" .
        "</div>" .
        "<div class='clear'></div>" .
        "</div>";

echo "
 &nbsp; <a href='./?p=map'>&laquo; към картата</a>
";

