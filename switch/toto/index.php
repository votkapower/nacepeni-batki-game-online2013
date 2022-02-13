<?php
if($_SESSION['logged'] != true)
{
	header("Location: login.php");
	exit;
}
if(user_info($conn,$_SESSION['u']['username'],"level") < 5)
{
	header("Location: ./?p=map");
	exit;
}
// -----------------------[..ЛОТАРИЯ .. ]---------------------------
echo "
 <h3 class='headline'>Тото 6/49</h3>
 
 <div class='info'> 
		Хей, Батка ! Това е чисто Тото, тук се иска сериозен КЪСМЕТ за да играеш. Тук може да спечелиш от <b>10 лв</b> до <b>1 500лв</b>, според това колко числа уцелиш! <br />
		 <br />
		Ето правилата: <br />
		 - Трябва да избереш 6 числа <br/>
		 - Трябва да изчакаш да видим дали ще познаеш някое от тях или всички<br/>
		 - Печелиш за всяко число, което познаеш <br/>
		 <br />
		 Можеш да играеш постоянно, стига да имаш късмет .. 
 </div>
 
";

if(isset($_POST['do-toto']))
{
$me = $_SESSION['u']['username'];

$_1 = rand(1,49);
$_2 = rand(1,49);
$_3 = rand(1,49);
$_4 = rand(1,49);
$_5 = rand(1,49);
$_6 = rand(1,49);
$myNumbers = $_POST['checkNum'];
$selectedNumbers="<div><b>Твоите числа:</b></div>";
$poznatiChisla=0;
$poznatoChislo = array();
$sql=false;	

for($i=0; $i< count($myNumbers); $i++)
{
	$number = $myNumbers[$i];
		if(
			$number == $_1 ||
			$number == $_2 ||
			$number == $_3 ||
			$number == $_4 ||
			$number == $_5 ||
			$number == $_6 
		  )
		{
			$poznatiChisla++;
			$poznatoChislo[$i]=$number;
		}
	//------
	if($poznatoChislo[$i] == $number)
	{	
		$selectedNumbers .= "<div class='guessed-number'>".$number."</div>"; 
	}
	else
		{
			$selectedNumbers .= "<div>".$number."</div>"; 
		}
}
// за колко числа какво ще спечели ?
if($poznatiChisla == 6)
{
	$MoneyWin = 1500;
	$win_id = "success-lotary-msg";
	$win_html = "ДЖАКПОТ! Имаш <b>".$poznatiChisla." познати</b> числа и печелиш <b>".$MoneyWin." лв.</b> " ;
	$sql=true;
		mysqli_query($conn,"INSERT INTO `admin-toto-lotary-stats`
			(`username`,`timestamp`,`what`,`chisla`)
			values
			('$me','$now','toto','$poznatiChisla')
		"); //SQL
}
else if($poznatiChisla == 5)
{
	$MoneyWin = 1000;
	$win_id = "success-lotary-msg";
	$win_html = "Ухх, за малко! Имаш <b>".$poznatiChisla." познати</b> числа и печелиш <b>".$MoneyWin." лв.</b> " ;
	$sql=true;
	
	 mysqli_query($conn,"INSERT INTO `admin-toto-lotary-stats`
			(`username`,`timestamp`,`what`,`chisla`)
			values
			('$me','$now','toto','$poznatiChisla')
		"); //SQL
}
else if($poznatiChisla == 0)
{
$MoneyWin= 0;
$win_id = "fuck-lotary-msg";
$win_html = "Хахах! Имаш <b>".$poznatiChisla." познати</b>, днес не ти върви ! Избери нови 6 числа ? " ;	
$sql=false;	
}
else
	{
		$MoneyWin = $poznatiChisla * 10; // 2 poznati po 10 -> 20 лв.
		$win_id = "success-lotary-msg";
		$win_html = "Честито! Имаш <b>".$poznatiChisla." познати</b> числа и печелиш </b>".$MoneyWin." лв.</b> " ;
		$sql=true;		

	}
$win_total = "<div id='$win_id'>$win_html</div>" ;	

// -----------------------------


echo  "<script>
		$(function () {
			randomNumbersAnimation();
		});
		
			function randomNumbersAnimation()
			{
				var input = $('#machine-form .slot-machine-input');
				var i = 0;
				var a = 0;
				var interval = setInterval(function (){
					i++;
					if(i==49){ i=0;}
					input.each(function () {
						$(this).val(i);
					});
					a++;
					if(a>=20) // 1
					{
					 input[0].value = '".$_1."';
					}
					if(a>=40)// 2
					{
					 input[1].value = '".$_2."';
					}
					if(a>=60)// 3
					{
					 input[2].value = '".$_3."';
					}
					if(a>=80)// 4
					{
					 input[3].value = '".$_4."';
					}
					if(a>=100)// 5
					{
					 input[4].value = '".$_5."';
					}
					if(a>=120)// 6
					{
					 input[5].value = '".$_6."'; 
					 clearInterval(interval);
					 //console.log('intervala e izchisten i chislata sa: ".$_1." - ".$_2." -  ".$_3." -  ".$_4." -  ".$_5." -  ".$_6."');
					setTimeout(function () {
							$(\"#loto-msg\").fadeIn();
							$('.guessed-number').css({
								'color':'#FFF',
								'background':'#2DB300'
							});
							$('#success-lotary-msg').fadeIn();
							$('#fuck-lotary-msg').fadeIn();
						}, 1300); 
					}
				}, 100);
			}
		
</script>";

$guessedNumbersCount = "<div id='loto-msg' style='display:none;margin-top:5px;float:left;color:#FFF;padding:5px;background:#2693FF;'><b>".$poznatiChisla." познати</b> числа</div>" ;



}

	 echo 
		"<div style='background:#fafafa;padding:20px;padding-top:10px;'>".
		"<div style='text-align:left;float:left;margin-right:10px;'>".
			"<img src='".$DEFAULT_URL."images/totot--blank-icon.jpg' width='100' title='ДЪРПАЙЙ РЪЧКАТАА!!' />".
		"</div>".
		  "<h2 style='color:green;margin-bottom:5px;'>Печеливши числа:</h2>" .
				"<div style='font-size:14px;font-family:Verdana;'>".
				"<form id='machine-form' method='post'>".
					"<input type='text' name='n' class='slot-machine-input' readonly placeholder='0' value='0' />".
					"<input type='text' name='n' class='slot-machine-input' readonly placeholder='0' value='0' />".
					"<input type='text' name='n' class='slot-machine-input' readonly placeholder='0' value='0' />".
					"<input type='text' name='n' class='slot-machine-input' readonly placeholder='0' value='0' />".
					"<input type='text' name='n' class='slot-machine-input' readonly placeholder='0' value='0' />".
					"<input type='text' name='n' class='slot-machine-input' readonly placeholder='0' value='0' />".
			   "</form>" .
			    "<div id='to-select-numbers-left'>Избери 6 числа ..</div>" .
			    "<div class='clear'></div>".
				"<div id='user-selected-numbers'>".$selectedNumbers."</div>  ".$guessedNumbersCount."" .
			   "<form id='user-form' method='post'>" .
			     "<div class='clear'></div>"
			 
		;
			   
			   $i=0;
			   while($i < 49)
			   {
				   $i++;
					   echo "
						<label class='blank-toto-number'>
							<input type='checkbox' class='check' name='checkNum[]' value='".$i."' >
							".$i."
						</label>
					   ";
				}
		echo  "<div class='clear'></div>".
			  "<button id='do-toto' type='submit' name='do-toto'><b>Започни ново теглене</b></button>" .
				$win_total.
			  "</form>" .
			   "</div>" .
			  "<div class='clear'></div>".
		"</div>" 
		;
// SQLLLL
if($sql == true || $sql == 1)
{
	$me = $_SESSION['u']['username'];
	mysqli_query($conn,"UPDATE `users` SET `money`= `money` + ".$MoneyWin." WHERE `username`='$me'") or die(mysqli_error($conn));
}


 echo"
 &nbsp; <a href='./?p=map'>&laquo; към картата</a>
";