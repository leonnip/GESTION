<?php
	session_start();
	unset($_SESSION['UserId']); 
	unset($_SESSION['Logged']);
	unset($_SESSION['IdUsuario']);
	session_destroy();
	header("Location: https://" . $_SERVER['HTTP_HOST'] . "/");
?>
