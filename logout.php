<?php
session_start();
session_unset();
session_destroy();
header("Location: cooking-recipe-frontpage.php");
exit();
?>
