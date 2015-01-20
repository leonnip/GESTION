<?php
	@session_start();
	require_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
	
	$nombreOferta = $_POST['nombreOfert'];
	$estados = $_POST['estados']; //Recibimos los ordenes y las lineas de la orden seleccionadas.
	$_n = count($estados);
	$_i = 0;
	while ($_i < $_n) {
		$_orden = explode("|", $estados[$_i]);
		$_ord[] = array('idorden'=>$_orden[0], 'lineaorden'=>$_orden[1]);
		$_i++;
	}
	
	if (isset($_POST['estados'])) {
		$estadoOrder = $_POST['estadoOrder'];
		$tipoPedido = $_POST['tipoPedido'];
		$listados = $_POST['listados'];
		$idofertaList = $_POST['idofertaList'];
		$estado = $_POST['estados'];
		$n		= count($estado);
		$i		= 0;
		$fechaExport = date('Y-m-d');
		while ($i < $n) {			
			 $orden = explode("|", $estado[$i]);
			 //$sql = "UPDATE lineasorden SET EstadoPedido = '$estadoOrder' WHERE lineasorden.Id = '$orden[1]' AND lineasorden.IdOrden = '$orden[0]'";	
			 
			 //ACTUALIZAMOS EL ESTADO DE LA ORDEN
			 /*
			 $sql = "UPDATE ordenes SET EstadoOrden = '$estadoOrder' WHERE ordenes.IdOrden = '$orden[0]'";	
		     $db->setQuery($sql);
			 $db->execute();
			 $db->freeResults();
			 */
			 
			 //ACTUALIZAMOS LA LINEA DE LA ORDEN DEL PRODUCTO			 
			 $sql = "UPDATE lineasorden SET EstadoPedido = '$estadoOrder', FechaExport = '$fechaExport', TramitaExport = '".$_SESSION['seudonimo']."' WHERE lineasorden.Idorden = '$orden[0]' AND lineasorden.Id = '$orden[1]'";
			 $db->setQuery($sql);
			 $db->execute();
			 $db->freeResults();			 
			$i++;
		}
		
		include_once('config.inc.php');
		include_once("function_orders_update.inc.php");
		
		$resultado = OrdersUpdate($db,$web, $tipoPedido, $listados, $idofertaList, $_ord, $nombreOferta);
		echo $resultado;
	}
?>