<?php
session_start();
if ((!isset($_SESSION['Logged'])) && (!isset($_SESSION['UserIdAdmin']))) {
	header('Location: http://'.$_SERVER['HTTP_HOST']);
} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="css/stylos-pag.css" type="text/css" rel="stylesheet" />
        <link href="css/select-styles.css" type="text/css" rel="stylesheet" />
        <link href="css/tinytips.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
		<title>ADMIN WEB SERVICE</title>
        <script type="text/javascript" src="js/jquery.tools.min.js"></script>
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.jcookie.min.js"></script>
        <script type="text/javascript" src="js/jquery.validate.js"></script>
        <script type="text/javascript" src="js/jquery-class.js"></script>           
	</head>
<body>
</body>
<div id="wrapper" class="wrapper">

	<?php
		$active2 = 1;
		include('config.inc.php');
		require_once('conexion/conexion.inc.php');
		$db = DataBase::getInstance();
		$country = $_GET['country'];
	?>

	<!--
    <div id="TopBar" class="iluminacion">	
        <?php 
			//$active2 = 1;
			//include('config.inc.php');
			//include('menu.inc.php'); 
		?>
    </div> 
    -->
    
    <div id="content-body">
    	<div id="general" class="content">
        	<div id="Contenido" style="padding-top: 20px; overflow: auto; overflow-x:hidden">  
            	<div style="position: absolute; top: 0px; left: 990px; z-index:9999999"><img src="images/clientes.png" /></div> 
            	<ul class="tabs">
    		    	<li class="active"><a href="#tab1"><span>Modificar Pedido</span></a></li>
		   			<!--<li><a href="#tab2">Anular Pedidos</a></li>-->
   		  	        <!--<li><a href="#tab3">Productos</a></li>-->                
		  	    </ul>
        		
                <div class="tab_container">                	                    
                    <!-- MODIFICAR EL PEDIDO DEL CLIENTE -->
                    <div id="tab1" class="tab_content">
                    	<table class="modific_1">
                        	<tr>
                            	<td>
                                	<span>Orden N&uacute;mero:</span>&nbsp;&nbsp;&nbsp;<strong><?php echo base64_decode($_GET['orden']); ?>&nbsp;</strong>
                                	<?php
										$SQL = "SELECT * FROM relordendireccion ";
										$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion ";
										$SQL .= "WHERE relordendireccion.IdOrden = '".base64_decode($_GET['orden'])."'";					
										$db->setQuery($SQL);
										$result = $db->loadObject();
										echo '<label>'.utf8_encode($result->D_Nombres).' '.utf8_encode($result->D_Apellidos).'</label>';
									?>    
                                </td>
                            </tr>                                                       
                            <tr>
                            	<td height="45px"><span>Pedido Actual</span></td>
                            </tr>
                            <?php
							if (isset($_GET['response']) == 1) {
								echo '
	                            <tr>
    	                        	<td>
        	                        	<h4 class="opt"><img src="images/ok.png" style="float: left" />&nbsp;&nbsp;&nbspSu pedido ha sido modificado correctamente</h4>
            	                    </td>
                	            </tr>';
							}
							?>
                            <tr>
                            	<td>
                                	<table style="width: 740px; background: #F5F5F5; padding: 30px; border: 1px solid #D9DCDE; margin-bottom: 20px;" cellpadding="0" cellspacing="0">
                                    	<thead>
                                        	<th width="20px"></th>
                                        	<th width="50px"></th>
                                            <th width="40px">Estado</th>
                                            <th width="300px">Nombre</th>                                     
                                            <th>Tipo/Talla</th>
                                            <th>Unidades</th>
                                            <th>Subtotal</th>
                                        </thead>                                        
                                        <tbody>
                                        	<?php
											$orden = base64_decode($_GET['orden']);
											$cliente = base64_decode($_GET['cliente']);
											
											$orden_cliente = $orden . '|' . $cliente;
											
											$SQL ="SELECT ordenes.*, lineasorden.*, productos.*, opcionesoferta.Opcion, opcionesoferta.Precio, imagenes.BaseUrl, imagenes.Imagen FROM ordenes ";
											$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
											$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
											$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
											$SQL .= "LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1 ";
											$SQL .= "WHERE ordenes.IdOrden = '".$orden."'";
											$db->setQuery($SQL);
											$row = $db->execute();	
											
											$activeOpt = 0;										
											if (mysqli_num_rows($row) > 0) {
												$result = $db->loadObjectList();
												foreach($result as $result1) {
													$activeOpt++;
													echo '
                    		                    	<tr style="background: white; height:45px">
														<td align="center">';
															if($result1->EstadoPedido == 'Transito') {
																echo '<a id="deleteMod" href="update-amount.inc.php?producto='.$result1->IdOferta.'&talla='.$result1->Talla.'&opcion=deletep&orden='.$orden.'">&nbsp;</a>';
															}
														echo '
														</td>
                            		                	<td width="70px"><img src="'.$result1->BaseUrl . $result1->Imagen.'" width="50px" /></td>
														<td width="30px">';
															if ($result1->EstadoPedido == 'Transito') { echo '<img src="images/icon_transito.png" title="'.$result1->EstadoPedido.'" />'; }
															else if ($result1->EstadoPedido == 'Enviado') { echo '<img src="images/icon_enviado.png" title="'.$result1->EstadoPedido.'" />'; }
															else if ($result1->EstadoPedido == 'No-entregado') { echo '<img src="images/icon_noentregado.png" width="24px" title="'.$result1->EstadoPedido.'" />'; }
															else if ($result1->EstadoPedido == 'Entregado') { echo '<img src="images/icon_entregado.png" width="24px" title="'.$result1->EstadoPedido.'" />'; }
															else if ($result1->EstadoPedido == 'Anulado') { echo '<img src="images/icon_anulado.png" width="24px" title="'.$result1->EstadoPedido.'" />'; }
															else if ($result1->EstadoPedido == 'Devuelto') { echo '<img src="images/icon_devolucion.png" title="'.$result1->EstadoPedido.'" />'; }
														echo '
														</td>
                                    		            <td><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
                                            		    <td><label>'.utf8_encode($result1->Opcion).'</label></td>
		                                                <td>';
															if(trim($result1->EstadoPedido) == 'Transito' && $activeOpt > 1) {
																echo '<a id="up" href="update-amount.inc.php?producto='.$result1->IdOferta.'&talla='.$result1->Talla.'&opcion=downp&orden='.$orden.'"><img src="images/quantity_down.gif"/></a>&nbsp;&nbsp;';
															}
															echo '<label>'.$result1->Cantidad.'</label>&nbsp;&nbsp;';
															if(trim($result1->EstadoPedido) == 'Transito') {
																echo '<a id="up" href="update-amount.inc.php?producto='.$result1->IdOferta.'&talla='.$result1->Talla.'&opcion=upp&orden='.$orden.'"><img src="images/quantity_up.gif"/></a>&nbsp;&nbsp;';
															}
														echo '
														</td>
        		                                        <td><label>'.$result1->Subtotal.'</label></td>
                		                            </tr>';
													$subTotal = $subTotal + $result1->Subtotal;
												   $gastosEnvio = $gastosEnvio + $result1->GastosEnvio;								   							   
												}								   
												$TOTAL = $subTotal + $gastosEnvio;
											}
											$estadoPedido = $result1->EstadoPedido;
											?>
                                        </tbody>
                                        <tfoot bgcolor="#fff">
                                        	<tr>
                                            	<td colspan="4" bgcolor="#fff"></td>
                                                <td bgcolor="#fff"><label>Subtotal</label></td>
                                                <td bgcolor="#fff"><label>&rarr;</label></td>
                                                <td bgcolor="#fff"><label><?php echo $subTotal; ?>&euro;</label></td>
                                            </tr>
                                            <tr>
                                            	<td colspan="4" bgcolor="#fff"></td>
                                                <td bgcolor="#fff"><label>Gastos de Env&iacute;o</label></td>
                                                <td bgcolor="#fff"><label>&rarr;</label></td>
                                                <td bgcolor="#fff"d><label><?php echo $gastosEnvio; ?>&euro;</label></td>
                                            </tr>
                                            <tr>
                                            	<td colspan="4" bgcolor="#fff"></td>
                                                <td bgcolor="#fff"><label>Total A Pagar</label></td>
                                                <td bgcolor="#fff"><label>&rarr;</label></td>
                                                <td bgcolor="#fff"><label style="color: #f00; font-size: 14px"><?php echo $result1->Total; ?>&euro;</label></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table class="modific_3" style="width: 490px; float: left; margin-bottom: 40px;">
                        	<tr>
                            	<td><span id="title">Productos a A&ntilde;adir</span></td>
                            </tr>
                            <tr>
                            	<td>
                                	<table style="width: 490px; background: #F5F5F5; padding: 30px; border: 1px solid #D9DCDE">
                                    	<?php
										$SQL = "SELECT carritocompra.*, productos.IdOferta, productos.Nombre, productos.Nombre_Producto, 
												imagenes.BaseUrl, imagenes.Imagen, opcionesoferta.Precio, opcionesoferta.Opcion, opcionesoferta.OptActiva, carritocompra.Talla 
												FROM carritocompra ";
										$SQL .= "INNER JOIN productos ON carritocompra.IdProducto = productos.IdOferta ";
										$SQL .= "INNER JOIN opcionesoferta ON carritocompra.Talla = opcionesoferta.Id ";
										$SQL .= "LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1 ";
										$SQL .= "WHERE carritocompra.IdCarrito = '$cliente'";
										$db->setQuery($SQL);
										$row = $db->execute();
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
												echo '
		                                    	<tr style="background:#fff; height: 45px">
													<td width="30px" align="center"><a id="deleteMod" href="update-amount.inc.php?producto='.$result1->IdOferta.'&talla='.$result1->Talla.'&opcion=delete&cliente='.$cliente.'">&nbsp;</a></td>
        		                                	<td width="70px"><img src="'.$result1->BaseUrl . $result1->Imagen.'" width="50px" style="border: 1px solid #e5e5e5" /></td>
                		           		            <td width="270px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
                        		           		    <td width="140px"><label>'.utf8_encode($result1->Opcion).'</label></td>
	                            		            <td width="80px">
														<a id="up" href="update-amount.inc.php?producto='.$result1->IdOferta.'&talla='.$result1->Talla.'&opcion=down&cliente='.$cliente.'"><img src="images/quantity_down.gif"/></a>&nbsp;&nbsp;
														<label>'.$result1->Cantidad.'</label>&nbsp;&nbsp;
														<a id="down" href="update-amount.inc.php?producto='.$result1->IdOferta.'&talla='.$result1->Talla.'&opcion=up&cliente='.$cliente.'"><img src="images/quantity_up.gif"/></a>
													</td>
                                        		    <td><label>'.$result1->Cantidad * $result1->Precio.'</label></td>
		                                        </tr>';
											} 
										} else {
											echo '
											<tr>
												<td height="55px" style="background: white; border: 1px solid #CCC;"><label>Aun no tienes productos para a&ntilde;adir...</label></td>
											</tr>
											';
										}
										?>
                                    </table>                                	
                                </td>
                            </tr>
                        </table>
                        
                        <table class="modific_3" style="width: 490px; float: right">
                        	<tr>
                            	<td><span id="title">Selecci&oacute;n de Producto</span></td>
                            </tr>
                            <tr>
                            	<td>
                                	<table style="width: 490px; background: #F5F5F5; padding: 30px; border: 1px solid #D9DCDE" cellpadding="0" cellspacing="0">
                                    	<form id="formSelectProductMod" name="formSelectProductMod" method="post" action="add-to-car-mod.inc.php?idbd=" . <?php echo base64_decode($_GET['idbd']); ?> enctype="application/x-www-form-urlencoded">                                      	
                                    	<tr>
                                            <td height="28px">
                                            	<div style="position:static; width: 400px; margin: 0 auto; height: 28px">
                                            	<!--<label style="font-variant: small-caps !important; font-size: 14px !important">Seleccione Producto : &nbsp;&nbsp;</label>-->
                                                	<div style="position: absolute; z-index: 999999">
	            	                                <select id="selectProduct" name="selectProduct" data-placeholder="Listado de Productos..." class="chosen-select" style="width: 400px;">
    	            	                               	<option value=""></option>
        	            	                                <?php															
															$sql = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id FROM productos ";
															$sql .= "INNER JOIN opcionesoferta ON productos.IdOferta = opcionesoferta.IdOpcion ";
															$sql .= "AND productos.Gestion = 1 GROUP BY opcionesoferta.IdOpcion ORDER BY IdOferta DESC";
															$db->setQuery($sql);
															$result = $db->loadObjectList();
															foreach($result as $result1) {
																echo '<option value="'.$result1->IdOferta.'|'.$result1->Id.'|'.$cliente.'|'.$country.'">'.utf8_encode(($result1->Nombre_Producto)).'</option>';
															}
															$db->freeResults();
															?>
		                                            </select>                                       
                                                    </div>         
                                              	</div>
                                            </td>
                                        </tr>
                                        <tr>
                	                      	 <td id="option" style="height: 0px"></td>
                    	                </tr>
                                        <tr>
                                        	<td colspan="2" align="center" style="height: 35px;">
                                       			<input type="submit" id="" name="" value="A&Ntilde;ADIR PRODUCTO" <?php if ($estadoPedido == 'Anulado' || $estadoPedido == 'Enviado') { echo 'disabled="disabled" style="background: #CCC"'; } ?> />                                                
                                            </td>
                                        </tr>
                                        </form>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                            	<td>&nbsp;</td>
                            </tr>
                            <tr>
                            	<td align="center">
                                	<?php
										if ($estadoPedido == 'Anulado' || $estadoPedido == 'Enviado') {
											echo '<label style="color: red">Este pedido no se puede modificar.</label>';
										} else { ?>
    		                            	<input type="button" id="" name="" value="CONFIRMAR EDICION" style="width:300px" onclick="location.href='update-mod-orden.inc.php?orden_cliente=<?php echo base64_encode($orden_cliente); ?>'" />
                                     <?php
                                        }
                                    ?>                                   
                                </td>
                            </tr>
                            <tr height="50px">
                            	<td align="center"><input type="button" value="Cerrar" onclick="javascript:window.close()" /></td>
                            </tr>
                        </table>
                    </div>
                    <!-- CAMBIAR ESTADO DE LOS PEDIDOS
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
    <!--<div id="push"></div>   -->
</div>

<!--
<div id="footer" class="footer"> 
	<?php //include('footer.inc.php'); ?>        	
</div>
-->

</html>
<?php } ?>