<div id='index-map'>
    <div id='map-town-name-display'>
        <?php
        // Вземи града на потребителя
        $getUserTown = mysqli_query($conn, "SELECT `name` FROM `cities` WHERE `id`='" . $_SESSION['u']['city'] . "'");
        $myTown = mysqli_fetch_assoc($getUserTown);
        echo $myTown['name'];
        ?>
    </div>

    <?php
    $myLevel = user_info($conn,$_SESSION['u']['username'], 'level');
    $GetMapPoints_q = mysqli_query($conn, "SELECT * FROM `map-points` ORDER BY `id` DESC ");
    while ($point = mysqli_fetch_assoc($GetMapPoints_q)) {

            if ($point['need-level'] > $myLevel) {
                    echo "<a href='javascript:;' style='margin-left:" . $point['x'] . "px;margin-top:" . $point['y'] . "px;'>
		<div class='point'>
			<div class='point-title'>" . $point['title'] . " <small>(" . $point['need-level'] . "-ти LVL)</small></div>
		</div>
			</a>";
                } else {
                    ?>

    <a href='<?php echo $point['url'] ?: ''; ?>' style='margin-left:
        <?php echo $point['x']; ?>px;margin-top:
        <?php echo $point['y']; ?>px;'>
        <div class='point'>
            <div class='point-title'>
                <?php echo $point['title']; ?>
            </div>
        </div>
    </a>
    <?php

}
}
?>
</div> 