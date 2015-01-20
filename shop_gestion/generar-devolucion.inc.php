<?php
	/*
	$checkbox_defaults = array(
 		0 => 0,
	    1 => 0,
		2 => 0
	);
	foreach($checkbox_defaults as $k=>$v){
    	$_POST["selection"][$k] = (isset($_POST["selection"][$k])?$_POST["selection"][$k]:$v);
		$_POST["gastosDev"][$k] = (isset($_POST["gastosDev"][$k])?$_POST["gastosDev"][$k]:$v);
	}
		*/
	
	//------------------------------------------------------------------------------------------------------------------- 
	 session_start();
	 require_once('conexion/conexion.inc.php');
	 $db = DataBase::getInstance();
	
	 $referer = $_SERVER['HTTP_REFERER'];
	 $array = explode('&', $referer);
	 $urlReturn = $array[0];
	 
	 $orden = $_POST['ordenDev'];
	 $estate = $_POST['estate'];
	 $motivoDevol = utf8_decode($_POST['nota']);
	 $dateDevol = date('Y-m-d');
	 $usserSystem = trim($_SESSION['seudonimo']);
	 
	 $values = array_keys($_POST); 
	 for($i = 0; $i < count($values); $i++){
         if(substr($values[$i],0,4) == 'sel_'){
		     $var = 'und_' . substr($values[$i],4);
			 $data = explode('|', $_POST[$values[$i]]);
			 //----------------------------------------------------------------------------------------
			 //Aqui tenemos que meter la consulta, la variable de unidades es $_POST[$var]
			 //$data son los valores de radio seleccionamos numeración e insertamos
			 //----------------------------------------------------------------------------------------
			 if($data[4] == 0)
				$tipoDevol = 0;			//SI NO SE ABONA EL IMPORTE DEL GASTO DE ENVIO TIPODEVOL = 0
			else
				$tipoDevol = 1;			//SI SE ABONO EL IMPORTE DEL GASTO DE ENVÍO TIPODEVOL = 1
				
		     try {
				$SQL = "UPDATE lineasorden SET EstadoPedido = '$estate', FechaCambioEstado = '$dateDevol', Tramita = '$usserSystem' WHERE IdOrden = '$orden' AND Id = '$data[0]'";
				$db->setQuery($SQL);
				if(!$db->alter())
					throw new Exception('Imposible actualizar linea de la orden.');
				
				$SQL = "INSERT INTO lineasdevolucion (IdOrden, IdProducto, Cantidad, Talla, Subtotal, GastosEnvio, FechaDevolucion, TipoDevolucion, Tramita, Motivo) ";
				$SQL .= "VALUES('$orden', '$data[1]', '$data[3]', '$data[2]', '$data[5]', '$data[4]', '$dateDevol', '$tipoDevol', '$usserSystem', '$motivoDevol')";
				$db->setQuery($SQL);
				if(!$db->alter())
					throw new Exception('Imposible insertar devolucion');
				$response = 1;
			} catch (Exception $e) {
				$response = 0;	
				header('Location: ' . $urlReturn . '&response='.$response);
			}
		 }
	 }
	 if ($response == 1) {
		 header('Location: ' . $urlReturn . '&response='.$response);
	 }
	//-------------------------------------------------------------------------------------------------------------------
