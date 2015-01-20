<?php
	require_once("conexion/conexion.inc.php");
	$db = dataBase::getInstance();
	
	$lineaOrden = $_POST['lineaorden'];
	$totalLineaOrd = $_POST['totalLineaOrd'];
	$fecha = @date('Y-m-d');
	
	$SQL = "UPDATE lineasorden SET AgenciaPago = '$totalLineaOrd', AgenciaFechaRegistro = '$fecha', AgenciaHoraRegistro = CURTIME() WHERE Id = '$lineaOrden'";
	$db->setQuery($SQL);
	$db->execute();
	
	if($db->Affected_Rows() > 0) {
		echo '<i class="fa icon-ok"></i>';
	} else {
		echo '<i class="fa icon-ko"></i>';
	}
?>