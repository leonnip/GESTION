<?php
	function ordenesFechas($web) {
		//RECIBIMOS LAS DOS FECHAS
		$fecha1 = $_POST['Fecha1'];
		$fecha2 = $_POST['Fecha2'];
		
		//RECIBIMOS DATOS DEL SELECT PRODUCTOS
		$producto = explode('|', $_POST['selectProduct']);
		$_prod_ = $producto[0];
		$_tipo_ = $producto[1];
		
		//SEPARAMOS SEGUN EL TIPO DE OPCION
		if (isset($_POST['opcion']))
			$_tipo_ = $_POST['opcion'];
		else
			$_tipo_ = $producto[1];
		
		//RECIBIMOS DATOS DEL SELECT USUARIOS
		$usuario = explode('|', $_POST['selectUsuario']);
		$_usser_ = $usuario[0];
		
		//SEGUN SELECT RECIBIDO ESCOGEMOS CONSULTA
		if (isset($_POST['selectProduct']))
			//$addSQL = "AND productos.IdOferta = '$_prod_' AND opcionesoferta.Id = '$_tipo_'";
			$addSQL = "AND lineasorden.IdProducto = '$_prod_' AND lineasorden.Talla = '$_tipo_'";
		else if (isset($_POST['selectUsuario']))
			$addSQL = "AND ordenes.Tramitado = '".trim($_POST['selectUsuario'])."'";
		
				
		require_once('conexion/conexion.inc.php');
		$db = DataBase::getInstance();
				
		//NOS GUARDA EN EL ARREGLO ORD LAS ORDENES SEAN ESTAS POR CONTRA-REMBOLSO PAYPLA O TARJETA Y QUE SEAN OK
		$SQL = "SELECT ordenes.IdOrden, lineasorden.Id ";
		$SQL .= "FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
		$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
		$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";		
		$SQL .= "WHERE lineasorden.EstadoPedido != 'Anulado' AND ordenes.EstadoPago ='ok' AND (ordenes.FormaPago = 'contra-rembolso' OR ordenes.FormaPago = 'tarjeta' OR ordenes.FormaPago = 'paypal') ";
		$SQL .= "AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2'";									
		$db->setQuery($SQL);
		$row = $db->execute();
		if (mysqli_num_rows($row) > 0) {
			$result = $db->loadObjectList();
			foreach($result as $result1) {
				$ord[] = array('idorden'=>$result1->IdOrden, 'lineaorden'=>$result1->Id); 
			}
		}
		//FIN
											
				//RECORREMOS EL ARREGLO ORDENES PARA IR PRESENTANDO LOS DATOS UNO A UNO CON LAS DISTINTAS RESTRICCIONES, AQUI TENEMOS QUE APLICAR RESTRICCIONES DE BÚSQUEDA
				$contOrders = count($ord);											
				//FIN
				for ($i = 0; $i <= $contOrders; $i++) {
				$SQL = "SELECT *, 
					ordenes.IdOrden, 
					lineasorden.Id as lineasOrden,
					lineasorden.Cantidad, 
					lineasorden.GastosEnvio, 
					opcionesoferta.Opcion, 
					opcionesoferta.Precio, 
					ordenes.Total, 
					ordenes.Hora, 
					direcciones.D_Nombres, 
					direcciones.D_Apellidos, 
					direcciones.TipoVia, 
					direcciones.Direccion, 
					direcciones.TipoNumero, 
					direcciones.Numero, 
					direcciones.Piso, 
					direcciones.Puerta, 
					direcciones.Telefono, 
					direcciones.Cp, 
					direcciones.Poblacion, 
					direcciones.Provincia, 
					direcciones.Fecha, 
					direcciones.Comentarios, 
					direcciones.Activo, 
					usuarios.Dni, 
					productos.Nombre_Producto
					FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden AND ordenes.IdOrden = '".$ord[$i]['idorden']."' AND lineasorden.Id = '".$ord[$i]['lineaorden']."' ";
				$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
				$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	 			$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
	 			$SQL .= "INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden ";
				$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion AND usuarios.Id = direcciones.D_IdCliente ";
				$SQL .= "LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND Imagenes.Estado = 1 ";
				$SQL .= "WHERE (ordenes.FormaPago = 'contra-rembolso' OR ordenes.FormaPago = 'tarjeta' OR ordenes.FormaPago = 'paypal') ";
				$SQL .= "AND ordenes.EstadoPago = 'ok' AND lineasorden.EstadoPedido != 'Anulado' ";
				$SQL .= "AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' ";
				$SQL .= $addSQL;
				$db->setQuery($SQL);
				$row = $db->execute();
					
				if (mysqli_num_rows($row) > 0) {
					$result1 = $db->loadObject();
						$nomProd_Array = explode(" ", utf8_encode($result1->Nombre_Producto));
						$nomProducto = $nomProd_Array[0] ." ".$nomProd_Array[1]." ".$nomProd_Array[2]." ".$nomProd_Array[3];
						echo '
							<tr>
								<td height="24px"><label>'.$result1->IdOrden.'</label></td>
								<td height="24px"><label>'.$result1->FechaOrden.' | '.$result1->Hora.'</label></td>
								<td height="24px"><label>'.utf8_encode(ucwords(strtolower($result1->Nombres))).' ' .utf8_encode(ucwords(strtolower($result1->Apellidos))).'</label></td>
								<td height="24px"><label>'.$nomProducto.'...</label></td>
								<td height="24px">
									<img src="'.$result1->BaseUrl . $result1->Imagen.'" width="30px" style="border: 0px solid #BDBDBD" title="'.utf8_encode($result1->Nombre_Producto).'" />
								</td>
								<td height="24px"><label>'.$result1->Cantidad.'</label></td>
								<td height="24px">';
									if ($result1->OptActiva == 1) echo '<label>'.utf8_encode($result1->Opcion).'</label>';
										echo '
								</td>
								<td><label>'.number_format(($result1->Precio),2,',','.').'</label></td>
								<td><label>'.number_format(($result1->Subtotal),2,',','.').'</label></td>
								<td><label>'.number_format(($result1->Subtotal/$result1->Iva),2,',','.').'</label></td>
								<td>';
									if ($result1->EstadoPedido == 'Transito') { $imagenEst = 'icon_transito.png'; }
									else if ($result1->EstadoPedido == 'Anulado') { $imagenEst = 'icon_anulado.png'; }
									else if ($result1->EstadoPedido == 'Enviado') { $imagenEst = 'icon_enviado.png'; }
									else if ($result1->EstadoPedido == 'No-entregado') { $imagenEst = 'icon_noentregado.png'; }
									else if ($result1->EstadoPedido == 'Devuelto') { $imagenEst = 'icon_devolucion.png'; }
									else if ($result1->EstadoPedido == 'Entregado') { $imagenEst = 'icon_entregado.png'; }											
									echo '
										<img src="images/'.$imagenEst.'" title="'.$result1->EstadoPedido.'" />
								</td>
								<td height="24px"><label>'.$result1->EstadoPedido.'</label></td>
								<td>';
									if ($result1->FormaPago == 'tarjeta') { $imagen = 'visap.png'; } 
									else if ($result1->FormaPago == 'paypal') { $imagen = 'paypalp.png'; }	
									else if ($result1->FormaPago  == 'contra-rembolso') { $imagen = 'contrap.png'; }											
								echo '
									<img src="images/'.$imagen.'" title="'.$result1->Tramitado.'||'.$result1->FormaPago.'='.$result1->Code_Authorisation.'" style="cursor: pointer" />
								</td>																				
							</tr>';
							
							$facturacion = $facturacion + ($result1->Precio * $result1->Cantidad);
							
							$cantidad = $cantidad + $result1->Cantidad;
							$array_orders_contra[] = array('nombres'=>utf8_encode($result1->D_Nombres).' '.utf8_encode($result1->D_Apellidos), 'dni'=>$result1->Dni, 'email'=>$result1->Email,
												   'direccion'=>utf8_encode($result1->TipoVia).' '.utf8_encode($result1->Direccion).','.$result1->TipoNumero.','.utf8_encode($result1->Numero).','.utf8_encode($result1->Piso).
												   ','.utf8_encode($result1->Puerta), 'cp'=>$result1->Cp, 'poblacion'=>utf8_encode($result1->Poblacion), 'provincia'=>utf8_encode($result1->Provincia),
												   'telefono'=>utf8_encode($result1->Telefono), 'producto'=>utf8_encode($result1->Nombre_Producto), 'tipo'=>utf8_encode($result1->Opcion),
												   'precio'=>$result1->Precio, 'gastosenvio'=>$result1->GastosEnvio, 'cantidad'=>$result1->Cantidad, 'total'=>$result1->Total, 'referencias'=>$result1->Referencias,
												   'mensaje'=>$result1->Comentarios, 'idorden'=>$result1->IdOrden, 'pesoreal'=>$result1->PesoReal); 
								
					 } 									
				}
			echo '<tr height="25px" style="font-size: 14px; font-variant:small-caps;" >
				  	<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
					<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
					<td bgcolor="#1F88A7"><strong style="font-size:14px; color:#FFF;">Pedidos</strong></td>
					<td bgcolor="#1F88A7"><strong style="font-size: 14px; color: #FFF">'.$cantidad.'</strong></td><td bgcolor="#1F88A7"></td>
					<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"><strong style="font-size: 14px; color: #FFF">'.number_format($facturacion,2,',','.').'</strong></td>
					<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7" colspan="3"></td>
				  </tr><tr><td colspan="13" bgcolor="#FFF">&nbsp;</td></tr>';
				  
	}



//OBTENEMOS LAS DEVOLUCIONES Y NO ENTREGADOS DE LOS PEDIDOS POR FECHA Y POR USUARIO TRAMITADA
function DevolucionesFechas($web) {
	//RECIBIMOS LAS DOS FECHAS
		$fecha1 = $_POST['Fecha1'];
		$fecha2 = $_POST['Fecha2'];
		
		//RECIBIMOS DATOS DEL SELECT PRODUCTOS
		$producto = explode('|', $_POST['selectProduct']);
		$_prod_ = $producto[0];
		$_tipo_ = $producto[1];
		
		//SEPARAMOS SEGUN EL TIPO DE OPCION
		if (isset($_POST['opcion']))
			$_tipo_ = $_POST['opcion'];
		else
			$_tipo_ = $producto[1];
		
		//RECIBIMOS DATOS DEL SELECT USUARIOS
		$usuario = explode('|', $_POST['selectUsuario']);
		$_usser_ = $usuario[0];
		
		//SEGUN SELECT RECIBIDO ESCOGEMOS CONSULTA
		if (isset($_POST['selectProduct']))
			//$addSQL = "AND productos.IdOferta = '$_prod_' AND opcionesoferta.Id = '$_tipo_'";
			$addSQL = "AND lineasorden.IdProducto = '$_prod_' AND lineasorden.Talla = '$_tipo_'";
		else if (isset($_POST['selectUsuario']))
			$addSQL = "AND ordenes.Tramitado = '".trim($_POST['selectUsuario'])."'";
		
				
		require_once('conexion/conexion.inc.php');
		$db = DataBase::getInstance();
				
		//NOS GUARDA EN EL ARREGLO ORD LAS ORDENES SEAN ESTAS POR CONTRA-REMBOLSO PAYPLA O TARJETA Y QUE SEAN OK
		$SQL = "SELECT ordenes.IdOrden, lineasorden.Id
				FROM ordenes 
				INNER JOIN lineasdevolucion ON ordenes.IdOrden = lineasdevolucion.IdOrden 
				INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden
				INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id 
				INNER JOIN productos ON lineasdevolucion.IdProducto = productos.IdOferta AND lineasorden.IdProducto = productos.IdOferta
				INNER JOIN opcionesoferta ON lineasdevolucion.Talla = opcionesoferta.Id 
				WHERE ordenes.EstadoPago ='ok'
				AND (ordenes.FormaPago = 'contra-rembolso' OR ordenes.FormaPago = 'tarjeta' OR ordenes.FormaPago = 'paypal') 
				AND lineasdevolucion.FechaDevolucion >= '$fecha1' AND lineasdevolucion.FechaDevolucion <= '$fecha2'";									
		$db->setQuery($SQL);
		$row = $db->execute();
		if (mysqli_num_rows($row) > 0) {
			$result = $db->loadObjectList();
			foreach($result as $result1) {
				$ord[] = array('idorden'=>$result1->IdOrden, 'lineaorden'=>$result1->Id); 
			}
		}
		//FIN
											
				//RECORREMOS EL ARREGLO ORDENES PARA IR PRESENTANDO LOS DATOS UNO A UNO CON LAS DISTINTAS RESTRICCIONES, AQUI TENEMOS QUE APLICAR RESTRICCIONES DE BÚSQUEDA
				$contOrders = count($ord);											
				//FIN
				echo '<tr bgcolor="#1F88A7" height="40px">
					      <td colspan="13" align="center">DEVOLUCIONES</td>
					  </tr>';
				for ($i = 0; $i <= $contOrders; $i++) {				
				$SQL = "SELECT *, lineasorden.Id as lineasOrden, lineasdevolucion.Motivo as Motiv
					FROM ordenes 
					INNER JOIN lineasdevolucion ON ordenes.IdOrden = lineasdevolucion.IdOrden 
					INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden
					INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id 
					INNER JOIN productos ON lineasdevolucion.IdProducto = productos.IdOferta AND lineasorden.IdProducto = productos.IdOferta
					INNER JOIN opcionesoferta ON lineasdevolucion.Talla = opcionesoferta.Id 
					LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND Imagenes.Estado = 1
					WHERE ordenes.EstadoPago ='ok'
					AND (ordenes.FormaPago = 'contra-rembolso' OR ordenes.FormaPago = 'tarjeta' OR ordenes.FormaPago = 'paypal') 
					AND lineasdevolucion.FechaDevolucion >= '$fecha1' AND lineasdevolucion.FechaDevolucion <= '$fecha2'
					AND lineasorden.Id = '".$ord[$i]['lineaorden']."' AND ordenes.IdOrden = '".$ord[$i]['idorden']."' ";
				$SQL .= $addSQL;
				$db->setQuery($SQL);
				$row = $db->execute();
					
				if (mysqli_num_rows($row) > 0) {
					$result1 = $db->loadObject();
						$nomProd_Array = explode(" ", utf8_encode($result1->Nombre_Producto));
						$nomProducto = $nomProd_Array[0] ." ".$nomProd_Array[1]." ".$nomProd_Array[2]." ".$nomProd_Array[3];
						echo '							
							<tr>
								<td height="24px"><label>'.$result1->IdOrden.'</label></td>
								<td height="24px"><label>'.$result1->FechaOrden.' | '.$result1->Hora.'</label></td>
								<td height="24px"><label>'.utf8_encode(ucwords(strtolower($result1->Nombres))).' ' .utf8_encode(ucwords(strtolower($result1->Apellidos))).'</label></td>
								<td height="24px"><label>'.$nomProducto.'...</label></td>
								<td height="24px">
									<img src="'.$result1->BaseUrl . $result1->Imagen.'" width="30px" style="border: 0px solid #BDBDBD" title="'.utf8_encode($result1->Nombre_Producto).'" />
								</td>
								<td height="24px"><label>'.$result1->Cantidad.'</label></td>
								<td height="24px">';
									if ($result1->OptActiva == 1) echo '<label>'.utf8_encode($result1->Opcion).'</label>';
										echo '
								</td>
								<td><label>'.number_format(($result1->Precio),2,',','.').'</label></td>
								<td><label>'.number_format(($result1->Subtotal),2,',','.').'</label></td>
								<td><label>'.number_format(($result1->Subtotal/$result1->Iva),2,',','.').'</label></td>
								<td>';
									if ($result1->EstadoPedido == 'Transito') { $imagenEst = 'icon_transito.png'; }
									else if ($result1->EstadoPedido == 'Anulado') { $imagenEst = 'icon_anulado.png'; }
									else if ($result1->EstadoPedido == 'Enviado') { $imagenEst = 'icon_enviado.png'; }
									else if ($result1->EstadoPedido == 'No-entregado') { $imagenEst = 'icon_noentregado.png'; }
									else if ($result1->EstadoPedido == 'Devuelto') { $imagenEst = 'icon_devolucion.png'; }
									else if ($result1->EstadoPedido == 'Entregado') { $imagenEst = 'icon_entregado.png'; }											
									echo '
										<img src="images/'.$imagenEst.'" title="'.$result1->EstadoPedido.'" />
								</td>
								<td height="24px"><label>'.$result1->EstadoPedido.'</label></td>
								<td>';
									if ($result1->FormaPago == 'tarjeta') { $imagen = 'visap.png'; } 
									else if ($result1->FormaPago == 'paypal') { $imagen = 'paypalp.png'; }	
									else if ($result1->FormaPago  == 'contra-rembolso') { $imagen = 'contrap.png'; }											
								echo '
									<img src="images/'.$imagen.'" title="'.$result1->Tramitado.'||'.$result1->FormaPago.'='.$result1->Code_Authorisation.'" style="cursor: pointer" />
								</td>	
								<td style="display: none">
									<label>'.$result1->Motiv.'</label>
								</td>																			
							</tr>';
							
							$facturacion = $facturacion + ($result1->Precio * $result1->Cantidad);
							
							$cantidad = $cantidad + $result1->Cantidad;
							
							$array_orders_contra[] = array('nombres'=>utf8_encode($result1->D_Nombres).' '.utf8_encode($result1->D_Apellidos), 'dni'=>$result1->Dni, 'email'=>$result1->Email,
												   'direccion'=>utf8_encode($result1->TipoVia).' '.utf8_encode($result1->Direccion).','.$result1->TipoNumero.','.utf8_encode($result1->Numero).','.utf8_encode($result1->Piso).
												   ','.utf8_encode($result1->Puerta), 'cp'=>$result1->Cp, 'poblacion'=>utf8_encode($result1->Poblacion), 'provincia'=>utf8_encode($result1->Provincia),
												   'telefono'=>utf8_encode($result1->Telefono), 'producto'=>utf8_encode($result1->Nombre_Producto), 'tipo'=>utf8_encode($result1->Opcion),
												   'precio'=>$result1->Precio, 'gastosenvio'=>$result1->GastosEnvio, 'cantidad'=>$result1->Cantidad, 'total'=>$result1->Total, 'referencias'=>$result1->Referencias,
												   'mensaje'=>$result1->Comentarios, 'idorden'=>$result1->IdOrden, 'pesoreal'=>$result1->PesoReal); 
								
					 } 									
				}
			echo '<tr height="25px" style="font-size: 14px; font-variant:small-caps;" >
				  	<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
					<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
					<td bgcolor="#1F88A7"><strong style="font-size:14px; color:#FFF;">Pedidos</strong></td>
					<td bgcolor="#1F88A7"><strong style="font-size: 14px; color: #FFF">'.$cantidad.'</strong></td><td bgcolor="#1F88A7"></td>
					<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"><strong style="font-size: 14px; color: #FFF">'.number_format($facturacion,2,',','.').'</strong></td>
					<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7" colspan="3"></td>
				  </tr><tr><td colspan="13" bgcolor="#FFF">&nbsp;</td></tr>';
				  
	}
	
	
	
function OrdenesPorOferta ($web) {
	//RECIBIMOS LAS DOS FECHAS
	$fecha1 = $_POST['Fecha1'];
	$fecha2 = $_POST['Fecha2'];
		
	//RECIBIMOS DATOS DEL SELECT PRODUCTOS
	$producto = $_POST['selectOferta'];
	
	require_once('conexion/conexion.inc.php');
	$db = DataBase::getInstance();
		
	$SQL = "SELECT productos.IdOferta, opcionesoferta.Id, productos.Nombre_Producto, opcionesoferta.Opcion, ";
	$SQL .= "SUM(CASE WHEN TRIM(ordenes.FormaPago) = 'contra-rembolso' THEN lineasorden.Cantidad ELSE 0 END) AS TotalContra, ";
	$SQL .= "SUM(CASE WHEN TRIM(ordenes.FormaPago) = 'tarjeta' THEN lineasorden.Cantidad ELSE 0 END) AS TotalTarjeta, ";
	$SQL .= "SUM(CASE WHEN TRIM(ordenes.FormaPago) = 'paypal' THEN lineasorden.Cantidad ELSE 0 END) AS TotalPaypal, ";
	$SQL .= "SUM(lineasorden.Cantidad) as TotalT, (opcionesoferta.Precio * SUM(lineasorden.Cantidad)) as Facturacion, opcionesoferta.Precio ";
	$SQL .= "FROM ordenes ";
	$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
	$SQL .= "INNER JOIN productos ON productos.IdOferta = lineasorden.IdProducto ";
	$SQL .= "INNER JOIN opcionesoferta ON opcionesoferta.Id = lineasorden.Talla ";
	$SQL .= "WHERE lineasorden.IdProducto = '$producto' AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND ordenes.EstadoPago = 'ok' AND lineasorden.EstadoPedido <> 'Anulado' GROUP BY opcionesoferta.Id";	
	
	$db->setQuery($SQL);
	$row = $db->execute();
					
	if (mysqli_num_rows($row) > 0) {
		$result = $db->loadObjectList();
		foreach($result as $result1) {
			echo '
				<tr>
					<td height="24px"><label>'.$result1->IdOferta.'</label></td>
					<td height="24px"><label>'.$result1->Id.'</label></td>
					<td height="24px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
					<td height="24px"><label>'.utf8_encode($result1->Opcion).'</label></td>
					<td height="24px"><label>'.$result1->TotalContra.'</label></td>
					<td height="24px"><label>'.$result1->TotalTarjeta.'</label></td>
					<td height="24px"><label>'.$result1->TotalPaypal.'</label></td>
					<td height="24px"><label>'.$result1->TotalT.'</label></td>
					<td height="24px"><label>'.number_format($result1->Precio,2,',','.').'</label></td>
					<td height="24px"><label>'.number_format($result1->Facturacion,2,',','.').'</label></td>
				</tr>';		
				$totalContra = $totalContra + $result1->TotalContra;
				$totalTarjeta = $totalTarjeta + $result1->TotalTarjeta;
				$totalPaypal = $totalPaypal + $result1->TotalPaypal;
				$factTotal = $factTotal + $result1->Facturacion;
		}
	}					
	
	echo '<tr height="25px" style="font-size: 14px; font-variant:small-caps;" >
			<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
			<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
			<td bgcolor="#1F88A7"><strong style="font-size:14px; color:#FFF;">'.$totalContra.'</strong></td>
			<td bgcolor="#1F88A7"><strong style="font-size: 14px; color: #FFF">'.$totalTarjeta.'</strong></td>
			<td bgcolor="#1F88A7"><strong style="font-size: 14px; color: #FFF">'.$totalPaypal.'</strong></td>
			<td bgcolor="#1F88A7"><strong style="font-size: 14px; color: #FFF">'.($totalContra + $totalTarjeta + $totalPaypal).'</strong></td>
			<td bgcolor="#1F88A7"></td>
			<td bgcolor="#1F88A7" style="color: #FFF">'.number_format($factTotal,2,',','.').'</td>
	  </tr><tr><td colspan="10" bgcolor="#FFF">&nbsp;</td></tr>';
				  
}
?>                                 
        