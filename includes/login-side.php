<?php
if ($_SESSION['logged'] != true) // ако не е логнат
    {
        ?>
<form method="post">
    Потребител: <br />
    <input type='text' name='username' value=''> <br />
    Парола: <br />
    <input type='password' name='password' value=''> <br />
    <input type='submit' name='login' value='Вход'> или <a href='./?p=register'>се регистрирай</a>, ако нямаш профил.
</form>
<?php  // покажи формата
if (isset($_POST['login'])) // ако е цъкнат бутона за логин
    {
        // вземи входната информация
        $username = trim(htmlspecialchars($_POST['username']));
        $password = md5(trim(htmlspecialchars($_POST['password'])));
        // Провери дали съществува такъв потребител
        $check = mysqli_query($conn, "SELECT * FROM `users` WHERE `username`='$username' AND `password`='$password'");
        if (mysqli_num_rows($check) == 1) // ако ИМА 1 такъв потребител
            {
                // вземи инфото му..
                // вдигни сесия..
                // кажи че е логнат..
                // рефрешни .. 
                $r = mysqli_fetch_assoc($check);
                $_SESSION['u'] = $r;
                $_SESSION['logged'] = true;
                header("Location: ./");
                exit;
            }
    }
} else // инак .. щом е логнат ..
    {

        // ----------------------------------
        $me = $_SESSION['u']; // да не пиша постояяно $_SESSION['u']['НЕЩО СИ .. ']
        // Вземи снимката на героя ми
        $currentLevel = user_info($conn,$me['username'], 'level');
        $getCharImage_q = mysqli_query($conn, "SELECT `image` FROM `charecters` WHERE `level`='" . $currentLevel . "'");
        $char = mysqli_fetch_assoc($getCharImage_q);
        // Вземи града на потребителя
        $getUserTown = mysqli_query($conn, "SELECT `name` FROM `cities` WHERE `id`='" . $me['city'] . "'");
        $myTown = mysqli_fetch_assoc($getUserTown);
        $session_town_name = $myTown['name'];
        // ------ 
        //Работи ли нещо батката ?
        if (is_user_working($conn,$me['username']) == true) {
                // щом работи, покажи какво работи СЕГА
                $GetUserWorkNow_q = mysqli_query($conn, "SELECT * FROM `working` LEFT JOIN `jobs` ON `jobs`.`id` = `working`.`job_id` WHERE `username`='" . $me['username'] . "' ORDER  BY `working`.`end-time` ASC"); // вземи това което В МОМЕНТА РАБОТИ
                $work = mysqli_fetch_assoc($GetUserWorkNow_q); // да

                $getExp = $work['exp-boost']; // покажи какво получава

                $getMoney = number_format($work['money-boost'], 2); // и КОЛКО получава за да знае

                $workName = mb_substr($work['title'], 0, 10, 'utf-8') . ".."; // съкрати заглавието, че няма място

                $working = " <i>" . $workName . "</i> [ +<span style='color:#4C94DB;'>" . $getExp . "</span> exp, +<span style='color:green;'>" . $getMoney . "</span> лв. ] "; // изкарай в този вид
            } else // инак
            {
                $working = "не работиш нищо .."; // щом не работи .. изкарай това ..
            }

        // Сметни колко му остава до следващия левел ?
        $session_current_enegry = user_info($conn,$me['username'], 'energy');
        $session_current_level = user_info($conn,$me['username'], 'level');
        $session_current_exp = user_info($conn,$me['username'], 'exp');
        $_1_lvl_exp = 700; // на всеки левел ..
        // за първи левел:  ((1лвл * 13) 650) ~ 8 000
        //_DEFAULLT_TRUDNOST_ - ot conf.php
        $nextLevelXp = (($session_current_level * $_DEFAULLT_TRUDNOST_) * $_1_lvl_exp);

        if ($session_current_exp  >= $nextLevelXp) {
                mysqli_query($conn, "UPDATE `users` SET `level`= `level` + 1 WHERE `username`='" . $me['username'] . "'");
            }
        // сметката
        $nextLvlProcent = @floor((($session_current_exp  /  $nextLevelXp)) * 100);

        $energy_procentage = @round(($session_current_enegry / 100) * 100);
        $session_current_enegry_text = "";
        if ($energy_procentage == 0) // ако няма енергия
            {
                // сложи потребителя да си почине малко
                // според левела му де ;д 
                if (is_user_resting($conn,$user) != true) // само ако НЕ почива, а енергията му я няма .. 
                    {
                        user_rest($conn,$me['username']); // почини си малко ;д
                    }
                // ----
                // Покажи на потребителя колко време трябва да изчака .. 
                $restingTimeleft =  user_resting_timeleft($conn,$me['username']);
                // --  Ако времето за почивка е свършило, т.е, ако потребителя си е починал
                // --  То, махни го от таблицата с почиващите .. 
                if ($restingTimeleft <= 0) {
                        if (@restup_user($conn,$me['username'])) {
                                refresh(0);
                                echo @restup_user($conn,$me['username']);
                            }
                    }
                // ----
                $session_current_enegry_text = "Нуждаеш се от <b>" . $restingTimeleft . "</b> мин. почивка..";
            } else {
                $session_current_enegry_text = $energy_procentage . "%";
            }

        // Ъпдейтни таймстамп-а на потребителя ..
        $time = time() + (1 * 60);
        mysqli_query($conn, "UPDATE `users` SET `timestamp`='$time' WHERE `username`='" . $_SESSION['u']['username'] . "'");

        if ($me['type'] == 'admin') {
                $ADMIN_HTML_LINK = "<div  class='info-block-element' align='center' > <a href='./control/'><b>Админ панел</b></a> </div>";
            } else {
            $ADMIN_HTML_LINK = "";
        }

        // --------- ПОКАЖИ ВСИЧКО ---------- //
        echo "
			
			<div id='side-logged'>
					<div align='center'>
						<img src='" . $char['image'] . "' width='180' alt='Твоята батка'  title='Твоята батка' />
					</div>
					   <div align='center'>" . $session_town_name . "</div>
					   
					" . $ADMIN_HTML_LINK . "
					   
				<b>Инфо</b>
					<div class='info-block-element'><b>Левел:</b> " . $session_current_level . "
							<div class='progressbar'>
								<div style='width:" . $nextLvlProcent . "%;'></div>
							</div>
					</div>
					<div class='info-block-element'><b>Енергия:</b> " . $session_current_enegry_text . "
							<div class='progressbar'>
								<div style='width:" . $energy_procentage . "%;'></div>
							</div>
					</div>
					<div class='info-block-element'><b>Пари:</b>  " . number_format(user_info($conn,$me['username'], 'money'), 2) . " лв.</div>
					<div class='info-block-element'><b>EXP:</b>   " . $session_current_exp . " </div>
					<div class='info-block-element'><b>Работи:</b>   " . $working . " </div>
			</div>
		";
    }
