<?php
	session_start();
	require_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
	
	$orden = $_POST['orden'];
	$motivo = $_POST['motiv'];
	$estado = trim($_POST['estado']);
	$fecha = date('Y-m-d');
	
	$SQL = "UPDATE lineasorden SET EstadoPedido = '$estado', FechaCambioEstado = '$fecha', Tramita = '".$_SESSION['seudonimo']."', Motivo = '$motivo' WHERE IdOrden = '$orden'";
	$db->setQuery($SQL);
	$row = $db->execute();

	if($db->Affected_Rows($row) > 0) {
		$db->freeResults();
		$SQL = "SELECT EstadoPedido FROM lineasorden WHERE IdOrden = '$orden' GROUP BY IdOrden";
		$db->setQuery($SQL);
		$row = $db->execute();
		if (mysqli_num_rows($row) > 0) {
			$est = $db->loadObject();
			if ($est->EstadoPedido == 'Transito') echo '<label id="resultEst'.$orden.'" style="color: magenta">Transito</label>';
			if ($est->EstadoPedido == 'Enviado') echo '<label id="resultEst'.$orden.'" style="color: #0877BF">Enviado</label>';
			if ($est->EstadoPedido == 'Anulado') echo '<label id="resultEst'.$orden.'" style="color: #000">Anulado</label>';
			if ($est->EstadoPedido == 'Entregado') echo '<label id="resultEst'.$orden.'" style="color: green">Entregado</label>';
			if ($est->EstadoPedido == 'No-entregado') echo '<label id="resultEst'.$orden.'" style="color: #0CF">No Entregado</label>';
		} 
	} else {
		echo '<label id="resultEst'.$orden.'" style="color: #F00">'.$estado.'</label>';
		echo '<script>alert("No se actualizo ningún valor.")</script>';
	}
	
?>