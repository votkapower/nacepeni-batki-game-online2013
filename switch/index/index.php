<?php
if($_SESSION['logged'] == true)
{
	 header("Location: ./?p=map");
	 exit;
}