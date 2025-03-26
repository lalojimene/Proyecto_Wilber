<?php
session_start();
setcookie("jwt", "", time() - 3600, "/", "", false, true); // Expira el JWT
session_destroy();
header("Location: login.php?expired=1");
exit();

?>
