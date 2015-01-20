<?php
	require_once 'conexion/conexion.inc.php';
	$db = DataBase::getInstance();
	
	$URL = $_SERVER['HTTP_REFERER'];
	$listUrl = explode('&', $URL);
	$returnUrl = $listUrl[0] . '&' . $listUrl[1];
	
	$SQL = "UPDATE direcciones SET D_Nombres = '".utf8_decode($_POST['name_payment'])."', D_Apellidos = '".utf8_decode($_POST['last_name_payment'])."', Sexo = '".$_POST['sexo_payment']."', TipoVia = '".utf8_decode($_POST['type_via_payment'])."', Direccion = '".utf8_decode($_POST['address_payment'])."', TipoNumero = '".utf8_decode($_POST['type_number_payment'])."', Numero = '".utf8_decode($_POST['number_payment'])."', Piso = '".utf8_decode($_POST['piso_payment'])."', Puerta = '".utf8_decode($_POST['door_payment'])."', Telefono = '".$_POST['phone_payment']."',  Cp = '".utf8_decode($_POST['cp_payment'])."', Poblacion = '".utf8_decode($_POST['city_payment'])."', Provincia = '".utf8_decode($_POST['province_payment'])."', Comentarios = '".utf8_decode($_POST['message_payment'])."' WHERE IdDireccion = '".$_POST['id_direccion']."'";
	$db->setQuery($SQL);
	$row = $db->execute();
	
	if ($db->Affected_Rows() > 0) {
		header('Location: ' . $returnUrl . '&response=1');
	} else {
		header('Location: ' . $returnUrl . '&response=0');
	}
?>