<?php
session_start();
if ((!isset($_SESSION['Logged'])) && (!isset($_SESSION['UserIdAdmin']))) {
	header('Location: https://'.$_SERVER['HTTP_HOST']);
} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="css/stylos-pag.css" type="text/css" rel="stylesheet" />
        <link href="css/select-styles.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
		<title>ADMIN EL PAIS SELECCION</title>
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
    		    	<li class="active"><a href="#tab1"><span>Informe Forma Pago</span></a></li>
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
                                            	<td colspan="2">
                                                	<table style="width:100%; margin-top:10px">
                                                    	<tr>
                                                        	<td><input type="radio" name="tipopago" id="visa" value="tarjeta" required="required" />&nbsp;&nbsp;&nbsp;<img src="images/visap.png" /></td>
                                                            <td><input type="radio" name="tipopago" id="paypal" value="paypal"  required="required"/>&nbsp;&nbsp;&nbsp;<img src="images/paypalp.png" /></td>
                                                            <td><input type="radio" name="tipopago" id="visa" value="contra-rembolso" required="required" />&nbsp;&nbsp;&nbsp;<img src="images/contrap.png" /></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                            	<td colspan="2" align="center" height="50px">
                                    	        	<input type="submit" id="consultar" name="consultar" value="Consultar Informe" />                                                    
                                        	    </td>
                                            </tr>
    	            	                </tbody>
        	            	        </table>
            	                </form>
                            </div>
                        </div>
                        <div id="resultSearch">
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Informe Forma Pago <?php echo $nombre; ?></h3><br />
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
        	                        	<th width="100px">Fecha Orden</th>
            	                        <th width="300px">Nombre Cliente</th>
                	                    <th width="80px">Nª Orden</th>
                    	            	<th width="100px">Total Productos</th>
                        	            <th width="100px">Total Orden</th>
                            	        <th width="100px">Forma Pago</th>                                    
                                	    <th width="130px">C&oacute;digo Autorizaci&oacute;n</th>                                   
	                                </thead>                                
    	                            <?php
									if (isset($_POST['consultar'])) {
	
										echo '<tbody id="dataCustomer">';									
										
										$fecha1 = $_POST['Fecha1'];
										$fecha2 = $_POST['Fecha2'];
										$tipopago = $_POST['tipopago'];
									
										$SQL = "SELECT ordenes.FechaOrden, usuarios.Nombres, usuarios.Apellidos, ordenes.IdOrden, SUM(lineasorden.Cantidad) as totalProductos, ordenes.Total, ordenes.FormaPago, ordenes.Code_Authorisation FROM usuarios ";
										$SQL .= "INNER JOIN ordenes ON usuarios.Id = ordenes.IdCliente ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "WHERE ordenes.FormaPago = RTRIM('$tipopago') AND ordenes.FechaOrden >='$fecha1' AND ordenes.FechaOrden <= '$fecha2' GROUP BY ordenes.IdOrden";
										$db->setQuery($SQL);
										$result = $db->loadObjectList();
										foreach($result as $result1) {
											echo '
												<tr height="24px">
													<td><label>'.$result1->FechaOrden.'</label></td>
													<td><label>'.utf8_encode(ucwords(strtolower($result1->Nombres))).' '.utf8_encode(ucwords(strtolower($result1->Apellidos))).'</label></td>
													<td><label>'.utf8_encode($result1->IdOrden).'</label></td>
													<td><label>'.$result1->totalProductos.'</label></td>
													<td><label>'.$result1->Total.'</label></td>
													<td><label>'.$result1->FormaPago.'</label></td>
													<td><label>'.$result1->Code_Authorisation.'</label></td>												
												</tr>
											'; 
											$ordenes++;
											$ttProductos = $ttProductos + $result1->totalProductos;
											$ttFacturacion = $ttFacturacion + $result1->Total;
										}
										echo '
										</tbody>
										<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
    		                            	<tr height="30px" style="color: white; font-size: 14px; font-variant:small-caps;">
        		                            	<td bgcolor="#1F88A7" colspan="2" align="center"><span>TOTALES</span></td>
            		                            <td bgcolor="#1F88A7"><span>'.$ordenes.'</span></td>
                		                        <td bgcolor="#1F88A7"><span>'.$ttProductos.'</span></td>
                    		                    <td bgcolor="#1F88A7"><span>'.number_format($ttFacturacion,2,',','.').'</span></td>                        	                
                        	    	            <td bgcolor="#1F88A7"></td>
                            	    	        <td bgcolor="#1F88A7"></td>
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
	                        <?php
								if (isset($_POST['consultar'])) {
									if (empty($ordenes)) { $ordenes = 0; $ttProductos = 0; }
								?>
	        	                <script type="text/javascript">
     								google.load("visualization", "1", {packages:["corechart"]});
								    google.setOnLoadCallback(drawChart);
								    function drawChart() {
								        var data = google.visualization.arrayToDataTable([
					    	    	  	['Year', 'Histórico'],								  
						          		['Ordenes',      <?php echo $ordenes; ?> ],								  	
							          	['Productos',      <?php echo $ttProductos; ?> ]
							        ]);
	
							        var options = {
						        	   title: 'Relación Ordenes Productos',
						    	       hAxis: {title: 'Ventas', titleTextStyle: {color: 'red'}}
							        };
	
							        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
							        chart.draw(data, options);
							      }
							    </script>
                    	        <div id="chart_div" style="width: 900px; height: 300px; margin: 0 auto"></div>
                            <?php } ?>
                       		<!-- FIN DE GRAFICA PORCENTAJES -->
                            
                        </div>
                    </div>                    
                    
                    <!--MOSTRAMOS EL CUADRO COMPLETO -->
                    <div id="tab2" class="tab_content">                    	                        
                    	                  
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