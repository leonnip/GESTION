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
        <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->        
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.jcookie.min.js"></script>
         <script type="text/javascript" src="js/tablas.js"></script>
         <script type="text/javascript" src="js/jquery.validate.js"></script>
        
        <script type="text/javascript" src="js/jquery-latest.js"></script>	
		<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
        
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
			//La variable active nos ayuda a saber que tab es el que esta activo
			$active4 = 1;
			include('menu.inc.php'); 
			include('informe-pedidos.inc.php');
			
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
				//$date = $week_days[$week_day_now] . ", " . $day_now . " de " . $months[$month_now] . " de " . $year_now;   
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
    <!-- Para el tooltip del searh operation y estado -->
    <div class="tooltip_customer"></div>
    <!-- Fin tooltip -->
    
    <div id="content-body">
    	<div id="general" class="content">
        	<div id="Contenido" style="padding-top: 20px; overflow: auto; overflow-x:hidden">   
            	<div style="position: absolute; top: 0px; left: 990px; z-index:9999999"><img src="images/pedidos.png" /></div>
            	<ul class="tabs">
    		    	<li class="active"><a href="#tab1"><span>Informe de Pedidos</span></a></li>
		   			<li><a href="#tab2">Cuadro Completo</a></li>
   		  	        <li><a href="#tab3">Informe Ventas Detalle</a></li>
		  	    </ul>
        		
                <div class="tab_container">                	                    
                    <!-- INFORME DE PEDIDOS DESDE FECHA INCIO A FECHA FIN -->
                    <div id="tab1" class="tab_content">
                    	<div id="busquedaClientes">	
                        	<div class="fieldset">
                            	<h2 class="legend">Rango de B&uacute;squeda</h2>
                                <span class="tooltip_links">&nbsp;</span>                                
	                        	<form id="formInforme" name="formInforme" class="loading" method="post" action="<?php echo $_SERVER['../elpais_seleccion/PHP_SELF']; ?>" accept-charset="utf-8" enctype="multipart/form-data">
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
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Informe Ventas <?php echo $nombre; ?></h3><br />
                            <div id="dvData0">
                        	<table id="resultSearch_Opt" cellpadding="0" cellspacing="2" style="border-collapse:collapse;" class="clsTabla">
                            	<thead>
                                	<td colspan="11" align="center">
                                    	<?php 
											if (isset($_POST['consultar'])) {
												
												$f1 = $_POST['Fecha1']; $_f1 = explode('-', $f1); $Y = $_f1[0]; $M = $_f1[1]; $D = $_f1[2]; $diaSemana1 = diaSemana($Y, $M, $D); $fechaTexto1 = actual_date($Y, $M, $D, $diaSemana1);
												$f2 = $_POST['Fecha2']; $_f2 = explode('-', $f2); $Y = $_f2[0]; $M = $_f2[1]; $D = $_f2[2]; $diaSemana2 = diaSemana($Y, $M, $D); $fechaTexto2 = actual_date($Y, $M, $D, $diaSemana2);
												
												if ($_POST['Fecha1'] == $_POST['Fecha2']) { echo 'Fecha de Informe : ' . $fechaTexto1; } else { echo 'Rango Fecha Informe : &nbsp;&nbsp;'.$fechaTexto1. ' &nbsp;-&nbsp; ' .$fechaTexto2; }
											}
										?>
                                    </td>
                                </thead>
                            	<thead>
                                	<th data-sort="int" width="50px">IdOferta</th>
                                    <th data-sort="string-ins" width="300px">Producto</th>
                                    <th data-sort="moveBlancks" width="150px">Talla/Tipo</th>
                                	<th data-sort="int" width="100px">Oficina</th>
                                    <th data-sort="int" width="100px">Call-Center</th>
                                    <th data-sort="int" width="100px">Web</th>
                                    <th data-sort="int" width="100px">Total</th>                                    
                                    <th data-sort="float" width="75px">PVP</th>                                   
                                    <th data-sort="float" width="75px">Coste</th> 
                                    <th data-sort="float" data-sort-default="desc" width="100px">Facturaci&oacute;n Total</th>                                    
                                    <th data-sort="float" width="75px">Comisi&oacute;n <?php echo $comisionPrensa * 100; ?>%</th>
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
                                </thead>                              
                                <?php
								if (isset($_POST['consultar'])) {
									$informe = informePedidos($db, $comisionPrensa);	
									$_factTotal = $informe[0]['fact_total'];					
									$_comisionPatner = $informe[0]['comision_patner'];									
									
									$devoluciones = devoluciones($db, $comisionPrensa);		
									$_devolTotal = $devoluciones[0]['fact_total'];
									$_devolComiPatner = $devoluciones[0]['comision_patner'];							
								} else {
									echo '
									<tbody id="dataCustomer">
										<tr><td colspan="11" height="24px"></td></tr><tr><td colspan="11" height="24px"></td></tr>
	                                    <tr><td colspan="11" height="24px"></td></tr><tr><td colspan="11" height="24px"></td></tr>
    	                                <tr><td colspan="11" height="24px"></td></tr><tr><td colspan="11" height="24px"></td></tr>
        	                            <tr><td colspan="11" height="24px"></td></tr><tr><td colspan="11" height="24px"></td></tr>
            	                        <tr><td colspan="11" height="24px"></td></tr><tr><td colspan="11" height="24px"></td></tr>                	                    
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
									if (empty($subtotal)) { $subtotal = 0; }
							?>
    	                    <script type="text/javascript">
     							google.load("visualization", "1", {packages:["corechart"]});
							    google.setOnLoadCallback(drawChart);
							    function drawChart() {
							        var data = google.visualization.arrayToDataTable([
					    	      ['Year', 'Facturación', 'Devoluciones'],								  
						          ['Facturación Total',      <?php echo $_factTotal; ?>, 0 ],
								  ['Devoluciones Total', <?php echo $_devolTotal; ?>, 0 ],
						          ['Comisión el Prensa',      <?php echo $_comisionPatner; ?>, 0 ],
								  ['Dev. Comisión el Prensa',      <?php echo $_devolComiPatner; ?>, 0 ]
						        ]);

						        var options = {
					        	  title: 'Resumen de ventas',
					    	      hAxis: {title: 'Ventas', titleTextStyle: {color: 'red'}}
						        };

						        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
						        chart.draw(data, options);
						      }
						    </script>
                            <div id="chart_div" style="width: 900px; height: 300px; margin: 0 auto"></div> 
                            <?php } ?>
                            <!-- FIN GRAFICA RENDIMIENTO DE PRODUCTO -->
                           
                        </div>
                    </div>                    
                    
                    <!--MOSTRAMOS EL CUADRO COMPLETO -->
                    <div id="tab2" class="tab_content">                    	                        
                    	<div id="busquedaClientes">	
                        	<div class="fieldset">
                            	<h2 class="legend">Rango de B&uacute;squeda</h2>
                                <span class="tooltip_links">&nbsp;</span>                                
	                        	<form id="formInformeComp" name="formInformeComp" class="loading" method="post" action="<?php echo $_SERVER['../elpais_seleccion/PHP_SELF']; ?>" accept-charset="utf-8" enctype="multipart/form-data">
                                	<input type="hidden" id="_tab" name="_tab" value="2" />
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
                                            	<td colspan="2" align="center" height="50px">
                                    	        	<input type="submit" id="consultarInfor" name="consultarInfor" value="Consultar Informe" alt="1" />                                                    
                                        	    </td>
                                            </tr>
    	            	                </tbody>
        	            	        </table>  
                                </form>          	                
                            </div>
                        </div>
                        
                        <div id="resultSearch">
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Informe Completo Sin Iva <?php echo $nombre; ?></h3><br />                       
                            <div id="dvData1">     
                        		<table id="resultSearchCompleto" cellpadding="1" cellspacing="1" class="tablaExcel1">
                    	        	<thead>
           	    						<th colspan="16" align="center">
											<?php 
												if(isset($_POST['consultarInfor'])) {
													
													$f1 = $_POST['Fecha1']; $_f1 = explode('-', $f1); $Y = $_f1[0]; $M = $_f1[1]; $D = $_f1[2]; $diaSemana1 = diaSemana($Y, $M, $D); $fechaTexto1 = actual_date($Y, $M, $D, $diaSemana1);
													$f2 = $_POST['Fecha2']; $_f2 = explode('-', $f2); $Y = $_f2[0]; $M = $_f2[1]; $D = $_f2[2]; $diaSemana2 = diaSemana($Y, $M, $D); $fechaTexto2 = actual_date($Y, $M, $D, $diaSemana2);
													
													if ($_POST['Fecha1'] == $_POST['Fecha2']) { echo 'Fecha de Informe : ' . $fechaTexto1; } else { echo 'Rango Fecha Informe : &nbsp;&nbsp;'.$fechaTexto1. ' &nbsp;-&nbsp; ' .$fechaTexto2; } 
												}
											?>
				        	    		</th>
					        		</thead>
                            		<thead>
                                		<th width="50px">IdOferta</th>
	                                    <th width="300px">Producto</th>
    	                                <th width="150px">Talla/Tipo</th>
                                        <th width="150px">Oficina</th>
        	                        	<th width="100px">Call-Center</th>
            	                        <th width="100px">Web</th>
                	                    <th width="100px">Total</th>                                    
                    	                <th width="100px">PVP</th>                                                                       
                        	            <th width="75px">Facturaci&oacute;n Total</th>             
                            	        <th width="100px">Coste Unidad</th> 
                                	    <th width="75px">Coste Total</th> 
                                    	<th width="75px">Margen Bruto Unitario</th> 
	                                    <th width="75px">Aportación Bruta</th>                                     
    	                                <th width="75px">Comisión Unidad <?php echo $comisionPrensa * 100; ?>%</th> 
        	                            <th width="75px">Comisi&oacute;n <?php echo $comisionPrensa * 100; ?>%</th>
            	                        <th width="75px">Aportación Neta</th> 
                	                </thead>   
                    	            <?php
										if (isset($_POST['consultarInfor'])) {
											$cuadro = cuadroCompleto($db, $comisionPrensa);	
											$_cuadroFact = $cuadro[0]['fact_total'];
											$_aportBruta = $cuadro[0]['aport_bruta'];
											$_cuadroComision = $cuadro[0]['comision_patner'];
											
											$cuadrodevol = cuadroCompletoDevol($db, $comisionPrensa);	
											$_cuadroDevolFact = $cuadrodevol[0]['fact_total'];		
											$_aportDevolBruta = $cuadrodevol[0]['aport_bruta'];																																		
											$_cuadroDevolComision = $cuadrodevol[0]['comision_patner'];
										} else {
											echo '								
									<tbody id="dataCustomer">                                
										<tr><td colspan="16" height="24px"></td></tr><tr><td colspan="16" height="24px"></td></tr>
	    	                            <tr><td colspan="16" height="24px"></td></tr><tr><td colspan="16" height="24px"></td></tr>
    	    	                        <tr><td colspan="16" height="24px"></td></tr><tr><td colspan="16" height="24px"></td></tr>
        	    	                    <tr><td colspan="16" height="24px"></td></tr><tr><td colspan="16" height="24px"></td></tr>
            	    	                <tr><td colspan="16" height="24px"></td></tr><tr><td colspan="16" height="24px"></td></tr>
            	    	                <tr><td colspan="16" height="24px"></td></tr>                        	                          	                    
                            	    </tbody>';
									}
									?>	                                							
	                            </table>
                            
    	                        <table style="width: 400px" cellpadding="0" cellspacing="0" style="margin-top: 20px; border: 1px solid #ccc" >
        	                    	<thead>
            	                    	<th width="250px">Descripción</th><th>Total</th>
                	                </thead>
                    	            <tbody>
                        	        	<tr height="22px"><td><label>Facturación</label></td><td><label><?php echo number_format($_cuadroFact,2,',','.'); ?> &euro;</label></td></tr>                                    
                            	        <tr height="22px"><td><label>Aportación Bruta</label></td><td><label><?php echo number_format($_aportBruta,2,',','.'); ?> &euro;</label></td></tr>
                                	    <tr height="22px"><td><label style="color: #F00">Comisión Prensa</label></td><td><label style="color: #F00">- <?php echo number_format($_cuadroComision,2,',','.') ?> &euro;</label></td></tr>
                                        
	                                    <!-- COSTOS CARTILLAS MAILING -->                                    
    	                               	<?php 
										if (isset($_POST['consultarInfor'])) {
											$SQL = "SELECT * FROM publicaciones WHERE CosteActivo = '1' AND FechaPublicacion >= '".$_POST['Fecha1']."' AND FechaPublicacion <= '".$_POST['Fecha2']."'";
											$db->setQuery($SQL);
											$row = $db->execute();
											if (mysqli_num_rows($row) > 0) {												
												$result = $db->loadObjectList();
												foreach($result as $result1) {
													echo '<tr height="22px">';
													echo '<td><label style="color: #F0F">Costes '.utf8_encode($result1->Concepto).'</label></td><td><label style="color: #F0F">- '.number_format($result1->CosteBaseImp,2,',','.').' &euro;</label></td>';
													echo '</tr>';
													$costes = $costes + $result1->CosteBaseImp;
												}												
											} else {
												$costes = 0;
											}
										}
										?>                                    
                    	                <!-- FIN -->
                        	            
                                        <tr height="22px"><td><label style="color:#F00">Devoluciones y No Entregados</label></td><td><label style="color:#F00">- <?php echo number_format($_aportDevolBruta,2,',','.') ?> &euro;</label></td></tr>
                            	        <tr height="22px"><td><label>Comisión Prensa Devoluciones</label></td><td><label><?php echo number_format($_cuadroDevolComision,2,',','.') ?> &euro;</label></td></tr>
                                	    <tr height="25px">
                                    		<td bgcolor="#1F88A7"><label style="color:#fff; font-size: 1.2em">aportacion Neta Total</label></td>
                                        	<td bgcolor="#1F88A7">
                                            	<label style="color:#fff; font-size: 1.2em">
													<?php echo number_format(($_aportBruta - $_cuadroComision - $costes - $_aportDevolBruta) + $_cuadroDevolComision,2,',','.'); ?> &euro;
                                                </label>
                                            </td>
	                                    </tr>
    	                            </tbody>
        	                    </table>                            
            	            </div>                            
                            
                            <div class="excel">
                                <form id="excel1" name="excel1" method="post" action="ficheroExcel.php">
                                	<input type="hidden" id="values1" name="values1" />
                                    <input type="submit" id="btnExport" value="Exportar Excel" alt="1" />
                                </form>                               
                            </div>
                            
                            <!-- GRAFICA -->
    						<?php
							$subtotal = $cuadro[0]['fact_total']; $aportBruta = $cuadro[0]['aport_bruta']; $aportNeta = $cuadro[0]['aport_neta']; $comision = $cuadro[0]['comision_patner'];
							if (isset($_POST['consultarInfor'])) {
								if (empty($comision)) { $subtotal = 0; $aportBruta = 0; $aportNeta = 0;  $comision = 0; }
							?>
						    <script type="text/javascript">
						    	google.load("visualization", "1", {packages:["corechart"]});
								google.setOnLoadCallback(drawChart);
								function drawChart() {
								    var data = google.visualization.arrayToDataTable([
									    ['Year', 'Facturación', 'Devoluciones'],								  
									    ['Facturación / Devoluiones',     <?php echo $_cuadroFact; ?>, <?php echo $_cuadroDevolFact; ?> ],
									    ['Aportación Bruta', <?php echo $_aportBruta; ?>, <?php echo $_aportDevolBruta; ?> ],
										['Comisión el País', <?php echo $_cuadroComision; ?>, <?php echo $_cuadroDevolComision; ?>  ],
										['Aportación Neta', <?php echo ($_aportBruta - $_aportDevolBruta) - ($_cuadroComision - $_cuadroDevolComision) - $costes; ?>,0  ]
									]);

									var options = {
									  title: 'Resumen de ventas',
									  hAxis: {title: 'Ventas', titleTextStyle: {color: 'red'}}
									};

									var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_2'));
									     chart.draw(data, options);
								}
							</script>
						    <div id="chart_div_2" style="width: 900px; height: 300px; margin: 0 auto; text-align: center"></div> 
                            <?php } ?>
						    <!-- FIN GRAFICA -->                            
                        </div>                        
                    </div>                                        
                    
                	<!-- CUADRO INFORME DETALLE CLEINTES -->
                	<div id="tab3" class="tab_content">   
		                <div id="busquedaClientes">	                        	
                        	<div class="fieldset">
                            	<h2 class="legend">Rango de B&uacute;squeda</h2>
                                <span class="tooltip_links">&nbsp;</span>                                
	                        	<form id="formInforme" name="formInforme" class="loading" method="post" action="<?php echo $_SERVER['../elpais_seleccion/PHP_SELF']; ?>" accept-charset="utf-8" enctype="multipart/form-data">
		                        	<table style="width:40%">
    		                        	<thead>
        		                        	<th><label class="cab">Fecha Inicio</label></th>
            		                        <th><label class="cab">Fecha Fin</label></th>
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
                                            	<td colspan="2" align="center" height="50px">
                                    	        	<input type="submit" id="consultarClientesDetalle" name="consultarClientesDetalle" value="Consultar Informe" alt="2" />                                                    
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
                            <div id="dvData2">
	                        	<table id="resultSearch" cellpadding="0" cellspacing="0" class="clsTabla">
                                	<thead>
	                                	<th colspan="11" align="center">
											<?php 
												if(isset($_POST['consultarClientesDetalle'])) {
													
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
                                    	<th width="20px">Gastos</th> 
							            <th width="100px">Precio</th>
							            <th width="32px">Pago</th>		
                                        <th width="100px">Transacción</th>				                                                                                   
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
                                	</thead>
            	                    <?php
										if (isset($_POST['consultarClientesDetalle'])) {
											ventasDetalleCliente($web);
										} else {
											echo '								
	                            	    <tbody id="dataCustomerEst">
											<tr><td colspan="11" height="24px"></td></tr><tr><td colspan="11" height="24px"></td></tr>
        	                            	<tr><td colspan="11" height="24px"></td></tr><tr><td colspan="11" height="24px"></td></tr>
	            	                        <tr><td colspan="11" height="24px"></td></tr><tr><td colspan="11" height="24px"></td></tr>
    	            	                    <tr><td colspan="11" height="24px"></td></tr><tr><td colspan="11" height="24px"></td></tr>
        	            	                <tr><td colspan="11" height="24px"></td></tr><tr><td colspan="11" height="24px"></td></tr>
            	            	            <tr><td colspan="11" height="24px"></td></tr><tr><td colspan="11" height="24px"></td></tr>
                	            	        <tr><td colspan="11" height="24px"></td></tr><tr><td colspan="11" height="24px"></td></tr>
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
    	            <!-- FIN TAB -->    
                    
                </div>
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