<?php
@session_start();
//=== FUNCION INFORME PEDIDOS ===
function informePedidos($db, $comisionPrensa) {
	//echo '<tr><td colspan="10"><table cellpadding="1" cellspacing="1" bordercolor="#666666" style="border-collapse:collapse;" class="clsTabla">';
	echo '<tbody id="dataCustomer">';									
									
	$fecha1 = $_POST['Fecha1'];
	$fecha2 = $_POST['Fecha2'];
	$usserTram = $_POST['usserTram'];
	
	if($usserTram == trim('oficina'))
		$addSql = "AND ordenes.Tramitado <> 'usuario-web' AND ordenes.Tramitado <> 'call-center' ";
	else
		$addSql = "AND ordenes.Tramitado = '$usserTram' ";
	
	//GUARDAMOS LOS USUARIOS EN UN ARREGLO
	$SQL = "SELECT Usuario FROM administrador";
	$db->setQuery($SQL);
	$result = $db->loadObjectList();
	foreach($result as $result1) 
		$administradores[] = trim($result1->Usuario);
					
	$SQL = "SELECT productos.IdOferta, opcionesoferta.Id, SUM(lineasorden.Cantidad) as Unidades, productos.Nombre_Producto 
			FROM ordenes 
			INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden 
			INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id 
			INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta
			WHERE TRIM(ordenes.EstadoPago) = TRIM('ok') AND TRIM(lineasorden.EstadoPedido) <> TRIM('Anulado') $addSql 
			AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' 
			GROUP BY opcionesoferta.Id ORDER BY productos.IdOferta ASC";
	$db->setQuery($SQL);
	$result = $db->loadObjectList();
	foreach($result as $result1) {
		$arreglo[] = array('IdOferta'=>$result1->IdOferta, 'Tipo'=>$result1->Id);
	}
	$totalOfertas = count($arreglo);
	//print_r($arreglo);
								
	for($i = 0; $i <= $totalOfertas - 1; $i++ ) {							
		$SQL = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id, opcionesoferta.Opcion, opcionesoferta.Precio, opcionesoferta.PrecioSesion, ";
		$SQL .= "opcionesoferta.Iva, SUM(lineasorden.Cantidad) AS Unidades,SUM(lineasorden.Subtotal) as SubTotal, SUM(lineasorden.GastosEnvio) AS GastosEnvio, ordenes.Tramitado ";
		$SQL .= "FROM ordenes ";
		$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
		$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
		$SQL .= "WHERE TRIM(ordenes.EstadoPago) = '".trim('ok')."' AND TRIM(lineasorden.EstadoPedido) <> '".trim('Anulado')."' AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' ";
		$SQL .= "AND productos.IdOferta = ".$arreglo[$i]['IdOferta']." AND opcionesoferta.Id = ".$arreglo[$i]['Tipo']." $addSql ";
		$SQL .= "GROUP BY opcionesoferta.Id, ordenes.Tramitado ORDER BY productos.IdOferta ASC";
		$db->setQuery($SQL);
		$row = $db->execute();									
										
		$ordenWeb = 0;
		$ordenCall = 0;
		$ordenOfi = 0;
		if (mysqli_num_rows($row) > 0) {
			$result = $db->loadObjectList();
			foreach($result as $result1) {															
				$ordenTramitada = rtrim($result1->Tramitado);
				$totalSubtotal = $totalSubtotal;
				
				//if ($ordenTramitada == rtrim('call-center')) {
				if(in_array($ordenTramitada, $administradores)) {
					if($ordenTramitada == trim('usuario-web'))
						$ordenWeb = $ordenWeb + $result1->Unidades;	
					else if ($ordenTramitada == 'call-center')
						$ordenCall = $ordenCall + $result1->Unidades;
					else
						$ordenOfi = $ordenOfi + $result1->Unidades;
				} else {
					$errores = $errores + 1;
				}
				$subtotal = $subtotal + $result1->SubTotal;
				$comision = $comision + (($result1->SubTotal) * ($comisionPrensa));
			}
			$totalOrdersCall = $totalOrdersCall + $ordenCall;
			$totalOrdersWeb = $totalOrdersWeb + $ordenWeb;	
			$totalOrdersOfi = $totalOrdersOfi + $ordenOfi;
											
			$facturacion = ($ordenCall + $ordenWeb + $ordenOfi)*$result1->Precio;
			echo '
				<tr height="20px">
					<td width="50px"><label>'.$result1->IdOferta.'</label></td>
					<td width="300px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
					<td width="150px"><label>'.utf8_encode($result1->Opcion).'</label></td>
					<td width="100px"><label>'.$ordenOfi.'</label></td>
					<td width="100px"><label>'.$ordenCall.'</label></td>
					<td width="100px"><label>'.$ordenWeb.'</label></td>
					<td width="100px"><label>'.($ordenCall + $ordenWeb + $ordenOfi).'</label></td>
					<td width="75px"><label>'.number_format($result1->Precio,2,',','.').'</label></td>
					<td width="75px" bgcolor="#71BA00"><label style="color: white; font-family: tahoma">'.number_format($result1->PrecioSesion,2,',','.').'</label></td>
					<td width="100px"><label>'.number_format($facturacion,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format((($facturacion)*($comisionPrensa)),2,',','.').'</label></td>
				</tr>
			';			
		} 
	}
	
	$graphInforme[] = array('fact_total'=>$subtotal, 'aport_bruta'=>$aportBruta, 'comision_patner'=>$comision, 'aport_neta'=>$aportNeta);
	
	echo '
		</tbody>
		<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
           	<tr height="30px" style="color: white; font-size: 14px; font-variant:small-caps;">
				<td bgcolor="#1F88A7"></td>
               	<td bgcolor="#1F88A7" align="center"><span>TOTALES</span></td>
				<td bgcolor="#1F88A7"></td>
				<td bgcolor="#1F88A7"><span>'.$totalOrdersOfi.'</span></td>
                <td bgcolor="#1F88A7"><span>'.$totalOrdersCall.'</span></td>
                <td bgcolor="#1F88A7"><span>'.$totalOrdersWeb.'</span></td>
                <td bgcolor="#1F88A7"><span>'.($totalOrdersWeb + $totalOrdersCall + $totalOrdersOfi).'</span></td>
                <td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
    	        <td bgcolor="#1F88A7"><span>'.number_format($subtotal,2,',','.').'</span></td>
                <td bgcolor="#1F88A7"><span>'.number_format($comision,2,',','.').'</span></td>
           	</tr>
	      </tfoot>	
	';
	echo '</table></td></tr>';
	print_r($err);
	
	return $graphInforme;
}
//=== FIN INFORME PEDIDO ===




//=== ESTA FUNCION ES PARA CALCULAR DEVOLUCIONES Y NO ENTREGADOS ===
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
				
	echo '<tr><td colspan="10"><table class="_importante" cellpadding="1" cellspacing="1" bordercolor="#666666" style="display: none">';
	echo '<tbody id="dataCustomer">';									
									
	$fecha1 = $_POST['Fecha1'];
	$fecha2 = $_POST['Fecha2'];
	$usserTram = $_POST['usserTram'];
	
	if($usserTram == trim('oficina'))
		$addSql = "AND ordenes.Tramitado <> 'usuario-web' AND ordenes.Tramitado <> 'call-center' ";
	else
		$addSql = "AND ordenes.Tramitado = '$usserTram' ";
	
	//GUARDAMOS LOS USUARIOS EN UN ARREGLO
	$SQL = "SELECT Usuario FROM administrador";
	$db->setQuery($SQL);
	$result = $db->loadObjectList();
	foreach($result as $result1) 
		$administradores[] = trim($result1->Usuario);
					
	/*$SQL = "SELECT productos.IdOferta, opcionesoferta.Id, SUM(lineasorden.Cantidad) as Unidades, productos.Nombre_Producto ";
	$SQL .= "FROM ordenes ";
	$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
	$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
	$SQL .= "WHERE TRIM(ordenes.EstadoPago) = '".trim('ok')."' AND TRIM(lineasorden.EstadoPedido) <> '".trim('Anulado')."' AND TRIM(lineasorden.EstadoPedido) IN ('No-entregado', 'Devuelto') ";
	$SQL .= "AND lineasorden.FechaCambioEstado >= '$fecha1' AND lineasorden.FechaCambioEstado <= '$fecha2' ";
	$SQL .= "GROUP BY opcionesoferta.Id ORDER BY productos.IdOferta ASC";*/
	
	$SQL = "SELECT productos.IdOferta, opcionesoferta.Id, SUM(lineasdevolucion.Cantidad) as Unidades, productos.Nombre_Producto 
FROM ordenes 
INNER JOIN lineasdevolucion ON ordenes.IdOrden = lineasdevolucion.IdOrden 
INNER JOIN opcionesoferta ON lineasdevolucion.Talla = opcionesoferta.Id 
INNER JOIN productos ON lineasdevolucion.IdProducto = productos.IdOferta 
WHERE TRIM(ordenes.EstadoPago) = 'ok' 
AND lineasdevolucion.FechaDevolucion >= '$fecha1' AND lineasdevolucion.FechaDevolucion <= '$fecha2' $addSql
GROUP BY opcionesoferta.Id ORDER BY productos.IdOferta ASC";
	$db->setQuery($SQL);
	$result = $db->loadObjectList();
	foreach($result as $result1) {
		$arreglo[] = array('IdOferta'=>$result1->IdOferta, 'Tipo'=>$result1->Id);
	}
	$totalOfertas = count($arreglo);
	//print_r($arreglo);
		
	$subtotal = 0;
	$comision = 0;	
								
	for($i = 0; $i <= $totalOfertas - 1; $i++ ) {								
		/*$SQL = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id, opcionesoferta.Opcion, opcionesoferta.Precio, opcionesoferta.PrecioSesion, ";
		$SQL .= "opcionesoferta.Iva, SUM(lineasorden.Cantidad) AS Unidades,SUM(lineasorden.Subtotal) as SubTotal, SUM(lineasorden.GastosEnvio) AS GastosEnvio, ordenes.Tramitado ";
		$SQL .= "FROM ordenes ";
		$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
		$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
		$SQL .= "WHERE TRIM(ordenes.EstadoPago) = '".trim('ok')."' AND TRIM(lineasorden.EstadoPedido) <> '".trim('Anulado')."' AND TRIM(lineasorden.EstadoPedido) IN ('No-entregado', 'Devuelto') ";
		$SQL .= "AND lineasorden.FechaCambioEstado >= '$fecha1' AND lineasorden.FechaCambioEstado <= '$fecha2' AND productos.IdOferta = ".$arreglo[$i]['IdOferta']." AND opcionesoferta.Id = ".$arreglo[$i]['Tipo']." ";
		$SQL .= "GROUP BY opcionesoferta.Id, ordenes.Tramitado ORDER BY productos.IdOferta ASC";*/
		
		$SQL = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id, opcionesoferta.Opcion, opcionesoferta.Precio, opcionesoferta.PrecioSesion, ";
		$SQL .= "opcionesoferta.Iva, SUM(lineasdevolucion.Cantidad) AS Unidades,SUM(lineasdevolucion.Subtotal) as SubTotal, SUM(lineasdevolucion.GastosEnvio) AS GastosEnvio, ordenes.Tramitado ";
		$SQL .= "FROM ordenes ";
		$SQL .= "INNER JOIN lineasdevolucion ON ordenes.IdOrden = lineasdevolucion.IdOrden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasdevolucion.Talla = opcionesoferta.Id ";
		$SQL .= "INNER JOIN productos ON lineasdevolucion.IdProducto = productos.IdOferta ";
		$SQL .= "WHERE TRIM(ordenes.EstadoPago) = 'ok' ";
		$SQL .= "AND lineasdevolucion.FechaDevolucion >= '$fecha1' AND lineasdevolucion.FechaDevolucion <= '$fecha2' AND productos.IdOferta = ".$arreglo[$i]['IdOferta']." AND opcionesoferta.Id = ".$arreglo[$i]['Tipo']." $addSql ";
		$SQL .= "GROUP BY opcionesoferta.Id, ordenes.Tramitado ORDER BY productos.IdOferta ASC";
		$db->setQuery($SQL);
		$row = $db->execute();									
										
		$ordenWeb = 0;
		$ordenCall = 0;
		$ordenOfi = 0;
		if (mysqli_num_rows($row) > 0) {
			$result = $db->loadObjectList();
			foreach($result as $result1) {												
				$ordenTramitada = trim($result1->Tramitado);
				$totalSubtotal = $totalSubtotal;
				
				if(in_array($ordenTramitada, $administradores)) {
					if($ordenTramitada == trim('usuario-web'))
						$ordenWeb = $ordenWeb + $result1->Unidades;	
					else if($ordenTramitada == 'call-center')
						$ordenCall = $ordenCall + $result1->Unidades;
					else 
						$ordenOfi = $ordenOfi + $result1->Unidades;
				} else {
					$errores = $errores + 1;
				}
				$subtotal = $subtotal + $result1->SubTotal;
				$comision = $comision + (($result1->SubTotal) * ($comisionPrensa));
			}
			$totalOrdersCall = $totalOrdersCall + $ordenCall;
			$totalOrdersWeb = $totalOrdersWeb + $ordenWeb;	
			$totalOrdersOfi = $totalOrdersOfi + $ordenOfi;
											
			$facturacion = ($ordenCall + $ordenWeb + $ordenOfi)*$result1->Precio;
			echo '
				<tr height="20px">
					<td width="50px"><label>'.$result1->IdOferta.'</label></td>
					<td width="300px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
					<td width="150px"><label>'.utf8_encode($result1->Opcion).'</label></td>
					<td width="100px"><label>'.$ordenOfi.'</label></td>
					<td width="100px"><label>'.$ordenCall.'</label></td>
					<td width="100px"><label>'.$ordenWeb.'</label></td>
					<td width="100px"><label>'.($ordenCall + $ordenWeb + $ordenOfi).'</label></td>
					<td width="75px"><label>'.number_format($result1->Precio,2,',','.').'</label></td>
					<td width="75px" bgcolor="#71BA00"><label style="color: white; font-family: tahoma">'.number_format($result1->PrecioSesion,2,',','.').'</label></td>
					<td width="100px"><label>'.number_format($facturacion,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format((($facturacion)*($comisionPrensa)),2,',','.').'</label></td>
				</tr>
			';
		} 
	}
	
	$graphDevoluciones[] = array('fact_total'=>$subtotal, 'aport_bruta'=>$aportBruta, 'comision_patner'=>$comision, 'aport_neta'=>$aportNeta);
	
	echo '
		</tbody>
		<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
           	<tr height="30px" style="color: white; font-size: 14px; font-variant:small-caps;">
       	       	<td bgcolor="#1F88A7" colspan="3" align="center"><span>TOTALES</span></td>
				<td bgcolor="#1F88A7"><span>'.$totalOrdersOfi.'</span></td>
           	    <td bgcolor="#1F88A7"><span>'.$totalOrdersCall.'</span></td>
               	<td bgcolor="#1F88A7"><span>'.$totalOrdersWeb.'</span></td>
	               <td bgcolor="#1F88A7"><span>'.($totalOrdersWeb + $totalOrdersCall + $totalOrdersOfi).'</span></td>
                <td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
    	        <td bgcolor="#1F88A7"><span>'.number_format($subtotal,2,',','.').'</span></td>
           	    <td bgcolor="#1F88A7"><span>'.number_format($comision,2,',','.').'</span></td>
	       	</tr>
	     </tfoot>	
	';
	echo '</table></td></tr>';
	
	return $graphDevoluciones;
}
//=== FIN NO ENTREGADOS Y DEVOLUCIONES ==

?>