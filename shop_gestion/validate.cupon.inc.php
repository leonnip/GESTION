<?php
	include_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
	
	$cod = $_POST['CodDescuento'];	
	$SQL = "SELECT * FROM cupones WHERE CodCupon = '$cod' AND Estado = '0' AND Activo = '1'";
	$db->setQuery($SQL);
	$row = $db->execute();
	if (mysqli_num_rows($row) > 0) {
		setcookie('CuponDescuento', base64_encode($cod));
		echo 1;
	} else {
		echo 0;	
	}
?>