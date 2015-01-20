<?php
session_start();
if ((!isset($_SESSION['Logged'])) && (!isset($_SESSION['UserIdAdmin']))) {
	header('Location: https://'.$_SERVER['HTTP_HOST']);
} else {
	include('config.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="css/stylos-pag.css" type="text/css" rel="stylesheet" />
        <link href="css/select-styles.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
		<title><?php echo $nombre; ?></title>
        <!--<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>-->
        <script type="text/javascript" src="js/jquery.tools.min.js"></script>
        <!--<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>-->
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.jcookie.min.js"></script>
        <script type="text/javascript" src="js/tablas.js"></script>
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

	<!-- GADGET MENU LATERAL -->
    <?php require("gadget.inc.php"); ?>
    <!-- FIN GADGET -->

    <div id="TopBar" class="iluminacion">	
        <?php 
			//La variable active nos ayuda a saber que tab es el que esta activo
			$active3 = 1;
			include('menu.inc.php'); 
			include('facturacion-usuario.inc.php');
			
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
    
    <div id="content-body">
    	<div id="general" class="content">
        	<div id="Contenido" style="padding-top: 20px; overflow: auto; overflow-x:hidden">   
            	<div style="position: absolute; top: 0px; left: 990px; z-index:9999999"><img src="images/pedidos.png" /></div>
            	<ul class="tabs">
    		    	<li class="active"><a href="#tab1"><span>Facturación por Usuario</span></a></li>
		   			<!--<li><a href="#tab2">Cuadro Completo</a></li>-->
   		  	        <!--<li><a href="#tab3">Cuadro Completo</a></li>-->
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
                                            	<td align="center" colspan="2" height="40px">
                                                	<select id="selectUsuario" name="selectUsuario" style="width: 350px" class="chosen-select">
	                                                	<?php
															$SQL = "SELECT * FROM administrador";
															$db->setQuery($SQL);
															$result = $db->loadObjectList();
															foreach($result as $result1) {
																echo '<option value="'.trim($result1->Usuario).'">'.utf8_encode($result1->Nombres).' '.utf8_encode($result1->Apellidos).'</option>';	
															}
														?>
                                                    </select>
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
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Informe Facturación <?php echo $nombre . ' |'. $_POST['selectUsuario'] .'| '; ?></h3><br />
                            <div id="dvData0">
                        	<table id="resultSearch_Opt" cellpadding="1" cellspacing="1" bordercolor="#666666" style="border-collapse:collapse;" class="clsTabla" >
                            	<thead>
                                	<th colspan="10" align="center">
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
                                	<th width="50px">IdOferta</th>
                                    <th width="300px">Producto</th>
                                    <th width="150px">Talla/Tipo</th>
                                	<th width="100px">Total</th>                                                                       
                                    <th width="75px">PVP</th>                                   
                                    <th width="75px">Coste</th> 
                                    <th width="100px">Facturaci&oacute;n Total</th>                                    
                                    <th width="100px">Base Imponible</th>
                                    <th width="100px">--</th> 
                                    <th width="75px">Comisi&oacute;n <?php echo $comisionPrensa*100; ?>%</th>
                                </thead>   
                                <thead>
                                    	<th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." >eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." >eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." >eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." >eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." >eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." >eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." >eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." >eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." >eliminar</a></th>
                                        <th width="50"><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." >eliminar</a></th>                                        
                                </thead>                              
                                <?php
								if (isset($_POST['consultar'])) {
									$factUsuario = factUsuario($db, $comisionPrensa);
									$_usuaroFact = $factUsuario[0]['fact_total'];
									$_productos = $factUsuario[0]['productos'];
									
									$factDevolucion = devoluciones($db, $comisionPrensa);
									$_usuaroDevolFact = $factDevolucion[0]['fact_total'];
									$_productosDevol = $factDevolucion[0]['productos'];
								} else {
									echo '
									<tbody id="dataCustomer">
										<tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
	                                    <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
    	                                <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
        	                            <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
            	                        <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr> 
										<tr><td colspan="10" height="24px"></td></tr>                	                    
                                	</tbody>
									';	
								}
								?>  
                               </table>
                               
                               <table style="width: 400px" cellpadding="0" cellspacing="0" style="margin-top: 20px; border: 1px solid #ccc" >
                            	<thead>
                                	<th width="250px">Descripción</th><th>Total</th>
                                </thead>
                                <tbody>
                                	<tr height="22px"><td><label>Facturación</label></td><td><label><?php echo number_format($_usuaroFact,2,',','.'); ?> &euro;</label></td></tr>                                    
                                    <tr height="22px"><td><label>Devolucion y No Entregados</label></td><td><label><?php echo number_format($_usuaroDevolFact,2,',','.'); ?> &euro;</label></td></tr>                                    
                                    <tr height="25px">
                                    	<td bgcolor="#1F88A7"><label style="color:#fff; font-size: 1.2em">Total Facturación</label></td>
                                        <td bgcolor="#1F88A7"><label style="color:#fff; font-size: 1.2em"><?php echo number_format($_usuaroFact - $_usuaroDevolFact,2,',','.'); ?> &euro;</label></td>
                                    </tr>
                                </tbody>
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
									if (empty($subtotal)) { $subtotal = 0; $comision = 0; }
							?>
    	                    <script type="text/javascript">
     							google.load("visualization", "1", {packages:["corechart"]});
							    google.setOnLoadCallback(drawChart);
							    function drawChart() {
							        var data = google.visualization.arrayToDataTable([
					    	      ['Year', 'Facturación', 'Productos'],								  
						          ['Facturación Total',      <?php echo $_usuaroFact; ?>, <?php echo $_productos; ?> ],
								  ['Devoluciones', <?php echo $_usuaroDevolFact; ?>, <?php echo $_productosDevol; ?> ],
						          ['Total Facturado',      <?php echo ($_usuaroFact - $_usuaroDevolFact); ?>, <?php echo ($_productos - $_productosDevol); ?> ]
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
                                  
                	</div>
                    <!-- FIN TAB -->
                
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