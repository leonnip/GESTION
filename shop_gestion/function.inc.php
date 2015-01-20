<?php
	function GetIdCarrito() {
		session_start();
		if (isset($_SESSION["Login"])) {
			return $_SESSION["IdUsuario"];
		} else if (isset($_COOKIE["usuarioAdmin"])) {
			return $_COOKIE["usuarioAdmin"];
		} else {
			$tempCarrito = uniqid(rand(), 1);
			setcookie("usuarioAdmin", $tempCarrito);
			return $tempCarrito;
		}
	}
	
	function AgregarCarrito($carrito, $producto, $cantidadComprada, $fecha, $opcion, $db, $paisDefecto) {
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
			$sql .= "VALUES('$carrito', '$producto', '$cantidadComprada', '$opcion', '$fecha', '$paisDefecto')";
			$db->setQuery($sql);
			if (!$db->alter()) {
				throw new Exception("A Ocurrido un error al Insertar el Carrito");
			}
			$db->freeResults();
		}
		
	}	
?>