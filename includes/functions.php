<?php
 // функции от  (ВотКаПоуъР) Димитър Папазов

// еррор мсг 
function error($msg)
{
  return "<div class='error-msg'>" . $msg . "</div>";
}

function warn($msg)
{
  return "<div class='warn-msg'>" . $msg . "</div>";
}
function ok($msg)
{
  return "<div class='ok-msg'>" . $msg . "</div>";
}
// МЕТА РЕФРЕШШ
function refresh($time, $url = "")
{
  echo "<meta http-equiv=\"refresh\" content=\"" . $time . ";url=" . $url . "\">";
}

// бРой ..
function _cnt($conn, $what, $from, $where = '')
{
    
  if (strlen($where) > 3) {
    $where  = "WHERE " . $where;
  }
  $sql = mysqli_query($conn, "SELECT " . $what . " FROM " . $from . " " . $where);
  $n  = mysqli_num_rows($sql);
  return $n;
}
// Вземи..
function _get($conn,$what = array(), $from, $where = '', $orderBy = '')
{
  if (strlen($where) > 3) {
    $where  = "WHERE " . $where;
  }

  $select = implode(", ", $what);
  $sql = mysqli_query($conn, "SELECT " . $select . " FROM " . $from . " " . $where . $orderBy . " ");
  $n  = mysqli_fetch_array($sql);
  return $n;
}



function is_user_treining($conn,$user, $what_id = null, $sfORf = 'fitness') // streetfitnes OR fitness
{
  $where = '';
  if ($what_id != null) {
    if ($sfORf == 'fitness') {
      $where = " AND `fitness_id`='" . $what_id . "' AND `type`='fitness' ";
    } else {
      $where = " AND `fitness_id`='" . $what_id . "'  AND `type`='streetfitness'";
    }
  }
  $trenira = mysqli_query($conn, "SELECT * FROM `training` WHERE `username` = '$user' " . $where);
  $trenira_c = mysqli_num_rows($trenira);
  if ($trenira_c == 0) {
    return false;
  } else {
    return true;
  }
}

// Провери дали потребителя РАБОТИ В МОМЕНТА НЕЩО ?
function is_user_working($conn,$user, $what_id = null)
{
  $where = '';
  if ($what_id != null) {
    $where = " AND `job_id`='" . $what_id . "' ";
  }
  $checkUsername = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `users` WHERE `username`='$user'"));
  if ($checkUsername != 1) {
    $return = "user-not-exits";
  }
  $GetWorking = mysqli_query($conn, "SELECT * FROM `working` WHERE `username`='$user' " . $where);
  if (mysqli_num_rows($GetWorking) >= 1) {
    $return = true;
  } else {
    $return = false;
  }
  return  $return;
}

// провери дали потребителя съществува
function user_exists($conn,$user)
{
  $checkUsername = mysqli_query($conn, "SELECT `id` FROM `users` WHERE `username`='$user'");
  if (mysqli_num_rows($checkUsername) != 1) {
    $return = false;
  } else {
    $return = true;
  }
  return $return;
}

//Вземи инфото на потребителя, а не от сесията
function user_info($conn,$user, $what)
{
  $checkUsername = mysqli_query($conn, "SELECT * FROM `users` WHERE `username`='$user'");
  if (mysqli_num_rows($checkUsername) != 1) {
    $return = error("Такъв потребител не съществува, не се прави на хакер.");
  } else {
    $user = mysqli_fetch_assoc($checkUsername);
    $return = $user[$what];
  }
  return $return;
}

// изчисти свършените работи ..
function clear_finished_work($conn)
{
  $time = time(); // сега ?
  $worked_q = mysqli_query($conn, "SELECT * FROM `working` LEFT JOIN `jobs` ON `jobs`.`id`=`working`.`job_id`  WHERE `end-time` <= $time"); // вземи изтичащите работи + информцията за всяка от ДЖОБС
  if (mysqli_num_rows($worked_q) > 0) // ако ИМА изтичащи
    {
      while ($jobz = mysqli_fetch_assoc($worked_q)) // за всеки резултат
        {
          // 	exp-boost,	money-boost
          $exp = $jobz['exp-boost']; // колко EXP получава
          $money = $jobz['money-boost']; // колко пари получава
          $user = $jobz['username']; // коЙ получава ттова 
          mysqli_query($conn, "UPDATE `users` SET `exp`= `exp` + " . $exp . ", `money`= `money` + " . $money . " WHERE `username`='" . $user . "'") or die(mysqli_error($conn)); // добави му го
        }
      mysqli_query($conn, "DELETE FROM `working` WHERE `end-time` < $time"); // изтрий всичко
    }
}

// ДАЙ РАБОТА НА ПОТРЕБИТЕля
function set_work_for_user($conn, $user, $job_id)
{
  if (user_exists($conn,$user) == true) {
    $Mylvl = user_info($conn,$user, 'level');
    $myEnergy = user_info($conn,$user, 'energy');
    $checkJob = mysqli_query($conn, "SELECT * FROM `jobs` WHERE `id`='$job_id' AND `neaded-lvl` <= $Mylvl ");
    if (mysqli_num_rows($checkJob) == 1) {
      $qCheck  =  mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `working` WHERE `job_id`='$job_id' AND `username`='$user'"));
      if ($qCheck == 0) {
        $time = time(); // СЕГА ?
        $job = mysqli_fetch_assoc($checkJob); // изкарай инфото за работата
        $job_original_work_time = $job['work-time-minutes']; // да си знаем
        if ($myEnergy >= $job['need-energy'] && $Mylvl >= $job['neaded-lvl'] && is_user_resting($conn,$user) != true) {
          //-------------------------------------------------
          $getLastUserJob = mysqli_query($conn, "SELECT `start-time`,`end-time` FROM  `working`  WHERE `username`='$user' AND `end-time` < $time ORDER BY `end-time` DESC LIMIT 1"); // изкарай ПОСЛЕДНАТА РАБОТА НА ПОТРЕБИТЕЛЯ
          if (mysqli_num_rows($getLastUserJob) >= 1) // ако сега върши някоя работа
            {
              $lastJob = mysqli_fetch_assoc($getLastUserJob); // да .. 
              $start_time = $lastJob['end-time']; // КРАЯ И ЩЕ Е НАЧАЛОТО НА СЛЕДВАЩАТА РАБОТА КОЯТО СЕГА ВКАРВАМ
            } else // иначе
            {
              $start_time =  time(); // щом не работи нищо запични СЕГА
            }
          $end_time = ($start_time + ($job_original_work_time * 60)); // кога трябва да приключи ?
          if ($job['need-energy'] <= $myEnergy) {
            //-----------------------------------------
            mysqli_query($conn, "INSERT INTO `working` 
							(`job_id`,`username`,`start-time`,`end-time`)
							VALUES
							('$job_id','$user','$start_time','$end_time')
							") or die(mysqli_error($conn));
            // NEW -> вземи енергията на потребитля
            $nrg = $job['need-energy'];
            mysqli_query($conn, "UPDATE `users` SET `energy` = `energy` - $nrg WHERE `username`='$user'");
            return 'ok';
          }
        } else {
          return warn("Човече, измурен си, нуждаеш се от почивка за да възвърнеш енергията си !");
        }
      } else {
        return error("Стига препира ! Не може да работиш една работа 2 пъти !");
      }
    } else {
      return error("Няма такава работа, не се прави на хакер :)");
    }
  } else {
    return error("Няма такъв  потребител, не се прави на хакер :)");
  }
}

// Купии продуктаа
function buy_product($conn, $user, $product_id)
{
  // провери дали има такъв потребител
  if (user_exists($conn,$user) == true) {
    $myLevel = user_info($conn, $user, 'level');
    // провери дали съществува такъв пордукт
    $OrgnProductCheck_q = mysqli_query($conn, "SELECT * FROM `shop` WHERE `id`='$product_id' AND `neaded-lvl` <= $myLevel") or die(mysqli_error($conn));
    if (mysqli_num_rows($OrgnProductCheck_q) == 1) // ako ima .. 
      {
        // провери дали не е купен вече тоя продукт ?
        $chekBought = mysqli_query($conn, "SELECT * FROM `bought_products` WHERE `username`='$user' AND `product_id`='$product_id'");
        if (mysqli_num_rows($chekBought) == 0) // ako NE E
          {
            $product = mysqli_fetch_assoc($OrgnProductCheck_q); // вземи инфото му
            $price = $product['price']; // цена ?
            $product_for_days = $product['days-for-use']; // дни ?
            // вземи парите
            mysqli_query($conn, "UPDATE `users` SET `money` = `money` - $price WHERE `username`='$user'");

            // Вземи последния поръчак продукт
            $LastAOrdq = mysqli_query($conn, "SELECT * FROM `bought_products` WHERE `username`='$user' AND `product_id`='$product_id' ORDER BY `end-time` DESC LIMIT 1");
            if (mysqli_num_rows($LastAOrdq) == 1) // ako ima поне 1 работа
              {
                $lastProduct = mysqli_fetch_assoc($LastAOrdq); // вземия 
                $start_time = $lastProduct['end-time']; // кога ще свърши тя ?
              } else // ако не ..
              {
                $start_time  = time(); ///запични сега
              }
            $end_time = ($start_time + ($product_for_days * 86400)); // кога трябва да приключи ?;

            //добави в КУПЕНИ ПРОДУКТИ
            mysqli_query($conn, "INSERT INTO `bought_products` 
			(`username`,`product_id`,`start-time`,`end-time`)
			VALUES
			('$user','$product_id','$start_time','$end_time')
			") or die(mysqli_error($conn));

            return 'ok';
          } else {
          return error("Вече си купил този продукт! Като ти свърши продукта, може пак да си го купиш :)");
        }
      } else // инак .. 
      {
        return error("Няма такъв продукт, не се прави на хакер ;) ");
      }
  } else // Иначи .. 
    {
      return error("Няма такъв потребител, не се прави на хакер ;) ");
    }
}
// Когато ти свърши енергията, почини си и тогава пак ;д
function user_rest($conn,$user)
{
  if (user_exists($conn,$user) == true) {
    // провери дали има такъв запис вече .. 
    $now = time();
    $q = mysqli_query($conn, "SELECT * FROM `resting` WHERE `username`='$user' AND `timestamp-end` <= $now");
    if (mysqli_num_rows($q) == 0) {
      // Колко останала енергия имаш ?
      $user_energy = user_info($conn, $user, "energy");
      $user_level = user_info($conn, $user, "level");
      if ($user_energy <= 4) {
        // таблица за почивки: resting -> username, time-in-min, timestamp-end
        $restTime = (($user_level * 3) - $user_level); // колко минути почивка ?
        $timestamp = (time() + ($restTime * 60)); // конверт в timestamp
        mysqli_query($conn, "INSERT INTO `resting` 
				(`username`, `time-in-min`, `timestamp-end`)
				VALUES
				('$user','$restTime','$timestamp')");
        $re = $restTime;
      } else {
        $re = error("Все още имаш енергия, не си за почивка .. ");
      }
    } else {
      $re =   error("Ти вече си почиваш ..");
    }
  } else {
    $re =   error("Такъв потребител Не съществува.. ");
  }
  return $re;
}

// Провери дали потребителя си почива ..
function is_user_resting($conn,$user)
{
  //Вземи потребителя, който си почива .. 
  // т.е. който Е В ТАБЛИЦАТА, ама и НЕ МУ Е СВЪРШИЛА ПОЧИВКАТА
  $now = time();
  $q = mysqli_query($conn, "SELECT * FROM `resting` WHERE `username`='$user'");
  if (mysqli_num_rows($q) >= 1) {
    $bool = true;
  } else {
    $bool = false;
  }
  return $bool;
}


// Сметни колко време му остава ..
function user_resting_timeleft($conn,$user)
{
  //Вземи потребителя, който си почива .. 
  // т.е. който Е В ТАБЛИЦАТА, ама и НЕ МУ Е СВЪРШИЛА ПОЧИВКАТА
  $now = time();
  $qg = mysqli_query($conn, "SELECT * FROM `resting` WHERE `username`='$user' ") or die(mysqli_error($conn));
  if (mysqli_num_rows($qg) >= 1) {
    $z = mysqli_fetch_assoc($qg);
    $time = $z['timestamp-end'];
    $totalMin = $z['time-in-min'];
    if ($time <= $now) {
      $sec =  floor(($now - $time));
      $min = number_format(($sec / 60), 0);
      $tt = $totalMin - $min;
    } else {
      $tt = 0;
    }
  }
  return $tt;
}

// Когато потребителя е отпочинал, му зареди енергията .. 
function restup_user($conn,$user)
{
  if (user_exists($conn,$user)) {
    if (is_user_resting($conn,$user) == true) {
      if (user_resting_timeleft($conn,$user) <= 0) {
        // изтрий от таблицака с почиващите
        mysqli_query($conn, "DELETE FROM `resting` WHERE `username`='$user' LIMIT 1") or die(mysqli_error($conn));
        // сложи енергията на потребителя на МАХ .. 
        mysqli_query($conn, "UPDATE `users` SET `energy`=100 WHERE `username`='$user' LIMIT 1") or die(mysqli_error($conn));
        return true;
      } else {
        return error("Има още време .. ");
      }
    } else {
      return error("Все още не е свършила почивката .. ");
    }
  } else {
    return error("Няма такъв потребител .. ");
  }
}
// ПРЕМАХНИ ВСИЧКИ КОИТО СА СИ ПОЧИНАЛИ -> НЕ СЕ ПОЛЗВА ЗА СЕГА !
function removeRestingUsers($conn)
{
  $time = time();
  $w = mysqli_query($conn, "SELECT * FROM `resting` WHERE `timestamp-end` < $time") or die(mysqli_error($conn));
  $mysql = mysqli_num_rows($w);
  if ($mysql >= 1) {
    while ($j = mysqli_fetch_assoc($w)) {
      $user = $j['username'];
      $time_then = $j['timestamp-end'];
      if ($time_then < $time) {
        mysqli_query($conn, "DELETE FROM `resting` WHERE `username`='$user' LIMIT 1") or die(mysqli_error($conn));
        mysqli_query($conn, "UPDATE `users` SET `energy`=100 WHERE `username`='$user' LIMIT 1") or die(mysqli_error($conn));
      }
    }
  }
}

// Премахни всички закупени продукти от потребителя, които са изконсомиране вече .. 
function remove_finished_products($conn)
{
  $now = time();
  $qn = mysqli_query($conn, "SELECT * FROM `bought_products` LEFT JOIN `shop` ON `bought_products`.`product_id`=`shop`.`id` ");
  while ($r = mysqli_fetch_assoc($qn)) {
    $ostawashtiDni = floor(($r['end-time'] - $now) / 86400);
    if ($r['end-time'] < $now) {
      //Изкарай да ги видим .. ако искаш де ..
      // echo $r['id']." - ".$ostawashtiDni." <br/>";

      // Ъпдейтни потребителя със това, което предлага продукта ..
      mysqli_query($conn, "UPDATE `users` SET `exp` = `exp` + " . $r['exp-boost'] . " WHERE `username`='" . $r['username'] . "' LIMIT 1");

      // Изтрий от КУПЕНИТЕ ПРОДУКТИ, след като вече си го ъпдейтнал ..
      mysqli_query($conn, "DELETE FROM `bought_products` WHERE `product_id`='" . $r['product_id'] . "' LIMIT 1");
    }
  }
}

//---------------------------------------------
// Тренирай потребителю, тренирай .. 
//---------------------------------------------
// -- 1. Провери дали има такъв потребител
// -- 2. Провери дали потребителя не си почива
// -- 3. Провери дали има нужната енергията
// -- 4. Провери дали има нужните ПАРИ
// -- 5. Провери дали вече НЕ ГО ТРЕНИРА ТОВА .. 
//---------------------------------------------
function do_training($conn,$user, $finess_ured_id, $FITTNESS_ID_TYPE = 'fitness') // fitness/streetfitness
{



  if (user_exists($conn,$user)) // 1
    {
      if (!is_user_resting($conn,$user) == true) // 2
        {
          $u_lvl = user_info($conn,$user, 'level');
          $u_energy = user_info($conn,$user, 'energy');
          $u_money = user_info($conn,$user, 'money');

          $ch = mysqli_query($conn, "SELECT * FROM `training` WHERE `username` = '$user' AND `fitness_id`='$finess_ured_id' AND `type`='$FITTNESS_ID_TYPE'") or die(mysqli_error($conn));
          $ch_n = mysqli_num_rows($ch);
          if ($ch_n == 0) // 5 - ако вече го тренира това .. 
            {
              // Какъв тип е упрражнението което трябва да тренира ?
              if ($FITTNESS_ID_TYPE == 'fitness') {
                $checkTable = 'fitness';
              } else {
                $checkTable = 'streetfitness';
              }
              // провери дали има изовщо такъв запис ?
              $ch1 = mysqli_query($conn, "SELECT * FROM `" . $checkTable . "` WHERE `need-lvl` <= '$u_lvl' AND `id`='$finess_ured_id'") or die(mysqli_error($conn));

              $ch1_n = mysqli_num_rows($ch1);
              if ($ch1_n == 1) // ако съществува такъв запис
                {

                  // вземи парите на потребителя
                  // вземи енергията на потребителя
                  // сложи го да тренира
                  $f = mysqli_fetch_assoc($ch1); // информацията за финес упражниението
                  if ($u_energy >= $f['need-energy']) // има ли достатъчно енергия ?
                    {
                      if ($u_money >= $f['price']) // има ли достатъчно пари ?
                        {
                          // ----
                          // Трябав ми последното упражнение на потребителя
                          $last_q = mysqli_query($conn, "SELECT * FROM `training` WHERE `username`='$user'  ORDER BY `end-time` DESC LIMIT 1");
                          // Сметни кога трябва да започне това, и кога трябва да свърши .. 
                          if (mysqli_num_rows($last_q) >= 1) // ако сега върши някоя работа
                            {
                              $last_tr = mysqli_fetch_assoc($last_q); // да .. 
                              $startTime = $last_tr['end-time']; // КРАЯ И ЩЕ Е НАЧАЛОТО НА СЛЕДВАЩАТА РАБОТА КОЯТО СЕГА ВКАРВАМ
                            } else // иначе
                            {
                              $startTime =  time(); // щом не работи нищо запични СЕГА
                            }

                          //$endTime = ($startTime + ($f['work-in-minutes'] * 60)); // кога трябва да приключи ?
                          $endTime = (time() + ($f['work-in-minutes'] * 60)); // кога трябва да приключи ?

                          mysqli_query($conn, "UPDATE `users` SET `energy` = `energy` - " . $f['need-energy'] . ", `money` = `money` - " . $f['price'] . " WHERE `username`='$user' LIMIT 1");

                          mysqli_query($conn, "INSERT INTO `training` 
							(`username`,`fitness_id`,`start-time`,`end-time`,`type`)
							VALUES
							('$user','$finess_ured_id','$startTime','$endTime','$FITTNESS_ID_TYPE')");
                          $error = 'ok';
                        } else {
                        $error = error("Нямаш достатъчно пари ! Пробвай нещо друго.");
                      }
                    } else {
                    $error = error("Нямаш достатъчно енергия ! Пробвай нещо друго.");
                  }
                } else {
                $error = error("Wut ?! Какво се опитваш да направиш, не ми е ясно нещо. Не виждаш ли че нямащ права/левел за да извършиш това !");
              }
            } else {
            $error = error("Е, изчакай де ! Това вече го тренираш, изчакай да преключиш и пак го тренирай щом искаш .");
          }
        } else {
        $error = error("Не можеш да тренираш, сега си почиваш !");
      }
    } else {
    $error = error("Няма такъв потребител, не се прави на хакер..");
  }
  return $error;
}


// Премахни всички тренировки които са преключили .. 
function remove_finished_trainings($conn)
{
  $now = time();
  $qn = mysqli_query($conn, "SELECT * FROM `training` LEFT JOIN `fitness` ON `training`.`fitness_id`=`fitness`.`id` WHERE  `end-time` < $now  ") or die(mysqli_error($conn));
  while ($r = mysqli_fetch_assoc($qn)) {
    // Ъпдейтни потребителя със това, което предлага продукта ..
    mysqli_query($conn, "UPDATE `users` SET `exp` = `exp` + " . $r['exp-boost'] . " WHERE `username`='" . $r['username'] . "' LIMIT 1") or die(mysqli_error($conn));

    // Изтрий от КУПЕНИТЕ ПРОДУКТИ, след като вече си го ъпдейтнал ..
    mysqli_query($conn, "DELETE FROM `training` WHERE `fitness_id`='" . $r['fitness_id'] . "' LIMIT 1") or die(mysqli_error($conn));
  }
}



// ПОДАРЪЦИТЕЕ ---
function make_gift($conn,$user)
{
  $now = time();
  $return = '';
  $q_gifst = mysqli_query($conn, "SELECT * FROM `gifts-given` WHERE `username`='$user' AND `end-time` > $now ") or die(mysqli_error($conn));
  if (mysqli_num_rows($q_gifst) == 0) {

    // какво може да спечели
    $ginfst_array = array(
      // може по едно нещо да получиш..
      "money",
      "exp",
      "energy",
      // може и комбинирано .. 

      "money&exp",
      "money&energy",

      "exp&money",
      "exp&energy",

      "energy&exp",
      "energy&money",
    );

    // колко са ?
    $gifts_n = count($ginfst_array) - 1;
    $rnd_i = rand(0, $gifts_n);
    $random_gif = $ginfst_array[$rnd_i];

    // от кое колко може да спечели МАКС И МИНИМУМ ..
    $random_money = rand(10, 200);
    $random_exp = rand(100, 300);
    $random_energy = 5 * rand(1, 20);

    // ако има разделител, значи са 2 или повече
    if (preg_match("/\&/", $random_gif)) {
      $chunks = explode("&", $random_gif); // частите
      $sql = "UPDATE `users` SET "; // започване на заявка за НЯКОЛКО работи .. 


      foreach ($chunks as $what) // обходи ги 
        {

          if ($what == "money") {
            $won = $random_money;
            $text = " лв.";
            $sql .= " `money` = `money` + " . $won . " , ";
          }
          if ($what == "exp") {
            $won = $random_exp;
            $text = " ехр";
            $sql .= " `exp` = `exp` + " . $won . " , ";
          }
          if ($what == "energy") {
            $won = $random_energy;
            $text = " енергия";
            // ако това, което ще получи като енергия се добави към неговата
            // и тя НЕ надвишава 100%
            if (user_info($conn,$user, 'energy') + $won <= 100) {
              // продължи със добавянето й!
              $sql .= " `energy` = `energy` + " . $won . " , ";
            } else {
              // иначе не добавяй нищо
              $sql .= '';
            }


            // ако почива и получи подарък енергия
            if (is_user_resting($conn,$user) == true) {
              // изтрий от таблицака с почиващите
              mysqli_query($conn, "DELETE FROM `resting` WHERE `username`='$user' LIMIT 1") or die(mysqli_error($conn));
            }
          }


          $color = '#D90000';
          $return .= " <b style='color:" . $color . ";'>" . $won . " " . $text . "</b> и ";
        }
      $sql = mb_substr($sql, 0, -2, "utf-8");
      $sql .= "WHERE `username`='" . $user . "' ";
      $return = mb_substr($return, 0, -2, "utf-8");

      // echo $sql;
      // Изпълни генерираната заявка и подари подаръка .. 
      mysqli_query($conn, $sql) or die(mysqli_error($conn));
      // А СЕГА ТРЯБВА ДА ИЗЧАКА В ЗАВИСИМИОСТ ДАЛИ Е 1 подарък или 2 .. 
      // тука е за 1 подарък значи трябва да изчака ->>>  4 часа
      $chas = 4;
      $wait = (time() + ($chas * 3600));
      mysqli_query($conn, "INSERT INTO `gifts-given` (`username`,`end-time`)VALUES('" . $user . "', '$wait')") or die(mysqli_error($conn));
    } else // значи е само един подарък
      {
        $what = $random_gif;
        if ($what == "money") {
          $won = $random_money;
          $text = " лв.";
          $sql = "UPDATE `users` SET `money`= `money` + " . $won . " WHERE `username`='" . $user . "'";
        }
        if ($what == "exp") {
          $won = $random_exp;
          $text = " ехр";
          $sql = "UPDATE `users` SET `exp`= `exp` + " . $won . " WHERE `username`='" . $user . "'";
        }
        if ($what == "energy") {
          $won = $random_energy;
          $text = " енергия";

          // ако почива и получи подарък енергия
          if (is_user_resting($conn,$user) == true) {
            // изтрий от таблицака с почиващите
            mysqli_query($conn, "DELETE FROM `resting` WHERE `username`='$user' LIMIT 1") or die(mysqli_error($conn));
          }

          if (user_info($conn,$user, 'energy') + $won <= 100) {
            $sql = "UPDATE `users` SET `energy`= `energy` + " . $won . " WHERE `username`='" . $user . "'";
          } else {
            $sql = '';
          }
        }

        $color = '#00D900';
        $return  = " <b style='color:" . $color . ";'>" . $won . " " . $text . "</b> ";
        // Изпълни генерираната заявка и подари подаръка .. 
        @mysqli_query($conn, $sql) or die(mysqli_error($conn));
        // А СЕГА ТРЯБВА ДА ИЗЧАКА В ЗАВИСИМИОСТ ДАЛИ Е 1 подарък или 2 .. 
        // тука е за 1 подарък значи трябва да изчака  ->>> 2 часа
        $chas = 2;
        $wait = (time() + ($chas * 3600));
        mysqli_query($conn, "INSERT INTO `gifts-given` (`username`,`end-time`)VALUES('" . $user . "', '$wait')") or die(mysqli_error($conn));
        //echo $sql;
      }


    return $return;
  } else {
    return 'error';
  }
}


// Премахни всички подаръци които вееч им е време
function clear_gift_ended($conn)
{
  $sega = time();
  $s = mysqli_query($conn, "SELECT * FROM `gifts-given` WHERE `end-time` < $sega");
  if (mysqli_num_rows($s) >= 1) {
    mysqli_query($conn, "DELETE FROM `gifts-given` WHERE `end-time` < $sega");
  }
}


// изчислява колко ЧАСОВЕ, МИНУТИ, СЕК остават докато нещо сварши .. 
function timer($togava)
{
  $sega = time();
  if ($sega > $togava) {
    $return = 0;
  } else {
    $chasove = floor(($togava - $sega) / 3600);
    $min = floor(($togava - $sega) / 60);
    $sec = floor($togava - $sega);



    if ($chasove >= 1) {
      //if($min >= 59){ $chasove++; }
      $return .= $chasove . " часа и";
    }
    if ($min >= 0) {
      if ($chasove >= 1) {
        $min = $min - ($chasove * 60);
      }
      $return .= " " . $min . " мин. ";
    }

    $return = mb_substr($return, 0, -1, 'utf-8');
  }

  return $return;
}

// Запиши от къде идва потребителя .. 
function record_referer($conn,$referer)
{
  $domein = $_SERVER['HTTP_HOST'];
  if (!strstr($referer, $domein)) {
    $time = time();
    $domain = parse_url($referer);
    $domain  = $domain['host'];
    $count = mysqli_query($conn, "SELECT * FROM `visits-stats` WHERE `domain`='$domain' ");
    if (mysqli_num_rows($count) >= 1) {
      mysqli_query($conn, "UPDATE `visits-stats` SET  `timestamp` = $time,`count` = `count` + 1, `full_url`='$referer' WHERE `domain`='$domain' LIMIT 1");
    } else {
      mysqli_query($conn, "INSERT INTO `visits-stats`
				(`domain`,`full_url`,`timestamp`,`count`)
				VALUES
				('$domain','$referer','$time','0')");
    }
  }
}
