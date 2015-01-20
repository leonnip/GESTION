<?php
	function AgregarCarrito($carrito, $producto, $cantidadComprada, $fecha, $opcion, $db, $country) {
		$sql = "SELECT COUNT(IdProducto) AS ExisteProducto, Cantidad ";
		$sql .= "FROM carritocompra WHERE IdProducto = '$producto' AND IdCarrito = '$carrito' AND Talla = '$opcion' ";
		$sql .= "GROUP BY Cantidad";
		$db->setQuery($sql);
		$resultado = $db->loadObjectList();
		foreach($resultado as $result) {
			$Existe = $result->ExisteProducto;
			$Cantidad = $result->Cantidad;
		}
		$db->freeResults();
		
		if ($Existe > 0) {
			$nuevacantidad = $Cantidad + $cantidadComprada;
			$sql = "UPDATE carritocompra ";
			$sql .= "SET Cantidad = '$nuevacantidad', FechaRegistro = '$fecha' ";
			$sql .= "WHERE IdProducto = '$producto' AND IdCarrito = '$carrito' AND Talla = '$opcion'";
			$db->setQuery($sql);
			if (!$db->alter()) {
				throw new Exception("Ha Ocurrido un Error al Actualizar el Carrito");
			}
			$db->freeResults();
		} else {
			$sql = "INSERT INTO carritocompra(IdCarrito, IdProducto, Cantidad, Talla, FechaRegistro, PaisEnvio) ";
			$sql .= "VALUES('$carrito', '$producto', '$cantidadComprada', '$opcion', '$fecha', '$country')";
			$db->setQuery($sql);
			if (!$db->alter()) {
				throw new Exception("A Ocurrido un error al Insertar el Carrito");
			}
			$db->freeResults();
		}
	}

	if (isset($_POST['idproducto'])) {
		$producto = explode('|', $_POST['idproducto']);
		$idproducto = $producto[0];
		$opcion = $_POST['opcion'];
		$cliente = $producto[2];
		$country = $producto[3];
		
		$cantidadComprada = 1;
		$fecha = date('Y-m-d');
		
		include_once("conexion/conexion.inc.php");
		$db = DataBase::getInstance();
		try {
			$db->AutoCommit();
			AgregarCarrito($cliente, $idproducto, $cantidadComprada, $fecha, $opcion, $db, $country);
			echo 1;
			$db->Commit();
		} catch(Exception $e) {
			$db->Rollback();
		}
	}
?>