<?php
@session_start();
//=== FUNCION INFORME PEDIDOS ===
function informePedidos($db, $comisionPrensa) {
	//echo '<tr><td colspan="10"><table cellpadding="1" cellspacing="1" bordercolor="#666666" style="border-collapse:collapse;" class="clsTabla">';
	echo '<tbody id="dataCustomer">';									
									
	$fecha1 = $_POST['Fecha1'];
	$fecha2 = $_POST['Fecha2'];
	
	//GUARDAMOS LOS USUARIOS EN UN ARREGLO
	$SQL = "SELECT Usuario FROM administrador";
	$db->setQuery($SQL);
	$result = $db->loadObjectList();
	foreach($result as $result1) 
		$administradores[] = trim($result1->Usuario);
					
	$SQL = "SELECT productos.IdOferta, opcionesoferta.Id, SUM(lineasorden.Cantidad) as Unidades, productos.Nombre_Producto ";
	$SQL .= "FROM ordenes ";
	$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
	$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
	$SQL .= "WHERE TRIM(ordenes.EstadoPago) = '".trim('ok')."' AND TRIM(lineasorden.EstadoPedido) <> '".trim('Anulado')."' AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' ";
	$SQL .= "GROUP BY opcionesoferta.Id ORDER BY productos.IdOferta ASC";
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
		$SQL .= "AND productos.IdOferta = ".$arreglo[$i]['IdOferta']." AND opcionesoferta.Id = ".$arreglo[$i]['Tipo']." ";
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
					<td width="100px"><label>'.number_format($facturacion,2,',', '').'</label></td>
					<td width="75px"><label>'.number_format((($facturacion)*($comisionPrensa)),2,',','').'</label></td>
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
AND lineasdevolucion.FechaDevolucion >= '$fecha1' AND lineasdevolucion.FechaDevolucion <= '$fecha2' 
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
		$SQL .= "AND lineasdevolucion.FechaDevolucion >= '$fecha1' AND lineasdevolucion.FechaDevolucion <= '$fecha2' AND productos.IdOferta = ".$arreglo[$i]['IdOferta']." AND opcionesoferta.Id = ".$arreglo[$i]['Tipo']." ";
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




//=== ESTA FUNCION ES PARA EL TAB INFORME COMPLETO ===
function cuadroCompleto($db, $comisionPrensa) {
	if (isset($_SESSION['IdAdmin'])) {
		//echo '<tr><td colspan="15"><table cellpadding="1" cellspacing="1" bordercolor="#666666" style="border-collapse:collapse;">';		
		echo '<tbody>';						
									
		$fecha1 = $_POST['Fecha1'];
		$fecha2 = $_POST['Fecha2'];
		
		//GUARDAMOS LOS USUARIOS EN UN ARREGLO
		$SQL = "SELECT Usuario FROM administrador";
		$db->setQuery($SQL);
		$result = $db->loadObjectList();
		foreach($result as $result1) 
			$administradores[] = trim($result1->Usuario);
									
		$SQL = "SELECT productos.IdOferta, opcionesoferta.Id, SUM(lineasorden.Cantidad) as Unidades, productos.Nombre_Producto ";
		$SQL .= "FROM ordenes ";
		$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
		$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
		$SQL .= "WHERE TRIM(ordenes.EstadoPago) = '".trim('ok')."' AND TRIM(lineasorden.EstadoPedido) <> '".trim('Anulado')."' AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' ";
		$SQL .= "GROUP BY opcionesoferta.Id ORDER BY productos.IdOferta ASC";
		$db->setQuery($SQL);
		$result = $db->loadObjectList();
		foreach($result as $result1) {
			$arreglo[] = array('IdOferta'=>$result1->IdOferta, 'Tipo'=>$result1->Id);
		}
		$totalOfertas = count($arreglo);
		//print_r($arreglo);
									
		for($i = 0; $i <= $totalOfertas - 1; $i++ ) {								
		$SQL = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id, opcionesoferta.OptActiva, opcionesoferta.Opcion, opcionesoferta.Precio, opcionesoferta.PrecioSesion, opcionesoferta.Iva, ";
		$SQL .= "SUM(lineasorden.Cantidad) AS Unidades, SUM(lineasorden.Subtotal) as SubTotal, SUM(lineasorden.GastosEnvio) AS GastosEnvio, ordenes.Tramitado ";
		$SQL .= "FROM ordenes ";
		$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
		$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
		$SQL .= "WHERE TRIM(ordenes.EstadoPago) = '".trim('ok')."' AND TRIM(lineasorden.EstadoPedido) <> '".trim('Anulado')."' ";
		$SQL .= "AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND productos.IdOferta = ".$arreglo[$i]['IdOferta']." AND opcionesoferta.Id = ".$arreglo[$i]['Tipo']." ";
		$SQL .= "GROUP BY opcionesoferta.Id, ordenes.Tramitado ORDER BY productos.IdOferta ASC";
		$db->setQuery($SQL);
		$row = $db->execute();									
										
		$ordenWeb = 0;
		$ordenCall = 0;
		$ordenOfi = 0;
		if (mysqli_num_rows($row) > 0) {
			$result = $db->loadObjectList();
			foreach($result as $result1) {												
				$ordenTramitada = $result1->Tramitado;
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
				$subtotal = $subtotal + ($result1->SubTotal/$result1->Iva);
				$comision = $comision + (($result1->SubTotal/$result1->Iva)* ($comisionPrensa));
			}
			$totalOrdersCall = $totalOrdersCall + $ordenCall;
			$totalOrdersWeb = $totalOrdersWeb + $ordenWeb;	
			$totalOrdersOfi = $totalOrdersOfi + $ordenOfi;
											
			$facturacion = (($ordenOfi + $ordenCall + $ordenWeb)*$result1->Precio)/$result1->Iva;			
			$precioSesionTotal = ($result1->PrecioSesion/$result1->Iva) * ($ordenCall + $ordenWeb + $ordenOfi);			
			$margenBrutoUnitario = ($result1->Precio/$result1->Iva) - ($result1->PrecioSesion/$result1->Iva);			
			$aportacionBruta = (($result1->Precio/$result1->Iva) - ($result1->PrecioSesion/$result1->Iva)) * ($ordenCall + $ordenWeb + $ordenOfi);			
			$comisionUnidad = ($result1->Precio/$result1->Iva) * ($comisionPrensa);			
			$comisionTotal = (($result1->Precio/$result1->Iva)*($comisionPrensa)) * ($ordenCall + $ordenWeb + $ordenOfi);
											
			echo '												
				<tr height="20px">													
					<td width="50px"><label>'.$result1->IdOferta.'</label></td>
					<td width="300px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
					<td width="150px"><label>';
						if($result1->OptActiva == 1)
						    echo utf8_encode($result1->Opcion);						
					echo '
					</label></td>
					<td width="100px"><label>'.$ordenOfi.'</label></td>
					<td width="100px"><label>'.$ordenCall.'</label></td>
					<td width="100px"><label>'.$ordenWeb.'</label></td>
					<td width="100px"><label>'.($ordenCall + $ordenWeb + $ordenOfi).'</label></td>
					<td width="75px"><label>'.number_format(($result1->Precio/$result1->Iva),2,',','.').'</label></td>													
					<td width="100px" bgcolor="#71BA00"><label>'.number_format($facturacion,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format(($result1->PrecioSesion/$result1->Iva),2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($precioSesionTotal,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($margenBrutoUnitario,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($aportacionBruta,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($comisionUnidad,2,',','.').'</label></td>
					<td width="75px" bgcolor="#73DF72"><label>'.number_format($comisionTotal,2,',','.').'</label></td>
													
					<td bgcolor="#71BA00"><label>'.number_format(($aportacionBruta - $comisionTotal),2,',','.').'</label></td>
				</tr>
			';
			//TOTALES
			$aportBruta = $aportBruta + $aportacionBruta;
			$aportNeta = $aportNeta + ($aportacionBruta - $comisionTotal);
		} 
	}
							
	$graph[] = array('fact_total'=>$subtotal, 'aport_bruta'=>$aportBruta, 'comision_patner'=>$comision, 'aport_neta'=>$aportNeta);
									
	echo '
		</tbody>
		<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
    	  	<tr height="30px" style="color: white; font-size: 14px; font-variant:small-caps;">
               	<td colspan="3" align="center" bgcolor="#1F88A7"><span>TOTALES</span></td>
				<td bgcolor="#1F88A7"><span>'.$totalOrdersOfi.'</span></td>
                <td bgcolor="#1F88A7"><span>'.$totalOrdersCall.'</span></td>
                <td bgcolor="#1F88A7"><span>'.$totalOrdersWeb.'</span></td>
                <td bgcolor="#1F88A7"><span>'.($totalOrdersWeb + $totalOrdersCall + $totalOrdersOfi).'</span></td>
				<td bgcolor="#1F88A7"></td>
                <td bgcolor="#1F88A7"><span>'.number_format($subtotal,2,',','.').'</span></td>
				<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
				<td bgcolor="#1F88A7"></td>
				<td bgcolor="#1F88A7">'.number_format($aportBruta,2,',','.').'</td>
				<td bgcolor="#1F88A7"></td>
                <td bgcolor="#1F88A7"><span>'.number_format($comision,2,',','.').'</span></td>
				<td bgcolor="#1F88A7"><span>'.number_format($aportNeta,2,',','.').'</span></td>	           
            </tr>										
	    </tfoot>';
		echo '</table></td></tr>';
	} 
	return $graph;
}
//=== FIN INFORME COMPLETO ===


//===  FUNCIÓN INFORME DEVOLUCIONES CUADRO COMPLETO ===
function cuadroCompletoDevol($db, $comisionPrensa) {
	if (isset($_SESSION['IdAdmin'])) {
		
	echo '
		<tr><td colspan="16" style="background:white">
			<div id="importantePedido" style="width:100%; margin-top: 16px">
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
				
	echo '<tr><td colspan="16"><table class="_importante" cellpadding="1" cellspacing="1" bordercolor="#666666" style="border-collapse:collapse; display: none">';
				
		echo '<tbody>';						
									
		$fecha1 = $_POST['Fecha1'];
		$fecha2 = $_POST['Fecha2'];
		
		//GUARDAMOS LOS USUARIOS EN UN ARREGLO
		$SQL = "SELECT Usuario FROM administrador";
		$db->setQuery($SQL);
		$result = $db->loadObjectList();
		foreach($result as $result1) 
			$administradores[] = trim($result1->Usuario);
									
		require_once('conexion/conexion.inc.php');
		$db = DataBase::getInstance();
									
		$SQL = "SELECT productos.IdOferta, opcionesoferta.Id, SUM(lineasdevolucion.Cantidad) as Unidades, productos.Nombre_Producto ";
		$SQL .= "FROM ordenes ";
		$SQL .= "INNER JOIN lineasdevolucion ON ordenes.IdOrden = lineasdevolucion.IdOrden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasdevolucion.Talla = opcionesoferta.Id ";
		$SQL .= "INNER JOIN productos ON lineasdevolucion.IdProducto = productos.IdOferta ";
		$SQL .= "WHERE TRIM(ordenes.EstadoPago) = 'ok' ";
		$SQL .= "AND lineasdevolucion.FechaDevolucion >= '$fecha1' AND lineasdevolucion.FechaDevolucion <= '$fecha2' ";
		$SQL .= "GROUP BY opcionesoferta.Id ORDER BY productos.IdOferta ASC";
		$db->setQuery($SQL);
		$result = $db->loadObjectList();
		foreach($result as $result1) {
			$arreglo[] = array('IdOferta'=>$result1->IdOferta, 'Tipo'=>$result1->Id);
		}
		$totalOfertas = count($arreglo);
		//print_r($arreglo);
		
		$subtotal = 0;
		$aportBruta = 0;
		$comision = 0;
		$aportNeta = 0;
											
		for($i = 0; $i <= $totalOfertas - 1; $i++ ) {								
		$SQL = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id, opcionesoferta.Opcion, opcionesoferta.Precio, opcionesoferta.PrecioSesion, ";
		$SQL .= "opcionesoferta.Iva, SUM(lineasdevolucion.Cantidad) AS Unidades, SUM(lineasdevolucion.Subtotal) as SubTotal, SUM(lineasdevolucion.GastosEnvio) AS GastosEnvio, ordenes.Tramitado ";
		$SQL .= "FROM ordenes ";
		$SQL .= "INNER JOIN lineasdevolucion ON ordenes.IdOrden = lineasdevolucion.IdOrden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasdevolucion.Talla = opcionesoferta.Id ";
		$SQL .= "INNER JOIN productos ON lineasdevolucion.IdProducto = productos.IdOferta ";
		$SQL .= "WHERE TRIM(ordenes.EstadoPago) = TRIM('ok') ";
		$SQL .= "AND lineasdevolucion.FechaDevolucion >= '$fecha1' AND lineasdevolucion.FechaDevolucion <= '$fecha2' AND productos.IdOferta = '".$arreglo[$i]['IdOferta']."' AND opcionesoferta.Id = '".$arreglo[$i]['Tipo']."' ";
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
				$subtotal = $subtotal + ($result1->SubTotal/$result1->Iva);
				$comision = $comision + (($result1->SubTotal/$result1->Iva)* ($comisionPrensa));
			}
			$totalOrdersCall = $totalOrdersCall + $ordenCall;
			$totalOrdersWeb = $totalOrdersWeb + $ordenWeb;	
			$totalOrdersOfi = $totalOrdersOfi + $ordenOfi;
			
			$facturacion = (($ordenOfi + $ordenCall + $ordenWeb)*($result1->Precio))/$result1->Iva;			
			$precioSesionTotal = ($result1->PrecioSesion/$result1->Iva) * ($ordenCall + $ordenWeb + $ordenOfi);			
			$margenBrutoUnitario = ($result1->Precio/$result1->Iva) - ($result1->PrecioSesion/$result1->Iva);			
			$aportacionBruta = (($result1->Precio/$result1->Iva) - ($result1->PrecioSesion/$result1->Iva)) * ($ordenCall + $ordenWeb + $ordenOfi);			
			$comisionUnidad = ($result1->Precio/$result1->Iva) * ($comisionPrensa);			
			$comisionTotal = (($result1->Precio/$result1->Iva)*($comisionPrensa)) * ($ordenCall + $ordenWeb + $ordenOfi);
											
			echo '												
				<tr height="20px">													
					<td width="40px"><label>'.$result1->IdOferta.'</label></td>
					<td width="130px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
					<td width="90px"><label>'.utf8_encode($result1->Opcion).'</label></td>
					<td width="60px"><label>'.$ordenOfi.'</label></td>
					<td width="60px"><label>'.$ordenCall.'</label></td>
					<td width="60px"><label>'.$ordenWeb.'</label></td>
					<td width="60px"><label>'.($ordenCall + $ordenWeb + $ordenOfi).'</label></td>
					<td width="60px"><label>'.number_format(($result1->Precio/$result1->Iva),2,',','.').'</label></td>													
					<td width="60px" bgcolor="#71BA00"><label>'.number_format($facturacion,2,',','.').'</label></td>
					<td width="60px"><label>'.number_format(($result1->PrecioSesion/$result1->Iva),2,',','.').'</label></td>
					<td width="60px"><label>'.number_format($precioSesionTotal,2,',','.').'</label></td>
					<td width="60px"><label>'.number_format($margenBrutoUnitario,2,',','.').'</label></td>
					<td width="60px"><label>'.number_format($aportacionBruta,2,',','.').'</label></td>
					<td width="60px"><label>'.number_format($comisionUnidad,2,',','.').'</label></td>
					<td width="60px" bgcolor="#73DF72"><label>'.number_format($comisionTotal,2,',','.').'</label></td>
													
					<td width="60px" bgcolor="#71BA00"><label>'.number_format(($aportacionBruta - $comisionTotal),2,',','.').'</label></td>
				</tr>
			';
			//TOTALES
			$aportBruta = $aportBruta + $aportacionBruta;
			$aportNeta = $aportNeta + ($aportacionBruta - $comisionTotal);
		} 
	}
							
	$graphDevol[] = array('fact_total'=>$subtotal, 'aport_bruta'=>$aportBruta, 'comision_patner'=>$comision, 'aport_neta'=>$aportNeta);
									
	echo '
		</tbody>
		<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
    	  	<tr height="30px" style="color: white; font-size: 14px; font-variant:small-caps;">
               	<td colspan="3" align="center" bgcolor="#1F88A7"><span>TOTALES</span></td>
				<td bgcolor="#1F88A7"><span>'.$totalOrdersOfi.'</span></td>
                <td bgcolor="#1F88A7"><span>'.$totalOrdersCall.'</span></td>
                <td bgcolor="#1F88A7"><span>'.$totalOrdersWeb.'</span></td>
                <td bgcolor="#1F88A7"><span>'.($totalOrdersWeb + $totalOrdersCall + $totalOrdersOfi).'</span></td>                
				<td bgcolor="#1F88A7"></td>
                <td bgcolor="#1F88A7"><span>'.number_format($subtotal,2,',','.').'</span></td>
				<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
				<td bgcolor="#1F88A7"></td>
				<td bgcolor="#1F88A7">'.number_format($aportBruta,2,',','.').'</td>
				<td bgcolor="#1F88A7"></td>
                <td bgcolor="#1F88A7"><span>'.number_format($comision,2,',','.').'</span></td>
				<td bgcolor="#1F88A7"><span>'.number_format($aportNeta,2,',','.').'</span></td>				
            </tr>
			<tr height="20px"><td colspan="15" bgcolor="#FFFFFF"></td></tr>										
	    </tfoot>';
		echo '</table></td></tr>';
	} 
	return $graphDevol;
}
//=== FIN DEVOLUCIONES CUADRO COMPLETO

//=== INFORME POR VENTAS Y DETALLE DE CLIENTES ===
function ventasDetalleCliente($web) {
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
			$addSQL = "AND productos.IdOferta = '$_prod_' AND opcionesoferta.Id = '$_tipo_'";
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
									<img src="'.$web.'/productos/'.$result1->Nombre.'/'.$result1->Images3.'" width="30px" style="border: 1px solid #BDBDBD" title="'.utf8_encode($result1->Nombre_Producto).'" />
								</td>
								<td height="24px"><label>'.$result1->Cantidad.'</label></td>
								<td height="24px">';
									if ($result1->OptActiva == 1) echo '<label>'.utf8_encode($result1->Opcion).'</label>';
										echo '
										</td>
										<td>'.number_format($result1->GastosEnvio,2,',','.').'</td>
										<td>'.number_format($result1->Precio,2,',','.').'</td>
										<td>'.number_format((($result1->Precio * $result1->Cantidad) + $result1->GastosEnvio),2,',','.').'</td>		
										<td>';
											//VERIFICAMOS QUE LOS IMPORTES DE LA TRANSACCION SEAN CORRECTOS EN LINEASORDEN Y EN ORDEN
											(int)$valid1 = ($result1->Precio * $result1->Cantidad)*100;
											(int)$valid2 = ($result1->Subtotal)*100;											
											if (number_format($valid1) === number_format($valid2))
												$imagenValid = 'icon_ok.png';
											else
												$imagenValid = 'icon_error.png';
											echo '	
											<img title="'.($valid1/100).'&euro; = '.($valid2/100).'&euro;" src="images/'.$imagenValid.'" />
										</td>																																				
							</tr>';
							
							$facturacionSinGastos = $facturacionSinGastos + ($result1->Precio * $result1->Cantidad);
							$Gastos = $Gastos + $result1->GastosEnvio;
							
							
							if($result1->EstadoPedido == trim('Devuelto') || $result1->EstadoPedido == trim('No-entregado'))
								$totalDevolucion = $totalDevolucion + ($result1->Precio * $result1->Cantidad);
							
							$cantidad = $cantidad + $result1->Cantidad;
							$array_orders_fecha[] = array('nombres'=>utf8_encode($result1->D_Nombres).' '.utf8_encode($result1->D_Apellidos), 'dni'=>$result1->Dni, 'email'=>$result1->Email,
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
					<td bgcolor="#1F88A7"><strong style="font-size: 14px; color: #FFF">'.$cantidad.'</strong></td>
					<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
					<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
					<td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
				  </tr><tr><td colspan="11" bgcolor="#FFF">&nbsp;</td></tr>';
				  
			echo '<tr height="25px">
					<th bgcolor="#EBEFF2"></th>
					<th bgcolor="#EBEFF2">Descripción</th>
					<th bgcolor="#EBEFF2">Totales</th>
				 </tr>
				 <tr height="22px">
				 	<td bgcolor="#FFF"></td>
					<td bgcolor="#FFF"><label>Facturación sin Gastos</label></td>
					<td bgcolor="#FFF"><label>'.number_format($facturacionSinGastos,2,',','.').'</label></td>
				 </tr>
				 <tr height="22px">
				 	<td bgcolor="#EBEFF2"></td>
					<td bgcolor="#EBEFF2"><label>Gastos de Envío</label></td>
					<td bgcolor="#EBEFF2">'.number_format($Gastos,2,',','.').'</td>
				 </tr>
				 <!--
				 <tr height="22px">
				 	<td bgcolor="#EBEFF2"></td>
					<td bgcolor="#EBEFF2"><label>Devoluciones</label></td>
					<td bgcolor="#EBEFF2">'.number_format($totalDevolucion,2,',','.').'</td>
				 </tr>
				 -->
				 <tr height="25px">
				 	<td bgcolor="#1F88A7"></td>
					<th bgcolor="#1F88A7" style="color: white">Total</th>
					<td bgcolor="#1F88A7" style="color: white">'.number_format((($facturacionSinGastos+$Gastos)-$totalDevolucion),2,',','.').' &euro;</td>
				 </tr>
				 ';
						
	}
//=== FIN INFORME ===

?>