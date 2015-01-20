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
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
		<title>ADMIN WEB SERVICE</title>
        <script type="text/javascript" src="js/jquery.tools.min.js"></script>
        <!--<script type="text/javascript" src="js/jquery.jcookie.min.js"></script>
        <script type="text/javascript" src="js/jquery-class.js"></script>-->
        <script type="text/javascript" src="js/validar-num.js"></script>
        <script type="text/javascript">
			$(document).ready(function(){
    			$('#importD').numeric();
			    $('#decimal').numeric(","); 
			});
		</script>    
	</head>
<body>
</body>
<div id="wrapper" class="wrapper">

	<?php
		$active2 = 1;
		include('config.inc.php');
		require_once('conexion/conexion.inc.php');
		$db = DataBase::getInstance();
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
    
    <!-- Tooltip -->
    <div class="tooltip_customer"></div>
    <!-- fin -->
    
    <div id="content-body">
    	<div id="general" class="content">
        	<div id="Contenido" style="padding-top: 20px; overflow: auto; overflow-x:hidden">  
            	<div style="position: absolute; top: 0px; left: 990px; z-index:9999999"><img src="images/clientes.png" /></div> 
            	<ul class="tabs">
    		    	<li class="active"><a href="#tab1"><span>Generar Devolución</span></a></li>
		   			<!--<li><a href="#tab2">Anular Pedidos</a></li>-->
   		  	        <!--<li><a href="#tab3">Productos</a></li>-->                
		  	    </ul>
        		
                <div class="tab_container">                	                    
                    <!-- MODIFICAR EL PEDIDO DEL CLIENTE -->
                    <div id="tab1" class="tab_content">
                    	<table class="modific_1">
                        	<tr>
                            	<td><span>Orden N&uacute;mero:</span>&nbsp;&nbsp;<strong><?php echo base64_decode($_GET['orden']); ?></strong></td>
                            </tr>                                                                               
                            <?php
							if ($_GET['response'] == 1) {
								echo '
	                            <tr>
    	                        	<td>
        	                        	<h4 class="opt"><img src="images/ok.png" style="float: left" />&nbsp;&nbsp;&nbspDevolución insertada correctamente.</h4>
            	                    </td>
                	            </tr>';
							}
							?>                            
						</table>
                            
		                <div style="width: 740px; margin: 0 auto; background: #F5F5F5; padding: 30px; border: 1px solid #D9DCDE">
       		            	<fieldset>                                         
                            	<?php
									$SQL = "SELECT * FROM relordendireccion ";
									$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion ";
									$SQL .= "WHERE relordendireccion.IdOrden = '".base64_decode($_GET['orden'])."'";					
									$db->setQuery($SQL);
									$result = $db->loadObject();
									echo '						
		                                <table id="address">
    		                            	<tr>
        		                            	<td align="left">
			    	                            	<label for="nombres">Nombres :<strong class="obligado">*</strong></label><br />
        				                            <input type="text" name="name_payment" style="width: 200px" value="'.utf8_encode($result->D_Nombres).'" required="required" autocomplete="off" readonly="readonly" />
                    		                    </td>
                        		                <td>
                            		            	<label for="apellidos">Apellidos :</label><strong class="obligado">*</strong><br />
                                		            <input type="text" id="last_name_payment" name="last_name_payment" style="width: 200px" value="'.utf8_encode($result->D_Apellidos).'" autocomplete="off" readonly="readonly" />
                                    		    </td>   
												<td>
                                                	<label for="telefono">Tel&eacute;fono :</label><strong class="obligado">*</strong><br />
                                                    <input type="text" id="phone_payment" name="phone_payment" style="width: 200px;" value="'.$result->Telefono.'" autocomplete="off" readonly="readonly"/>
                                                </td>                                             
	                                    	</tr>                                                                                                                                  
	    	                            </table>';
								?>
                        	</fieldset>
                            	<form id="formId" name="formInsertDevolucion" method="post" action="generar-devolucion.inc.php?idbd=" . <?php echo base64_decode($_GET['idbd']); ?> accept-charset="utf-8" onSubmit="return formSubmit(formId);" enctype="application/x-www-form-urlencoded">
                        			<fieldset>
                                    	<table cellpadding="1" cellspacing="1">	
	                                    	<caption style="height: 50px; line-height: 50px; color: #f00; font-size: 17px; font-variant: small-caps">Productos Comprados</caption>                                 
    	                                    <thead>
        	                                	<th><label style="color: #f00">SubTotal</label></th>    
                                                <th><label style="color: #f00">SubTotal<br />+Envío</label></th>          
                                                <th style="height: 30px; border-bottom: 1px solid #ccc;"><label style="color: #F00; font-weight: 100">Cant</label></th>                                         	
                                                <th style="height: 30px; border-bottom: 1px solid #ccc;"><label style="color: #F00; font-weight: 100">Cobrado</label></th> 
            	                            	<th style="height: 30px; border-bottom: 1px solid #ccc;"><label style="color: #F00; ">Descripción</label></th>                                         	
                	                            <th style="height: 30px; border-bottom: 1px solid #ccc;"><label style="color: #F00; ">Tipo</label></th>
                    	                        <th style="height: 30px; border-bottom: 1px solid #ccc;"><label style="color: #F00; ">Precio</label></th>
                        	                    <th style="height: 30px; border-bottom: 1px solid #ccc;"><label style="color: #F00; ">Cant</label></th>                                            
                            	                <th width="60px" style="height: 30px; border-bottom: 1px solid #ccc;"><label style="color: #F00; ">SubTotal</label></th>
                                                <th width="50px" style="height: 30px; border-bottom: 1px solid #ccc;"><label style="color: #F00; ">Envío</label></th>
                                	        </thead>
                                    	    <tbody>
                                        		<?php
													$SQL = "SELECT ordenes.*, lineasorden.*, productos.*, opcionesoferta.Id AS Tipo, opcionesoferta.Opcion, opcionesoferta.Precio FROM ordenes ";
													$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
													$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
													$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
													$SQL .= "WHERE ordenes.IdOrden = '".base64_decode($_GET['orden'])."'";						
													$db->setQuery($SQL);
													$result = $db->loadObjectList();
													foreach($result as $result1) {										
														$varSubtotal = $result1->Id.'|'.$result1->IdOferta.'|'.$result1->Tipo.'|'.$result1->Cantidad.'|0|'.$result1->Subtotal;	
														$varSubGast = $result1->Id.'|'.$result1->IdOferta.'|'.$result1->Tipo.'|'.$result1->Cantidad.'|'.$result1->GastosEnvio.'|'.$result1->Subtotal;															
														echo '											
			                	                       	<tr height=" 30px" bgcolor="#FFF">
															<!--<input type="hidden" name="idProducto[]" value="'.$result1->IdOferta.'" />
															<input type="hidden" name="talla[]" value="'.$result1->tipo.'" />
															<input type="hidden" name="subtotal[]" value="'.$result1->Subtotal.'" />-->
															
															<td>																
																<input type="radio" name="sel_'.$result1->Id.'" value="'.$varSubtotal.'"'; 
																if($result1->EstadoPedido == 'Devuelto' || $result1->EstadoPedido == 'No-entregado' || $result1->EstadoPedido == 'Anulado') echo 'disabled="disabled"'; echo '/>
															</td>
															<td>
																<input type="radio" name="sel_'.$result1->Id.'" value="'.$varSubGast.'"';
																if($result1->EstadoPedido == 'Devuelto' || $result1->EstadoPedido == 'No-entregado' || $result1->EstadoPedido == 'Anulado') echo 'disabled="disabled"'; echo '/>
															</td>
															<td>
																<input type="text" id="unidadesDev" name="und_'.$result1->Id.'" value="'.$result1->Cantidad.'" style="width:30px; text-align: center;" autocomplete="off" readonly="readonly" />
															</td>
															<td bgcolor="#71BA00">
																<strong>'.number_format(($result1->Subtotal + $result1->GastosEnvio),2,',','.').' EUR</strong>
															</td>
        			                	                   	<td><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
            	    		            	                <td><label>'.utf8_encode($result1->Opcion).'</label></td>
															<td><label>'.number_format($result1->Precio,2,',','.').' &euro;</label></td>
                	        		            	        <td>
																<label>'.number_format($result1->Cantidad,0,',','.').'</label>
																<input type="hidden" name="cantidadValidar" value="'.$result1->Cantidad.'" />
															</td>
                    	            		        	    <td bgcolor="#71BA00"><label>'.number_format($result1->Precio,2,',','.').' &euro;</label></td>
															<td bgcolor="#71BA00"><label>'.number_format($result1->GastosEnvio,2,',','.').' &euro;</label></td>
	                        	                	    </tr>';
														$subTotal = $subTotal + $result1->Subtotal;
														$gastosEnvio = $gastosEnvio + $result1->GastosEnvio;															
																																		
													}													
													echo '
														<tr>
															<td colspan="8" height="45px" align="left">
																<label style="color: red; font-size: 12px; line-height: 18px">
																	Marque la casilla que corresponda, si se abona importe de producto y gastos de envío marque las 2 casillas. En el cuadro unidades se tendra que poner el
																	número de productos a abonar, por defecto es 1, NUNCA tiene que ser cero.
																</label>
															</td>
														</tr>';
												?>
                    	                    </tbody>                                    	                                    
                        	            </table>
                        			</fieldset>   
                                     
                                	<fieldset style="margin-top: 30px">
                                		<?php
											$ord = base64_decode($_GET['orden']);
											$SQL = "SELECT COALESCE(SUM(lineasdevolucion.Subtotal),0) as Devolucion, COALESCE(SUM(lineasdevolucion.GastosEnvio),0) AS TGastos FROM lineasdevolucion WHERE lineasdevolucion.IdOrden = '$ord'";
											$db->setQuery($SQL);										
											$resultDev = $db->loadObject();
																						
											//$devol1 = $result->ImporteDevol;													
											/*$SQL = "SELECT lineasorden.Subtotal, lineasorden.LineaImporte FROM lineasorden WHERE IdOrden = '".base64_decode($_GET['orden'])."' AND (EstadoPedido = 'Devuelto' OR EstadoPedido = 'No-entregado')";
											$db->setQuery($SQL);										
											$resultDev = $db->loadObjectList();
											foreach($resultDev as $resultDev1) {
												if($resultDev1->LineaImporte > 0) {
													$lineaImport = $lineaImport + ($resultDev1->Subtotal - $resultDev1->LineaImporte);
												}
											}*/
																				
											$devolucionesSubtotal = $resultDev->Devolucion;
											$devolucionesGastos = $resultDev->TGastos;
											
											echo '=>'.$devolucionesSubtotal;
										?>
                                	
                                    	<input type="hidden" id="ordenDev" name="ordenDev" value="<?php echo base64_decode($_GET['orden']); ?>" />
	                                	<table>
                                        	<tr align="left" height="20px">
        	                                	<td><label>Estado Pedido : &nbsp;&nbsp;</label></td>
            	                                <td><label><?php echo $result1->EstadoPedido; ?></label></td>
                	                        </tr>
                                        	<tr align="left" height="20px">
        	                                	<td><label>Forma de Pago : &nbsp;&nbsp;</label></td>
            	                                <td>
                                                	<label style="display: block; width: 130px; line-height: 25px;">
														<?php 
															if ($result1->FormaPago <> '--' && $result1->EstadoPago == 'ok')
																echo $result1->FormaPago . '&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/icon_ok.png" title="Pago Correcto" style="float: right;" />'; 
															else
																echo $result1->FormaPago . '&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/icon_error.png" title="Error de Pago" />'; 
														?>
                                                    </label>
                                                </td>
                	                        </tr>
    	                                	<tr align="left" height="20px">
        	                                	<td><label>SubTotal : &nbsp;&nbsp;</label></td>
            	                                <td><label><?php echo number_format($subTotal,2,',','.') .' + ' .number_format($gastosEnvio,2,',','.'); ?></label></td>
                	                        </tr>
                    	                	<tr align="left" height="20px">
                        	                	<td><label>Pago Original : &nbsp;&nbsp;</label></td>
                            	                <td><label><?php echo number_format(($subTotal + $gastosEnvio),2,',','.'); ?> EUR</label></td>
                                	        </tr>
                                    	    <tr align="left" height="20px">
                                        		<td><label>Devoluciones Anteriores : &nbsp;&nbsp;</label></td>
                                            	<td>
                                                	<label style="color: #F00"><?php echo number_format($devolucionesSubtotal,2,',','.') .' + ' . number_format($devolucionesGastos,2,',','.') ; ?> EUR</label>
                                                    <input type="hidden" id="devolAnt" name="devolAnt" value="<?php echo $devoluciones; ?>" />
                                                </td>
	                                        </tr>
                                            <!--
    	                                	<tr align="left" height="30px">
        	                                	<td><label>Importe Máximo a Devolver : &nbsp;&nbsp;</label></td>
            	                                <td>
                                                	<input type="text" id=" Dev" name="importeDev" style="width: 70px" data-id="<?php echo $subTotal-$devoluciones; ?>" value="<?php echo $subTotal + $gastosEnvio - $devoluciones; ?>" autocomplete="off" />
                                                </td>                                            
                	                        </tr>
                                            -->
                                            <tr align="left">
                                            	<td><label>Seleccione estado :</label></td>
                                            	<td>
                                                	<select id="estate" name="estate" style="width: 130px; border: 1px solid #ccc">
                                                    	<option value="Devuelto">Devolución</option>
                                                        <option value="No-entregado">No Entregado</option>
                                                        <?php if(trim($result1->EstadoPedido) == 'Transito'): ?>
	                                                        <option value="Anulado">Anular</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </td>
                                            </tr>
                    	                    <tr align="left" height="30px">
                        	                	<td><label>Nota Aclaratoria: &nbsp;&nbsp; </label></td>
                            	                <td><input id="nota" name="nota" type="text" style="width: 530px" placeholder="Motivo de devolución"  autocomplete="off"/></td>
                                	        </tr>                                            
                                    	    <tr height="50px">
                                        		<td colspan="2" align="center">
                                                	<?php													
														if ($result1->FormaPago <> '--') 
															echo '<input type="submit" id="submitDev" name="" value="GENERAR DEVOLUCIÓN" style="width:300px"/>';																										
													?>
	                                            </td>
    	                                    </tr>
                                            <tr height="50px">
                            					<td align="center"><input type="button" value="Cerrar" onclick="javascript:window.close()" /></td>
				                            </tr>                                            
        	                            </table>                                   
                                	</fieldset>   
                                </form>
                                <fieldset>
                                	
                                </fieldset>            
                        </div>                    	                                               	
                       
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
   <!-- <div id="push"></div>   -->
</div>

<!--<div id="footer" class="footer"> 
	<?php //include('footer.inc.php'); ?>        	
</div>
-->
</html>
<?php } ?>