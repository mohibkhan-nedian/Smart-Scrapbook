<?php

session_name('tzLogin');
session_set_cookie_params(2*7*24*60*60);
session_start();
?>

<?php
$_SESSION["usr"] = null;
header("Location: demo.php");

?>