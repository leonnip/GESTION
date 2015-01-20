<?php
//=== FUNCION FACTURACION USUARIO ===
function factUsuario($db, $comisionPrensa) {
	echo '<tr><td colspan="10"><table cellpadding="1" cellspacing="1" bordercolor="#666666" style="border-collapse:collapse;">';
	echo '<tbody id="dataCustomer">';									
									
									//Recogemos los datos que enviarmos por el formulario
									$fecha1 = $_POST['Fecha1'];
									$fecha2 = $_POST['Fecha2'];
									$usuarioAdmin = $_POST['selectUsuario'];
									
									/*$SQL = "SELECT productos.IdOferta, opcionesoferta.Id, SUM(lineasorden.Cantidad) as Unidades, productos.Nombre_Producto ";
									$SQL .= "FROM ordenes ";
									$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
									$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
									$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
									$SQL .= "WHERE ordenes.EstadoPago = 'ok' AND lineasorden.EstadoPedido <> 'Anulado' AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' ";
									$SQL .= "GROUP BY opcionesoferta.Id ORDER BY productos.IdOferta ASC";
									$db->setQuery($SQL);
									$result = $db->loadObjectList();
									foreach($result as $result1) {
										$arreglo[] = array('IdOferta'=>$result1->IdOferta, 'Tipo'=>$result1->Id);
										}
									$totalOfertas = count($arreglo);
									//print_r($arreglo);
									
									for($i = 0; $i <= $totalOfertas - 1; $i++ ) {	*/							
										$SQL = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id, opcionesoferta.Opcion, opcionesoferta.Precio, 
												opcionesoferta.PrecioSesion, opcionesoferta.Iva, SUM(lineasorden.Cantidad) AS Unidades, 									
												SUM(lineasorden.Subtotal) as SubTotal, SUM(lineasorden.GastosEnvio) AS GastosEnvio, ordenes.Tramitado 
												FROM ordenes 
												INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden 
												INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id 
												INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta 
												WHERE TRIM(ordenes.EstadoPago) = '".trim('ok')."' AND TRIM(lineasorden.EstadoPedido) <> '".trim('Anulado')."' 
												AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' 
												AND ordenes.Tramitado = '$usuarioAdmin' 
												GROUP BY opcionesoferta.Id, ordenes.Tramitado ORDER BY productos.IdOferta ASC";
										$db->setQuery($SQL);
										$row = $db->execute();									
										
										$ordenUss = 0;
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {												
												$ordenTramitada = $result1->Tramitado;
												$totalSubtotal = $totalSubtotal;
												$ordenUss = $result1->Unidades;
												/*
												if ($ordenTramitada == rtrim('call-center')) {
													$ordenCall = $ordenCall + $result1->Unidades;
												} else {
													$ordenWeb = $ordenWeb + $result1->Unidades;
												}
												*/
												$subtotal = $subtotal + $result1->SubTotal;
												$comision = $comision + ($result1->SubTotal* ($comisionPrensa));
											/*}*/
											$totalOrdersUss = $totalOrdersUss + $ordenUss;
											
											$facturacion = ($ordenUss)*$result1->Precio;
											$baseimponible = $facturacion/$result1->Iva;
											$baseImponibleTotal = $baseImponibleTotal + $baseimponible;
											
											echo '
												<tr height="20px">
													<td width="50px"><label>'.$result1->IdOferta.'</label></td>
													<td width="300px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
													<td width="150px"><label>'.utf8_encode($result1->Opcion).'</label></td>
													<td width="100px"><label>'.$ordenUss.'</label></td>													
													<td width="75px"><label>'.number_format($result1->Precio,2,',','.').'</label></td>
													<td width="75px"><label>'.number_format($result1->PrecioSesion,2,',','.').'</label></td>
													<td width="100px"><label>'.number_format($facturacion,2,',','.').'</label></td>
													<td width="100px"><label>'.number_format($baseimponible,2,',','.').'</label></td>
													<td width="100px"><label>'.($ordenCall + $ordenWeb).'</label></td>
													<td width="75px"><label>'.number_format((($facturacion)*($comisionPrensa)),2,',','.').'</label></td>
												</tr>
												';
											}
										} 
									/*}*/
									
									$graph[] = array('fact_total'=>$subtotal, 'base_imponible'=>$baseImponibleTotal, 'aport_bruta'=>$aportBruta, 'comision_patner'=>$comision, 'aport_neta'=>$aportNeta, 'productos'=>$totalOrdersUss);
									
									echo '
									</tbody>
									<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
    	                            	<tr height="30px" style="color: white; font-size: 14px; font-variant:small-caps;">
											<td bgcolor="#1F88A7"></td>
        	                            	<td bgcolor="#1F88A7" align="center"><span>TOTALES</span></td>
											<td bgcolor="#1F88A7"></td>
            	                            <td bgcolor="#1F88A7"><span>'.$totalOrdersUss.'</span></td>											
                	                        <td bgcolor="#1F88A7"></td>											
                    	                    <td bgcolor="#1F88A7"></td>
											<td bgcolor="#1F88A7"><span>'.number_format($subtotal,2,',','.').'</span></td>
                        	                <td bgcolor="#1F88A7"><span>'.number_format($baseImponibleTotal,2,',','.').'</span></td><td bgcolor="#1F88A7"></td>                            	            
                                	        <td bgcolor="#1F88A7"><span>'.number_format($comision,2,',','.').'</span></td>
                                    	</tr>
	                                </tfoot>';
									echo '</table></td></tr>';
	return $graph;
}


function devoluciones($db, $comisionPrensa) {
	echo '
		<tr><td colspan="10" style="background:white">
			<div id="importantePedido" style="width:100%; margin-top: 15px">
    	      	<div style="height:20px">
			      	<img id="import" src="images/import.png" />
        		    <label id="text">Devoluciones y No Entregados</label>
	            </div>
    	        <div class="caption-control">
					<span class="caption-control-wrap">
						<i></i>
					</span>
				</div>
			</div>			
		</td></tr>';
				
	echo '<tr><td colspan="10"><table class="_importante" cellpadding="1" cellspacing="1" bordercolor="#666666" style="border-collapse:collapse; display: none">';
	
	echo '<tbody id="dataCustomer">';									
									
									//Recogemos los datos que enviarmos por el formulario
									$fecha1 = $_POST['Fecha1'];
									$fecha2 = $_POST['Fecha2'];
									$usuarioAdmin = $_POST['selectUsuario'];
									
									/*$SQL = "SELECT productos.IdOferta, opcionesoferta.Id, SUM(lineasorden.Cantidad) as Unidades, productos.Nombre_Producto ";
									$SQL .= "FROM ordenes ";
									$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
									$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
									$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
									$SQL .= "WHERE ordenes.EstadoPago = 'ok' AND lineasorden.EstadoPedido <> 'Anulado' AND lineasorden.FechaCambioEstado >= '$fecha1' AND lineasorden.FechaCambioEstado <= '$fecha2' ";
									$SQL .= "GROUP BY opcionesoferta.Id ORDER BY productos.IdOferta ASC";
									$db->setQuery($SQL);
									$result = $db->loadObjectList();
									foreach($result as $result1) {
										$arreglo[] = array('IdOferta'=>$result1->IdOferta, 'Tipo'=>$result1->Id);
										}
									$totalOfertas = count($arreglo);
									//print_r($arreglo);
									
									$subtotal = 0;
									$totalOrdersUss = 0;
									
									for($i = 0; $i <= $totalOfertas - 1; $i++ ) {	*/							
										/*$SQL = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id, opcionesoferta.Opcion, opcionesoferta.Precio, opcionesoferta.PrecioSesion, opcionesoferta.Iva, SUM(lineasorden.Cantidad) AS Unidades, 									SUM(lineasorden.Subtotal) as SubTotal, SUM(lineasorden.GastosEnvio) AS GastosEnvio, ordenes.Tramitado ";
										$SQL .= "FROM ordenes ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
										$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
										$SQL .= "WHERE ordenes.EstadoPago = '".trim('ok')."' AND lineasorden.EstadoPedido <> '".trim('Anulado')."' AND lineasorden.EstadoPedido IN ('No-entregado', 'Devuelto') ";
										$SQL .= "AND lineasorden.FechaCambioEstado >= '$fecha1' AND lineasorden.FechaCambioEstado <= '$fecha2' ";
										$SQL .= "AND ordenes.Tramitado = '$usuarioAdmin' ";
										$SQL .= "GROUP BY opcionesoferta.Id, ordenes.Tramitado ORDER BY productos.IdOferta ASC";*/
										
										$SQL = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id, opcionesoferta.Opcion, opcionesoferta.Precio, opcionesoferta.PrecioSesion, 
opcionesoferta.Iva, SUM(lineasdevolucion.Cantidad) AS Unidades,SUM(lineasdevolucion.Subtotal) as SubTotal, SUM(lineasdevolucion.GastosEnvio) AS GEnvio, ordenes.Tramitado 
FROM ordenes 
INNER JOIN lineasdevolucion ON ordenes.IdOrden = lineasdevolucion.IdOrden 
INNER JOIN opcionesoferta ON lineasdevolucion.Talla = opcionesoferta.Id 
INNER JOIN productos ON lineasdevolucion.IdProducto = productos.IdOferta 
WHERE TRIM(ordenes.EstadoPago) = 'ok' AND ordenes.Tramitado = '$usuarioAdmin' 
AND lineasdevolucion.FechaDevolucion >= '$fecha1' AND lineasdevolucion.FechaDevolucion <= '$fecha2'
GROUP BY opcionesoferta.Id, lineasdevolucion.TipoDevolucion ORDER BY productos.IdOferta ASC";
										$db->setQuery($SQL);
										$row = $db->execute();									
										
										$ordenUss = 0;
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {												
												$ordenTramitada = $result1->Tramitado;
												$totalSubtotal = $totalSubtotal;
												$ordenUss = $result1->Unidades;
												/*
												if ($ordenTramitada == rtrim('call-center')) {
													$ordenCall = $ordenCall + $result1->Unidades;
												} else {
													$ordenWeb = $ordenWeb + $result1->Unidades;
												}
												*/
												$subtotal = $subtotal + $result1->SubTotal;
												$comision = $comision + ($result1->SubTotal* ($comisionPrensa));
											/*}*/
											$totalOrdersUss = $totalOrdersUss + $ordenUss;
											
											$facturacion = ($ordenUss)*$result1->Precio;
											$baseimponible = $facturacion/$result1->Iva;
											$baseImponibleTotal = $baseImponibleTotal + $baseimponible;
											
											echo '
												<tr height="20px">
													<td width="50px"><label>'.$result1->IdOferta.'</label></td>
													<td width="300px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
													<td width="150px"><label>'.utf8_encode($result1->Opcion).'</label></td>
													<td width="100px"><label>'.$ordenUss.'</label></td>													
													<td width="75px"><label>'.number_format($result1->Precio,2,',','.').'</label></td>
													<td width="75px"><label>'.number_format($result1->PrecioSesion,2,',','.').'</label></td>
													<td width="100px"><label>'.number_format($facturacion,2,',','.').'</label></td>
													<td width="100px"><label>'.number_format($baseimponible,2,',','.').'</label></td>
													<td width="100px"><label>'.($ordenCall + $ordenWeb).'</label></td>
													<td width="75px"><label>'.number_format((($facturacion)*($comisionPrensa)),2,',','.').'</label></td>
												</tr>
												';
											}
										} 
									/*}*/
									
									$graphDevoluciones[] = array('fact_total'=>$subtotal, 'base_imponible'=>$baseImponibleTotal, 'aport_bruta'=>$aportBruta, 'comision_patner'=>$comision, 'aport_neta'=>$aportNeta, 'productos'=>$totalOrdersUss);
									
									echo '
									</tbody>
									<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
    	                            	<tr height="30px" style="color: white; font-size: 14px; font-variant:small-caps;">
											<td bgcolor="#1F88A7"></td>
        	                            	<td bgcolor="#1F88A7" align="center"><span>TOTALES</span></td>
											<td bgcolor="#1F88A7"></td>
            	                            <td bgcolor="#1F88A7"><span>'.$totalOrdersUss.'</span></td>											
                	                        <td bgcolor="#1F88A7"></td>											
                    	                    <td bgcolor="#1F88A7"></td>
											<td bgcolor="#1F88A7"><span>'.number_format($subtotal,2,',','.').'</span></td>
                        	                <td bgcolor="#1F88A7"><span>'.number_format($baseImponibleTotal,2,',','.').'</span></td><td bgcolor="#1F88A7"></td>                            	            
                                	        <td bgcolor="#1F88A7"><span>'.number_format($comision,2,',','.').'</span></td>
                                    	</tr>
										<tr height="22px"><td colspan="7" bgcolor="#fff"></td></tr>
	                                </tfoot>';
									echo '</table></td></tr>';
									
	return $graphDevoluciones;
}

?>