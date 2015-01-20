<?php
	require_once("config.inc.php");
	require_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
?>
<html>
	<head>
    	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    	<link href="css/stylos-pag.css" type="text/css" rel="stylesheet" />
    	<style type="text/css">
			* { margin: 0px; padding: 0px; }
			label { font-weight: 100; padding: 4px 5px; color: #626262; }
			table { text-align: left;}
		</style>
        <script type="text/javascript" src="js/jquery.tools.min.js"></script>
        <script type="text/javascript" src="js/jquery-class.js"></script>
	</head>
<body bgcolor="#FFFFFF" style="width: 600px; margin: 0 auto">
	<table cellspacing="0" border="0" cellpadding="0" style="width:100%; background-color: #EBEBEB; margin: 20px auto;">
	   	<tr>
    		<td>
	           	<table style="width:530px; margin: 0 auto" height="auto" cellpadding="0" cellspacing="0" bgcolor="EBEBEB" hspace="100px" vspace="100px" style="padding:30px">
    		    	<tr>
          				<td align="center"><h1 style="font-size: 35px; font-variant: small-caps; color: #033; margin-bottom: 0px; margin-top: 20px"><?php echo $nombre; ?></h1></td>
	                </tr>
    		        <tr>
           				<td><strong style="color: #516470; display: block; padding-top: 10px; padding-bottom: 10px; font-size: 18px; font-variant: small-caps">Resumen del Pedido</strong></td>
	                </tr>
    		        
    		        <tr>
           				<td>&nbsp;&nbsp;</td>
	                </tr>
    		    </table>
		    </td>
		 </tr>
		 <tr>
         	<td>
				<?php
					$orden = $_GET['idorden'];
					$SQL = "SELECT ordenes.*, SUM(lineasorden.Cantidad) AS totalProductos, direcciones.*, lineasorden.*, relordendireccion.IdCliente  FROM ordenes ";
					$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
					$SQL .= "INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden ";
					$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion ";					
					$SQL .= "WHERE ordenes.IdOrden = '".$orden."'";
					$db->setQuery($SQL);							
					$user = $db->loadObject();
					
					$_key = '|elpaisseleccion_';
					$_user = sha1(($user->Email).$_key.($user->Contrasena));
					$_idUser = base64_encode($user->IdCliente);
					$_order = base64_encode($orden);
							
					echo '
			           	<table style="width:550px; margin: 0 auto" height="auto" cellpadding="10" cellspacing="1" bgcolor="#EBEBEB" style="padding: 20px">
			               	<tr>
			                   	<td bgcolor="#FFF" width="200px">
			                   		<label style="display:block; color: #79858C; font-variant: small-caps; font-size: 15px">N&uacute;mero de Orden</label>
			                    </td>
            		            <td bgcolor="#FFF">
                       				<label style="display:block; font-variant: small-caps; font-size: 19px; font-weight: bold; color:#099">'.$user->IdOrden.$tpv_orden.'</label>
			                    </td>                        
            		        </tr>
			                <tr>
            		        	<td bgcolor="#FFF">
                       				<label style="display:block; color: #79858C; font-variant: small-caps; font-size: 15px">Nombres</label>
			                    </td>
            		            <td bgcolor="#FFF">
                       				<label style="display:block; color: #79858C; font-size: 12px">'.utf8_encode(ucwords(strtolower($user->D_Nombres))).'  '.utf8_encode(ucwords(strtolower($user->D_Apellidos))).'</label>
			                     </td>                        
            		        </tr>							
							<tr>
            		        	<td bgcolor="#FFF">
                       				<label style="display:block; color: #79858C;  font-size: 12px">Tel&eacute;fono</label>
			                    </td>
            		            <td bgcolor="#FFF">
                       				<label style="display:block; color: #79858C;  font-size: 12px">'.utf8_encode($user->Telefono).'</label>
			                     </td>                        
            		        </tr>
							<tr>
            		        	<td bgcolor="#FFF">
                       				<label style="display:block; color: #79858C;  font-size: 12px">Direccion</label>
			                    </td>
            		            <td bgcolor="#FFF">
                       				<label style="display:block; color: #79858C;  font-size: 12px">
										'.utf8_encode($user->TipoVia).'  '.utf8_encode(ucwords(strtolower($user->Direccion))).' '.utf8_encode($user->TipoNumero).' '.utf8_encode($user->Numero).' '.utf8_encode($user->Piso).' '.utf8_encode($user->Puerta).'
									</label>
			                     </td>                        
            		        </tr>		
                    		<tr>
			                   	<td bgcolor="#FFF">
            			           	<label style="display:block; color: #79858C;  font-size: 12px">C&oacute;digo Postal</label>
                        		</td>
			                    <td bgcolor="#FFF">
            			           	<label style="display:block; color: #79858C;  font-size: 12px">'.utf8_encode(str_pad($user->Cp, 5, "0", STR_PAD_LEFT)).'</label>
			                    </td>                        
            			     </tr>
			                 <tr>
            			       	<td bgcolor="#FFF">
                        			<label style="display:block; color: #79858C;  font-size: 12px">Ciudad y Provincia</label>
			                    </td>
            			        <td bgcolor="#FFF">
                        			<label style="display:block; color: #79858C;  font-size: 12px">'.utf8_encode(ucwords(strtolower($user->Poblacion))).' - ' .utf8_encode($user->Provincia). '</label>
			                    </td>      	                  
            			    </tr>
							<tr>
            		       		<td bgcolor="#FFF">
                    				<label style="display:block; color: #79858C;  font-size: 12px">Fecha de Compra</label>
			                	</td>
            		        	<td bgcolor="#FFF">
                    				<label style="display:block; color: #79858C;  font-size: 12px">'.$user->FechaOrden.' &nbsp;||&nbsp; ' .$user->Hora.'</label>
			                	</td>                        
            		    	</tr>
			                    <tr>
            			        	<td bgcolor="#FFF">
                        				<label style="display:block; color: #79858C;  font-size: 12px">Estado Pedido</label>
			                        </td>
            			            <td bgcolor="#FFF">
                        				<label style="display:block; color: #79858C;  font-size: 12px">'.$user->EstadoPedido.'</label>
			                        </td>                        
            			        </tr>
			                    <tr>
            			        	<td bgcolor="#FFF">
                        				<label style="display:block; color: #79858C;  font-size: 12px">Le Atendi&oacute;</label>
			                        </td>
            			            <td bgcolor="#FFF">
                        				<label style="display:block; color: #79858C;  font-size: 12px; text-transform: capitalize">'.$user->Tramitado.'</label>
			                        </td>                        
            			        </tr>
								<tr>
            			        	<td bgcolor="#FFF">
                        				<label style="display:block; color: #79858C;  font-size: 12px">Forma de Pago</label>
			                        </td>
            			            <td bgcolor="#FFF">
                        				<label style="display:block; color: #79858C;  font-size: 12px; text-transform: capitalize">'.$user->FormaPago.'</label>
			                        </td>                        
            			        </tr>
			            </table>';
					?>
			        </td>
			    </tr>
					
        		<tr>
			       	<td>
            			<table style="width:530px; margin: 0 auto" height="auto" cellpadding="2" cellspacing="1" bgcolor="EBEBEB" hspace="100px" vspace="100px" style="padding:30px">
			               	<tr>
            		        	<td>
			           				<strong style="color: #516470; display: block; padding-top: 10px; padding-bottom: 10px; font-size: 18px; font-variant: small-caps">Productos</strong>
			                    </td>
				            </tr>
                            <tr>
                            	<td style="width:50px"><label>Estado</label></td>
                                <td><label></label></td>
                                <td><label>Producto</label></td>
                                <td><label>Tipo</label></td>
                                <td><label>#</label></td>
                                <td><label>Precio</label></td>
                                <td><label>Subtotal</label></td>
                            </tr>
							<?php
								$SQL ="SELECT ordenes.*, lineasorden.*, productos.*, opcionesoferta.Opcion, opcionesoferta.OptActiva, opcionesoferta.Precio, imagenes.BaseUrl, imagenes.Imagen FROM ordenes ";
								$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
								$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
								$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
								$SQL .= "LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1 ";
								$SQL .= "WHERE ordenes.IdOrden = '".$orden."'";
								$db->setQuery($SQL);
								$row = $db->execute();
								if (mysqli_num_rows($row) > 0) {
									$result = $db->loadObjectList();
									foreach($result as $result1) {
								    
									echo '
        	        			    <tr>
										<td bgcolor="#FFF" align="center" style="width:10px">';
										if ($result1->EstadoPedido == 'Transito') { echo '<img src="images/icon_transito.png" title="'.$result1->EstadoPedido.'" />'; }
										else if ($result1->EstadoPedido == 'Enviado') { echo '<img src="images/icon_enviado.png" title="'.$result1->EstadoPedido.'" />'; }
										else if ($result1->EstadoPedido == 'No-entregado') { echo '<img src="images/icon_noentregado.png" width="24px" title="'.$result1->EstadoPedido.'" />'; }
										else if ($result1->EstadoPedido == 'Entregado') { echo '<img src="images/icon_entregado.png" width="24px" title="'.$result1->EstadoPedido.'" />'; }
										else if ($result1->EstadoPedido == 'Anulado') { echo '<img src="images/icon_anulado.png" width="24px" title="'.$result1->EstadoPedido.'" />'; }
										else if ($result1->EstadoPedido == 'Devuelto') { echo '<img src="images/icon_devolucion.png" title="'.$result1->EstadoPedido.'" />'; }
										echo '
										</td>
				                   		<td bgcolor="#FFF" align="center" width="90px"><img src="'.$result1->BaseUrl . $result1->Imagen.'" width="80px" height="40px" /></td>
            				            <td bgcolor="#FFF" width="360px"><label style="display:block; color: #79858C;  font-size: 11px">'.utf8_encode($result1->Nombre_Producto).'</label></td>
										<td bgcolor="#FFF" width="100px" align="center" style="display:block; color: #79858C;  font-size: 11px">';
										if ($result1->OptActiva == 1) {
											echo utf8_encode($result1->Opcion);
										}
										echo '
										</label>
			        	                <td bgcolor="#FFF" width="50px" align="center"><label style="display:block; color: #79858C;  font-size: 11px">'.$result1->Cantidad.'</label></td>
            				            <td bgcolor="#FFF" width="80px" align="center"><label style="display:block; color: #79858C;  font-size: 11px">'.$result1->Precio.'&euro;</label></td>
										<td bgcolor="#FFF" width="80px" align="center"><label style="display:block; color: #79858C;  font-size: 11px">'.$result1->Subtotal.'&euro;</label></td>
			                	   </tr>';
								   $subTotal = $subTotal + $result1->Subtotal;
								   $gastosEnvio = $gastosEnvio + $result1->GastosEnvio;									   							   
									}								   
									$TOTAL = $subTotal + $gastosEnvio;
								}
							?>
				         </table>
				     </td>            
				 </tr>    

	          		<tr>
						<td>
							<table style="width:530px; margin: 0 auto" height="auto" cellpadding="2" cellspacing="1" bgcolor="EBEBEB" hspace="100px" vspace="100px" style="padding:30px">
								<tr>
									<td colspan="3" align="right" style="border-bottom: 1px solid #CCC">
										<label style="display:block; color: #79858C; font-variant: small-caps; font-size: 12px">Subtotal</label>
									</td>
				                    <td width="80px" align="center" style="border-bottom: 1px solid #CCC">
										<label style="display:block; color: #79858C; font-variant: small-caps; font-size: 12px"><?php echo $subTotal; ?>&euro;</label>
									</td> 
								</tr>
								<tr>
									<td colspan="3" align="right" style="border-bottom: 1px solid #CCC">
										<label style="display:block; color: #79858C; font-variant: small-caps; font-size: 12px">Gastos Env&iacute;o</label>
									</td>
                        			<td align="center" style="border-bottom: 1px solid #CCC">
										<label style="display:block; color: #79858C; font-variant: small-caps; font-size: 12px"><?php echo $gastosEnvio; ?>&euro;</label>
									</td>
								</tr>
								<tr>
									<td colspan="3" align="right" style="border-bottom: 1px solid #CCC">
										<label style="display:block; color: #79858C; font-variant: small-caps; font-size: 12px">Importe Total</label>
									</td>
                        			<td align="center" style="border-bottom: 1px solid #CCC">
										<label style="display:block; color: #09C; font-variant: small-caps; font-size: 15px; font-weight: bold"><?php echo $TOTAL; ?>&euro;</label>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				    <tr>
        				<td>&nbsp;
			        	   	
				        </td>
				    </tr>   
                    <tr>
                    	<td align="center">
                        	<input type="button" value="Imprimir" onClick="print()" style="width: 150px; ">
                        </td>
                    </tr> 
                    <tr><td>&nbsp;</td></tr>
				</table>   
	</body>
</html>