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
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
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
    		    	<li class="active"><a href="#tab1"><span>Resumen Facturación</span></a></li>
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
                                            	<td colspan="2" align="center">
                                               	<div class="divSelect">
                                                		<select id="agencia" name="agencia">
                                                        	<option value="0">Seleccione Agencia</option>
                                                           <?php
														   	     $SQL = "SELECT * FROM agenciatransportes";
																 $db->setQuery($SQL);
																 $result = $db->loadObjectList();
																 foreach($result as $result1) {
																	 echo '<option value="'.$result1->IdAgencia.'">'.$result1->NombreAgencia.'</option>';
																 }
														     ?>                                                         	
                                                        </select>
                                                   </div> 
                                               </td>
                                            </tr>
                                            <tr>
                                            	<td colspan="2" align="center">
                                               	<input type="radio" name="tipoPago" value="contra" />&nbsp;&nbsp;&rArr;&nbsp;Contra-reembolso&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                	<input type="radio" name="tipoPago" value="tarjeta" />&nbsp;&nbsp;&rArr;&nbsp;Tarjeta-PayPal
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
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Facturación Agencia Transportes <?php echo $nombre; ?></h3><br />
                            
                            <div id="dvData0">
                            	<form id="geneararFact" name="generarFact" method="post" action="generar-fact/index.php?idbd=" . <?php echo base64_decode($_GET['idbd']); ?>>
                                	<fieldset id="group_1">
			                        	<table id="resultSearch_Opt" cellpadding="1" cellspacing="1">
    			                        	<thead>
        			                        	<th bgcolor="#F5F5F5" colspan="10" align="center">
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
        	    		                    	<th bgcolor="#F5F5F5" width="300px">Descripción</th>  
                    		                    <th bgcolor="#F5F5F5" width="150px">Tipo</th>                                  
            	        		                <th bgcolor="#F5F5F5" width="70px">Precio</th>
                	        		            <th bgcolor="#F5F5F5" width="50px">Unidades</th>
                                                <th bgcolor="#F5F5F5" width="100px">SubTotal</th>
                    	        		    	<th bgcolor="#F5F5F5" width="70px">Gastos Envio Unidad</th>
                                               <th bgcolor="#F5F5F5" width="100px">Gastos Envio Total</th>
                                    		    <th bgcolor="#F5F5F5" width="100px">Total</th>
                        	            		<th bgcolor="#F5F5F5" width="70px">Agencia</th>
	                                        	<th bgcolor="#F5F5F5" width="100px">Forma Pago</th>	    	                                   
                	    	        	    </thead>                                
                    	    	        	<?php
											if (isset($_POST['consultar'])) {									
										
												echo '<tbody id="dataCustomer">';									
									
												$fecha1 = $_POST['Fecha1'];
												$fecha2 = $_POST['Fecha2'];		
												$agencia = $_POST['agencia'];
												$tipoPago = $_POST['tipoPago'];												
												
												if($tipoPago == 'contra')
													$tpPago = "('contra-rembolso')";
												else if($tipoPago == 'tarjeta')
													$tpPago = "('tarjeta', 'paypal')";
												
												//NO PUEDO USAR EL TOTAL DE LA COMPRA PORQUE SE DA EL CASO DE QUE UN CLIENTE COMPRO PRODUCTOS QUE SALEN DEN TIPSA Y REDUR CON LO CUAL VARIA EL TOTAL
												$SQL = "SELECT productos.Nombre_Producto, opcionesoferta.Opcion, opcionesoferta.Precio, lineasorden.Talla, SUM(lineasorden.Cantidad) as Unidades, ";
												$SQL .= "SUM(lineasorden.Subtotal) AS STotal, SUM(lineasorden.GastosEnvio) AS GEnvio, agenciatransportes.IdAgencia, agenciatransportes.NombreAgencia, ";
												$SQL .= "ordenes.FormaPago ";
												$SQL .= "FROM ordenes ";
												$SQL .= "INNER JOIN lineasorden ON lineasorden.IdOrden = ordenes.IdOrden ";
												$SQL .= "INNER JOIN productos ON productos.IdOferta = lineasorden.IdProducto ";
												$SQL .= "INNER JOIN opcionesoferta ON opcionesoferta.Id = lineasorden.Talla ";
												$SQL .= "INNER JOIN agenciatransportes ON agenciatransportes.IdAgencia = opcionesoferta.IdAgencia ";
												$SQL .= "WHERE ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND TRIM(lineasorden.EstadoPedido) <> 'Anulado' ";
												$SQL .= "AND TRIM(ordenes.EstadoPago) = 'ok' AND opcionesoferta.IdAgencia = '$agencia' AND ordenes.FormaPago in $tpPago ";
												$SQL .= "GROUP BY opcionesoferta.Opcion";																								
													
												$db->setQuery($SQL);
												$row = $db->execute();	
													
												$productos = 0;
												if (mysqli_num_rows($row) > 0) {
													$result = $db->loadObjectList();														
														foreach($result as $result1) {	
														
															$TOTALPRODUCTOS = $TOTALPRODUCTOS + $result1->Unidades;
															$SUBTOTAL = $SUBTOTAL + $result1->STotal;
															$GENVIO = $GENVIO + $result1->GEnvio;
															$TOTAL = $TOTAL + ($result1->STotal + $result1->GEnvio);
															
															echo '
																<tr height="20px">																
																	<td><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
																	<td><label>'.utf8_encode($result1->Opcion).'</label></td>
																	<td><label>'.number_format($result1->Precio,2,',','.').'</label></td>
																	<td><label>'.$result1->Unidades.'</label></td>																
																	<td style="color:#FFFFFF" bgcolor="#9ACD32"><label style="color: #FFFFFF; font-family: tahoma">'.number_format(($result1->Precio * $result1->Unidades),2,',','.').'</label></td>
																	<td><label>'.number_format(($result1->GEnvio/$result1->Unidades),2,',','.').'</label></td>
																	<td bgcolor="#9ACD32"><label style="color: #FFFFFF; font-family: tahoma">'.number_format(($result1->GEnvio),2,',','.').'</label></td>
																	<td bgcolor="#71BA00"><label style="color: #FFFFFF; font-family: tahoma">'.number_format(($result1->STotal + $result1->GEnvio),2,',','.').'</label></td>
																	<td><label>'.$result1->NombreAgencia.'</label></td>
																	<td><label>'.$result1->FormaPago.'</label></td>
																</tr>
															';																	
														}
																																						
												} 											
												
												
												$graph[0] = array('data0'=>'Facturación Productos', 'data1'=>round($factProductos,2), 'data2'=>round($ivaProductos,2), 'data3'=>round($factProdSinIva,2));
												$graph[1] = array('data0'=>'Facturación Portes', 'data1'=>round($portesProd,2), 'data2'=>round($ivaPortes,2), 'data3'=>round($portesSinIva,2));
										
												echo '
													</tbody>
													<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
    	    		        		                		<tr height="30px" style="color: white; font-size: 14px; font-variant:small-caps;">
															<td bgcolor="#1F88A7" colspan="2" align="center"><span>TOTALES</span></td>            	                            
															<td bgcolor="#1F88A7" colspan="1" align="center"><span></span></td>  
															<td bgcolor="#1F88A7"><span>'.$TOTALPRODUCTOS.'</span></td>        	    		        		                	          	                            
                    			    		                <td bgcolor="#1F88A7"><span>'.number_format($SUBTOTAL,2,',','.').'</span></td>                        	                
                        		    			            <td bgcolor="#1F88A7"></td>
                            		    			        <td bgcolor="#1F88A7"><span>'.number_format($GENVIO,2,',','.').'</span></td>
															<td bgcolor="#1F88A7"><span>'.number_format($TOTAL,2,',','.').'</span></td>
															<td bgcolor="#1F88A7"><span></span></td>
															<td bgcolor="#1F88A7"></td>																													
		                                	    		</tr>
				                                	</tfoot>											
												';
												$db->freeResults();
											} else {
												echo '
												<tbody id="dataCustomer">
													<tr><td colspan="14" height="24px"></td></tr><tr><td colspan="14" height="24px"></td></tr>
	                		        		        <tr><td colspan="14" height="24px"></td></tr><tr><td colspan="14" height="24px"></td></tr>
    	                		        	        <tr><td colspan="14" height="24px"></td></tr><tr><td colspan="14" height="24px"></td></tr>
        	                		       		    <tr><td colspan="14" height="24px"></td></tr><tr><td colspan="14" height="24px"></td></tr>
            	                		       		<tr><td colspan="14" height="24px"></td></tr><tr><td colspan="14" height="24px"></td></tr>                	                    
		                               			</tbody>
												';	
											}
											?>                               
	        		                    </table>  
                                        <hr />
                                        <h3>DEVOLUCIONES Y NO ENTREGADOS</h3>
                                        <table>                                        	
                                        	<thead>            		                        	
        	    		                    	<th bgcolor="#F5F5F5" width="300px">Descripción</th>  
                    		                    <th bgcolor="#F5F5F5" width="150px">Tipo</th>                                  
            	        		                <th bgcolor="#F5F5F5" width="70px">Precio</th>
                	        		            <th bgcolor="#F5F5F5" width="50px">Unidades</th>
                                                <th bgcolor="#F5F5F5" width="100px">SubTotal</th>
                    	        		    	<th bgcolor="#F5F5F5" width="70px">Gastos Envio Unidad</th>
                                               <th bgcolor="#F5F5F5" width="100px">Gastos Envio Total</th>
                                    		    <th bgcolor="#F5F5F5" width="100px">Total</th>
                        	            		<th bgcolor="#F5F5F5" width="70px">Agencia</th>
	                                        	<th bgcolor="#F5F5F5" width="100px">Forma Pago</th>	    	                                   
                	    	        	    </thead> 
                                        	<?php
											if (isset($_POST['consultar'])) {
												echo '<tbody id="dataCustomer">';
												
                                        		//AQUI VAMOS AÑADIR LAS DEVOLUCONES
												$SQL = "SELECT productos.Nombre_Producto, opcionesoferta.Opcion, opcionesoferta.Precio, lineasorden.Talla, SUM(lineasorden.Cantidad) as Unidades, ";
												$SQL .= "SUM(lineasorden.Subtotal) AS STotal, SUM(lineasorden.GastosEnvio) AS GEnvio, agenciatransportes.IdAgencia, agenciatransportes.NombreAgencia, ";
												$SQL .= "ordenes.FormaPago ";
												$SQL .= "FROM ordenes ";
												$SQL .= "INNER JOIN lineasorden ON lineasorden.IdOrden = ordenes.IdOrden ";
												$SQL .= "INNER JOIN productos ON productos.IdOferta = lineasorden.IdProducto ";
												$SQL .= "INNER JOIN opcionesoferta ON opcionesoferta.Id = lineasorden.Talla ";
												$SQL .= "INNER JOIN agenciatransportes ON agenciatransportes.IdAgencia = opcionesoferta.IdAgencia ";
												$SQL .= "WHERE lineasorden.FechaCambioEstado >= '$fecha1' AND lineasorden.FechaCambioEstado <= '$fecha2' AND TRIM(lineasorden.EstadoPedido) <> 'Anulado' ";
												$SQL .= "AND TRIM(ordenes.EstadoPago) = 'ok' AND opcionesoferta.IdAgencia = '$agencia' AND ordenes.FormaPago in $tpPago ";
												$SQL .= "GROUP BY opcionesoferta.Opcion";																								
													
												$db->setQuery($SQL);
												$row = $db->execute();	
													
												$productos = 0;
												if (mysqli_num_rows($row) > 0) {	
													$result = $db->loadObjectList();													
														foreach($result as $result1) {	
														
															$TOTALPRODUCTOS_DEV = $TOTALPRODUCTOS_DEV + $result1->Unidades;
															$SUBTOTAL_DEV = $SUBTOTAL_DEV + $result1->STotal;
															$GENVIO_DEV = $GENVIO_DEV + $result1->GEnvio;
															$TOTAL_DEV = $TOTAL_DEV + ($result1->STotal + $result1->GEnvio);
															
															echo '
																<tr height="20px">																
																	<td><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
																	<td><label>'.utf8_encode($result1->Opcion).'</label></td>
																	<td><label>'.number_format($result1->Precio,2,',','.').'</label></td>
																	<td><label>'.$result1->Unidades.'</label></td>																
																	<td style="color:#FFFFFF" bgcolor="#9ACD32"><label style="color: #FFFFFF; font-family: tahoma">'.number_format(($result1->Precio * $result1->Unidades),2,',','.').'</label></td>
																	<td><label>'.number_format(($result1->GEnvio/$result1->Unidades),2,',','.').'</label></td>
																	<td bgcolor="#9ACD32"><label style="color: #FFFFFF; font-family: tahoma">'.number_format(($result1->GEnvio),2,',','.').'</label></td>
																	<td bgcolor="#71BA00"><label style="color: #FFFFFF; font-family: tahoma">'.number_format(($result1->STotal + $result1->GEnvio),2,',','.').'</label></td>
																	<td><label>'.$result1->NombreAgencia.'</label></td>
																	<td><label>'.$result1->FormaPago.'</label></td>
																</tr>
															';																	
														}
																																						
												}
											}
												
												
												$graph[0] = array('data0'=>'Facturación Productos', 'data1'=>round($factProductos,2), 'data2'=>round($ivaProductos,2), 'data3'=>round($factProdSinIva,2));
												$graph[1] = array('data0'=>'Facturación Portes', 'data1'=>round($portesProd,2), 'data2'=>round($ivaPortes,2), 'data3'=>round($portesSinIva,2));
										
												echo '
													</tbody>
													<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
    	    		        		                		<tr height="30px" style="color: white; font-size: 14px; font-variant:small-caps;">
															<td bgcolor="#1F88A7" colspan="2" align="center"><span>TOTALES</span></td>            	                            
															<td bgcolor="#1F88A7" colspan="1" align="center"><span></span></td>  
															<td bgcolor="#1F88A7"><span>'.$TOTALPRODUCTOS_DEV.'</span></td>        	    		        		                	          	                            
                    			    		                <td bgcolor="#1F88A7"><span>'.number_format($SUBTOTAL_DEV,2,',','.').'</span></td>                        	                
                        		    			            <td bgcolor="#1F88A7"></td>
                            		    			        <td bgcolor="#1F88A7"><span>'.number_format($GENVIO_DEV,2,',','.').'</span></td>
															<td bgcolor="#1F88A7"><span>'.number_format($TOTAL_DEV,2,',','.').'</span></td>
															<td bgcolor="#1F88A7"><span></span></td>
															<td bgcolor="#1F88A7"></td>																													
		                                	    		</tr>
				                                	</tfoot>												
												';
												$db->freeResults();
												//HASTA AQUI LAS DEVOLUCIONES
										 	  ?>
                                        </table>
                                        
                                        <!--
                                        <hr />      
                                        <table>   
                                        	<thead>
                                            	<tr>
                                                	<th bgcolor="#F5F5F5">Bases Imponibles</th>
                                                    <th bgcolor="#F5F5F5">Importes</th>
                                                </tr>
                                            </thead>                                     												
											<tbody>
												<tr height="25px">
                                                	<td><label>Base Imponible 10%</label></td>
                                                	<td><label><?php echo number_format($baseImp10,2,',','.'); ?></label></td>
                                                </tr>
                                                <tr height="25px">
                                                	<td><label>Base Imponible 21%</label></td>
                                                    <td><label><?php echo number_format($baseImp21,2,',','.'); ?></label></td>
                                                </tr>
                                                <tr height="25px">
                                                	<td><label>Base Imponible Portes</label></td>
                                                    <td><label><?php echo number_format($portesSinIva,2,',','.'); ?></label></td>
                                                </tr>
                                            </tbody>
											<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
                                            	<tr height="25px">
                                                	<td bgcolor="#1F88A7"><span>Total Bases</span></td>
                                                	<td bgcolor="#1F88A7"><span><?php echo number_format(($baseImp10+$baseImp21+$portesSinIva),2,',','.'); ?> &euro;</span></td>
                                                </tr>
                                            </tfoot>											
                                        </table>        		                
                                        -->
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
										['Importes', 'TOTAL', 'SIN IVA', 'IVA'],											
											<?php for($i=0; $i<=1; $i++) {
											echo "['".$graph[$i]['data0']."', ".$graph[$i]['data1'].",".$graph[$i]['data3'].", ".$graph[$i]['data2']."],";
										 } ?>									
							        ]);
	
							        var options = {
							          title: 'Rendimiento De Ventas'
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