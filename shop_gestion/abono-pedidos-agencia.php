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
			$active4 = 1;
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
		?>
    </div> 
    
    <!-- GADGET MENU LATERAL -->
    <?php require("gadget.inc.php"); ?>
    <!-- FIN GADGET -->
    
    <div id="content-body">
    	<div id="general" class="content">
        	<div id="Contenido" style="padding-top: 20px; overflow: auto; overflow-x:hidden"> 
            	<div style="position: absolute; top: 0px; left: 990px; z-index:9999999"><img src="images/pedidos.png" /></div>  
            	<ul class="tabs">
    		    	<li class="active"><a href="#tab1"><span>Abono Pedidos Agencia</span></a></li>
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
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Abono de Pedidos por Agencia <?php echo $nombre; ?></h3><br />
                            
                            <div id="dvData0">
	                        	<table id="resultSearch_Opt" cellpadding="1" cellspacing="1">
    	                        	<thead>
        	                        	<th colspan="7" align="center">
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
        	                        	<th width="100px">IdOrden</th>                                    
                                        <th width="100px">Fecha</th>                                    
                                        <th width="250px">Nombres</th>                                    
            	                        <th width="250px">Producto</th>
                	                    <th width="100px">Cantidad</th>
                    	            	<th width="100px">Total</th>
                        	            <th width="100px">Estado</th>
                            	    </thead>                                
                                	<?php
									if (isset($_POST['consultar'])) {									
									
										echo '<tbody id="dataCustomer">';									
									
										$fecha1 = $_POST['Fecha1'];
										$fecha2 = $_POST['Fecha2'];
									
										$fechaInicio=strtotime($fecha1);
										$fechaFin=strtotime($fecha2);				
										/*
										for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){	
											$fechaff = date("Y-m-d", $i);
										
											$fechass = explode('-', $fechaff);
											$Y = $fechass[0];
											$M = $fechass[1];
											$D = $fechass[2];
											
											$diaSemana = diaSemana($Y, $M, $D);										
											$fechaTexto = actual_date($Y, $M, $D, $diaSemana);
										*/									
											$SQL = "SELECT ordenes.IdOrden, ordenes.FechaOrden, lineasorden.Subtotal, lineasorden.GastosEnvio, lower(usuarios.Nombres), lower(usuarios.Apellidos), 
													lineasorden.Cantidad, lineasorden.AgenciaPago AS Pago, productos.Nombre_Producto, opcionesoferta.Opcion, opcionesoferta.OptActiva
													FROM ordenes
													INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden
													INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta
													INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id
													INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id
													WHERE ordenes.EstadoOrden <> TRIM('Anulado') AND ordenes.FechaOrden BETWEEN '$fecha1' AND '$fecha2'
													HAVING Pago > 0";
										
											$db->setQuery($SQL);
											$result = $db->loadObjectList();
											
											/*							
											$var = 1;
											foreach($result as $result1) {
												if ($var == 1) { 
													if (empty($result1->totalProductos))
														$totalProductos = 0;
													else
														$totalProductos = $result1->totalProductos;
												} else if ($var == 2) {
													$totalOrdenes = $result1->totalProductos;
													$var = 0;
												}
												$var = $var + 1;
											}
											
											$fechaP = explode('-', $fechaff);
											$dia = $fechaP[2];
											$mes = $fechaP[1];
									
											if ($mes == 1) { $meses = 'Enero'; } else if($mes == 2) { $meses = 'Febrero'; } else if($mes == 3) { $meses = 'Marzo'; } else if($mes == 4) { $meses = 'Abril'; }
											else if($mes == 5) { $meses = 'Mayo'; } else if($mes == 6) { $meses = 'Junio'; } else if($mes == 7) { $meses = 'Julio'; } else if($mes == 8) { $meses = 'Agosto'; }
											else if($mes == 9) { $meses = 'Septiembre'; } else if($mes == 10) { $meses = 'Octubre'; } else if($mes == 11) { $meses = 'Noviembre'; } else if($mes == 12) { $meses = 'Diciembre'; }
											*/
																	
											foreach($result1 as $result) {
												echo '
													<tr height="24px">
														<td><label>'.$result1->IdOrden.'</label></td>												
														<td><label>'.$result1->FechaOrden.'</label></td>
														<td><label>'.$result1->Nombres.' ' .$result1->Apellidos. '</label></td>
														<td><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
														<td><label>'.number_format(($precioCallcenter * $totalOrdenes),2,',','.').'</label></td>																										
													</tr>
													';	
												$totalPP = $totalPP + $totalProductos;
												$totalOO = $totalOO + $totalOrdenes;
												$totalFacturacion = $totalOO * $precioCallcenter;	
											
												$ff = explode(' ', $fechaTexto);
												$fff = $ff[0]. ' '.$ff[1];
											
												$graph[] = array("fecha"=>$fff, "productos"=>$totalProductos, "ordenes"=>$totalOrdenes);								
											}
										$totalGraph = count($graph);
										
										echo '
											</tbody>
											<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
    	    	        	                	<tr height="30px" style="color: white; font-size: 14px; font-variant:small-caps;">
        	    	        	                	<td bgcolor="#1F88A7" colspan="1" align="center"><span>TOTALES</span></td>            	                            
                    		    	                <td bgcolor="#1F88A7"><span>'.$totalPP.'</span></td>                        	                
                        	    		            <td bgcolor="#1F88A7"><span>'.$totalOO.'</span></td>
                            	    		        <td bgcolor="#1F88A7"><span></span></td>
													<td bgcolor="#1F88A7"><span>'.number_format($totalFacturacion,2,',','.').'</span></td>
	                                	    	</tr>
		                                	</tfoot>	
										';
									
									} else {
										echo '
										<tbody id="dataCustomer">
											<tr><td colspan="7" height="24px"></td></tr><tr><td colspan="7" height="24px"></td></tr>
	                	        	        <tr><td colspan="7" height="24px"></td></tr><tr><td colspan="7" height="24px"></td></tr>
    	                	                <tr><td colspan="7" height="24px"></td></tr><tr><td colspan="7" height="24px"></td></tr>
        	                	       	    <tr><td colspan="7" height="24px"></td></tr><tr><td colspan="7" height="24px"></td></tr>
            	                	       	<tr><td colspan="7" height="24px"></td></tr><tr><td colspan="7" height="24px"></td></tr>                	                    
	                               		</tbody>
										';	
									}
									?>                               
	                            </table>
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
										['Fecha', 'Ordenes', 'Productos'],
											['Inicio',  0,      0],
										<?php for($i=0; $i<=$totalGraph-1; $i++) {
											echo "['".$graph[$i]['fecha']."',".$graph[$i]['ordenes'].",".$graph[$i]['productos']."],";
										 } ?>
							        ]);
	
							        var options = {
							          title: 'Rendimiento Ordenes Productos'
							        };
	
								    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
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