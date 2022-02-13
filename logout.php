<?php
session_start();
session_destroy();
header("Location: http://batki.votkapower.eu/");
exit;