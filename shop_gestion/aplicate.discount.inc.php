<?php
	include_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
	
	$cuponDiscount = base64_decode($_COOKIE['CuponDescuento']);
	
	$SQL = "SELECT * FROM cupones WHERE CodCupon = '$cuponDiscount' AND Estado = '0' AND Activo = '1'";
	$db->setQuery($SQL);
	$result = $db->loadObject();
	session_start();
	$_SESSION['Cupon'] = base64_encode($result->CodCupon);
	$_SESSION['valorCupon'] = base64_encode($result->ValorCupon);
	setcookie('CuponDescuento', '0', time()-3600, '/');
	echo 1;
?>