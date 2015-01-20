<?php
	include_once("function.inc.php");
	include_once("config.inc.php");
	
	if (isset($_POST['idproducto'])) {
		if (isset($_GET['idorden']))
			$carrito = $_GET['idorden'];
		else
			$carrito = GetIdCarrito();
		
		$producto = explode('|', $_POST['idproducto']);
		$idproducto = $producto[0];
		$opcion = $_POST['opcion'];
		$cantidadComprada = 1;
		$fecha = date('Y-m-d');
		
		include_once("conexion/conexion.inc.php");
		$db = DataBase::getInstance();
		try {
			$db->AutoCommit();
			AgregarCarrito($carrito, $idproducto, $cantidadComprada, $fecha, $opcion, $db, $paisDefecto);
			echo 1;
			$db->Commit();
		} catch(Exception $e) {
			echo $e->getMessage();
			$db->Rollback();
		}		
	}
?>