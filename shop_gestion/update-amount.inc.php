<?php
	@session_start();
	$prodEliminar = ($_GET["producto"]);
	$prodTalla = utf8_decode(($_GET["talla"]));
	$option = ($_GET["opcion"]);
	if (isset($_SESSION["user"])) {
		$cliente = $_SESSION["user"];
	} else if (isset($_GET["idcliente"])) {
		$cliente = $_GET["idcliente"];
	} else if (isset($_GET["cliente"])){
		$cliente = $_GET['cliente'];
	} else if (isset($_GET['orden'])) {
		$cliente = $_GET['orden'];
	}else {
		$cliente = $_COOKIE["usuarioAdmin"];
	}
	
	require_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
	
	if ($option == "delete") {
		$sql = "DELETE FROM carritocompra WHERE IdCarrito = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
		$db->setQuery($sql);
		$db->execute();
		header('Location:' . $_SERVER['HTTP_REFERER']);
	} elseif ($option == "up") {
		$sql = "UPDATE carritocompra SET Cantidad = Cantidad + 1 WHERE IdCarrito = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
		$db->setQuery($sql);
		$db->execute();
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	} elseif ($option == "down") {
		$sql = "UPDATE carritocompra SET Cantidad = Cantidad - 1 WHERE IdCarrito = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
		$db->setQuery($sql);
		$db->execute();		
		$sql = "SELECT * FROM carritocompra WHERE IdCarrito = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
		$db->setQuery($sql);
		$db->execute();
		if($db->Affected_Rows() > 0) {
			$result = $db->loadObjectList();
			foreach($result as $result1) {
				$Obtenido = $result1->Cantidad;
			}
		}
		
		if ($Obtenido == '0') {
			$sql = "DELETE FROM carritocompra WHERE IdCarrito = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
			$db->setQuery($sql);
			$db->execute();
		}
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	
	//CON ESTO BORRAMOS EL PEDIDO DE LINEAS ORDEN
	
	elseif ($option == "deletep") {
		$sql = "DELETE FROM lineasorden WHERE IdOrden = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
		$db->setQuery($sql);
		$db->execute();
		//ACTUALIZAMOS EL TOTAL DE LA ORDEN
		$SQL = "SELECT SUM(Subtotal + GastosEnvio) AS totalOrden FROM lineasorden WHERE IdOrden = '$cliente'";
		$db->setQuery($SQL);
		$result = $db->loadObject();
		$totalOrden = $result->totalOrden;
		
		$SQL = "UPDATE ordenes SET Total = '$totalOrden' WHERE IdOrden = '$cliente'";
		$db->setQuery($SQL);
		$db->execute();
		
		header('Location:' . $_SERVER['HTTP_REFERER']);
		
	} elseif ($option == "upp") {
		$SQL = "SELECT * FROM lineasorden WHERE IdOrden = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
		$db->setQuery($SQL);
		$result = $db->loadObject();
		$__GastosUnidad__ = $result->GastosEnvio / $result->Cantidad;
		
		$sql = "UPDATE lineasorden SET Cantidad = Cantidad + 1 WHERE IdOrden = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
		$db->setQuery($sql);
		$db->execute();
		
		$SQL = "SELECT * FROM lineasorden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
		$SQL .= "WHERE opcionesoferta.IdOpcion = '$prodEliminar' AND opcionesoferta.Id = '$prodTalla' AND IdOrden = '$cliente'";
		$db->setQuery($SQL);
		$result = $db->loadObject();
		$__produc__ = $result->Precio;
		$__cant__ = $result->Cantidad;
		$__Subtotal__ = $__produc__ * $__cant__;
		
		$__totalGastos = $__cant__ * $__GastosUnidad__;
		
		$SQL = "UPDATE lineasorden SET Subtotal = '$__Subtotal__', GastosEnvio = '$__totalGastos' WHERE IdOrden = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
		$db->setQuery($SQL);
		$db->execute();
		
		//ACTUALIZAMOS EL TOTAL DE LA ORDEN
		$SQL = "SELECT SUM(Subtotal + GastosEnvio) AS totalOrden FROM lineasorden WHERE IdOrden = '$cliente'";
		$db->setQuery($SQL);
		$result = $db->loadObject();
		$totalOrden = $result->totalOrden;
		
		$SQL = "UPDATE ordenes SET Total = '$totalOrden' WHERE IdOrden = '$cliente'";
		$db->setQuery($SQL);
		$db->execute();
		
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		
		
	} elseif ($option == "downp") {
		$SQL = "SELECT * FROM lineasorden WHERE IdOrden = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
		$db->setQuery($SQL);
		$result = $db->loadObject();
		$__GastosUnidad__ = $result->GastosEnvio / $result->Cantidad;
		
		$sql = "UPDATE lineasorden SET Cantidad = Cantidad - 1 WHERE IdOrden = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
		$db->setQuery($sql);
		$db->execute();		
		$sql = "SELECT * FROM lineasorden WHERE IdOrden = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
		$db->setQuery($sql);
		$db->execute();
		if ($db->Affected_Rows() > 0) {
			$result = $db->loadObjectList();
			foreach($result as $result1) {
				$Obtenido = $result1->Cantidad;
			}
		}
		
		$SQL = "SELECT * FROM lineasorden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
		$SQL .= "WHERE opcionesoferta.IdOpcion = '$prodEliminar' AND opcionesoferta.Id = '$prodTalla' AND IdOrden = '$cliente'";
		$db->setQuery($SQL);
		$result = $db->loadObject();
		$__produc__ = $result->Precio;
		$__cant__ = $result->Cantidad;
		$__Subtotal__ = $__produc__ * $__cant__;
		
		$__totalGastos = $__cant__ * $__GastosUnidad__;
		
		$SQL = "UPDATE lineasorden SET Subtotal = '$__Subtotal__', GastosEnvio = '$__totalGastos' WHERE IdOrden = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
		$db->setQuery($SQL);
		$db->execute();
		
		//ACTUALIZAMOS EL TOTAL DE LA ORDEN
		$SQL = "SELECT SUM(Subtotal + GastosEnvio) AS totalOrden FROM lineasorden WHERE IdOrden = '$cliente'";
		$db->setQuery($SQL);
		$result = $db->loadObject();
		$totalOrden = $result->totalOrden;
		
		$SQL = "UPDATE ordenes SET Total = '$totalOrden' WHERE IdOrden = '$cliente'";
		$db->setQuery($SQL);
		$db->execute();
		
		if ($Obtenido == '0') {
			$sql = "DELETE FROM lineasorden WHERE IdOrden = '$cliente' AND IdProducto = '$prodEliminar' AND Talla = '$prodTalla'";
			$db->setQuery($sql);
			$db->execute();
		}
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	
	$db->__destruct();
?>