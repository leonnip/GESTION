<?php
	require_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
	$orden = utf8_decode(trim($_POST['filtro']));
	$criterio = $_POST['criterio'];
	
	//SERIALIZAMOS EL ARRAY
	function array_envia($array) { 
	    $tmp = serialize($array); 
    	$tmp = urlencode($tmp); 
	    return $tmp; 
	} 
	//FIN SERIALIZACION
	
	if ($criterio == 'idorden') {
		$crite = "ordenes.IdOrden = '$orden'";
	} else if ($criterio == 'dni') { 
		$crite = "usuarios.Dni LIKE '%$orden%'";
	} else if ($criterio == 'destinatario'){
		$crite = "concat_ws(' ', direcciones.D_Nombres, direcciones.D_Apellidos) LIKE '%$orden%'";
	} else if ($criterio == 'direccion') {
		$crite = "concat_ws(' ', direcciones.TipoVia, direcciones.Direccion, direcciones.TipoNumero, direcciones.Numero, direcciones.Piso, direcciones.Puerta) LIKE '%$orden%'";
	} else if ($criterio == 'telefono') {
		$crite = "direcciones.Telefono LIKE '%$orden%'";
	}
	
	$SQL = "SELECT usuarios.Id, usuarios.Nombres, usuarios.Dni, usuarios.Apellidos, usuarios.FechaRegistro, direcciones.D_Nombres, direcciones.D_Apellidos, direcciones.TipoVia, 
			direcciones.Direccion, direcciones.TipoNumero, direcciones.Numero, direcciones.Piso, direcciones.Puerta, direcciones.Cp, direcciones.Poblacion, direcciones.Telefono, 
			direcciones.D_Pais, ordenes.IdOrden, ordenes.IdCliente, ordenes.FechaOrden, productos.Nombre_Producto, lineasorden.Id as IdLineaOrden, lineasorden.EstadoPedido, lineasorden.Tramita, lineasorden.GastosEnvio,
			lineasorden.Subtotal, lineasorden.AgenciaPago 
			FROM usuarios 
			INNER JOIN relordendireccion ON usuarios.Id = relordendireccion.IdCliente 
			INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion 
			INNER JOIN ordenes ON relordendireccion.IdOrden = ordenes.IdOrden 
			INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden 
			INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta 
			WHERE $crite  AND ordenes.EstadoPago = 'ok' AND lineasorden.EstadoPedido <> 'Anulado' ORDER BY ordenes.IdOrden";
	
	echo '
		<thead>
            <th width="40px"></th>
            <th width="60px">IdOrden</th>
            <th width="90px">Fecha Orden</th>
            <th width="80px">Dni</th>
			<th width="80px">Teléfono</th>
            <th width="240px">Nombres</th>                                    
            <th>Producto</th>                                    
            <th width="120px">Importe</th>
            <th width="60px">Estado</th>
            <th width="40px"></th>
        </thead>
		<tbody id="dataCustomer">
	';
	
	$db->setQuery($SQL);
	$row = $db->execute();
	$totalRegister = mysqli_num_rows($row);
	
	$pagination = 10;
	$reg = $totalRegister % $pagination;
	$tReg = $pagination - $reg;
	$totalRegister1 = $totalRegister + $tReg;
	 
	if (mysqli_num_rows($row) > 0) {
		$result = $db->loadObjectList();
		foreach($result as $result1) {
			$ord = array();
			$array = array();
			
			$ord[] = $result1->IdOrden;
			$array=array_envia($ord); 
			echo '				
				<tr height="22px" id="__tooltip__">
					<td><a class="tTip" id="LinkVer" href="#" data-id="'.$result1->IdOrden.'" title="Datos de Entrega"><label class="ver"></i></label></td>
					
					<td><label>'.$result1->IdOrden.'</label></td>
					<td><label>'.$result1->FechaOrden.'</label></td>
					<td><label>'.$result1->Dni.'</label></td>
					<td><label>'.$result1->Telefono.'</label></td>
					<td><label><a href="index.php?idcustomer='.$result1->Id.'">'.utf8_encode(ucwords(strtolower($result1->D_Nombres))).' '.utf8_encode(ucwords(strtolower($result1->D_Apellidos))).'</a></label></td>
					<td><label>'. utf8_encode($result1->Nombre_Producto).' / '.utf8_encode($result1->Puerta).'</label></td>
					<td><label>'.number_format($result1->Subtotal,2,',','.').' + ' .number_format($result1->GastosEnvio,2,',','.'). ' &euro;</label></label></td>
					<td>';
					
						if($result1->AgenciaPago > 0) {
							echo '<div id="divRes"><i id="icon" class="ok-pay" title="Pagado"></i></div>';
							$disabled = 'disabled="disabled"';
						} else {
							echo '<div id="divRes"><i id="icon" class="ko-pay" title="No Pagado"></i></div>';
							$disabled = "";
						}
						
					echo '
					</td>					
					<td id="actionPay">
						<button type="button" class="" id="action" name="action" value="'.$result1->IdLineaOrden.'" data-title="'.($result1->Subtotal + $result1->GastosEnvio).'" style="width:30px" '.$disabled.'>&rarr;</button>
					</td>
				</tr>				
			';
		}
		for ($i=1; $i <= $tReg; $i++) {
			echo '<tr height="24px"><td colspan="11"></td></tr>';
		}
	} else {
		echo '<tr style="background: none; cursor: default" height="115px"><td colspan="11"><img src="images/logs.png" /><br/><label style="font-variant: small-caps; font-size: 16px; color: #484848" >Sin Resultados a Mostrar</labe></td></tr>';
	}
	echo '</tbody>';
?>


<script type="text/javascript">
	$(document).ready(function(e) {
		/*PAGINACION*/
		$('#green2').smartpaginator({ totalrecords: <?php echo $totalRegister1; ?>, recordsperpage: 10, datacontainer: 'resultSearch1', dataelement: 'tr', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });
		
		/*=== REALIZA LA ACCIÓN DE APUNTAR IMPORTE PAGADO DE AGENCIA ===*/
		$("#actionPay button").on('click', function(e){
			e.preventDefault();
			$.post( "customer-pay-action.inc.php", { lineaorden: $(this).val(), totalLineaOrd: $(this).attr('data-title') })
			  .done(function(data) {
		    	$('#divRes').html(data);
  			});
		});
		
    });
</script>