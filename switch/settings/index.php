<h3 class='headline'>Име за показване:</h3>
<form style="margin-left:20px;" method="post">
<input type="text" style='padding:10px;' name="newDisplayName" value="<?php echo user_info($conn,$_SESSION['u']['username'],"display-name");?>"  maxlength="50" />
<button type="submit" name="changeName">Смени ми името!</button>
</form>
<br/>
<?php
if(isset($_POST['changeName']))
{
$me = $_SESSION['u']['username'];
$newName = trim(htmlspecialchars($_POST['newDisplayName']));
	if(user_exists($conn,$me))
	{
		if(strlen($newName) < 3)
		{
			echo error("Въвел си прекалено кратко име, което НЕ подхожда на една БАТКА !");
		}
		else
			{
				mysqli_query($conn,"UPDATE `users` SET `display-name`='$newName' WHERE `username`='$me' LIMIT 1");
				echo ok("Името ти е сменено, батка :)");
				refresh(1);
			}
	}
	else
		{
			echo error("Такава БАТКА няма ..");
		}
} 