<?php	
	$SERVER = $_SERVER['HTTP_HOST'];
	
	$orden_cliente = base64_decode($_GET['orden_cliente']);
	$listaOC = explode('|', $orden_cliente);
	
	$orden = $listaOC[0];
	$cliente = $listaOC[1];
	
	try {
	//if ($REFERER == 'http://' . $SERVER . '/anadir-productos-a-orden.php') {

		require_once("conexion/conexion.inc.php");
		$db = DataBase::getInstance();
	
		$ReturnUrl = $_SERVER['HTTP_REFERER'];
		
		/*$Opt_Orig = $_GET['idproducto'];
		list($producto,$Opt1) = explode("|",$Opt_Orig);*/

		//AÑADIR LAS LINEAS DE LA ORDEN
		$SQL = "INSERT INTO lineasorden (IdOrden, IdProducto, Cantidad, Talla, GastosEnvio, Subtotal) ";
		$SQL .= "SELECT $orden, carritocompra.IdProducto, carritocompra.Cantidad, carritocompra.Talla, (paisesenvio.TotalGastos * carritocompra.Cantidad) as gastosEnvio, (opcionesoferta.Precio * carritocompra.Cantidad) as subTotal FROM productos ";
		$SQL .= "INNER JOIN carritocompra ON productos.IdOferta = carritocompra.IdProducto ";
		$SQL .= "INNER JOIN paisesenvio ON carritocompra.PaisEnvio = paisesenvio.IdPais ";
		$SQL .= "INNER JOIN opcionesoferta ON carritocompra.Talla = opcionesoferta.Id ";
		$SQL .= "WHERE carritocompra.IdCarrito = '$cliente' AND opcionesoferta.Peso > paisesenvio.PesoIn AND opcionesoferta.Peso <= paisesenvio.PesoOut";
		$db->setQuery($SQL);
		if (!$db->alter())
			throw new Exception("Error al crear las líneas de la orden.");
		$db->freeResults();
		
		//ACTUALIZAMOS EL TOTAL DE LA ORDEN
		$SQL = "SELECT SUM(Subtotal + GastosEnvio) AS totalOrden FROM lineasorden WHERE IdOrden = '$orden'";
		$db->setQuery($SQL);
		$result = $db->loadObject();
		$totalOrden = $result->totalOrden;
		
		$SQL = "UPDATE ordenes SET Total = '$totalOrden' WHERE IdOrden = '$orden'";
		$db->setQuery($SQL);
		$db->execute();
			
		//ELIMANOS EL CARRITO DE LA COMPRA
		$SQL = "DELETE FROM carritocompra WHERE IdCarrito = '$cliente'";
		$db->setQuery($SQL);
		if (!$db->alter())
			throw new Exception("No se ha podido eliminar el carrito de la compra una vez generada la orden.");	
		$db->freeResults();			
		
		header("Location:" . $ReturnUrl. '&response=1');
	} catch (Exception $e) {
		echo $e->getMessage();
		exit();
	}
	//}
?>