<?php
	@session_start();
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
	
	$SQL = "SELECT usuarios.Id, usuarios.Nombres, usuarios.Dni, usuarios.Apellidos, usuarios.FechaRegistro, direcciones.D_Nombres, direcciones.D_Apellidos, direcciones.TipoVia, ";
	$SQL .= "direcciones.Direccion, direcciones.TipoNumero, direcciones.Numero, direcciones.Piso, direcciones.Puerta, direcciones.Cp, direcciones.Poblacion, direcciones.Telefono, ";
	$SQL .= "direcciones.D_Pais, ordenes.IdOrden, ordenes.IdCliente, ordenes.FechaOrden, productos.Nombre_Producto, lineasorden.Id as IdLineaOrden, lineasorden.EstadoPedido, lineasorden.Tramita ";
	$SQL .= "FROM usuarios ";
	$SQL .= "INNER JOIN relordendireccion ON usuarios.Id = relordendireccion.IdCliente ";
	$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion ";
	$SQL .= "INNER JOIN ordenes ON relordendireccion.IdOrden = ordenes.IdOrden ";
	$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
	$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
	$SQL .= "WHERE $crite  AND ordenes.EstadoPago = 'ok' GROUP BY ordenes.IdOrden ORDER BY ordenes.IdOrden";
	
	echo '
		<thead>
            <th width="40px"></th>
            <th width="40px"></th>
            <th width="60px">IdOrden</th>
            <th width="90px">Fecha Orden</th>
            <th width="80px">Dni</th>
			<th width="80px">Teléfono</th>
            <th width="240px">Nombres</th>                                    
            <th>Direcci&oacute;n</th>                                    
            <th width="40px"></th>
            <th width="40px"></th>
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
					<td><a class="tTip" id="LinkVer" href="#" data-id="'.$result1->IdOrden.'" data-title="'.$_REQUEST['idbd'].'" title="Datos de Entrega"><label class="ver"></i></label></td>
					<td>';
						if($result1->EstadoPedido == 'Anulado')
							echo '<a class="tTip" id="FacturaCliente" href="#" title="No es posible generar esta factura"><label class="imprimir"></label></a>';
						else
							echo '<a class="tTip" id="FacturaCliente" href="generar-fact/index.php?orden='.$array.'&idbd='.$_REQUEST['idbd'].'" name="'.$result1->IdOrden.'" target="_blank" title="Factura Cliente"><label class="imprimir"></label></a>';
					echo '
					</td>
					<!--<td><a class="tTip" id="Factura1" href="#" name="'.$result1->IdOrden.'" class="'.$result1->IdLineaOrden.'" title="Factura Cliente"><label class="imprimir"></label></a></td>-->
					<td><label>'.$result1->IdOrden.'</label></td>
					<td><label>'.$result1->FechaOrden.'</label></td>
					<td><label>'.$result1->Dni.'</label></td>
					<td><label>'.$result1->Telefono.'</label></td>
					<td><label><a href="index.php?idcustomer='.$result1->Id.'&idbd='.$_REQUEST['idbd'].'">'.utf8_encode(ucwords(strtolower($result1->D_Nombres))).' '.utf8_encode(ucwords(strtolower($result1->D_Apellidos))).'</a></label></td>
					<td><label>'.utf8_encode($result1->TipoVia).' '.utf8_encode(ucwords(strtolower($result1->Direccion))).' '.utf8_encode($result1->TipoNumero).' '.utf8_encode($result1->Numero).' '.utf8_encode($result1->Piso).' '.utf8_encode($result1->Puerta).'</label></td>
					<td><a id="modific" class="tTip" title="Modificar Pedido" data-id="'.base64_encode($result1->IdOrden).'" data-title="'.$result1->D_Pais.'" name="'.base64_encode($result1->IdCliente).'"><label class="modificar"></i></label></td>
					<td><a id="direccion" class="tTip" title="Cambiar Direcci&oacute;n" data-id="'.base64_encode($result1->IdOrden).'"><label class="cambiar"></label></a></td>
					<!--<td><a id="devol" title="Anotar Devolución" class="tTip" data-id="'.base64_encode($result1->IdOrden).'" target="framename" ><i class="devolucion" style="color: #f00"></i></a></td>-->
					<td>';
						if($result1->EstadoPedido == 'Transito') echo '<a title="Pedido en Tránsito" data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="transito"></label></a>';
						if($result1->EstadoPedido == 'Entregado') echo '<a id="devol" title="Anotar Devolución" data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="entregado" style="color: #f00"></label></a>';
						if($result1->EstadoPedido == 'Devuelto') echo '<a title="Pedido Devuelto" data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="devolucion" style="color: #f00"></label></a>';
						if($result1->EstadoPedido == 'No-entregado') echo '<a title="Pedido No Entregado" data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="noentregado" style="color: #f00"></label></a>';
						if($result1->EstadoPedido == 'Anulado') echo '<a title="Pedido Anulado || '.$result1->Tramita.'" data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="anulado" style="color: #f00"></label></a>';
						if($result1->EstadoPedido == 'Enviado') echo '<a id="devol" title="Pedido Enviado / Generar Devol" data-id="'.base64_encode($result1->IdOrden).'" class="tTip" href="#" ><label class="enviado"></label></a>';
					echo '	
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
        $("tbody#dataCustomer tr").click(function() {
	        var href = $(this).find("a").attr("href");
    	    if(href) {
        	    window.location = href;
        	}
    	});
		
		/*===TOOLTIP DE LOS ICONOS DE BUSQUEDA DE CLIENTE===*/
		//$("#dataCustomer a[title]").tooltip({ tip: '.tooltip_links', position: "top center"});
		//$('a.tTip').tinyTips('black', 'title');
		$("a[title]").tooltip({
          tip: '.tooltip_customer',
          position: 'top center',
		  offset: [0, 10],
		  delay: 0         
      });
		
		
		/*PARA EL LOS DETALLES DEL PEDIDO*/
		$('a#LinkVer').click(function(){
			var idorden = $(this).attr('data-id');
			var idbd= $(this).attr('data-title');
			URL = 'detalles-cliente-pedido.php?idorden='+idorden+'&idbd='+idbd;
			day = new Date();
			id = day.getTime();
			eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=0,width=650,height=640,left = 490,top = 1');");
		});
		
		/*PARA IMPRIMIR EL CARTEL DEL CLIENTE*/
		$('a#LinkCartel').click(function(){
			var orden = $(this).attr('name');
			var idbd = $(this).attr('class');
			URL = 'cartel-cliente.php?orden='+orden+'&idbd='+idbd;
			/*URL = 'http://www.admin.bonocartilla.com/click/?n=2';*/
			day = new Date();
			id = day.getTime();
			eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=0,width=650,height=740,left = 490,top = 75');");
		});
		
		/*PARA IR A MODIFICAR EL PEDIDO*/
		$('a#modific').click(function(){
			var orden = $(this).attr('data-id');
			var country = $(this).attr('data-title');
			var cliente = $(this).attr('name');
			URL = 'modificar-pedido.php?orden='+orden+'&country='+country+'&cliente='+cliente;
			var winleft = (screen.width-1080)/2;
			var wintop = (screen.height-780)/2;
			//caracteristicas='height=760,width=1080,top='+wintop+',left='+winleft+',toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=0';
			day = new Date();
			id = day.getTime();
			eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=0,width=1080,height=740,left = "+winleft+",top = "+wintop+"');");
		});
		
		/*PARA IR A MODIFICAR EL LA DIRECCION DEL CLIENTE*/
		$('a#direccion').click(function(){
			var orden = $(this).attr('data-id');
			URL = 'modificar-direccion.php?orden='+orden;
			var winleft = (screen.width-1080)/2;
			var wintop = (screen.height-780)/2;
			//caracteristicas='height=760,width=1080,top='+wintop+',left='+winleft+',toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=0';
			day = new Date();
			id = day.getTime();
			eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=0,width=1080,height=740,left = "+winleft+",top = "+wintop+"');");
		});
		
		/*PARA IR A LA PAGINA DE DEVOLUCIONES*/
		$('a#devol').click(function(){
			var orden = $(this).attr('data-id');
			URL = 'generar-devolucion.php?orden='+orden;
			var winleft = (screen.width-1080)/2;
			var wintop = (screen.height-780)/2;
			//caracteristicas='height=760,width=1080,top='+wintop+',left='+winleft+',toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=0';
			day = new Date();
			id = day.getTime();
			eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=0,width=1080,height=740,left = "+winleft+",top = "+wintop+"');");
		});
		
		
		/*PAGINACION*/
		$('#green2').smartpaginator({ totalrecords: <?php echo $totalRegister1; ?>, recordsperpage: 10, datacontainer: 'resultSearch1', dataelement: 'tr', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });
    });
</script>