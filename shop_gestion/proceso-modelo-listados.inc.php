<?php
	require_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
	
	$var = $_GET['activo'];
	$idoferta = $_GET['id_oferta'];
	
	$sql = "UPDATE productos SET Listados = '$var' WHERE IdOferta = '$idoferta'";
	$db->setQuery($sql);
	$row = $db->execute();
	$db->setQuery("COMMIT");
	
?>