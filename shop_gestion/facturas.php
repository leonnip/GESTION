<?php
session_start();
if ((!isset($_SESSION['Logged'])) && (!isset($_SESSION['UserIdAdmin']))) {
	header('Location: https://'.$_SERVER['HTTP_HOST']);
} else {
	include("config.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="css/stylos-pag.css" type="text/css" rel="stylesheet" />
        <link href="css/select-styles.css" type="text/css" rel="stylesheet" />
        <link href="css/icons.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
		<title><?php echo $nombre; ?></title>
        <script type="text/javascript" src="js/jquery.tools.min.js"></script>
        <!--<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>-->
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.jcookie.min.js"></script>
         <script type="text/javascript" src="js/jquery.validate.js"></script>
        <script type="text/javascript" src="js/jquery-class.js"></script>   
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
	</head>
<body>

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
			$active3 = 1;
			include('config.inc.php');
			include('menu.inc.php'); 
			
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
			
			//SERIALIZAMOS EL ARRAY
			function array_envia($array) { 
	    		$tmp = serialize($array); 
    			$tmp = urlencode($tmp); 
	    		return $tmp; 
			} 
			//FIN SERIALIZACION
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
    		    	<li class="active"><a href="#tab1"><span>Generar Facturas</span></a></li>
		   			<!--<li><a href="#tab2">Cuadro Completo</a></li>
   		  	        <li><a href="#tab3">Cuadro Completo</a></li>-->
		  	    </ul>
        		
                <div class="tab_container">                	                    
                    <!-- INFORME DE PEDIDOS DESDE FECHA INCIO A FECHA FIN -->
                    <div id="tab1" class="tab_content">
                    	<div id="busquedaClientes">	
                        	<div class="fieldset">
                            	<h2 class="legend">Rango de B&uacute;squeda</h2>
                                <span class="tooltip_links">&nbsp;</span>                                
	                        	<form id="formInforme" name="formInforme" method="post" class="loading" action="<?php echo $_SERVER['../elpais_seleccion/PHP_SELF']; ?>" accept-charset="utf-8" enctype="multipart/form-data">
		                        	<table style="width:40%">
    		                        	<thead>
        		                        	<th><label class="cab">Fecha Inicio</label></th>
            		                        <th><label class="cab">Fecha Fin</label></th>
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
                                            	<td colspan="2" align="center" height="50px">
                                    	        	<input type="submit" id="consultar" name="consultar" value="Consultar Informe" alt="0" />                                                    
                                        	    </td>
                                            </tr>
    	            	                </tbody>
        	            	        </table>
            	                </form>
                            </div>
                        </div>
                        <div id="resultSearch">
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Facturas <?php echo $nombre; ?></h3><br />
                            
                            <div id="dvData0">
                            	<form id="geneararFact" name="generarFact" method="post" action="generar-fact/index.php">
                                	<fieldset id="group_1">
			                        	<table id="resultSearch_Opt" cellpadding="1" cellspacing="1">
    			                        	<thead>
        			                        	<th colspan="13" align="center">
            			                        	<?php 
														if (isset($_POST['consultar'])) {
															
															$f1 = $_POST['Fecha1']; $_f1 = explode('-', $f1); $Y = $_f1[0]; $M = $_f1[1]; $D = $_f1[2]; $diaSemana1 = diaSemana($Y, $M, $D); $fechaTexto1 = actual_date($Y, $M, $D, $diaSemana1);
															$f2 = $_POST['Fecha2']; $_f2 = explode('-', $f2); $Y = $_f2[0]; $M = $_f2[1]; $D = $_f2[2]; $diaSemana2 = diaSemana($Y, $M, $D); $fechaTexto2 = actual_date($Y, $M, $D, $diaSemana2);
														
															if ($_POST['Fecha1'] == $_POST['Fecha2']) { echo 'Fecha de Informe : ' . $fechaTexto1; } else { echo 'Rango Fecha Informe : &nbsp;&nbsp;'.$fechaTexto1. ' &nbsp;-&nbsp; ' .$fechaTexto2; }
														}
													?>
		                                	    </th>
			                                </thead>
    			                        	<thead>
            		                        	<th width="40px"></th>
        	    		                    	<th width="80px">IdOrden</th>  
                    		                    <th width="120px">Dni</th>                                  
            	        		                <th width="100px">FechaOrden</th>
                	        		            <th width="300px">Nombres</th>
                    	        		    	<th width="70px">Productos</th>
                                    		    <th width="50px"></th>
                        	            		<th width="100px">Subtotal</th>
	                                        	<th width="100px">Gastos Envío</th>
	    	                                    <th width="100px">Importe Fact.</th>
    	    	                                <th width="80px">Validado</th>
        	    	                            <th width="90px">Estado</th>
            	    	                        <th width="50px"></th>
                	    	        	    </thead>                                
                    	    	        	<?php
											if (isset($_POST['consultar'])) {									
										
												echo '<tbody id="dataCustomer">';									
									
												$fecha1 = $_POST['Fecha1'];
												$fecha2 = $_POST['Fecha2'];
									
												/*$fechaInicio=strtotime($fecha1);
												$fechaFin=strtotime($fecha2);				
									
												for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){	
													$fechaff = date("Y-m-d", $i);
											
													$fechass = explode('-', $fechaff);
													$Y = $fechass[0];
													$M = $fechass[1];
													$D = $fechass[2];
												
													$diaSemana = diaSemana($Y, $M, $D);										
													$fechaTexto = actual_date($Y, $M, $D, $diaSemana);
													*/
																			
													$SQL = "SELECT usuarios.Id, usuarios.Nombres, usuarios.Dni, usuarios.Apellidos, usuarios.FechaRegistro, direcciones.TipoVia, direcciones.Direccion, direcciones.TipoNumero, direcciones.Numero, direcciones.Piso, direcciones.Puerta, direcciones.Cp, direcciones.Poblacion, direcciones.Telefono, ordenes.IdOrden, ordenes.FechaOrden, productos.Nombre, productos.Nombre_Producto, productos.Images1, lineasorden.Id as linOrd, lineasorden.EstadoPedido, lineasorden.Talla, opcionesoferta.Opcion, SUM(lineasorden.Cantidad) as Unidades, SUM(lineasorden.Subtotal) as SubTotal, SUM(lineasorden.GastosEnvio) AS GastosEnvio, ordenes.Total, ordenes.Tramitado ";
													$SQL .= "FROM usuarios ";
													$SQL .= "INNER JOIN relordendireccion ON usuarios.Id = relordendireccion.IdCliente ";
													$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion ";
													$SQL .= "INNER JOIN ordenes ON relordendireccion.IdOrden = ordenes.IdOrden ";
													$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
													$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
													$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
													$SQL .= "WHERE TRIM(ordenes.EstadoPago) = 'ok' AND TRIM(lineasorden.EstadoPedido) <> 'Anulado' ";
													$SQL .= "AND ordenes.fechaOrden >= '$fecha1' AND ordenes.fechaOrden <= '$fecha2' GROUP BY ordenes.IdOrden ORDER BY ordenes.IdOrden";
										
													$db->setQuery($SQL);
													$result = $db->loadObjectList();
																		
													foreach($result as $result1) {
														echo '
														<tr height="24px">
															<td height="24px""><label><a class="tTip" id="LinkVer" href="#" data-id="'.$result1->IdOrden.'" title="Ver Pedido"><label class="ver"></label></a></label></td>	
															<td><label>'.$result1->IdOrden.'</label></td>	
															<td><label>'.$result1->Dni.'</label></td>
															<td><label>'.$result1->FechaOrden.'</label></td>	
															<td><label>'.utf8_encode(ucwords(strtolower(utf8_encode($result1->Nombres)))) .' '.utf8_encode(ucwords(strtolower($result1->Apellidos))).'</label></td>	
															<td><label>'.$result1->Unidades.'</label></td>												
															<td>';
																if (trim($result1->Tramitado) == 'usuario-web') { echo '<img src="images/usuario-web.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else if (trim($result1->Tramitado) == 'call-center') { echo '<img src="images/call-center.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else { echo '<img src="images/usuario.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
															echo '
															</td>
															<td>'.$result1->SubTotal.'</td>
															<td>'.$result1->GastosEnvio.'</td>
															<td><label>'.number_format($result1->Total,2,',','.').' &euro;</label></td>
															<td>';													
																//VERIFICAMOS QUE LOS IMPORTES DE LA TRANSACCION SEAN CORRECTOS EN LINEASORDEN Y EN ORDEN
																$valid1 = $result1->Precio * $result1->Cantidad;
																$valid2 = $result1->Subtotal;											
																if ($valid1 == $valid2)
																	$imagenValid = 'icon_ok.png';
																else
																	$imagenValid = 'icon_error.png';															
															echo '
																<img src="images/'.$imagenValid.'" />													
															</td>
															<td>';
																if ($result1->EstadoPedido == 'Transito') echo '<label style="color: red">Transito</label>';
																if ($result1->EstadoPedido == 'Enviado') echo '<label style="color: #0877BF">Enviado</label>';
																if ($result1->EstadoPedido == 'Anulado') echo '<label style="color: #000">Anulado</label>';
																if ($result1->EstadoPedido == 'Entregado') echo '<label style="color: green">Entregado</label>';
																if ($result1->EstadoPedido == 'No-entregado') echo '<label style="color: #09F">No Entregado</label>';
																echo '
															</td>
															<td><input type="checkbox" id="ordenes" name="orden[]" value="'.$result1->IdOrden.'" /> </td>																										
														</tr>
														';
														$unidades = $unidades + $result1->Unidades;
														$facturacion = $facturacion + $result1->Total;
														$gastos = $gastos + $result1->GastosEnvio;
														$subTotal = $subTotal + $result1->SubTotal;														
													}												
											
													$graph = array("Facturacion"=>"Facturación", "Total"=>round($facturacion,2), "SubTotal"=>round($subTotal,2), "Gastos"=>round($gastos,2));								
												//}
												//$totalGraph = count($graph);
										
												echo '
													</tbody>
													<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
    	    		        		                	<tr height="30px" style="color: white; font-size: 14px; font-variant:small-caps;">
															<td bgcolor="#1F88A7" colspan="4" align="center"><span>TOTALES</span></td>            	                            
        	    		        		                	<td bgcolor="#1F88A7" colspan="1" align="center"><span>TOTALES</span></td>            	                            
                    			    		                <td bgcolor="#1F88A7"><span>'.$unidades.'</span></td>                        	                
                        		    			            <td bgcolor="#1F88A7"></td>
                            		    			        <td bgcolor="#1F88A7"><span>'.number_format($subTotal,2,',','.').'</span></td>
															<td bgcolor="#1F88A7"><span>'.number_format($gastos,2,',','.').'</span></td>
															<td bgcolor="#1F88A7"><span>'.number_format($facturacion,2,',','.').'</span></td>
															<td colspan="3" bgcolor="#1F88A7"><span></span></td>
		                                	    		</tr>
				                                	</tfoot>	
												';
										
											} else {
												echo '
												<tbody id="dataCustomer">
													<tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
	                		        		        <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
    	                		        	        <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
        	                		       		    <tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>
            	                		       		<tr><td colspan="13" height="24px"></td></tr><tr><td colspan="13" height="24px"></td></tr>                	                    
		                               			</tbody>
												';	
											}
											?>                               
	        		                    </table>
                		                <table style="width: 360px; margin-top: 20px; margin-bottom: 20px">
                    		            	<tr>
                        		        		<td align="center"><a rel="group_1" href="#select_all" class="linkBoton"><img src="images/tick2.png" style="float: left; padding: 4px 0px 2px 7px" />Seleccionar Todos</a></td>
												<td align="center"><a rel="group_1" href="#select_none" class="linkBoton"><img src="images/tickno.png" style="float: left; padding: 4px 0px 2px 7px" />Deseleccionar Todos</a></td>
												<td align="center"><a rel="group_1" href="#invert_selection" class="linkBoton"><img src="images/invert.png" style="float: left; padding: 4px 0px 2px 7px" />Invertir Selección</a></td>	
    	                            		</tr>
		                                </table>
                                    </fieldset>
                                    <fieldset>
                                    	<table>
                                        	<tr>
                                            	<td align="center"><input type="submit" value="GENERAR FACTURAS" id="genFact" name="genFact" style="width:200px" formtarget="_new" /></td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </form>
    	                    </div>
                            
                            <div class="excel">
                            	<form id="excel0" name="excel0" method="post" action="ficheroExcel.php">
                                	<input type="hidden" id="values0" name="values0" />
                                    <input type="submit" id="btnExport" value="Exportar Excel" alt="0" />
                                </form>                               
                            </div>
                            
                            
                            
                            <!-- GRAFICA DE PORCENTAJES -->
                            <?php if(isset($_POST['consultar'])) { ?>
	                            <script type="text/javascript">
							    	google.load("visualization", "1", {packages:["corechart"]});
							        google.setOnLoadCallback(drawChart);
							        function drawChart() {
								        var data = google.visualization.arrayToDataTable([
										['Fecha', 'Facturación', 'SubTotal', 'Envío'],											
										<?php /*for($i=0; $i<=$totalGraph-1; $i++) {*/
											echo "['".$graph['Facturacion']."',".$graph['Total'].",".$graph['SubTotal'].", ".$graph['Gastos']."],";
										 //} ?>
							        ]);
	
							        var options = {
							          title: 'Rendimiento Ordenes Productos'
							        };
	
								    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
								        chart.draw(data, options);
								    }
								</script>
                    	        <div id="chart_div" style="width: 900px; height: 300px; margin: 0 auto; margin-bottom: 20px"></div>
                       	    <?php } ?>
                       		<!-- FIN DE GRAFICA PORCENTAJES --> 
                            
                        </div>
                    </div>                    
                    
                    <!--MOSTRAMOS EL CUADRO COMPLETO -->
                    <div id="tab2" class="tab_content">                    	                        
                    	                 
                    </div>                    
                </div>
                
                <!-- CAMBIAR ESTADO DE LOS PEDIDOS-->
                	<div id="tab3" class="tab_content">                 	
                        						                       
                    </div>
                <!-- FIN TAB -->
                
            </div>
        </div>
    </div>
    <div id="push"></div>   
</div>

<div id="footer" class="footer"> 
	<?php include('footer.inc.php'); ?>        	
</div>

</body>
</html>
<?php } ?>