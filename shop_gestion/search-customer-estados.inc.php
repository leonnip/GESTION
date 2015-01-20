<?php
	include_once("config.inc.php");
	require_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
	$orden = utf8_decode($_POST['filtroEst']);
	$criterio = $_POST['criterioEst'];
	
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
	
	$SQL = "SELECT usuarios.Id, usuarios.Nombres, usuarios.Dni, usuarios.Apellidos, usuarios.FechaRegistro, direcciones.TipoVia, direcciones.Direccion, direcciones.TipoNumero, direcciones.Numero, direcciones.Piso, direcciones.Puerta, direcciones.Cp, direcciones.Poblacion, direcciones.Telefono, ordenes.IdOrden, ordenes.FechaOrden, productos.Nombre, productos.Nombre_Producto, lineasorden.Id as linOrd, lineasorden.EstadoPedido, lineasorden.Talla, opcionesoferta.Opcion, SUM(lineasorden.Cantidad) as Unidades ";
	$SQL .= "FROM usuarios ";
	$SQL .= "INNER JOIN relordendireccion ON usuarios.Id = relordendireccion.IdCliente ";
	$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion ";
	$SQL .= "INNER JOIN ordenes ON relordendireccion.IdOrden = ordenes.IdOrden ";
	$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
	$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
	$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	$SQL .= "WHERE $crite AND ordenes.EstadoPago = '".trim('ok')."' GROUP BY ordenes.IdOrden ORDER BY ordenes.IdOrden";
	
	echo '
		<thead>
           <th width="40px"></th>
           <th width="60px">IdOrden</th>
		   <th width="150px">Fecha Orden</th>
		   <th width="80px">Dni</td>
           <th width="290px">Nombres</th>
		   <th width="120px">Telefono</th>
		   <th width="80px">Dirección</th>
           <th width="70px">Prod</th>
           <th width="120px">Estado</th>
           <th width="120px">Cambiar</th>
           <th width="80px"></th>
		   <th width="25px"></th>
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
				<tr height="24px">
					<td align="center"><a class="tTip" id="LinkVer" href="#" data-id="'.$result1->IdOrden.'" title="Ver Pedido"><label class="ver"></label></a></td>
					<td><label>'.$result1->IdOrden.'</label></td>
					<td><label>'.$result1->FechaOrden.'</label></td>
					<td><label>'.$result1->Dni.'</label></td>
					<td><label>'.utf8_encode(ucwords(strtolower($result1->Nombres))).' '.utf8_encode(ucwords(strtolower($result1->Apellidos))).'</a></label></td>
					<td><label>'.$result1->Telefono.'</label></td>
					<td><img src="images/icon_direction.png" title="'.$result1->TipoVia.' '.utf8_encode(ucwords(strtolower($result1->Direccion))).' '.utf8_encode($result1->TipoNumero).' '.utf8_encode($result1->Numero).' '.utf8_encode($result1->Piso).' '.utf8_encode($result1->Puerta).'"></td>
					<td><label>'.$result1->Unidades.'</label></td>
					<td>
						<label id="resultEst'.$result1->IdOrden.'">';
						if ($result1->EstadoPedido == 'Transito') echo '<label style="color: red">Transito</label>';
						if ($result1->EstadoPedido == 'Enviado') echo '<label style="color: #0877BF">Enviado</label>';
						if ($result1->EstadoPedido == 'Anulado') echo '<label style="color: #000">Anulado</label>';
						if ($result1->EstadoPedido == 'Entregado') echo '<label style="color: green">Entregado</label>';
						if ($result1->EstadoPedido == 'No-entregado') echo '<label style="color: #09F">No Entregado</label>';
						echo '
						</label>
					</td>';
					
					if ($result1->EstadoPedido == trim('Enviado') || $result1->EstadoPedido == trim('No-entregado') || $result1->EstadoPedido == trim('Entregado')) {
						echo '
						<td>
							<div class="divSelect" style="width: 100px">
								<select id="select_'.$result1->IdOrden.'" name="estado">
									<option value="0">Seleccione</option>
									<option value="Entregado">Entregado</option>							
									<!--<option value="No-entregado">No Entregado</option>-->
								</select>
							</div>
						</td>';
					} else {
						echo '
						<td>
							<div class="divSelect" style="width: 100px">
								<select id="select_'.$result1->IdOrden.'" name="estado"'; if($result1->EstadoPedido == trim('Devuelto')) echo "disabled='disabled'; "; echo '>
									<option value="0">Seleccione</option>
									<option value="Anulado">Anular</option>							
								</select>
							</div>
						</td>';
					}
					
					echo '
					<td>
						<input id="motivo_'.$result1->IdOrden.'" type="text" style="width: 70px" placeholder="Motivo" />
						<img id="okChange_'.$result1->IdOrden.'" style="display:none" src="images/icon_ok.png" title="Modificado Correntamente" />
					</td>
					<td id="tdCambiar"><a id="bot_'.$result1->IdOrden.'" name="'.$result1->IdOrden.'" class="cambiarEstado" href="#" title="Cambiar"></a></td>
				</tr>
			';
		}
		for ($i=1; $i <= $tReg; $i++) {
			echo '<tr height="24px"><td colspan="12"></td></tr>';
		}		
	} else {
		echo '<tr style="background: none; cursor: default" height="115px"><td colspan="12"><img src="images/logs.png" /><br/><label style="font-variant: small-caps; font-size: 16px; color: #484848" >Sin Resultados a Mostrar</labe></td></tr>';
	}
	echo '</tbody>';
?>
<script type="text/javascript">
	$(document).ready(function(e) {
        $('#tdCambiar a').click(function(){
			var ord = $(this).attr('name');
			var motivo = $('#motivo_'+ord).val(); 
			var est = $('#select_'+ord).val();
			if (est == '0') {
				alert('Debe Seleccionar un estado...');
			} else {
				$.post('cambiar-estado.inc.php', { orden: ord, motiv: motivo, estado: est }, function(data) {
					$('#resultEst'+ord).html(data);
					$('#motivo_'+ord).hide();
					$('#bot_'+ord).hide();
					$('#okChange_'+ord).show();
					console.log(data);
				});
			}
		});
		
		/*===TOOLTIP DE LOS ICONOS DE BUSQUEDA DE CLIENTE===*/
		$("a[title]").tooltip({
          tip: '.tooltip_customer',
          position: 'top center',
		  offset: [0, 10],
		  delay: 0         
      	});
	  	$("img[title]").tooltip({
          tip: '.tooltip_customer',
          position: 'top center',
		  offset: [0, 10],
		  delay: 0         
      	});
		
		/*PARA EL LOS DETALLES DEL PEDIDO*/
		$('a#LinkVer').click(function(){
			var idorden = $(this).attr('data-id');
			var lineasorden = $(this).attr('name');
			URL = 'detalles-cliente-pedido.php?idorden='+idorden+'&lineaorden='+lineasorden;
			day = new Date();
			id = day.getTime();
			eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=0,width=650,height=640,left = 490,top = 75');");
		});
		
		/*PAGINACION*/
		$('#green3').smartpaginator({ totalrecords: <?php echo $totalRegister1; ?>, recordsperpage: 10, datacontainer: 'resultSearch2', dataelement: 'tr', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });
    });
</script>