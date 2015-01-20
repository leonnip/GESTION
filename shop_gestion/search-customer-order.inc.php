<?php
	require_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
	$orden = utf8_decode($_POST['filtro']);
	$criterio = $_POST['criterio'];
	
	
	if ($criterio == 'idorden') {
		$crite = "ordenes.IdOrden = '$orden'";
	} else if ($criterio == 'dni') { 
		$crite = "usuarios.Dni LIKE '%$orden%'";
	} else if ($criterio == 'destinatario'){
		$crite = "concat_ws(' ', usuarios.Nombres, usuarios.Apellidos) LIKE '%$orden%'";
	} else if ($criterio == 'direccion') {
		$crite = "concat_ws(' ', direcciones.TipoVia, direcciones.Direccion, direcciones.TipoNumero, direcciones.Numero, direcciones.Piso, direcciones.Puerta) LIKE '%$orden%'";
	} else if ($criterio == 'telefono') {
		$crite = "direcciones.Telefono LIKE '%$orden%'";
	}
	
	$SQL = "SELECT usuarios.Id, usuarios.Nombres, usuarios.Dni, usuarios.Apellidos, usuarios.FechaRegistro, direcciones.Telefono, direcciones.TipoVia, lineasorden.EstadoPedido, ";
	$SQL .= "direcciones.Direccion, direcciones.TipoNumero, direcciones.Numero, direcciones.Piso, direcciones.Puerta, direcciones.Cp, direcciones.Poblacion, direcciones.Telefono, ordenes.IdOrden, productos.Nombre_Producto, ordenes.IdOrden, ordenes.EstadoPago ";
	$SQL .= "FROM usuarios ";
	$SQL .= "INNER JOIN relordendireccion ON usuarios.Id = relordendireccion.IdCliente ";
	$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion ";
	$SQL .= "INNER JOIN ordenes ON relordendireccion.IdOrden = ordenes.IdOrden ";
	$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
	$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
	$SQL .= "WHERE $crite ORDER BY ordenes.IdOrden";
	
	echo '
		
		<thead>
           	<th width="50px">Orden</th>
           	<th width="90px">Fecha</th>
            <th width="220px">Nombres</th>
            <th width="90px">Dni</th>
            <th width="300px">Direcci&oacute;n</th>                                   
            <th width="90px">Tel&eacute;fono</th>
            <th width="200px">Compra</th>
			<th width="100px">Estado</th>
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
			echo '					
				<tr height="30px">
					<td width="50px"><label>'.$result1->IdOrden.'</label></td>
					<td width="90px"><label>'.$result1->FechaRegistro.'</label></td>
					<td width="220px"><label><a href="index.php?idcustomer='.$result1->Id.'&idbd='.$_REQUEST['idbd'].'">'.utf8_encode(ucwords(strtolower($result1->Nombres))).' '.utf8_encode(ucwords(strtolower($result1->Apellidos))).'</a></label></td>
					<td width="90px"><label>'.$result1->Dni.'</label></td>
					<td width="300px">
						<label title="'.$result1->Cp.' - '.utf8_encode($result1->Poblacion).'">'
						.utf8_encode($result1->TipoVia).' '.utf8_encode(ucwords(strtolower($result1->Direccion))).' '.utf8_encode($result1->TipoNumero).' '.utf8_encode($result1->Numero).' '.utf8_encode($result1->Piso).' '.utf8_encode($result1->Puerta).'
						</label>
					</td>					
					<td width="90px"><label>'.utf8_encode($result1->Telefono).'</label></td>
					<td width="200px"><label>'.substr(utf8_encode($result1->Nombre_Producto), 0, 38) .'...</label></td>
					<td>';
					
						if($result1->EstadoPedido == 'Transito' && $result1->EstadoPago == 'ok') echo '<a title="Pedido en Tránsito" data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="transito"></label></a>';
						if($result1->EstadoPedido == 'Entregado' && $result1->EstadoPago == 'ok') echo '<a id="devol" title="Anotar Devolución" data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="entregado" style="color: #f00"></label></a>';
						if($result1->EstadoPedido == 'Devuelto' && $result1->EstadoPago == 'ok')  echo '<a title="Pedido Devuelto" data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="devolucion" style="color: #f00"></label></a>';
						if($result1->EstadoPedido == 'No-entregado' && $result1->EstadoPago == 'ok') echo '<a title="Pedido No Entregado" data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="noentregado" style="color: #f00"></label></a>';
						if($result1->EstadoPedido == 'Anulado' && $result1->EstadoPago == 'ok') echo '<a title="Pedido Anulado" data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="anulado" style="color: #f00"></label></a>';
						if($result1->EstadoPedido == 'Enviado' && $result1->EstadoPago == 'ok') echo '<a id="devol" title="Pedido Enviado / Generar Devol" data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="enviado"></label></a>';
						if(trim($result1->EstadoPago) == '--') echo '<a title="Pedido no Finalizado." data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="nofin">.</label></a>';
			
					echo '
					</td>
				</tr>				
			';
		}
		for ($i=1; $i <= $tReg; $i++) {
			echo '<tr height="22px"><td colspan="8"></td></tr>';
		}
	} else {
		echo '<tr style="background: none; cursor: default"><td colspan="8" height="75px"><img src="images/logs.png" /><br/><label style="font-variant: small-caps; font-size: 16px; color: #484848" >Sin Resultados a Mostrar</labe></td></tr>';
	}
?>
<script type="text/javascript">
	$(document).ready(function(e) {
		/*===TOOLTIP DE LOS ICONOS DE BUSQUEDA DE CLIENTE===*/
		$("a[title]").tooltip({
        	tip: '.tooltip_customer',
            position: 'top center',
		    offset: [0, 10],
		    delay: 0         
      	});
		
        $("tbody#dataCustomer tr").click(function() {
	        var href = $(this).find("a").attr("href");
    	    if(href) {
        	    window.location = href;
        	}
    	});
		
		$('#green').smartpaginator({ totalrecords: <?php echo $totalRegister1; ?>, recordsperpage: 10, datacontainer: 'resultSearch1', dataelement: 'tr', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });
		
    });
</script>