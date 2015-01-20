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
        <link href="css/smartpaginator.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
		<title><?php echo $nombre; ?></title>
        <script type="text/javascript" src="js/jquery.tools.min.js"></script>
        <script type="text/javascript" src="js/smartpaginator.js"></script>
        <script type="text/javascript" src="js/jquery.simplePagination.js"></script>
        <!--<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>-->
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.jcookie.min.js"></script>
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
    		    	<li class="active"><a href="#tab1"><span>Ordenar Productos</span></a></li>
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
	                        	<form id="formBuscar" name="formBuscar" method="post" action="<?php echo $_SERVER['../elpais_seleccion/PHP_SELF']; ?>" accept-charset="utf-8" enctype="multipart/form-data">
		                        	<table style="width:40%">
    		                        	<thead>
        		                        	<th><label class="cab">Nombre del Producto</label></th>            		                        
                		                    <th></th>
                    		            </thead>
                        		        <tbody>
                            		    	<tr>
                                		    	<td align="center">
                                    		    	<input type="text" id="producto" name="producto" style="text-align:center; width: 450px" autocomplete="off" />
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
                        <div id="resultSearch">
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Listado de Productos</h3><br />
                        	<table id="resultSearchL" cellpadding="0" cellspacing="0" style="margin-bottom: 110px">
                            	<thead>
                                	<th width="40px"></th>
            						<th width="50px">IdOferta</th>
						            <th width="90px">Imagen</th>
                                    <th width="60px">Web</th>
                                    <th width="60px">Gesti&oacute;n</th>
                                    <th width="60px">Promoci&oacute;n</th>
						            <th width="300px">Descripción</th>
						            <th width="80px">Listado</th>                                    
                                </thead>
                                <tbody id="dataCustomerL">
									<?php
										$SQL = "SELECT productos.IdOferta, productos.Activo, productos.Gestion, productos.Promo, productos.Nombre, productos.Nombre_Producto, productos.Listados, imagenes.BaseUrl, imagenes.Imagen
												FROM productos
												LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1
												ORDER BY IdOferta DESC";
										$db->setQuery($SQL);
										$row = $db->loadObjectList();
										$conta = 0;
										foreach($row as $row1) {
											$checkeo[] = $row1->Listados;										
											echo '
											<tr height="24px">
												<td></td>
    			                               	<td align="center"><label class="label">'.$row1->IdOferta.'</label></td>
												<td><img src="'.$row1->BaseUrl.'/'.$row1->Imagen.'" width="30px" /></td>
												<td>'; if($row1->Activo == 1) { echo '<img src="images/icon_web.png" width="22px" alt="Promoción sin gastos." />'; } else { echo '<img src="images/icon_incative.png" width="22px" />'; }echo '</td>
												<td>'; if($row1->Gestion == 1) { echo '<img src="images/icon_gestion.png" width="22px" />'; } else { echo '<img src="images/icon_incative.png" width="22px" />'; } echo '</td>
												<td>'; if($row1->Promo == 1) { echo '<img src="images/icon_promo.png" width="22px" />'; } echo '</td>
        	    		                        <td><label class="label">'.utf8_encode($row1->Nombre_Producto).'</label></td>
                	            		        <td align="center">';
													if($checkeo[$conta] == 1) { echo '<input type="checkbox" id="'.$row1->IdOferta.'" name="CheckTienda" checked />'; }
													else if ($checkeo[$conta] == 0) { echo '<input type="checkbox" id="'.$row1->IdOferta.'" name="CheckTienda" unchecked />'; }
												echo '
												</td>
                    	                	</tr>
											';
											$conta++;
										}
									?>                                  
                                </tbody>
                            </table>                                                        
                        </div>
                        
                        <table style="position: absolute; bottom: 75px">
	                       	<tr>
                               	<td><img src="images/icon_web.png" width="30px"/>Activo en Web</td>                                	
                                <td><img src="images/icon_gestion.png" width="30px"/>Activo en Gestión</td>
                                <td><img src="images/icon_promo.png" width="30px" />Promoción sin gastos</td>                                                                     
                                <td><img src="images/tick_l.png" />&nbsp;Separar para exportación</td>
                                <td><img src="images/icon_incative.png" width="30px"/>Inactivo</td>   
                            </tr>
                        </table>
                        <div id="greenL" style="position: absolute; bottom: 25px;"></div>
                        <script type="text/javascript">
							$('#greenL').smartpaginator({ totalrecords: <?php echo $conta; ?>, recordsperpage: 10, datacontainer: 'resultSearchL', dataelement: 'tr', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });
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