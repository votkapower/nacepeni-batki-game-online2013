<style>#errorLog b:last-child{ display:block;}</style>
<div class='box-big'>
	<div id='errorLog' style='height:500px;background:#ECECFB;color:#666;font-size:16px;border:1px sold #ccc;border-right:0px;overflow-y:scroll;'>
	<?php
	 $dir = "../reports/404.txt";
	 if(!file_exists($dir))
	 {
	  echo "файла НЕ съществува :/";
	 }
	 $file = @file_get_contents($dir);

	 $errors =  explode("###", $file);
	 $i=0;
	 foreach($errors as $err)
	 {
		$i++;
		$err = str_replace(array('"','";'), "", $err);
		if($i%2==0){ $bg = "#8C0000"; $color='#fff';}else{$bg='#FF4C4C'; $color='#333';}
		echo  "<div style='display:block;background:". $bg ."; color:".$color."; padding:10px; '>".$err."</div>";
	 }
	?>	
	</div>
</div>
