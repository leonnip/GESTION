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
		<title>ADMININISTRACION</title>
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
    		    	<li class="active"><a href="#tab1"><span>Informe Clientes Sorteo</span></a></li>
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
	                                                	<select name="idSorteo">
    	                                                	<?php 
																$SQL = "SELECT * FROM tiposorteos"; 
																$db->setQuery($SQL);
																$result = $db->loadObjectList();
																foreach($result as $result1) {
																	echo '<option value="'.$result1->IdSorteo.'"><label>'.$result1->NombreSorteo.'</label></option>';
																}
															?>
                                    	                </select>
                                                    </div>
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
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Clientes Sorteo <?php echo $nombre; ?></h3><br />
                            <div id="dvData0">
	                        	<table id="resultSearch_Opt" cellpadding="1" cellspacing="1">
    	                        	<thead>
        	                        	<th colspan="9" align="center">
            	                        	<?php 
												if (isset($_POST['consultar'])) {
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
												
													$f1 = $_POST['Fecha1']; $_f1 = explode('-', $f1); $Y = $_f1[0]; $M = $_f1[1]; $D = $_f1[2]; $diaSemana1 = diaSemana($Y, $M, $D); $fechaTexto1 = actual_date($Y, $M, $D, $diaSemana1);
													$f2 = $_POST['Fecha2']; $_f2 = explode('-', $f2); $Y = $_f2[0]; $M = $_f2[1]; $D = $_f2[2]; $diaSemana2 = diaSemana($Y, $M, $D); $fechaTexto2 = actual_date($Y, $M, $D, $diaSemana2);
										
													if ($_POST['Fecha1'] == $_POST['Fecha2']) { echo 'Fecha de Informe : ' . $fechaTexto1; } else { echo 'Rango Fecha Informe : &nbsp;&nbsp;'.$fechaTexto1. ' &nbsp;-&nbsp; ' .$fechaTexto2; }
												}
											?>
                    	                </th>
                        	        </thead>
                            		<thead>
                                    	<th width="60px">Orden</th>
                                        <th width="60px">Participación</th>
                                        <th width="300px">Nombre y Apellidos</th>
                                		<th width="80px">Dni</th>                                    
                                    	<th width="80px">Teléfono</th>                                        
	                                    <th width="100px">Email</th>  
                                        
                                        <th style="display:none" width="100px">Tipo Via</th>  
                                        <th style="display:none" width="100px">Dirección</th>  
                                        <th style="display:none" width="100px">Tipo Número</th>  
                                        <th style="display:none" width="100px">Número</th>  
                                        <th style="display:none" width="100px">Piso</th>  
                                        <th style="display:none" width="100px">Puerta</th>  
                                        <th style="display:none" width="100px">Cp</th>  
                                        <th style="display:none" width="100px">Población</th>  
                                         
    	                                <th width="100px">Provincia</th>   
                                        <th width="100px">Sorteo</th>                          	
                                        <th width="30px">Estado</th>
        	                        </thead>                                
            	                    <?php
									if (isset($_POST['consultar'])) {									
										
										echo '<tbody id="dataCustomer">';									
										
										$fecha1 = $_POST['Fecha1'];
										$fecha2 = $_POST['Fecha2'];
										$sorteo = $_POST['idSorteo'];
									
										$fechaInicio=strtotime($fecha1);
										$fechaFin=strtotime($fecha2);
													
										$SQL = "SELECT ordenes.IdOrden, lineasorden.Id, ordenes.FechaOrden, productos.Nombre_Producto, 
												usuarios.Nombres, usuarios.Apellidos, usuarios.Dni, usuarios.Telefono, usuarios.Email,
												direcciones.TipoVia, direcciones.Direccion, direcciones.TipoNumero,
												direcciones.Numero, direcciones.Piso, direcciones.Puerta, direcciones.Cp, direcciones.Poblacion, direcciones.Provincia, tiposorteos.NombreSorteo
												FROM ordenes
												INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden
												INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta
												INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id
												INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id
												INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden
												INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion
												INNER JOIN sorteos ON ordenes.IdOrden = sorteos.IdOrden
												INNER JOIN tiposorteos ON sorteos.IdSorteo = tiposorteos.IdSorteo
												WHERE sorteos.Participa = 1 AND ordenes.FechaOrden BETWEEN '$fecha1' AND '$fecha2'
												AND ordenes.EstadoPago = TRIM('ok') AND lineasorden.EstadoPedido = TRIM('Enviado')";
										
										$db->setQuery($SQL);
										$result = $db->loadObjectList();
									
										foreach($result as $result1) {																		
											echo '
												<tr height="24px">
													<td><label>'.$result1->IdOrden.'</label></td>
													<td><label>'.$result1->Id.'</label></td>
													<td><label>'.utf8_encode($result1->Nombres). ' ' . utf8_encode($result1->Apellidos) . '</label></td>												
													<td><label>'.utf8_encode($result1->Dni).'</label></td>
													<td><label>'.$result1->Telefono.'</label></td>
													<td><label>'.utf8_encode($result1->Email).'</label></td>
													
													<td style="display:none"><label>'.utf8_encode($result1->TipoVia).'</label></td>
													<td style="display:none"><label>'.utf8_encode($result1->Direccion).'</label></td>
													<td style="display:none"><label>'.utf8_encode($result1->TipoNumero).'</label></td>
													<td style="display:none"><label>'.utf8_encode($result1->Numero).'</label></td>
													<td style="display:none"><label>'.utf8_encode($result1->Piso).'</label></td>
													<td style="display:none"><label>'.utf8_encode($result1->Puerta).'</label></td>
													<td style="display:none"><label>'.utf8_encode($result1->Cp).'</label></td>
													<td style="display:none"><label>'.utf8_encode($result1->Poblacion).'</label></td>
													
													<td><label>'.utf8_encode($result1->Provincia).'</label></td>
													<td><label>'.utf8_encode($result1->NombreSorteo).'</label></td>
													<td><i class="fa icon-ok"></i></td>
												</tr>
												';	
											$totalPP = $totalPP + $result1->totalProductos;
											$totalOO = $totalOO + $result1->totalOrdenes;
											
											$graph[] = array("usuario"=>$result1->Usuario, "ordenes"=>$result1->totalOrdenes, "productos"=>$result1->totalProductos);								
										}
										$totalGraph = count($graph);
										//print_r($graph);
									
										echo '
											</tbody>
											<tfoot style="color: white; font-size: 14px; font-variant:small-caps; background: #1F88A7">
    	            		                	<tr height="30px" style="color: white; font-size: 14px; font-variant:small-caps;">
        	            		                	<td bgcolor="#1F88A7" colspan="9" align="center"></td>            	                                                	    		                                             		        
	                                    		</tr>
			                                </tfoot>	
										';
									
									} else {
										echo '
										<tbody id="dataCustomer">
											<tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
	                        		        <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
    	                        	        <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
        	                       		    <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
            	                       		<tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>                	                    
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