<?php
@session_start();
//=== FUNCION INFORME PEDIDOS ===
function informePedidos($db, $comisionPrensa) {
	echo '<tr><td colspan="10"><table cellpadding="1" cellspacing="1" bordercolor="#666666" style="border-collapse:collapse;">';
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
		$SQL .= "WHERE TRIM(ordenes.EstadoPago) = '".trim('ok')."' AND TRIM(lineasorden.EstadoPedido) <> '".trim('Anulado')."' AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND productos.IdOferta = ".$arreglo[$i]['IdOferta']." AND opcionesoferta.Id = ".$arreglo[$i]['Tipo']." ";
		$SQL .= "GROUP BY opcionesoferta.Id, ordenes.Tramitado ORDER BY productos.IdOferta ASC";
		$db->setQuery($SQL);
		$row = $db->execute();									
										
		$ordenWeb = 0;
		$ordenCall = 0;
		if (mysqli_num_rows($row) > 0) {
			$result = $db->loadObjectList();
			foreach($result as $result1) {												
				$ordenTramitada = rtrim($result1->Tramitado);
				$totalSubtotal = $totalSubtotal;
				
				//if ($ordenTramitada == rtrim('call-center')) {
				if(in_array($ordenTramitada, $administradores)) {
					if($ordenTramitada == trim('usuario-web'))
						$ordenWeb = $ordenWeb + $result1->Unidades;	
					else 
						$ordenCall = $ordenCall + $result1->Unidades;
				} else {
					$errores = $errores + 1;
				}
				$subtotal = $subtotal + $result1->SubTotal;
				$comision = $comision + ($result1->SubTotal* ($comisionPrensa));
			}
			$totalOrdersCall = $totalOrdersCall + $ordenCall;
			$totalOrdersWeb = $totalOrdersWeb + $ordenWeb;	
											
			$facturacion = ($ordenCall + $ordenWeb)*$result1->Precio;
			echo '
				<tr height="20px">
					<td width="50px"><label>'.$result1->IdOferta.'</label></td>
					<td width="300px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
					<td width="150px"><label>'.utf8_encode($result1->Opcion).'</label></td>
					<td width="100px"><label>'.$ordenCall.'</label></td>
					<td width="100px"><label>'.$ordenWeb.'</label></td>
					<td width="100px"><label>'.($ordenCall + $ordenWeb).'</label></td>
					<td width="75px"><label>'.number_format($result1->Precio,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($result1->PrecioSesion,2,',','.').'</label></td>
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
               	<td bgcolor="#1F88A7" colspan="3" align="center"><span>TOTALES</span></td>
                <td bgcolor="#1F88A7"><span>'.$totalOrdersCall.'</span></td>
                <td bgcolor="#1F88A7"><span>'.$totalOrdersWeb.'</span></td>
                <td bgcolor="#1F88A7"><span>'.($totalOrdersWeb + $totalOrdersCall).'</span></td>
                <td bgcolor="#1F88A7"></td><td bgcolor="#1F88A7"></td>
    	        <td bgcolor="#1F88A7"><span>'.number_format($subtotal,2,',','.').'</span></td>
                <td bgcolor="#1F88A7"><span>'.number_format($comision,2,',','.').'</span></td>
           	</tr>
	      </tfoot>	
	';
	echo '</table></td></tr>';
	
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
				
	echo '<tr><td colspan="10"><table class="_importante" cellpadding="1" cellspacing="1" bordercolor="#666666" style="border-collapse:collapse; display: none">';
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
		
	$subtotal = 0;
	$comision = 0;								
	for($i = 0; $i <= $totalOfertas - 1; $i++ ) {								
		$SQL = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id, opcionesoferta.Opcion, opcionesoferta.Precio, opcionesoferta.PrecioSesion, ";
		$SQL .= "opcionesoferta.Iva, SUM(lineasorden.Cantidad) AS Unidades,SUM(lineasorden.Subtotal) as SubTotal, SUM(lineasorden.GastosEnvio) AS GastosEnvio, ordenes.Tramitado ";
		$SQL .= "FROM ordenes ";
		$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
		$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
		$SQL .= "WHERE TRIM(ordenes.EstadoPago) = '".trim('ok')."' AND TRIM(lineasorden.EstadoPedido) <> '".trim('Anulado')."' AND lineasorden.EstadoPedido IN ('No-entregado', 'Devuelto') ";
		$SQL .= "AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND productos.IdOferta = ".$arreglo[$i]['IdOferta']." AND opcionesoferta.Id = ".$arreglo[$i]['Tipo']." ";
		$SQL .= "GROUP BY opcionesoferta.Id, ordenes.Tramitado ORDER BY productos.IdOferta ASC";
		$db->setQuery($SQL);
		$row = $db->execute();									
										
		$ordenWeb = 0;
		$ordenCall = 0;
		if (mysqli_num_rows($row) > 0) {
			$result = $db->loadObjectList();
			foreach($result as $result1) {												
				$ordenTramitada = $result1->Tramitado;
				$totalSubtotal = $totalSubtotal;
				
				if(in_array($ordenTramitada, $administradores)) {
					if($ordenTramitada == trim('usuario-web'))
						$ordenWeb = $ordenWeb + $result1->Unidades;	
					else 
						$ordenCall = $ordenCall + $result1->Unidades;
				} else {
					$errores = $errores + 1;
				}
				$subtotal = $subtotal + $result1->SubTotal;
				$comision = $comision + ($result1->SubTotal* ($comisionPrensa));
			}
			$totalOrdersCall = $totalOrdersCall + $ordenCall;
			$totalOrdersWeb = $totalOrdersWeb + $ordenWeb;	
											
			$facturacion = ($ordenCall + $ordenWeb)*$result1->Precio;
			echo '
				<tr height="20px">
					<td width="50px"><label>'.$result1->IdOferta.'</label></td>
					<td width="300px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
					<td width="150px"><label>'.utf8_encode($result1->Opcion).'</label></td>
					<td width="100px"><label>'.$ordenCall.'</label></td>
					<td width="100px"><label>'.$ordenWeb.'</label></td>
					<td width="100px"><label>'.($ordenCall + $ordenWeb).'</label></td>
					<td width="75px"><label>'.number_format($result1->Precio,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($result1->PrecioSesion,2,',','.').'</label></td>
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
           	    <td bgcolor="#1F88A7"><span>'.$totalOrdersCall.'</span></td>
               	<td bgcolor="#1F88A7"><span>'.$totalOrdersWeb.'</span></td>
	               <td bgcolor="#1F88A7"><span>'.($totalOrdersWeb + $totalOrdersCall).'</span></td>
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
		echo '<tr><td colspan="15"><table cellpadding="1" cellspacing="1" bordercolor="#666666" style="border-collapse:collapse;">';		
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
		$SQL = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id, opcionesoferta.Opcion, opcionesoferta.Precio, opcionesoferta.PrecioSesion, opcionesoferta.Iva, SUM(lineasorden.Cantidad) AS Unidades, SUM(lineasorden.Subtotal) as SubTotal, SUM(lineasorden.GastosEnvio) AS GastosEnvio, ordenes.Tramitado ";
		$SQL .= "FROM ordenes ";
		$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
		$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
		$SQL .= "WHERE TRIM(ordenes.EstadoPago) = '".trim('ok')."' AND TRIM(lineasorden.EstadoPedido) <> '".trim('Anulado')."' AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND productos.IdOferta = ".$arreglo[$i]['IdOferta']." AND opcionesoferta.Id = ".$arreglo[$i]['Tipo']." ";
		$SQL .= "GROUP BY opcionesoferta.Id, ordenes.Tramitado ORDER BY productos.IdOferta ASC";
		$db->setQuery($SQL);
		$row = $db->execute();									
										
		$ordenWeb = 0;
		$ordenCall = 0;
		if (mysqli_num_rows($row) > 0) {
			$result = $db->loadObjectList();
			foreach($result as $result1) {												
				$ordenTramitada = $result1->Tramitado;
				$totalSubtotal = $totalSubtotal;
				
				if(in_array($ordenTramitada, $administradores)) {
					if($ordenTramitada == trim('usuario-web'))
						$ordenWeb = $ordenWeb + $result1->Unidades;	
					else 
						$ordenCall = $ordenCall + $result1->Unidades;
				} else {
					$errores = $errores + 1;
				}
				$subtotal = $subtotal + $result1->SubTotal;
				$comision = $comision + ($result1->SubTotal* ($comisionPrensa));
			}
			$totalOrdersCall = $totalOrdersCall + $ordenCall;
			$totalOrdersWeb = $totalOrdersWeb + $ordenWeb;	
											
			$facturacion = ($ordenCall + $ordenWeb)*$result1->Precio;
			$precioSesionTotal = $result1->PrecioSesion * ($ordenCall + $ordenWeb);
			$margenBrutoUnitario = $result1->Precio - $result1->PrecioSesion;
			$aportacionBruta = ($result1->Precio - $result1->PrecioSesion) * ($ordenCall + $ordenWeb);
			$comisionUnidad = $result1->Precio * ($comisionPrensa);
			$comisionTotal = ($result1->Precio*($comisionPrensa)) * ($ordenCall + $ordenWeb);
											
			echo '												
				<tr height="20px">													
					<td width="50px"><label>'.$result1->IdOferta.'</label></td>
					<td width="300px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
					<td width="150px"><label>'.utf8_encode($result1->Opcion).'</label></td>
					<td width="100px"><label>'.$ordenCall.'</label></td>
					<td width="100px"><label>'.$ordenWeb.'</label></td>
					<td width="100px"><label>'.($ordenCall + $ordenWeb).'</label></td>
					<td width="75px"><label>'.number_format($result1->Precio,2,',','.').'</label></td>													
					<td width="100px"><label>'.number_format($facturacion,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($result1->PrecioSesion,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($precioSesionTotal,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($margenBrutoUnitario,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($aportacionBruta,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($comisionUnidad,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($comisionTotal,2,',','.').'</label></td>
													
					<td><label>'.number_format(($aportacionBruta - $comisionTotal),2,',','.').'</label></td>
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
                <td bgcolor="#1F88A7"><span>'.$totalOrdersCall.'</span></td>
                <td bgcolor="#1F88A7"><span>'.$totalOrdersWeb.'</span></td>
                <td bgcolor="#1F88A7"><span>'.($totalOrdersWeb + $totalOrdersCall).'</span></td>
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
		<tr><td colspan="15" style="background:white">
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
				
	echo '<tr><td colspan="15"><table class="_importante" cellpadding="1" cellspacing="1" bordercolor="#666666" style="border-collapse:collapse; display: none">';
				
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
									
		$SQL = "SELECT productos.IdOferta, opcionesoferta.Id, SUM(lineasorden.Cantidad) as Unidades, productos.Nombre_Producto ";
		$SQL .= "FROM ordenes ";
		$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
		$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
		$SQL .= "WHERE TRIM(ordenes.EstadoPago) = '".trim.('ok')."' AND TRIM(lineasorden.EstadoPedido) <> '".trim('Anulado')."' AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' ";
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
		$SQL = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id, opcionesoferta.Opcion, opcionesoferta.Precio, opcionesoferta.PrecioSesion, opcionesoferta.Iva, SUM(lineasorden.Cantidad) AS Unidades, SUM(lineasorden.Subtotal) as SubTotal, SUM(lineasorden.GastosEnvio) AS GastosEnvio, ordenes.Tramitado ";
		$SQL .= "FROM ordenes ";
		$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
		$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
		$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
		$SQL .= "WHERE TRIM(ordenes.EstadoPago) = '".trim('ok')."' AND TRIM(lineasorden.EstadoPedido) <> '".trim('Anulado')."' AND lineasorden.EstadoPedido IN ('No-entregado', 'Devuelto') ";
		$SQL .= "AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND productos.IdOferta = ".$arreglo[$i]['IdOferta']." AND opcionesoferta.Id = ".$arreglo[$i]['Tipo']." ";
		$SQL .= "GROUP BY opcionesoferta.Id, ordenes.Tramitado ORDER BY productos.IdOferta ASC";
		$db->setQuery($SQL);
		$row = $db->execute();									
										
		$ordenWeb = 0;
		$ordenCall = 0;
		if (mysqli_num_rows($row) > 0) {
			$result = $db->loadObjectList();
			foreach($result as $result1) {												
				$ordenTramitada = $result1->Tramitado;
				$totalSubtotal = $totalSubtotal;
				
				if(in_array($ordenTramitada, $administradores)) {
					if($ordenTramitada == trim('usuario-web'))
						$ordenWeb = $ordenWeb + $result1->Unidades;	
					else 
						$ordenCall = $ordenCall + $result1->Unidades;
				} else {
					$errores = $errores + 1;
				}
				$subtotal = $subtotal + $result1->SubTotal;
				$comision = $comision + ($result1->SubTotal* ($comisionPrensa));
			}
			$totalOrdersCall = $totalOrdersCall + $ordenCall;
			$totalOrdersWeb = $totalOrdersWeb + $ordenWeb;	
											
			$facturacion = ($ordenCall + $ordenWeb)*$result1->Precio;
			$precioSesionTotal = $result1->PrecioSesion * ($ordenCall + $ordenWeb);
			$margenBrutoUnitario = $result1->Precio - $result1->PrecioSesion;
			$aportacionBruta = ($result1->Precio - $result1->PrecioSesion) * ($ordenCall + $ordenWeb);
			$comisionUnidad = $result1->Precio * ($comisionPrensa);
			$comisionTotal = ($result1->Precio*($comisionPrensa)) * ($ordenCall + $ordenWeb);
											
			echo '												
				<tr height="20px">													
					<td width="50px"><label>'.$result1->IdOferta.'</label></td>
					<td width="300px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
					<td width="150px"><label>'.utf8_encode($result1->Opcion).'</label></td>
					<td width="100px"><label>'.$ordenCall.'</label></td>
					<td width="100px"><label>'.$ordenWeb.'</label></td>
					<td width="100px"><label>'.($ordenCall + $ordenWeb).'</label></td>
					<td width="75px"><label>'.number_format($result1->Precio,2,',','.').'</label></td>													
					<td width="100px"><label>'.number_format($facturacion,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($result1->PrecioSesion,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($precioSesionTotal,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($margenBrutoUnitario,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($aportacionBruta,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($comisionUnidad,2,',','.').'</label></td>
					<td width="75px"><label>'.number_format($comisionTotal,2,',','.').'</label></td>
													
					<td><label>'.number_format(($aportacionBruta - $comisionTotal),2,',','.').'</label></td>
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
                <td bgcolor="#1F88A7"><span>'.$totalOrdersCall.'</span></td>
                <td bgcolor="#1F88A7"><span>'.$totalOrdersWeb.'</span></td>
                <td bgcolor="#1F88A7"><span>'.($totalOrdersWeb + $totalOrdersCall).'</span></td>
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

?>