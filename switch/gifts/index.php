<?php
if ($_SESSION['logged'] != true) {
    header("Location: login.php");
    exit;
}

// -----------------------[..ПОДАРЪЦИИИИИИ .. ]---------------------------
echo "
 <h3 class='headline'>Подаръци</h3>
 
 <div class='info'> 
		Понеже си много луда батка, тук ти можеш да спечелиш както пари, така и енергия или exp. Всичко зависи от това какъв ти е късмета и колко голяма батка си ! Колко по-голям подарък получиш, толко повече време ще трябва да чакаш до следващия път, когато ще можеш да пробваш пак. Късмет ! :)
 </div>
 
";
$me = $_SESSION['u']['username'];
$now = time();
$q_gifst = mysqli_query($conn, "SELECT * FROM `gifts-given` WHERE `username`='$me' ORDER BY `end-time` DESC");
$g_n = mysqli_num_rows($q_gifst);
// ---------------------
$GIFT_WUT = make_gift($conn,$me);
if ($GIFT_WUT == 'error') // някакви грешки ?
    {
        $r = mysqli_fetch_assoc($q_gifst);
        $timeleft = timer($r['end-time']);

        echo
            "<div style='background:#fafafa;padding:20px;padding-top:10px;'>" .
                "<div style='text-align:left;float:left;'>" .
                "<img src='" . $DEFAULT_URL . "images/gift41.png' width='128' title='ПОДАРЪКА ТИ!!' />" .
                "</div>" .
                "<h2 style='color:red;margin-bottom:5px;'>Упс !</h2>" .
                "<br />" .
                "<div style='font-size:14px;font-family:Verdana;'>" .
                "<b>Следващ подарък може да получиш след:</b> " .
                $timeleft .
                "</div>" .
                "<div class='clear'></div>" .
                "</div>";
    } else {


    echo
        "<div style='background:#fafafa;padding:20px;padding-top:10px;'>" .
            "<div style='text-align:left;float:left;'>" .
            "<img src='" . $DEFAULT_URL . "images/gift41.png' width='128' title='ПОДАРЪКА ТИ!!' />" .
            "</div>" .
            "<h2 style='color:green;margin-bottom:5px;'>Честито !</h2>" .
            "<br />" .
            "<div style='font-size:14px;font-family:Verdana;'>" .
            "<b>Твоят подарък е:</b> " .
            $GIFT_WUT .
            "</div>" .
            "<div class='clear'></div>" .
            "</div>";
}




echo "
 &nbsp; <a href='./?p=map'>&laquo; към картата</a>
";
