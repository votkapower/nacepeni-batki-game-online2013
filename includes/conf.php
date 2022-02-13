<?php
$conn = mysqli_connect("localhost", "root", "", "votkapower_batki_game") or  die("НЕ мога да се свържа със сървара !");
mysqli_set_charset($conn, "UTF8");


$DEFAULT_URL = "http://batki.votkapower.eu/";
$DEFAULT_CEO_URL = "http://batki.votkapower.eu/";
$_DEFAULLT_TRUDNOST_ = 19; 

