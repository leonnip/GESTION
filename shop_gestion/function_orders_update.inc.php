<?php
function OrdersUpdate($db, $web, $tipoPedido, $listados, $idofertaList, $_ord, $nombreOferta) {
	
	//SI EL PRODUCTO ESTA SEPARADO O NO
	if ($listados == 0)
		$LQL = "AND productos.Listados = '$listados'";
	else
		$LQL = "AND productos.Listados = '$listados' AND productos.IdOferta = '$idofertaList'";
	
	//DE ACUERDO AL TIPO DE PEDIDO QUE SEA	
	if ($tipoPedido == 'contra-rembolso')
		$tipoP = "ordenes.FormaPago = '$tipoPedido'";
	else if ($tipoPedido == 'tarjeta')
		$tipoP = "(ordenes.FormaPago = 'tarjeta' OR ordenes.FormaPago = 'paypal')";

	
	$body = '<table id="resultSearch_Opt" cellpadding="0" cellspacing="0">
			<thead>
				<th colspan="12" align="center">'.$nombreOferta.'</th>
			</thead>
	      	<thead>
		   		<th width="30px">País</th>
			   	<th width="60px">Orden</th>
        		<th width="160px">Fecha</th>
            	<th width="250px">Nombres</th>
                <th width="250px">Producto</th>
                <th width="80px">Imagen</th>
	            <th width="20px">#</th>  
                <th width="100px">Refer</th>                                    
    	        <th width="100px">Talla/Tipo</th>                                    
        	    <th width="100px">Estado</th>
            	<th width="32px">Pago</th>
               	<th width="30px"></th>                                
		    </thead>
    		<!-- PEDIDOS CONTRA-REMBOLSO -->
        	<tbody id="dataCustomerOrder">
            <?php
			//DETECTAMOS LA CANTIDAD DE ORDENES POR TIPO FORMA DE PAGO';
			
			$SQL = "SELECT ordenes.IdOrden, lineasorden.Id ";
			$SQL .= "FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
			$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
			$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
			$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";		
			$SQL .= "WHERE TRIM(lineasorden.EstadoPedido) = 'Transito' ";
			$SQL .= "AND ordenes.EstadoPago ='ok' AND ordenes.FormaPago = '".$tipoPedido."' $LQL";									
			$db->setQuery($SQL);
			$row = $db->execute();
			if (mysqli_num_rows($row) > 0) {
				$result = $db->loadObjectList();
				foreach($result as $result1) {
					$ord[] = array('idorden'=>$result1->IdOrden, 'lineaorden'=>$result1->Id); 
				}
			} else {
				$ord[] = array('idorden'=>0, 'lineasorden'=>0);
			}
			//FIN
											
			//DATOS PARA PRESENTAR  UNIMOS LOS 2 ARREGLOS DE ENVIADOS Y TRANSITO
			$ordenes = @array_merge($_ord, $ord);
			$contOrders = @count($ordenes);		
														
			//FIN
			for ($i = 0; $i <= $contOrders; $i++) {
				$SQL = "SELECT *, 
						ordenes.IdOrden, 
													lineasorden.Id as lineasOrden,
													lineasorden.Cantidad, 
													lineasorden.GastosEnvio as GEnvio, 
													opcionesoferta.Opcion, 
													opcionesoferta.Precio, 
													opcionesoferta.Referencia,
													ordenes.Pais,
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
													direcciones.D_Pais,
													usuarios.Dni, 
													productos.Nombre_Producto
													
													FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden AND ordenes.IdOrden = '".$ordenes[$i]['idorden']."' AND lineasorden.Id = '".$ordenes[$i]['lineaorden']."' ";
					$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
					$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	 				$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
	 				$SQL .= "INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden ";
					$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion AND usuarios.Id = direcciones.D_IdCliente ";
					$SQL .= "WHERE $tipoP AND TRIM(ordenes.EstadoPago) = 'ok'";
					$db->setQuery($SQL);
					$row = $db->execute();
										
					if (mysqli_num_rows($row) > 0) {
						$result1 = $db->loadObject();
						$nomProd_Array = explode(" ", utf8_encode($result1->Nombre_Producto));
						$nomProducto = $nomProd_Array[0] ." ".$nomProd_Array[1]." ".$nomProd_Array[2]." ".$nomProd_Array[3];
						//foreach($result as $result1) {
							$body .= '
								<tr>
									<td height="24px"><img src="images/'.$result1->D_Pais.'.png" width="16px" /></td>
									<td height="24px"><label>'.$result1->IdOrden.'</label></td>
									<td height="24px"><label>'.$result1->FechaOrden.' | '.$result1->Hora.'</label></td>
									<td height="24px"><label>'.utf8_encode(ucwords(strtolower($result1->Nombres))).' ' .utf8_encode(ucwords(strtolower($result1->Apellidos))).'</label></td>
									<td height="24px"><label>'.$nomProducto.'...</label></td>
									<td height="24px">
										<img src="'.$web.'/productos/'.$result1->Nombre.'/'.$result1->Images7.'" width="30px" title="'.utf8_encode($result1->Nombre_Producto).'" />
									</td>
									<td height="24px"><label>'.$result1->Cantidad.'</label></td>
									<td height="24px">';
										if ($result1->Referencia == '0')
											$body .= '<label style="color: red">'.$result1->Referencia.'</label>';
										else
											$body .= '<label>'.$result1->Referencia.'</label>';
										$body .= '
									</td>
									<td height="24px">';
										if ($result1->OptActiva == 1) $body .= '<label>'.utf8_encode($result1->Opcion).'</label>';
											$body .= '
									</td>
									<td height="24px">';										
										if ($result1->EstadoPedido == 'Transito') $body .= '<label style="color: red">Transito</label>';
										if ($result1->EstadoPedido == 'Enviado') $body .= '<label style="color: #0877BF">Enviado</label>';
										if ($result1->EstadoPedido == 'Anulado') $body .= '<label style="color: #000">Anulado</label>';
										if ($result1->EstadoPedido == 'Entregado') $body .= '<label style="color: green">Entregado</label>';
										if ($result1->EstadoPedido == 'No-entregado') $body .= '<label style="color: #09F">No Entregado</label>';
										$body .= '
									</td>
									<td>';
										if ($tipoPedido == 'contra-rembolso')
											$body .= '<img src="images/contrap.png" title="'.$result1->FormaPago.'" style="cursor: pointer" />';
										else
											$body .= '<img src="images/visap.png" title="'.$result1->FormaPago.'" style="cursor: pointer" />';
									$body .= '
									</td>
									<td height="24px"><input class="check" type="checkbox" name="estados[]" value="'.$result1->IdOrden.'|'.$result1->lineasOrden.'" /></td>													
								</tr>
									';
								$cantidad = $cantidad + $result1->Cantidad;
								$array_orders_contra[] = array('nombres'=>utf8_encode($result1->D_Nombres).' '.utf8_encode($result1->D_Apellidos), 'dni'=>$result1->Dni, 
																 'direccion'=>utf8_encode($result1->TipoVia).' '.utf8_encode($result1->Direccion).','.$result1->TipoNumero.','.utf8_encode($result1->Numero).','.utf8_encode($result1->Piso).
																 ','.utf8_encode($result1->Puerta), 'cp'=>$result1->Cp, 'poblacion'=>utf8_encode($result1->Poblacion), 'provincia'=>utf8_encode($result1->Provincia),
															     'telefono'=>utf8_encode($result1->Telefono), 'producto'=>utf8_encode($result1->Nombre_Producto), 'tipo'=>utf8_encode($result1->Opcion),
																 'precio'=>$result1->Precio, 'gastosenvio'=>$result1->GEnvio, 'cantidad'=>$result1->Cantidad, 'total'=>$result1->Total, 'referencias'=>$result1->Referencias,
																 'mensaje'=>$result1->Comentarios, 'idorden'=>$result1->IdOrden, 'pesoreal'=>$result1->PesoReal); 
													//}
						} 									
					}
				$body .= '<tr style="background: #1F88A7;" height="25px">
					<td colspan="5"></td>
					<td><strong style="font-size:14px; color:#FFF;">Pedidos</strong></td>
					<td><strong style="font-size: 14px; color: #FFF">'.$cantidad.'</strong></td><td colspan="3"></td>
					<td></td><td></td>
				</tr>';											                               
$body .= '</tbody>';
return $body;
}
?>