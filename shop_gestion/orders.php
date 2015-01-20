<?php
session_start();
if ((!isset($_SESSION['Logged'])) && (!isset($_SESSION['UserIdAdmin']))) {
	header('Location: http://'.$_SERVER['HTTP_HOST']);
} else {
	include("config.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="css/stylos-pag.css" type="text/css" rel="stylesheet" />
        <link href="css/select-styles.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
		<title><?php echo $nombre; ?></title>
        <script type="text/javascript" src="js/jquery.tools.min.js"></script>
        <!--<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>-->
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.jcookie.min.js"></script>
         <script type="text/javascript" src="js/tablas.js"></script>
         <script type="text/javascript" src="js/jquery.validate.js"></script>
        <script type="text/javascript" src="js/jquery-class.js"></script>   
	</head>
<body>
</body>
<!-- LOADING -->
<div class="contentLoad">
	<div id="cargandoLoad">
    	<img src="images/ajax-loader.gif" />
	</div>
</div>
<!-- FIN -->

<div id="wrapper" class="wrapper">

    <div id="TopBar" class="iluminacion">	
        <?php 
			$active4 = 1;
			include('config.inc.php');
			include('menu.inc.php'); 
			include('ordenes-producto.inc.php');
			include('ordenes-por-fechas.inc.php');
			function array_envia($array) { 
  		  		$tmp = serialize($array); 
			    $tmp = urlencode($tmp); 
			    return $tmp; 
			} 
			
			//FUNCIONES PARA SACAR EL DIA SEMANA ESPAÑOL
			function actual_date($anyo, $mes, $dia, $diaSemana)  {  
				$week_days = array ("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");  
				$months = array ("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");  
				//$year_now = date ("Y");  
				$year_now = (int)$anyo;
				//$month_now = date ("n");  
				$month_now = (int)$mes;
				//$day_now = date ("j");  
				$day_now = (int)$dia;
				//$week_day_now = date ("w");
				$week_day_now = $diaSemana;
				$date = $week_days[$week_day_now] . ", " . $day_now . " de " . $months[$month_now] . " de " . $year_now;   
				    return $date;    
			}
										
			function diaSemana($ano,$mes,$dia) {
				// 0->domingo	 | 6->sabado
				$dia= date("w",mktime(0, 0, 0, $mes, $dia, $ano));
				return $dia;
			}
			//HASTA AQUI
			
		?>
    </div> 
    
    <!-- Para el tooltip del searh operation y estado -->
    <div class="tooltip_customer"></div>
    <!-- Fin tooltip -->
    
    <!-- GADGET MENU LATERAL -->
    <?php require("gadget.inc.php"); ?>
    <!-- FIN GADGET -->
    
    <div id="content-body">
    	<div id="general" class="content">
        	<div id="Contenido" style="padding-top: 20px; overflow: auto; overflow-x:hidden">   
            	<div style="position: absolute; top: 0px; left: 990px; z-index:9999999"><img src="images/pedidos.png" /></div>
            	<ul class="tabs">
    		    	<li class="active"><a href="#tab1"><span>Ordenes Actuales</span></a></li>
		   			<li><a href="#tab2">Ordenes por Producto</a></li>
                    <li><a href="#tab3">Ordenes por Oferta</a></li>
   		  	        <li><a href="#tab4">Ordenes por Usuario</a></li>               
		  	    </ul>
        		
                <div class="tab_container">                	                    
                    <!-- ORDENES DE PRODUCTOS ACTUALES -->
                    <div id="tab1" class="tab_content">
                    	<div id="busquedaClientes">	
                        	
                        </div>
                        <div id="resultSearch">
                        	<h3 style="margin-bottom:10px"><img src="images/result.png" style="float: left;" />&nbsp;Contra-Rembolso</h3>
                            <div id="frm">
                            <form id="form-cambiar-estadosC" name="form_cambiar_estadosC" data-title="group_1" method="post" action="cambiar-estados.orders.inc.php?idbd=<?php echo $_REQUEST['idbd']; ?>" enctype="application/x-www-form-urlencoded">
                            	<input type="hidden" id="listados" name="listados" value="0" />
                                <input type="hidden" id="idofertaList" name="idofertaList" value="0" />
                                <input type="hidden" name="idAgencia" value="1" />
                            	<fieldset id="group_1">
                        			<table id="resultSearch_Opt" cellpadding="0" cellspacing="0">
	                            		<thead>
                                        	<th width="30px">User</th>
                                        	<th width="30px">Pa&iacute;s</th>
		   	                            	<th width="60px">Orden</th>
        		                            <th width="160px">Fecha</th>
            		                        <th width="250px">Nombres</th>
                		                	<th width="250px">Producto</th>
                    		                <th width="70px">Imagen</th>
                        		            <th width="20px">#</th>  
                                            <th width="100px">Refer</th>                                    
                            		        <th width="100px">Talla/Tipo</th>  
                                            <th width="30px">Agencia</th>  
                                		    <th width="100px">Estado</th>
                                            <th width="32px">Pago</th>
                                    		<th width="30px"></th>                                   
		                                </thead>
    		                            <!-- PEDIDOS CONTRA-REMBOLSO -->
        		                        <tbody id="dataCustomerOrder">
            		                    	<?php
												//DETECTAMOS LA CANTIDAD DE ORDENES REALIZADAS POR CONTRA-REMBOLSO Y QUE SE ENVIAN POR TIPSA
												$ord = array();
												$cantidad = 0;
												$array_orders_contra = array();
												$SQL = "SELECT ordenes.IdOrden, lineasorden.Id ";
												$SQL .= "FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
												$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
												$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
												$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";		
												$SQL .= "INNER JOIN agenciatransportes ON opcionesoferta.IdAgencia = agenciatransportes.IdAgencia ";
												$SQL .= "WHERE TRIM(lineasorden.EstadoPedido) = 'Transito' ";
												$SQL .= "AND opcionesoferta.IdAgencia = 1 ";
												$SQL .= "AND TRIM(ordenes.EstadoPago) ='ok' AND TRIM(ordenes.FormaPago) = 'contra-rembolso' AND productos.Listados = 0";									
												$db->setQuery($SQL);
												$row = $db->execute();
												if (mysqli_num_rows($row) > 0) {
													$result = $db->loadObjectList();
													foreach($result as $result1) {
														$ord[] = array('idorden'=>$result1->IdOrden, 'lineaorden'=>$result1->Id); 
													}
												}
												//FIN
											
												//DATOS PARA PRESENTAR
												$contOrders = count($ord);											
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
													productos.Nombre_Producto,
													imagenes.BaseUrl,
													imagenes.Imagen
													
													FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden AND ordenes.IdOrden = '".$ord[$i]['idorden']."' AND lineasorden.Id = '".$ord[$i]['lineaorden']."' ";
												$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
											 	$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	 											$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
	 											$SQL .= "INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden ";
												$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion AND usuarios.Id = direcciones.D_IdCliente ";
												$SQL .= "INNER JOIN agenciatransportes ON opcionesoferta.IdAgencia = agenciatransportes.IdAgencia ";
												$SQL .= "LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1 ";
												$SQL .= "WHERE TRIM(ordenes.FormaPago) = 'contra-rembolso' AND TRIM(ordenes.EstadoPago) = 'ok' ";
												$SQL .= "AND TRIM(lineasorden.EstadoPedido) = 'Transito'";
												
												$db->setQuery($SQL);
												$row = $db->execute();
										
												if (mysqli_num_rows($row) > 0) {
													$result1 = $db->loadObject();
													$nomProd_Array = explode(" ", utf8_encode($result1->Nombre_Producto));
													$nomProducto = $nomProd_Array[0] ." ".$nomProd_Array[1]." ".$nomProd_Array[2]." ".$nomProd_Array[3];
													//foreach($result as $result1) {
														echo '														
														<tr>
															<td height="24px">';
																if (trim($result1->Tramitado) == 'usuario-web') { echo '<img src="images/usuario-web.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else if (trim($result1->Tramitado) == 'call-center') { echo '<img src="images/call-center.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else { echo '<img src="images/usuario.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																echo '
															</td>
															<td height="24px"><img src="images/'.$result1->D_Pais.'.png" width="16px" /></td>
															<td height="24px"><label>'.$result1->IdOrden.'</label></td>
															<td height="24px"><label class="date_fecha">'.$result1->FechaOrden.' | '.$result1->Hora.'</label></td>
															<td height="24px"><label>'.utf8_encode(ucwords(strtolower($result1->Nombres))).' ' .utf8_encode(ucwords(strtolower($result1->Apellidos))).'</label></td>
															<td height="24px"><label>'.$nomProducto.'...</label></td>
															<td height="24px">
																<img src="'.$result1->BaseUrl . $result1->Imagen.'" width="30px" title="'.utf8_encode($result1->Nombre_Producto).'" />
															</td>
															<td height="24px"><label>'.$result1->Cantidad.'</label></td>
															<td height="24px">';
																if ($result1->Referencia == '0')
																	echo '<label style="color: red">'.$result1->Referencia.'</label>';
																else
																	echo '<label>'.$result1->Referencia.'</label>';
															echo '
															</td>
															<td height="24px">';
																if ($result1->OptActiva == 1) echo '<label>'.utf8_encode($result1->Opcion).'</label>';
															echo '
															</td>
															<td>';
																if ($result1->IdAgencia == 1) echo '<label style="color: #0099CC">'.$result1->CodAgencia.'</label>'; else
																if ($result1->IdAgencia == 2) echo '<label style="color: #009900">'.$result1->CodAgencia.'</label>'; else
																echo '<label style="color: #800000">'.$result1->CodAgencia.'</label>';
																echo '
															</td>
															<td height="24px">';											
																 echo '<label style="color: red">'.$result1->EstadoPedido.'</label>';
																echo '
															</td>
															<td><img src="images/contrap.png" title="'.$result1->FormaPago.'" style="cursor: pointer" /></td>
															<td height="24px"><input class="check" type="checkbox" name="estados[]" value="'.$result1->IdOrden.'|'.$result1->lineasOrden.'" /></td>													
														</tr>
														';
														$cantidad = $cantidad + $result1->Cantidad;
														$array_orders_contra[] = array('nombres'=>($result1->D_Nombres).' '.($result1->D_Apellidos), 'dni'=>$result1->Dni, 'email'=>$result1->Email,
																				   'direccion'=>($result1->TipoVia).' '.($result1->Direccion).','.$result1->TipoNumero.','.($result1->Numero).','.($result1->Piso).
																				   ','.($result1->Puerta), 'cp'=>$result1->Cp, 'poblacion'=>($result1->Poblacion), 'provincia'=>($result1->Provincia),
																				   'telefono'=>($result1->Telefono), 'producto'=>($result1->Nombre_Producto), 'tipo'=>($result1->Opcion),
																				   'precio'=>$result1->Precio, 'gastosenvio'=>$result1->GEnvio, 'cantidad'=>$result1->Cantidad, 'total'=>$result1->Total, 'referencias'=>$result1->Referencia,
																				   'mensaje'=>$result1->Comentarios, 'idorden'=>$result1->IdOrden, 'pesoreal'=>$result1->PesoReal); 
													//}
												} 									
											}											
											?>                                 
        		                        </tbody>
                                        <tfoot>
                                        	<tr>
												<td colspan="5"></td>
												<td><strong style="font-size:14px; color:#FFF;">Pedidos</strong></td>
												<td><strong style="font-size: 14px; color: #FFF"><?php echo $cantidad; ?></strong></td><td colspan="3"></td>
												<td></td><td></td><td></td><td></td>
											</tr>
                                        </tfoot>
            		                </table>
                                    
                	            </fieldset>                               
                    	        <hr />
                        	    <table id="updateEstados">
                            		<tr>
                                		<td align="center"><a rel="group_1" href="#select_all" class="linkBoton"><img src="images/tick2.png" style="float: left; padding: 4px 0px 2px 7px" />Seleccionar Todos</a></td>
										<td align="center"><a rel="group_1" href="#select_none" class="linkBoton"><img src="images/tickno.png" style="float: left; padding: 4px 0px 2px 7px" />Deseleccionar Todos</a></td>
										<td align="center"><a rel="group_1" href="#invert_selection" class="linkBoton"><img src="images/invert.png" style="float: left; padding: 4px 0px 2px 7px" />Invertir Selección</a></td>	
    	                            </tr>
        	                        <tr>
            	                    	<td colspan="3" align="center" style="background: white">
                                        	<div class="divSelect" style="width: 200px">
	                	                    	<select id="estadoOrder" name="estadoOrder" data-placeholder="Seleccione Estado">
    	                	                    	<option value="0"></option>
        	                	                    <option value="Enviado">Enviado</option>
            	                	                <option value="Entregado">Entregado</option>
                	                	            <option value="Incidencia">Incidencia</option>
                    	                	        <option value="Transito">Transito</option>
                        	                	</select>
                                            </div>
	                                    </td>
    	                            </tr>
        	                        <tr>
            	                    	<td colspan="3" align="center">
                                        	<input type="hidden" id="tipoPedido" name="tipoPedido" value="contra-rembolso" />                                            
                	                    	<button type="submit" id="cambiarEstadoC" name="cambiarEstadoC"><img src="images/change-estado.png" style="float: left; padding: 2px 0px 2px 7px" />Cambiar estado</button>
                    	                </td>
                        	        </tr>
                            	</table>
                            </form>
                            </div>
                                                        
                            <hr />
                            <table>
                            	<tr>
                                	<td align="center">
                                    	<form action="export-excel-contra.inc.php" method="post" enctype="application/x-www-form-urlencoded">
                                        	<input type="hidden" id="datosExcel" name="datosExcel" value="<?php echo array_envia($array_orders_contra) ?>" />
	                                    	<button type="submit" id="exportExcelContra" name="exportExcelContra" ><img src="images/excel.png" style="float: left; padding: 2px 0px 2px 7px" />Exportar Todo</button>
                                        </form>
                                    </td>
                                    <td align="center">
	                                    	<button type="button" id="exportExcelContraSelect" name="exportExcelTarjetaSelect" ><img src="images/excel.png" style="float: left; padding: 2px 0px 2px 7px" />Exportar Selección</button>
                                    </td>
                                </tr>
                            </table>
                            <hr />
                            
                            <!-- PEDIDOS CONTRA-REEMBOLSO QUE SALEN POR REDUR --------------------------------------------------------------------------------------------------------- -->
                            <div id="frm">
                            <form id="form-cambiar-estadosCRedur" name="form_cambiar_estadosCRedur" data-title="group_redur" method="post" action="cambiar-estados.orders.inc.php?idbd=<?php echo $_REQUEST['idbd']; ?>" enctype="application/x-www-form-urlencoded">
                            	<input type="hidden" id="listados" name="listados" value="0" />
                                <input type="hidden" id="idofertaList" name="idofertaList" value="0" />
                                <input type="hidden" name="idAgencia" value="2" />
                            	<fieldset id="group_redur">
                        			<table id="resultSearch_Opt" cellpadding="0" cellspacing="0">
	                            		<thead>
                                        	<th width="30px">User</th>
                                        	<th width="30px">Pa&iacute;s</th>
		   	                            	<th width="60px">Orden</th>
        		                            <th width="160px">Fecha</th>
            		                        <th width="250px">Nombres</th>
                		                	<th width="250px">Producto</th>
                    		                <th width="70px">Imagen</th>
                        		            <th width="20px">#</th>  
                                            <th width="100px">Refer</th>                                    
                            		        <th width="100px">Talla/Tipo</th>  
                                            <th width="30px">Agencia</th>  
                                		    <th width="100px">Estado</th>
                                            <th width="32px">Pago</th>
                                    		<th width="30px"></th>                                   
		                                </thead>
    		                            <!-- PEDIDOS CONTRA-REMBOLSO -->
        		                        <tbody id="dataCustomerOrder">
            		                    	<?php
												//DETECTAMOS LA CANTIDAD DE ORDENES REALIZADAS POR CONTRA-REMBOLSO Y QUE SE ENVIAN POR TIPSA
												$ord = array();
												$cantidad = 0;
												$array_orders_contra = array();
												$SQL = "SELECT ordenes.IdOrden, lineasorden.Id ";
												$SQL .= "FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
												$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
												$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
												$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";		
												$SQL .= "INNER JOIN agenciatransportes ON opcionesoferta.IdAgencia = agenciatransportes.IdAgencia ";
												$SQL .= "WHERE TRIM(lineasorden.EstadoPedido) = 'Transito' ";
												$SQL .= "AND opcionesoferta.IdAgencia = 2 ";
												$SQL .= "AND TRIM(ordenes.EstadoPago) ='ok' AND TRIM(ordenes.FormaPago) = 'contra-rembolso' AND productos.Listados = 0";									
												$db->setQuery($SQL);
												$row = $db->execute();
												if (mysqli_num_rows($row) > 0) {
													$result = $db->loadObjectList();
													foreach($result as $result1) {
														$ord[] = array('idorden'=>$result1->IdOrden, 'lineaorden'=>$result1->Id); 
													}
												}
												//FIN
											
												//DATOS PARA PRESENTAR
												$contOrders = count($ord);											
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
													productos.Nombre_Producto,
													imagenes.BaseUrl,
													imagenes.Imagen
													
													FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden AND ordenes.IdOrden = '".$ord[$i]['idorden']."' AND lineasorden.Id = '".$ord[$i]['lineaorden']."' ";
												$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
											 	$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	 											$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
	 											$SQL .= "INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden ";
												$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion AND usuarios.Id = direcciones.D_IdCliente ";
												$SQL .= "INNER JOIN agenciatransportes ON opcionesoferta.IdAgencia = agenciatransportes.IdAgencia ";
												$SQL .= "LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1 ";
												$SQL .= "WHERE TRIM(ordenes.FormaPago) = 'contra-rembolso' AND TRIM(ordenes.EstadoPago) = 'ok' ";
												$SQL .= "AND TRIM(lineasorden.EstadoPedido) = 'Transito'";
												
												$db->setQuery($SQL);
												$row = $db->execute();
										
												if (mysqli_num_rows($row) > 0) {
													$result1 = $db->loadObject();
													$nomProd_Array = explode(" ", utf8_encode($result1->Nombre_Producto));
													$nomProducto = $nomProd_Array[0] ." ".$nomProd_Array[1]." ".$nomProd_Array[2]." ".$nomProd_Array[3];
													//foreach($result as $result1) {
														echo '														
														<tr>
															<td height="24px">';
																if (trim($result1->Tramitado) == 'usuario-web') { echo '<img src="images/usuario-web.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else if (trim($result1->Tramitado) == 'call-center') { echo '<img src="images/call-center.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else { echo '<img src="images/usuario.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																echo '
															</td>
															<td height="24px"><img src="images/'.$result1->D_Pais.'.png" width="16px" /></td>
															<td height="24px"><label>'.$result1->IdOrden.'</label></td>
															<td height="24px"><label class="date_fecha">'.$result1->FechaOrden.' | '.$result1->Hora.'</label></td>
															<td height="24px"><label>'.utf8_encode(ucwords(strtolower($result1->Nombres))).' ' .utf8_encode(ucwords(strtolower($result1->Apellidos))).'</label></td>
															<td height="24px"><label>'.$nomProducto.'...</label></td>
															<td height="24px">
																<img src="'.$result1->BaseUrl . $result1->Imagen.'" width="30px" title="'.utf8_encode($result1->Nombre_Producto).'" />
															</td>
															<td height="24px"><label>'.$result1->Cantidad.'</label></td>
															<td height="24px">';
																if ($result1->Referencia == '0')
																	echo '<label style="color: red">'.$result1->Referencia.'</label>';
																else
																	echo '<label>'.$result1->Referencia.'</label>';
															echo '
															</td>
															<td height="24px">';
																if ($result1->OptActiva == 1) echo '<label>'.utf8_encode($result1->Opcion).'</label>';
															echo '
															</td>
															<td>';
																if ($result1->IdAgencia == 1) echo '<label style="color: #0099CC">'.$result1->CodAgencia.'</label>'; else
																if ($result1->IdAgencia == 2) echo '<label style="color: #009900">'.$result1->CodAgencia.'</label>'; else
																echo '<label style="color: #800000">'.$result1->CodAgencia.'</label>';
																echo '
															</td>
															<td height="24px">';											
																 echo '<label style="color: red">'.$result1->EstadoPedido.'</label>';
																echo '
															</td>
															<td><img src="images/contrap.png" title="'.$result1->FormaPago.'" style="cursor: pointer" /></td>
															<td height="24px"><input class="check" type="checkbox" name="estados[]" value="'.$result1->IdOrden.'|'.$result1->lineasOrden.'" /></td>													
														</tr>
														';
														$cantidad = $cantidad + $result1->Cantidad;
														$array_orders_contra[] = array('nombres'=>($result1->D_Nombres).' '.($result1->D_Apellidos), 'dni'=>$result1->Dni, 'email'=>$result1->Email,
																				   'direccion'=>($result1->TipoVia).' '.($result1->Direccion).','.$result1->TipoNumero.','.($result1->Numero).','.($result1->Piso).
																				   ','.($result1->Puerta), 'cp'=>$result1->Cp, 'poblacion'=>($result1->Poblacion), 'provincia'=>($result1->Provincia),
																				   'telefono'=>($result1->Telefono), 'producto'=>($result1->Nombre_Producto), 'tipo'=>($result1->Opcion),
																				   'precio'=>$result1->Precio, 'gastosenvio'=>$result1->GEnvio, 'cantidad'=>$result1->Cantidad, 'total'=>$result1->Total, 'referencias'=>$result1->Referencia,
																				   'mensaje'=>$result1->Comentarios, 'idorden'=>$result1->IdOrden, 'pesoreal'=>$result1->PesoReal); 
													//}
												} 									
											}											
											?>                                 
        		                        </tbody>
                                        <tfoot>
                                        	<tr>
												<td colspan="5"></td>
												<td><strong style="font-size:14px; color:#FFF;">Pedidos</strong></td>
												<td><strong style="font-size: 14px; color: #FFF"><?php echo $cantidad; ?></strong></td><td colspan="3"></td>
												<td></td><td></td><td></td><td></td>
											</tr>
                                        </tfoot>
            		                </table>
                                    
                	            </fieldset>                               
                    	        <hr />
                        	    <table id="updateEstados">
                            		<tr>
                                		<td align="center"><a rel="group_redur" href="#select_all" class="linkBoton"><img src="images/tick2.png" style="float: left; padding: 4px 0px 2px 7px" />Seleccionar Todos</a></td>
										<td align="center"><a rel="group_redur" href="#select_none" class="linkBoton"><img src="images/tickno.png" style="float: left; padding: 4px 0px 2px 7px" />Deseleccionar Todos</a></td>
										<td align="center"><a rel="group_redur" href="#invert_selection" class="linkBoton"><img src="images/invert.png" style="float: left; padding: 4px 0px 2px 7px" />Invertir Selección</a></td>	
    	                            </tr>
        	                        <tr>
            	                    	<td colspan="3" align="center" style="background: white">
                                        	<div class="divSelect" style="width: 200px">
	                	                    	<select id="estadoOrder" name="estadoOrder" data-placeholder="Seleccione Estado">
    	                	                    	<option value="0"></option>
        	                	                    <option value="Enviado">Enviado</option>
            	                	                <option value="Entregado">Entregado</option>
                	                	            <option value="Incidencia">Incidencia</option>
                    	                	        <option value="Transito">Transito</option>
                        	                	</select>
                                            </div>
	                                    </td>
    	                            </tr>
        	                        <tr>
            	                    	<td colspan="3" align="center">
                                        	<input type="hidden" id="tipoPedido" name="tipoPedido" value="contra-rembolso" />                                            
                	                    	<button type="submit" id="cambiarEstadoC" name="cambiarEstadoC"><img src="images/change-estado.png" style="float: left; padding: 2px 0px 2px 7px" />Cambiar estado</button>
                    	                </td>
                        	        </tr>
                            	</table>
                            </form>
                            </div>
                                                        
                            <hr />
                            <table>
                            	<tr>
                                	<td align="center">
                                    	<form action="export-excel-contra.inc.php" method="post" enctype="application/x-www-form-urlencoded">
                                        	<input type="hidden" id="datosExcel" name="datosExcel" value="<?php echo array_envia($array_orders_contra) ?>" />
	                                    	<button type="submit" id="exportExcelContra" name="exportExcelContra" ><img src="images/excel.png" style="float: left; padding: 2px 0px 2px 7px" />Exportar Todo</button>
                                        </form>
                                    </td>
                                    <td align="center">
	                                    	<button type="button" id="exportExcelContraSelectRedur" name="exportExcelTarjetaSelectRedur" ><img src="images/excel.png" style="float: left; padding: 2px 0px 2px 7px" />Exportar Selección</button>
                                    </td>
                                </tr>
                            </table>
                            <!-- FIN PEDIDOS QUE SALEN POR REDUR ---------------------------------------------------------------------------------------------------------------------- -->
                            
                            <hr id="three" />
                            <!-- PEDIDOS POR PRODUCTO CONTRA-REMBOLSO -->
                            <?php
								$groupNumber = 100;
                               	$_sql = "SELECT * FROM productos WHERE Listados = '1'";
								$db->setQuery($_sql);
								$_row = $db->loadObjectList();
								foreach($_row as $_row1) {
									$idoferta = $_row1->IdOferta;
									$nombreP = $_row1->Nombre_Producto;
									PedidosProductoContra($ord[$i]['idorden'], $ord[$i]['lineaorden'], $idoferta, $nombreP, $db, $groupNumber);
									$groupNumber = $groupNumber + 1;
									echo '<hr id="three" />';
								}	
                            ?>
                            <!-- FIN PEDIDOS POR PRODUCTO -->
                            
                            
                            <!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
                            <!-- PEDIDOS POR TARJETA -------------------------------------------------------------------------------------------------------------------------------------------------------- -->
                            <!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
                            
                            <center><img src="images/visa_paypal.png" style="padding-top: 20px" /></center>
                            <h3 style="margin-bottom:10px; margin-top: 25px"><img src="images/result.png" style="float: left;" />&nbsp;Pedidos con Tarjeta</h3>
                            <div id="frm">
                            <form id="form-cambiar-estadosT" name="form_cambiar_estadosT" method="post" data-title="group_2" action="cambiar-estados.orders.inc.php?idbd=<?php echo $_REQUEST['idbd']; ?>" enctype="application/x-www-form-urlencoded">
                            <input type="hidden" id="listados" name="listados" value="0" />
                            <input type="hidden" id="idofertaList" name="idofertaList" value="0" />
                            <input type="hidden" name="idAgencia" value="1" />
                            <fieldset id="group_2">
                            	<table id="resultSearch_Opt" cellpadding="0" cellspacing="0">
                            		<thead>
                                    	<th width="30px">User</th>
                                    	<th width="30px">Pa&iacute;s</th>
                                		<th width="60px">Orden</th>
	                                    <th width="160px">Fecha</th>
    	                                <th width="250px">Nombres</th>
        	                        	<th width="250px">Producto</th>
            	                        <th width="70px">Imagen</th>
                	                    <th width="20px">#</th>  
                                        <th width="100px">Refer</th>                                  
                    	                <th width="100px">Talla/Tipo</th>  
                                        <th width="30px">Agencia</th>                                  
                        	            <th width="100px">Estado</th>
                                        <th width="32px">Pago</th>
                            	        <th width="30px"></th>                                   
                                	</thead>
	                                <!-- PEDIDOS TARJETA PAYPAL -->
    	                            <tbody id="dataCustomerOrder">
        	                        	<?php
											//DETECTAMOS LA CANTIDAD DE ORDENES REALIZADAS POR CONTRA-REMBOLSO
											$SQL = "SELECT ordenes.IdOrden, lineasorden.Id ";
											$SQL .= "FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
											$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
											$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
											$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";	
											$SQL .= "INNER JOIN agenciatransportes ON opcionesoferta.IdAgencia = agenciatransportes.IdAgencia ";	
											$SQL .= "WHERE TRIM(lineasorden.EstadoPedido) = 'Transito' "; 
											$SQL .= "AND opcionesoferta.IdAgencia = 1 ";
											$SQL .= "AND ordenes.EstadoPago ='ok' AND (ordenes.FormaPago = 'tarjeta' OR ordenes.FormaPago = 'paypal') AND productos.Listados = 0";									
											$db->setQuery($SQL);
											$row = $db->execute();
											if (mysqli_num_rows($row) > 0) {
												$result = $db->loadObjectList();
												foreach($result as $result1) {
													$ordT[] = array('idorden'=>$result1->IdOrden, 'lineaorden'=>$result1->Id); 
												}
											}
											//FIN
										
											
											//DATOS PARA PRESENTAR
											$contOrdersT = count($ordT);											
											//FIN
											for ($i = 0; $i <= $contOrdersT; $i++) {
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
												usuarios.Dni, 
												productos.Nombre_Producto,
												imagenes.BaseUrl,
												imagenes.Imagen										
												FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden AND ordenes.IdOrden = '".$ordT[$i]['idorden']."' AND lineasorden.Id = '".$ordT[$i]['lineaorden']."' ";
											$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
										 	$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	 										$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
	 										$SQL .= "INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden ";
											$SQL .= "INNER JOIN agenciatransportes ON opcionesoferta.IdAgencia = agenciatransportes.IdAgencia ";
											$SQL .= "LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1 ";
											$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion AND usuarios.Id = direcciones.D_IdCliente ";
											$SQL .= "WHERE (ordenes.FormaPago = 'tarjeta' OR ordenes.FormaPago = 'paypal') ";
											$SQL .= "AND TRIM(lineasorden.EstadoPedido) = 'Transito'";
											$db->setQuery($SQL);
											$row = $db->execute();
											
											if (mysqli_num_rows($row) > 0) {
												$result1 = $db->loadObject();	
													$nomProd_Array = explode(" ", utf8_encode($result1->Nombre_Producto));
													$nomProducto = $nomProd_Array[0] ." ".$nomProd_Array[1]." ".$nomProd_Array[2]." ".$nomProd_Array[3];											
													echo '
													<tr>
														<td height="24px">';
																if (trim($result1->Tramitado) == 'usuario-web') { echo '<img src="images/usuario-web.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else if (trim($result1->Tramitado) == 'call-center') { echo '<img src="images/call-center.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else { echo '<img src="images/usuario.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
															echo '
														</td>
														<td height="24px"><img src="images/'.$result1->Pais.'.png" width="16px" /></td>
														<td height="24px"><label>'.$result1->IdOrden.'</label></td>
														<td height="24px"><label class="date_fecha">'.$result1->FechaOrden.' | '.$result1->Hora.'</label></td>
														<td height="24px"><label>'.utf8_encode(ucwords(strtolower($result1->Nombres))).' ' .utf8_encode(ucwords(strtolower($result1->Apellidos))).'</label></td>
														<td height="24px"><label>'.$nomProducto.'...</label></td>
														<td height="24px">
															<img src="'.$result1->BaseUrl . $result1->Imagen.'" width="30px" title="'.utf8_encode($result1->Nombre_Producto).'" />
														</td>
														<td height="24px"><label>'.$result1->Cantidad.'</label></td>
														<td height="24px">';
															if ($result1->Referencia == '0')
																echo '<label style="color: red">'.$result1->Referencia.'</label>';
															else
																echo '<label>'.$result1->Referencia.'</label>';
														echo '
														</td>
														<td height="24px">';
														if ($result1->OptActiva == 1) echo '<label>'.utf8_encode($result1->Opcion).'</label>';
														echo '
														</td>
														<td>';
																if ($result1->IdAgencia == 1) echo '<label style="color: #0099CC">'.$result1->CodAgencia.'</label>'; else
																if ($result1->IdAgencia == 2) echo '<label style="color: #009900">'.$result1->CodAgencia.'</label>'; else
																echo '<label style="color: #800000">'.$result1->CodAgencia.'</label>';
																echo '
														</td>
														<td height="24px">';															
															if ($result1->EstadoPedido == 'Transito') echo '<label style="color: red">Transito</label>';
															else echo '<label style="color: blue">Parcial</label>';
														echo '
														</td>
														<td>';
															if ($result1->FormaPago == 'tarjeta') { $imagen = 'visap.png'; } else if ($result1->FormaPago == 'paypal') { $imagen = 'paypalp.png'; }															
														echo '
															<img src="images/'.$imagen.'" title="'.$result1->FormaPago.'='.$result1->Code_Authorisation.'" style="cursor: pointer" />
														</td>
														<td height="24px"><input class="check" type="checkbox" name="estados[]" value="'.$result1->IdOrden.'|'.$result1->lineasOrden.'" /></td>														
													</tr>
													';
													$cantidadT = $cantidadT + $result1->Cantidad;	
													$array_orders_tarjeta[] = array('nombres'=>utf8_encode($result1->D_Nombres).' '.utf8_encode($result1->D_Apellidos), 'dni'=>$result1->Dni, 'email'=>$result1->Email, 
																				   'direccion'=>utf8_encode($result1->TipoVia).' '.utf8_encode($result1->Direccion).','.$result1->TipoNumero.','.utf8_encode($result1->Numero).','.utf8_encode($result1->Piso).
																				   ','.utf8_encode($result1->Puerta), 'cp'=>$result1->Cp, 'poblacion'=>utf8_encode($result1->Poblacion), 'provincia'=>utf8_encode($result1->Provincia),
																				   'telefono'=>utf8_encode($result1->Telefono), 'producto'=>utf8_encode($result1->Nombre_Producto), 'tipo'=>utf8_encode($result1->Opcion),
																				   'precio'=>$result1->Precio, 'gastosenvio'=>$result1->GEnvio, 'cantidad'=>$result1->Cantidad, 'total'=>$result1->Total, 'referencias'=>$result1->Referencia,
																				   'mensaje'=>$result1->Comentarios, 'idorden'=>$result1->IdOrden, 'pesoreal'=>$result1->PesoReal); 											
											}											
										}											
										?>                                 
        	                        </tbody>
                                    <tfoot>
                                    	<tr style="background: #1F88A7;" height="25px">
											<td colspan="5"></td>
											<td><strong style="font-size:14px; color:#FFF;">Pedidos</strong></td>
											<td><strong style="font-size: 14px; color: #FFF"><?php echo $cantidadT; ?></strong></td><td colspan="3"></td>
											<td></td><td></td><td></td><td></td>
										</tr>
                                    </tfoot>
            	                </table>
                            </fieldset>
                            <hr />
                            <table id="updateEstados">
                            	<tr>
                                	<td align="center"><a rel="group_2" href="#select_all" class="linkBoton"><img src="images/tick2.png" style="float: left; padding: 4px 0px 2px 7px" />Seleccionar Todos</a></td>
									<td align="center"><a rel="group_2" href="#select_none" class="linkBoton"><img src="images/tickno.png" style="float: left; padding: 4px 0px 2px 7px" />Deseleccionar Todos</a></td>
									<td align="center"><a rel="group_2" href="#invert_selection" class="linkBoton"><img src="images/invert.png" style="float: left; padding: 4px 0px 2px 7px" />Invertir Selección</a></td>	
                                </tr>
                                <tr>
                                	<td colspan="3" align="center" style="background: white;">
                                    	<div class="divSelect" style="width: 200px">
	                                    	<select id="estadoOrder" name="estadoOrder" data-placeholder="Seleccione Estado">
    	                                    	<option value="0"></option>
        	                                    <option value="Enviado">Enviado</option>
            	                                <option value="Entregado">Entregado</option>
                	                            <option value="Incidencia">Incidencia</option>
                    	                        <option value="Transito">Transito</option>
                        	                </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                	<td colspan="3" align="center">     
                                    	<input type="hidden" id="tipoPedido" name="tipoPedido" value="tarjeta" />                                	
                                        <button type="submit" id="cambiarEstadoT" name="cambiarEstadoT"><img src="images/change-estado.png" style="float: left; padding: 2px 0px 2px 7px" />Cambiar estado</button>
                                    </td>
                                </tr>
                            </table>
                            </form>
                            </div>
                            
                            <hr />
                            <table>
                            	<tr>
                                	<td align="center">
                                    	<form action="export-excel-tarjeta.inc.php" method="post" enctype="application/x-www-form-urlencoded">
                                        	<input type="hidden" id="datosExcel" name="datosExcel" value="<?php echo array_envia($array_orders_tarjeta) ?>" />
	                                    	<button type="submit" id="exportExcelTarjeta" name="exportExcelTarjeta" ><img src="images/excel.png" style="float: left; padding: 2px 0px 2px 7px" />Exportar Todo</button>
                                        </form>
                                    </td>
                                    <td align="center">
	                                    	<button type="button" id="exportExcelTarjetaSelect" name="exportExcelTarjetaSelect" ><img src="images/excel.png" style="float: left; padding: 2px 0px 2px 7px" />Exportar Selección</button>
                                    </td>
                                </tr>
                            </table>
                            <hr />
                            
                            <!-- PEDIDOR POR TARJETA REDUR -------------------------------------------------------------------------------------------------------------------------------------------------- -->
                            <div id="frm">
                            <form id="form-cambiar-estadosTRedur" name="form_cambiar_estadosTRedur" method="post" data-title="group_redur_2" action="cambiar-estados.orders.inc.php?idbd=<?php echo $_REQUEST['idbd']; ?>" enctype="application/x-www-form-urlencoded">
                            <input type="hidden" id="listados" name="listados" value="0" />
                            <input type="hidden" id="idofertaList" name="idofertaList" value="0" />
                            <input type="hidden" name="idAgencia" value="2" />
                            <fieldset id="group_redur_2">
                            	<table id="resultSearch_Opt" cellpadding="0" cellspacing="0">
                            		<thead>
                                    	<th width="30px">User</th>
                                    	<th width="30px">Pa&iacute;s</th>
                                		<th width="60px">Orden</th>
	                                    <th width="160px">Fecha</th>
    	                                <th width="250px">Nombres</th>
        	                        	<th width="250px">Producto</th>
            	                        <th width="70px">Imagen</th>
                	                    <th width="20px">#</th>  
                                        <th width="100px">Refer</th>                                  
                    	                <th width="100px">Talla/Tipo</th>  
                                        <th width="30px">Agencia</th>                                  
                        	            <th width="100px">Estado</th>
                                        <th width="32px">Pago</th>
                            	        <th width="30px"></th>                                   
                                	</thead>
	                                <!-- PEDIDOS TARJETA PAYPAL -->
    	                            <tbody id="dataCustomerOrder">
        	                        	<?php
											//DETECTAMOS LA CANTIDAD DE ORDENES REALIZADAS POR CONTRA-REMBOLSO
											$SQL = "SELECT ordenes.IdOrden, lineasorden.Id ";
											$SQL .= "FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
											$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
											$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
											$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";	
											$SQL .= "INNER JOIN agenciatransportes ON opcionesoferta.IdAgencia = agenciatransportes.IdAgencia ";	
											$SQL .= "WHERE TRIM(lineasorden.EstadoPedido) = 'Transito' "; 
											$SQL .= "AND opcionesoferta.IdAgencia = 2 ";
											$SQL .= "AND ordenes.EstadoPago ='ok' AND (ordenes.FormaPago = 'tarjeta' OR ordenes.FormaPago = 'paypal') AND productos.Listados = 0";									
											$db->setQuery($SQL);
											$row = $db->execute();
											if (mysqli_num_rows($row) > 0) {
												$result = $db->loadObjectList();
												foreach($result as $result1) {
													$ordTR[] = array('idorden'=>$result1->IdOrden, 'lineaorden'=>$result1->Id); 
												}
											}
											//FIN
										
											
											//DATOS PARA PRESENTAR
											$contOrdersT = count($ordTR);											
											//FIN
											for ($i = 0; $i <= $contOrdersT; $i++) {
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
												usuarios.Dni, 
												productos.Nombre_Producto,
												imagenes.BaseUrl,
												imagenes.Imagen										
												FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden AND ordenes.IdOrden = '".$ordTR[$i]['idorden']."' AND lineasorden.Id = '".$ordTR[$i]['lineaorden']."' ";
											$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
										 	$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	 										$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
	 										$SQL .= "INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden ";
											$SQL .= "INNER JOIN agenciatransportes ON opcionesoferta.IdAgencia = agenciatransportes.IdAgencia ";
											$SQL .= "LEFT JOIN imagenes ON productos.IdOferta = imagenes.Idoferta AND imagenes.estado = 1 ";											
											$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion AND usuarios.Id = direcciones.D_IdCliente ";
											$SQL .= "WHERE (ordenes.FormaPago = 'tarjeta' OR ordenes.FormaPago = 'paypal') ";
											$SQL .= "AND TRIM(lineasorden.EstadoPedido) = 'Transito'";
											$db->setQuery($SQL);
											$row = $db->execute();
											
											if (mysqli_num_rows($row) > 0) {
												$result1 = $db->loadObject();	
													$nomProd_Array = explode(" ", utf8_encode($result1->Nombre_Producto));
													$nomProducto = $nomProd_Array[0] ." ".$nomProd_Array[1]." ".$nomProd_Array[2]." ".$nomProd_Array[3];											
													echo '
													<tr>
														<td height="24px">';
																if (trim($result1->Tramitado) == 'usuario-web') { echo '<img src="images/usuario-web.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else if (trim($result1->Tramitado) == 'call-center') { echo '<img src="images/call-center.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else { echo '<img src="images/usuario.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
															echo '
														</td>
														<td height="24px"><img src="images/'.$result1->Pais.'.png" width="16px" /></td>
														<td height="24px"><label>'.$result1->IdOrden.'</label></td>
														<td height="24px"><label class="date_fecha">'.$result1->FechaOrden.' | '.$result1->Hora.'</label></td>
														<td height="24px"><label>'.utf8_encode(ucwords(strtolower($result1->Nombres))).' ' .utf8_encode(ucwords(strtolower($result1->Apellidos))).'</label></td>
														<td height="24px"><label>'.$nomProducto.'...</label></td>
														<td height="24px">
															<img src="'.$result1->BaseUrl . $result1->Imagen.'" width="30px" title="'.utf8_encode($result1->Nombre_Producto).'" />
														</td>
														<td height="24px"><label>'.$result1->Cantidad.'</label></td>
														<td height="24px">';
															if ($result1->Referencia == '0')
																echo '<label style="color: red">'.$result1->Referencia.'</label>';
															else
																echo '<label>'.$result1->Referencia.'</label>';
														echo '
														</td>
														<td height="24px">';
														if ($result1->OptActiva == 1) echo '<label>'.utf8_encode($result1->Opcion).'</label>';
														echo '
														</td>
														<td>';
																if ($result1->IdAgencia == 1) echo '<label style="color: #0099CC">'.$result1->CodAgencia.'</label>'; else
																if ($result1->IdAgencia == 2) echo '<label style="color: #009900">'.$result1->CodAgencia.'</label>'; else
																echo '<label style="color: #800000">'.$result1->CodAgencia.'</label>';
																echo '
														</td>
														<td height="24px">';															
															if ($result1->EstadoPedido == 'Transito') echo '<label style="color: red">Transito</label>';
															else echo '<label style="color: blue">Parcial</label>';
														echo '
														</td>
														<td>';
															if ($result1->FormaPago == 'tarjeta') { $imagen = 'visap.png'; } else if ($result1->FormaPago == 'paypal') { $imagen = 'paypalp.png'; }															
														echo '
															<img src="images/'.$imagen.'" title="'.$result1->FormaPago.'='.$result1->Code_Authorisation.'" style="cursor: pointer" />
														</td>
														<td height="24px"><input class="check" type="checkbox" name="estados[]" value="'.$result1->IdOrden.'|'.$result1->lineasOrden.'" /></td>														
													</tr>
													';
													$cantidadT = $cantidadT + $result1->Cantidad;	
													$array_orders_tarjeta[] = array('nombres'=>utf8_encode($result1->D_Nombres).' '.utf8_encode($result1->D_Apellidos), 'dni'=>$result1->Dni, 'email'=>$result1->Email, 
																				   'direccion'=>utf8_encode($result1->TipoVia).' '.utf8_encode($result1->Direccion).','.$result1->TipoNumero.','.utf8_encode($result1->Numero).','.utf8_encode($result1->Piso).
																				   ','.utf8_encode($result1->Puerta), 'cp'=>$result1->Cp, 'poblacion'=>utf8_encode($result1->Poblacion), 'provincia'=>utf8_encode($result1->Provincia),
																				   'telefono'=>utf8_encode($result1->Telefono), 'producto'=>utf8_encode($result1->Nombre_Producto), 'tipo'=>utf8_encode($result1->Opcion),
																				   'precio'=>$result1->Precio, 'gastosenvio'=>$result1->GEnvio, 'cantidad'=>$result1->Cantidad, 'total'=>$result1->Total, 'referencias'=>$result1->Referencia,
																				   'mensaje'=>$result1->Comentarios, 'idorden'=>$result1->IdOrden, 'pesoreal'=>$result1->PesoReal); 											
											}											
										}											
										?>                                 
        	                        </tbody>
                                    <tfoot>
                                    	<tr style="background: #1F88A7;" height="25px">
											<td colspan="5"></td>
											<td><strong style="font-size:14px; color:#FFF;">Pedidos</strong></td>
											<td><strong style="font-size: 14px; color: #FFF"><?php echo $cantidadT; ?></strong></td><td colspan="3"></td>
											<td></td><td></td><td></td><td></td>
										</tr>
                                    </tfoot>
            	                </table>
                            </fieldset>
                            <hr />
                            <table id="updateEstados">
                            	<tr>
                                	<td align="center"><a rel="group_redur_2" href="#select_all" class="linkBoton"><img src="images/tick2.png" style="float: left; padding: 4px 0px 2px 7px" />Seleccionar Todos</a></td>
									<td align="center"><a rel="group_redur_2" href="#select_none" class="linkBoton"><img src="images/tickno.png" style="float: left; padding: 4px 0px 2px 7px" />Deseleccionar Todos</a></td>
									<td align="center"><a rel="group_redur_2" href="#invert_selection" class="linkBoton"><img src="images/invert.png" style="float: left; padding: 4px 0px 2px 7px" />Invertir Selección</a></td>	
                                </tr>
                                <tr>
                                	<td colspan="3" align="center" style="background: white;">
                                    	<div class="divSelect" style="width: 200px">
	                                    	<select id="estadoOrder" name="estadoOrder" data-placeholder="Seleccione Estado">
    	                                    	<option value="0"></option>
        	                                    <option value="Enviado">Enviado</option>
            	                                <option value="Entregado">Entregado</option>
                	                            <option value="Incidencia">Incidencia</option>
                    	                        <option value="Transito">Transito</option>
                        	                </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                	<td colspan="3" align="center">     
                                    	<input type="hidden" id="tipoPedido" name="tipoPedido" value="tarjeta" />                                	
                                        <button type="submit" id="cambiarEstadoT" name="cambiarEstadoT"><img src="images/change-estado.png" style="float: left; padding: 2px 0px 2px 7px" />Cambiar estado</button>
                                    </td>
                                </tr>
                            </table>
                            </form>
                            </div>
                            
                            <hr />
                            <table>
                            	<tr>
                                	<td align="center">
                                    	<form action="export-excel-tarjeta.inc.php" method="post" enctype="application/x-www-form-urlencoded">
                                        	<input type="hidden" id="datosExcel" name="datosExcel" value="<?php echo array_envia($array_orders_tarjeta) ?>" />
	                                    	<button type="submit" id="exportExcelTarjeta" name="exportExcelTarjeta" ><img src="images/excel.png" style="float: left; padding: 2px 0px 2px 7px" />Exportar Todo</button>
                                        </form>
                                    </td>
                                    <td align="center">
	                                    	<button type="button" id="exportExcelTarjetaSelect" name="exportExcelTarjetaSelect" ><img src="images/excel.png" style="float: left; padding: 2px 0px 2px 7px" />Exportar Selección</button>
                                    </td>
                                </tr>
                            </table>
                            <!-- FIN PEDIDOS POR TARJETA REDUR ---------------------------------------------------------------------------------------------------------------------------------------------- -->
                            
                            <hr id="three" />
                            <!-- PEDIDOS POR PRODUCTO TARJETAS -->
                            <?php
								$groupNumber = 200;
                               	$_sql = "SELECT * FROM productos WHERE Gestion = 1 AND Listados = '1'";
								$db->setQuery($_sql);
								$_row = $db->loadObjectList();
								foreach($_row as $_row1) {
									$idoferta = $_row1->IdOferta;
									$nombreP = $_row1->Nombre_Producto;
									PedidosProductoTarjeta($ord[$i]['idorden'], $ord[$i]['lineaorden'], $idoferta, $nombreP, $db, $groupNumber);
									$groupNumber = $groupNumber + 1;
									echo '<hr id="three" />';
								}	
                            ?>
                            <!-- FIN PEDIDOS POR PRODUCTO -->
                            
                            
                            <h3 style="margin-bottom:10px; margin-top: 25px"><img src="images/result.png" style="float: left;" />&nbsp;Resumen Pedidos</h3>
                            
                            <div id="dvData0">
	                            <table cellpadding="0" cellspacing="0">
    	                        	<thead>
        	                        	<th colspan="7" align="center">
            	                        	<?php
	            	                        	$f1 = date('Y-m-d'); $_f1 = explode('-', $f1); $Y = $_f1[0]; $M = $_f1[1]; $D = $_f1[2]; $diaSemana1 = diaSemana($Y, $M, $D); $fechaTexto1 = actual_date($Y, $M, $D, $diaSemana1);
												echo 'Pedidos a Fecha: &nbsp;&nbsp;'.$fechaTexto1;
											?>
                            	        </th>
                                	</thead>
	                            	<thead>
    	                            	<th width="70px">IdOferta</th>
        	                            <th width="250px">Nombre Oferta</th>
            	                        <th width="150px">Talla/Tipo</th>
                                        <th width="100px">Referencias</th>
                	                    <th width="80px">Precio</th>
                    	                <th width="80px">Gastos Env&iacute;o</th>
                        	            <th width="80px">Cantidad</th>
                            	    </thead>
                                	<tbody>
	                                	<?php
										$SQL = "SELECT SUM(lineasorden.Cantidad) AS Total, productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Precio, opcionesoferta.Opcion, opcionesoferta.Referencia  FROM ordenes ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
										$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
										$SQL .= "LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1 ";
										$SQL .= "WHERE lineasorden.EstadoPedido = 'Transito' AND ordenes.EstadoPago = 'ok' GROUP BY opcionesoferta.Id ASC";
										$db->setQuery($SQL);
										$row = $db->execute();
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
                                				echo '
												<tr height="20px">
			                                    	<td><label>'.$result1->IdOferta.'</label></td>
        			                                <td><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
            	    		                        <td><label>'.utf8_encode($result1->Opcion).'</label></td>
													<td>';
														if ($result1->Referencia == 0)
															echo '<label style="color: red">'.$result1->Referencia.'</label>';
														else
															echo '<label>'.$result1->Referencia.'</label>';
													echo '
													</td>
                	        		                <td><label>'.$result1->Precio.'</label></td>
                    	            		        <td><label>'.$result1->GastosEnvio.'</label></td>
                        	                		<td><label>'.$result1->Total.'</label></td>
                            	        		</tr>';
												$cantidadP = $cantidadP + $result1->Total;
											}
										} else {
											$cantidadP = 0;
											echo '
											<tr><td colspan="7" height="25px"></td></tr>
											<tr><td colspan="7" height="25px"></td></tr>
											';
											}
										?>
                                	</tbody>
	                                <tfoot>
    	                            	<tr style="color: white; font-size: 14px; font-variant:small-caps;">
        	                            	<td bgcolor="#1F88A7" colspan="5"></td>
            	                        	<td bgcolor="#1F88A7" align="center" height="30px"><strong style="color: #FFF; font-size: 14px">Total Pedidos&nbsp;&nbsp;</strong></td>
                	                        <td  bgcolor="#1F88A7" align="center"><strong style="color: #FFF; font-size: 14px"><?php echo $cantidadP; ?></strong></td>
                    	                </tr>
                        	        </tfoot>
                            	</table>
                            </div>
                            
                            <div class="excel">
                            	<form id="excel0" name="excel0" method="post" action="ficheroExcel.php">
                                	<input type="hidden" id="values0" name="values0" />
                                    <input type="submit" id="btnExport" value="Exportar Excel" alt="0" />
                                </form>                               
                            </div> 
                            
                        </div>
                    </div>
                    
                    <!-- INFORME DE PEDIDOS POR PRODUCTO -->
                	<div id="tab2" class="tab_content">
                    	<div id="busquedaClientes">	
                        	<div class="fieldset">
                            	<h2 class="legend">Rango de Fechas</h2>
	                        	<form id="formPedidosFecha" name="formPedidosFecha" class="loading" method="post" action="orders.php?idbd=<?php echo $_REQUEST['idbd']; ?>" accept-charset="utf-8" enctype="multipart/form-data">
		                        	<table style="width:40%">
    		                        	<thead>
        		                        	<th><span style="font-size:15px; font-variant: small-caps; font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif">Fecha Inicio</span></th>
            		                        <th><span style="font-size:15px; font-variant: small-caps; font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif">Fecha Fin</span></th>
                		                    <th></th>
                    		            </thead>
                        		        <tbody>
                            		    	<tr>
                                		    	<td align="center">
                                    		    	<input type="text" id="Fecha1" name="Fecha1" style="text-align:center; width: 150px" autocomplete="off" />
                	                        	</td>
	                	                        <td align="center">
    	                	                    	<input type="text" id="Fecha2" name="Fecha2" style="text-align: center; width: 150px" autocomplete="off" />
        	                	                </td>                                	            
	            	                        </tr>
                                            <tr>
                                            	<td colspan="2" align="center" height="40px">
                                                	<select id="selectProduct" name="selectProduct" class="chosen-select" data-placeholder="Listado de Productos..." style="width: 350px";>
                                                    	<option value="0"></option>
                                                        <?php
															$SQL = "SELECT * FROM productos ";
															$SQL .= "INNER JOIN opcionesoferta ON productos.IdOferta = opcionesoferta.IdOpcion ";
															$SQL .= "AND productos.Gestion = 1 GROUP BY opcionesoferta.IdOpcion ORDER BY IdOferta DESC";
															$db->setQuery($SQL);
															$result = $db->loadObjectList();
															foreach($result as $result1) {
																echo '<option value="'.$result1->IdOferta.'|'.$result1->Id.'">'.utf8_encode(($result1->Nombre_Producto)).'</option>';
															}
															$db->freeResults();
														?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                	                        	 <td id="option" style="height: 0px" colspan="2" align="center"></td>
                    	                    </tr>
                                            <tr>
                                            	<td colspan="2" align="center" height="50px">
                                    	        	<input type="submit" id="consultar" name="consultar" value="Consultar Informe" alt="1" />                                                    
                                        	    </td>
                                            </tr>
    	            	                </tbody>
        	            	        </table>
            	                </form>
                            </div>
                        </div>
                        <div id="resultSearch_Fecha">
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Resultados</h3><br />
                            <div id="dvData1">
	                        	<table id="resultSearch" cellpadding="0" cellspacing="0">
                                	<thead>
	                                	<th colspan="13" align="center">
											<?php 
												if(isset($_POST['consultar'])) {
													
													$f1 = $_POST['Fecha1']; $_f1 = explode('-', $f1); $Y = $_f1[0]; $M = $_f1[1]; $D = $_f1[2]; $diaSemana1 = diaSemana($Y, $M, $D); $fechaTexto1 = actual_date($Y, $M, $D, $diaSemana1);
													$f2 = $_POST['Fecha2']; $_f2 = explode('-', $f2); $Y = $_f2[0]; $M = $_f2[1]; $D = $_f2[2]; $diaSemana2 = diaSemana($Y, $M, $D); $fechaTexto2 = actual_date($Y, $M, $D, $diaSemana2);
												
													if ($_POST['Fecha1'] == $_POST['Fecha2']) { echo 'Fecha de Informe : ' . $fechaTexto1; } else { echo 'Rango Fecha Informe : &nbsp;&nbsp;'.$fechaTexto1. ' &nbsp;-&nbsp; ' .$fechaTexto2; } 
												}
											?>
				            			</th>
                                    </thead>
    	                        	<thead>
        	                        	<th width="60px">Orden</th>
							        	<th width="150px">Fecha</th>
							            <th width="250px">Nombres</th>
							            <th width="250px">Producto</th>
							            <th width="70px">Imagen</th>
						    	        <th width="50px">Cantidad</th>                                    
						        	    <th width="100px">Talla/Tipo</th>                                    
                                    	<th width="60px">Precio</th>
                                        <th width="60px">Total</th>
                                        <th width="60px">Base Imp.</th>
                                        <th width="40px"></th> 
							            <th width="70px">Estado</th>
							            <th width="32px">Pago</th>						                                                                                   
        	                        </thead>
            	                    <?php
										if (isset($_POST['consultar'])) {
											ordenesFechas($web);
										} else {
											echo '								
	                            	    <tbody id="dataCustomerEst">
											<tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
        	                            	<tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
	            	                        <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
    	            	                    <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
        	            	                <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
            	            	            <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
                	            	        <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
                    	            	</tbody>';
										}
									?>
	                            </table>
                             </div>
                            
							 <div class="excel">
                                <form id="excel1" name="excel1" method="post" action="ficheroExcel.php">
                                	<input type="hidden" id="values1" name="values1" />
                                    <input type="submit" id="btnExport" value="Exportar Excel" alt="1" />
                                </form>                               
                            </div>							
						
                        </div>							                       
                    </div>
                    
                    <!-- ORDENES POR OFERTA -->
                    <div id="tab3" class="tab_content">
                    	<div class="busquedaClientes">
                        	<div class="fieldset">
                            	<h2 class="legend">Rango de Fechas</h2>
	                        	<form id="formPedidosOferta" name="formPedidosOferta" class="loading" method="post" action="orders.php?idbd=<?php echo $_REQUEST['idbd']; ?>" accept-charset="utf-8" enctype="multipart/form-data">
		                        	<table style="width:40%">
    		                        	<thead>
        		                        	<th><span style="font-size:15px; font-variant: small-caps; font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif">Fecha Inicio</span></th>
            		                        <th><span style="font-size:15px; font-variant: small-caps; font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif">Fecha Fin</span></th>
                		                    <th></th>
                    		            </thead>
                        		        <tbody>
                            		    	<tr>
                                		    	<td align="center">
                                    		    	<input type="text" id="Fecha3" name="Fecha1" style="text-align:center; width: 150px" autocomplete="off" />
                	                        	</td>
	                	                        <td align="center">
    	                	                    	<input type="text" id="Fecha4" name="Fecha2" style="text-align: center; width: 150px" autocomplete="off" />
        	                	                </td>                                	            
	            	                        </tr>
                                            <tr>
                                            	<td colspan="2" align="center" height="40px">
                                                	<select id="selectOferta" name="selectOferta" class="chosen-select" data-placeholder="Listado de Productos..." style="width: 350px";>
                                                    	<option value="0"></option>
                                                        <?php
															$SQL = "SELECT * FROM productos ";
															$SQL .= "INNER JOIN opcionesoferta ON productos.IdOferta = opcionesoferta.IdOpcion ";
															$SQL .= "AND productos.Gestion = 1 GROUP BY opcionesoferta.IdOpcion ORDER BY IdOferta DESC";
															$db->setQuery($SQL);
															$result = $db->loadObjectList();
															foreach($result as $result1) {
																echo '<option value="'.$result1->IdOferta.'">'.utf8_encode(($result1->Nombre_Producto)).'</option>';
															}
															$db->freeResults();
														?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                	                        	 <td id="option" style="height: 0px" colspan="2" align="center"></td>
                    	                    </tr>
                                            <tr>
                                            	<td colspan="2" align="center" height="50px">
                                    	        	<input type="submit" id="consultarOrdOferta" name="consultarOrdOferta" value="Consultar Informe" alt="2" />                                                    
                                        	    </td>
                                            </tr>
    	            	                </tbody>
        	            	        </table>
            	                </form>
                            </div>
                        </div>
                        <!-- Inicio content tab -->
                        <div id="resultSearch">
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Resultados</h3><br />
                            <div id="dvData2"> <!-- EL VALOR dvData NOS SIRVE PARA LA EXPORTACION DE EXCEL -->
	                        	<table id="resultSearch" cellpadding="0" cellspacing="0">
                                	<thead>
	                                	<th colspan="10" align="center">
											<?php 
												if(isset($_POST['consultarOrdOferta'])) {
													
													$f1 = $_POST['Fecha1']; $_f1 = explode('-', $f1); $Y = $_f1[0]; $M = $_f1[1]; $D = $_f1[2]; $diaSemana1 = diaSemana($Y, $M, $D); $fechaTexto1 = actual_date($Y, $M, $D, $diaSemana1);
													$f2 = $_POST['Fecha2']; $_f2 = explode('-', $f2); $Y = $_f2[0]; $M = $_f2[1]; $D = $_f2[2]; $diaSemana2 = diaSemana($Y, $M, $D); $fechaTexto2 = actual_date($Y, $M, $D, $diaSemana2);
												
													if ($_POST['Fecha1'] == $_POST['Fecha2']) { echo 'Fecha de Informe : ' . $fechaTexto1; } else { echo 'Rango Fecha Informe : &nbsp;&nbsp;'.$fechaTexto1. ' &nbsp;-&nbsp; ' .$fechaTexto2; } 
												}
											?>
				            			</th>
                                    </thead>
    	                        	<thead>
        	                        	<th width="70px">Idoferta</th>
							        	<th width="70px">IdOpción</th>
							            <th width="380px">Nombre Oferta</th>
							            <th width="200px">Tipo</th>
							            <th width="70px">Contra-rembolso</th>
						    	        <th width="70px">Tarjeta</th>                                    
                                        <th width="70px">Paypal</th>                                    
						        	    <th width="70px">Total</th>  
                                        <th width="70px">Precio</th>  
                                        <th width="120px">Facturación</th>                                   
        	                        </thead>
            	                    <?php
										if (isset($_POST['consultarOrdOferta'])) {
											ordenesPorOferta($web);
										} else {
											echo '								
	                            	    <tbody id="dataCustomerEst">
											<tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
        	                            	<tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
	            	                        <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
    	            	                    <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
        	            	                <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
            	            	            <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
                	            	        <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
                    	            	</tbody>';
										}
									?>
	                            </table>
                             </div>                            
							 <div class="excel">
                                <form id="excel2" name="excel2" method="post" action="ficheroExcel.php">
                                	<input type="hidden" id="values2" name="values2" />
                                    <input type="submit" id="btnExport" value="Exportar Excel" alt="2" />
                                </form>                               
                            </div>		
                        </div>		
                        <!-- fin conten tab -->
                        
                    </div>
                    <!-- FIN ORDENES POR OFERTA -->                    
                   
                    
                    <!--ORDENES POR USUARIO-->
                    <div id="tab4" class="tab_content">    
                    
                    	<div id="busquedaClientes">	
                        	<div class="fieldset">
                            	<h2 class="legend">Rango de Fechas</h2>
	                        	<form id="formPedidosUsser" name="formPedidosUsser" class="loading" method="post" action="orders.php?idbd=<?php echo $_REQUEST['idbd']; ?>" accept-charset="utf-8" enctype="multipart/form-data">
		                        	<table style="width:40%">
    		                        	<thead>
        		                        	<th><span style="font-size:15px; font-variant: small-caps; font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif">Fecha Inicio</span></th>
            		                        <th><span style="font-size:15px; font-variant: small-caps; font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif">Fecha Fin</span></th>
                		                    <th></th>
                    		            </thead>
                        		        <tbody>
                            		    	<tr>
                                		    	<td align="center">
                                    		    	<input type="text" id="Fecha5" name="Fecha1" style="text-align:center; width: 150px" autocomplete="off" />
                	                        	</td>
	                	                        <td align="center">
    	                	                    	<input type="text" id="Fecha6" name="Fecha2" style="text-align: center; width: 150px" autocomplete="off" />
        	                	                </td>                                	            
	            	                        </tr>
                                            <tr>
                                            	<td colspan="2" align="center" height="40px">
                                                	<select id="selectUsuario" name="selectUsuario" class="chosen-select" data-placeholder="Listado de Administradores..." style="width: 350px";>
                                                    	<option value="0"></option>
                                                        <?php
															$SQL = "SELECT * FROM administrador";
															$db->setQuery($SQL);
															$result = $db->loadObjectList();
															foreach($result as $result1) {
																echo '<option value="'.trim($result1->Usuario).'">'.utf8_encode($result1->Nombres).' '.utf8_encode($result1->Apellidos).'</option>';	
															}
															$db->freeResults();
														?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                	                        	 <td id="option" style="height: 0px" colspan="2" align="center"></td>
                    	                    </tr>
                                            <tr>
                                            	<td colspan="2" align="center" height="50px">
                                    	        	<input type="submit" id="consultarUsser" name="consultarUsser" value="Consultar Informe" alt="3" />                                                    
                                        	    </td>
                                            </tr>
    	            	                </tbody>
        	            	        </table>
            	                </form>
                            </div>
                        </div>
                        <!-- Inicio content tab -->
                        <div id="resultSearch">
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Resultados</h3><br />
                            <div id="dvData3">
	                        	<table id="resultSearch" cellpadding="0" cellspacing="0" class="clsTabla">
                                	<thead>
	                                	<th colspan="13" align="center">
											<?php 
												if(isset($_POST['consultarUsser'])) {
													
													$f1 = $_POST['Fecha1']; $_f1 = explode('-', $f1); $Y = $_f1[0]; $M = $_f1[1]; $D = $_f1[2]; $diaSemana1 = diaSemana($Y, $M, $D); $fechaTexto1 = actual_date($Y, $M, $D, $diaSemana1);
													$f2 = $_POST['Fecha2']; $_f2 = explode('-', $f2); $Y = $_f2[0]; $M = $_f2[1]; $D = $_f2[2]; $diaSemana2 = diaSemana($Y, $M, $D); $fechaTexto2 = actual_date($Y, $M, $D, $diaSemana2);
												
													if ($_POST['Fecha1'] == $_POST['Fecha2']) { echo 'Fecha de Informe : ' . $fechaTexto1; } else { echo 'Rango Fecha Informe : &nbsp;&nbsp;'.$fechaTexto1. ' &nbsp;-&nbsp; ' .$fechaTexto2; } 
												}
											?>
				            			</th>
                                    </thead>
    	                        	<thead>
        	                        	<th width="60px">Orden</th>
							        	<th width="150px">Fecha</th>
							            <th width="250px">Nombres</th>
							            <th width="250px">Producto</th>
							            <th width="70px">Imagen</th>
						    	        <th width="50px">Cantidad</th>                                    
						        	    <th width="100px">Talla/Tipo</th>  
                                        <th width="100px">Precio</th>  
                                        <th width="100px">Total</th>  
                                        <th width="50px">Base Imp</th>                                  
                                    	<th width="20px"></th> 
							            <th width="100px">Estado</th>
							            <th width="32px">Pago</th>						                                                                                   
        	                        </thead>
                                    <thead>
                                    	<th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>                                        
                                	</thead>
            	                    <?php
										if (isset($_POST['consultarUsser'])) {
											ordenesFechas($web);											
											DevolucionesFechas($web);
										} else {
											echo '								
	                            	    <tbody id="dataCustomerEst">
											<tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
        	                            	<tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
	            	                        <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
    	            	                    <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
        	            	                <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
            	            	            <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
                	            	        <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
                    	            	</tbody>';
										}
									?>
	                            </table>
                             </div>                            
							 <div class="excel">
                                <form id="excel3" name="excel3" method="post" action="ficheroExcel.php">
                                	<input type="hidden" id="values3" name="values3" />
                                    <input type="submit" id="btnExport" value="Exportar Excel" alt="3" />
                                </form>                               
                            </div>		
                        </div>		
                        <!-- fin conten tab -->          	                        
                    </div>
                    <!-- FIN TAB-->
                </div>
                
            </div>
        </div>
    </div>
    <div id="push"></div>   
</div>

<div id="footer" class="footer"> 
	<?php include('footer.inc.php'); ?>        	
</div>

</html>
<?php } ?>