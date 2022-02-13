<?php
$_1min = time() - (2 * 60);
$mycity = user_info($conn,$_SESSION['u']['username'], 'city');

$countUsers = _cnt($conn, "id", 'users');
$countUsersOnline = _cnt($conn, "id", 'users', "timestamp > $_1min");
$getLastestUser =  _get($conn, array("username", "`display-name`"), 'users', '', 'ORDER BY `reg_date` DESC LIMIT 1');
$getTHEBESTUser =  _get($conn, array("username", "`display-name`"), 'users', '', 'ORDER BY `exp` DESC LIMIT 1');
$getBestUserFormYourCity =  _get($conn, array("`username`", "`display-name`"), 'users', "`city`=$mycity ORDER BY `exp` DESC LIMIT 1");
$getBestCityName = mysqli_fetch_assoc(mysqli_query($conn, "SELECT *	FROM `users` LEFT JOIN `cities` ON `cities`.`id` = `users`.`city` ORDER BY `users`.`exp` DESC LIMIT 1"));

$onlineList = '';
$getOnlineUsers_q = mysqli_query($conn, "SELECT `username`,`display-name` FROM `users` WHERE `timestamp` > $_1min");
if (mysqli_num_rows($getOnlineUsers_q) >= 1) {
        $onlineList = '&mdash; ';
    }
while ($u = mysqli_fetch_assoc($getOnlineUsers_q)) {
        $onlineList .=  $u['display-name'] . ", ";
    }
$onlineList = mb_substr($onlineList, 0, -2, "utf-8");
?>
<b>Общо играчи:</b>
<?php echo $countUsers; ?><br />
<b>Онлайн играчи:</b>
<?php echo $countUsersOnline; ?><br />
<div style='padding:5px; background:#fcfcfc;'>
    <?php echo $onlineList; ?>
</div>
<b>Най-новия:</b>
<?php echo $getLastestUser['display-name']; ?><br />
<b>Най-добрия:</b>
<?php echo $getTHEBESTUser['display-name']; ?> <br />
<b>Най-добрия от твоя град:</b>
<?php echo $getBestUserFormYourCity['display-name']; ?><br />
<b>Най-добрия град:</b>
<?php echo $getBestCityName['name']; ?><br /> 