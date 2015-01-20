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
        <link href="css/smartpaginator.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
		<title><?php echo $nombre; ?></title>
        <script type="text/javascript" src="js/jquery.tools.min.js"></script>
        <script type="text/javascript" src="js/smartpaginator.js"></script>
        <script type="text/javascript" src="js/jquery.simplePagination.js"></script>
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
			$active3 = 1;
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
    		    	<li class="active"><a href="#tab1"><span>Referencias Stock</span></a></li>
		   			<!--<li><a href="#tab2">Anular Pedidos</a></li>
   		  	        <li><a href="#tab3">Productos</a></li>-->                
		  	    </ul>
        		
                <div class="tab_container">                	                    
                    <!-- BUSQUEDA DE CLIENTE QUE YA COMPRARON CON OPCIONES DE MODIFICACION -->
                    <div id="tab1" class="tab_content">
                    	<div id="busquedaClientes">	
                        	<div class="fieldset">
                            	<h2 class="legend">Busqueda R&aacute;pida</h2>
                                <span class="tooltip_links">&nbsp;</span>                                
	                        	<form id="formBuscarReferencia" name="formBuscarReferencia" method="post" action="<?php echo $_SERVER['../elpais_seleccion/PHP_SELF']; ?>" accept-charset="utf-8" enctype="multipart/form-data">
		                        	<table style="width:40%">
    		                        	<thead>
        		                        	<th><label class="cab">Nombre del Producto</label></th>            		                        
                		                    <th></th>
                    		            </thead>
                        		        <tbody>
                            		    	<tr>
                                		    	<td align="center">
                                    		    	<input type="text" id="producto" name="producto" style="text-align:center; width: 450px" autocomplete="off" required="required" />
                	                        	</td>	                	                                                      	            
	            	                        </tr>
                                            <tr>
                                            	<td colspan="2" align="center" height="50px">
                                    	        	<input type="submit" id="buscar" name="buscar" value="Buscar" />                                                    
                                        	    </td>
                                            </tr>
    	            	                </tbody>
        	            	        </table>
            	                </form>
                            </div>
                        </div>
                        <div id="resultSearch" style="margin-bottom: 60px;">
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Listado de Productos</h3><br />
                            
                            <div id="dvData0">
	                        	<table id="resultSearchL" cellpadding="0" cellspacing="0" class="clsTabla">
    	                        	<thead>
        	                        	<th width="50px">IdOferta</th>
           		                        <th width="50">Images</th>
               		                    <th width="300px">Descripci&oacute;n</th>                                    
                   		                <th width="100px">Tipo</th>
                                        <th width="50px">Iva</th>                                    
                       		        	<th width="200px">Referencias</th>
                           		        <th width="100px">Vendidos</th>                               		    
                                   		<th width="100px">Stock Inicial.</th> 
                                        <th width="100px">Restantes.</th> 
	                                </thead>
                                    <thead>
                                    	<th><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                        <th><a href="" class="clsEliminar" title="Puedes borrar esta columna para exportar los datos." style="color:#090">eliminar</a></th>
                                    </thead>
    	                            <tbody id="dataCustomerS">                             
        		                        <?php
										$SQL = "SELECT productos.*, opcionesoferta.*, imagenes.BaseUrl, imagenes.Imagen FROM productos 
												INNER JOIN opcionesoferta ON productos.IdOferta = opcionesoferta.IdOpcion 
												LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1
												ORDER BY IdOferta Desc";
										$db->setQuery($SQL);
										$result = $db->loadObjectList();
										foreach($result as $result1) {
											echo '
												<tr height="26px">
													<td><label>'.$result1->IdOferta.'</label></td>
													<td><img src="'.$result1->BaseUrl.'/'.$result1->Imagen.'" width="30px"/></td>
													<td><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
													<td><label>';
														if ($result1->OptActiva == 1) {
															echo utf8_encode($result1->Opcion);
														}
													echo '
													</label></td>
													<td><label>'.number_format(($result1->Iva-1)*100, 0,',','.').'%</label></td>
													<td><label>'.$result1->Referencia.'</label></td>
													<td>
														<label>';
															$SQL_VENTAS = "SELECT SUM(lineasorden.Cantidad) as Total FROM lineasorden 
																		  INNER JOIN ordenes ON lineasorden.IdOrden = ordenes.IdOrden 
																		  INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id 
																		  WHERE lineasorden.IdProducto = '".$result1->IdOferta."' AND lineasorden.EstadoPedido != 'Anulado' AND lineasorden.EstadoPedido != 'Devuelto' AND ordenes.EstadoPago = 'ok'";
															$db->setQuery($SQL_VENTAS);
															$VENTAS = $db->loadObject();
															if ($VENTAS->Total > 0)
																echo $VENTAS->Total;	
															else
																echo '0';
														echo '
														</label>
													</td>
													<td><label>'.$result1->Stock.'</label></td>
													<td>';
														if ((($result1->Stock)-($VENTAS->Total)) > 0)
															echo '<label style="color: green">'.(($result1->Stock)-($VENTAS->Total)).'</label>';
														else
															echo '<label style="color: red">'.(($result1->Stock)-($VENTAS->Total)).'</label>';
													echo '
													</td>
												</tr>
											';
											$conta = $conta + 1;
										}
										
										$pagination = 10;
										$reg = $conta % $pagination;
										$tReg = $pagination - $reg;
										$totalRegister1 = $conta + $tReg;
										
										for ($i=1; $i <= $tReg; $i++) {
											echo '<tr height="26px"><td colspan="10"></td></tr>';
										}
										
										?>
               		                </tbody>
                    	        </table>
                        	</div>
                                
                            <div class="excel">
                            	<form id="excel0" name="excel0" method="post" action="ficheroExcel.php">
                                	<input type="hidden" id="values0" name="values0" />
                                    <input type="submit" id="btnExport" value="Exportar Excel" alt="0" />
                                </form>                               
                            </div>  
                        </div>
                        
                        <div id="greenL" style="position: absolute; bottom: 20px;"></div>
                        <script type="text/javascript">
							$('#greenL').smartpaginator({ totalrecords: <?php echo $totalRegister1; ?>, recordsperpage: 10, datacontainer: 'resultSearchL', dataelement: 'tbody tr', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });
						</script>
                        
                    </div>
                    <!-- CAMBIAR ESTADO DE LOS PEDIDOS-->
                	<div id="tab2" class="tab_content">
                    							                       
                    </div>
                    <!--MOSTRAMOS LOS PRODUCTOS ACTIVOS
                    <div id="tab3" class="tab_content">                    	                        
                    </div>
                    -->
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