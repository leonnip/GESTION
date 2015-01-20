<?php
	session_start();
	$orden = $_SESSION['__Orden__'];
	unset($_SESSION['__Orden__']);
	
	$arregloOrd = explode('|', $orden);
	$orden = $arregloOrd[0];
	
	include("config.inc.php");
	
	/*RECOMGEMOS LA VARIABLES POST O GET*/
	/*CONTRA-REMBOLSO*/		$Signature = $_POST['Ds_Merchant_MerchantSignature'];
	/*VISA-MASTERCARD*/		$pago_tar = $_GET['visa'];
	/*CLAVE */				$key = "|elpaisseleccion_";
	
	if (isset($_GET['visa'])) {
		/*VALIDAR PAGO VISA MASTERCAD*/	
		$Signature_Validate = $_GET['visa'];
		$valid_tar = base64_decode($_GET['ok_ko']);	
		$Signature = sha1($valid_tar.$key);
	} else if (isset($_POST['Ds_Merchant_MerchantSignature'])) {
		$Signature_Validate = sha1($Message);
		$Signature = $_POST['Ds_Merchant_MerchantSignature'];
	}
	
	if ((isset($_POST['Ds_Merchant_TPago'])) && ($_POST['Ds_Merchant_TPago'] == 'contra')) {
		$imagen = '<img src="images/tick.png" />';
		$texto = 'Operaci&oacute;n Realizada Correctamente';
		//ELIMINAMOS LAS COOKIES DEL FORMULARIO DE REGISTRO
		setcookie('name_payment','', time()-1); setcookie('last_name_payment','', time()-1); setcookie('dni_payment','', time()-1); setcookie('phone_payment','', time()-1);
		setcookie('email_payment','', time()-1); setcookie('address_payment','', time()-1); setcookie('number_payment','', time()-1); setcookie('piso_payment','', time()-1);
		setcookie('door_payment','', time()-1); setcookie('cp_payment','', time()-1); setcookie('city_payment','', time()-1); setcookie('message_payment','', time()-1);
	} else if (isset($_GET['visa'])) {
		if (base64_decode($_GET['ok_ko']) == 'tar_ok') {
			$imagen = '<img src="images/tick.png" />';
			$texto = 'Operaci&oacute;n Realizada Correctamente';			
		} else {
			$imagen = '<img src="images/error.png" />';
			$texto = 'Error al Realizar la Transacci&oacute;n';
		}
	}
	
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
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.validate.js"></script>
        <script type="text/javascript" src="js/jquery-class.js"></script>
	</head>
<body>
</body>
<div id="wrapper" class="wrapper">

    <div id="TopBar" class="iluminacion">	
        <?php require('menu.inc.php'); ?>
    </div> 
    
    <div id="content-body">
    	<div id="general" class="content">
        	<div id="transaccion">
            	<table>
                	<tr>
                    	<td><label id="title">Resultado de Transacci&oacute;n</label></td>
                    </tr>
                	<tr>
                    	<td><?php echo $imagen; ?></td>
                    </tr>
                    <tr>
                    	<td><strong><?php echo $texto; ?></strong></td>
                    </tr>
                    <tr>
                    	<td style="background: #f2f4f7;">                        	
                        	<?php							
							$SQL = "SELECT ordenes.*, SUM(lineasorden.Cantidad) AS totalProductos, direcciones.*, relordendireccion.IdCliente  FROM ordenes ";
							$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
							$SQL .= "INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden ";
							$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion ";
							$SQL .= "WHERE ordenes.IdOrden = '".$orden."'";
							$db->setQuery($SQL);							
							$user = $db->loadObject();
							echo '
    	                    	<table id="detalles" cellpadding="0" cellspacing="1">
        	                    	<tr>
            	                    	<td colspan="2"><label id="resumen">Detalles de la Orden</label></td>
                	                </tr>
                    	        	<tr style="height: 40px">
                        	        	<td style="width: 200px"><label class="font">N&uacute;mero de Orden</label></td>
                            	        <td><label id="orden">'.$user->IdOrden.'</label></td>
                                	</tr>
    	                            <tr>
	    	                           	<td><label class="font">Nombres y Apellidos</label></td>
            	                        <td><label class="font">'.utf8_encode($user->D_Nombres).' '.utf8_encode($user->D_Apellidos).'</label></td>
                	                </tr>
                    	            <tr>
                        	        	<td><label class="font">Fecha De Compra</label></td>
                            	        <td><label class="font">'.$user->FechaOrden.'</label></td>
                                	</tr>
	                                <tr>
    	                            	<td><label class="font">Hora Aproximada</label></td>
        	                            <td><label class="font">'.$user->Hora.'</label></td>
            	                    </tr>
                	                <tr>
                    	            	<td><label class="font">Cantidad de Productos</label></td>
                        	            <td><label class="font">'.$user->totalProductos.'</label></td>
                            	    </tr>
                                	<tr>
                                		<td><label class="font">Importe Total</label></td>
	                                    <td><label class="font">'.$user->Total.'&euro;</label></td>
    	                            </tr>
        	                        <tr>
            	                    	<td><label class="font">Forma de Pago</label></td>
                	                    <td><label class="font">'.$user->FormaPago.'</label></td>
                    	            </tr>
                        	    </table>
							';
							?>
                        </td>
                    </tr>
                    <tr>
                    	<td style="background: #f2f4f7;">
                        	<table id="detalles">
                            	<thead>
                                	<th></th>
                                    <th><label>Productos</label></th>
                                    <th><label>Tipo</label></th>
                                    <th><label>#</label></th>
                                    <th><label>Subtotal</label></th>
                                </thead>
                                	<?php
                                	$SQL ="SELECT ordenes.*, lineasorden.*, productos.*, opcionesoferta.Opcion, opcionesoferta.Precio, imagenes.BaseUrl, imagenes.Imagen FROM ordenes ";
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
						                   		<td bgcolor="#FFF" align="center" width="80px"><img src="'.$result1->BaseUrl . $result1->Imagen.'" width="80px" height="40px" /></td>
        		    				            <td bgcolor="#FFF" width="360px"><label style="display:block; color: #79858C;">'.utf8_encode($result1->Nombre_Producto).'</label></td>
												<td bgcolor="#FFF" width="360px">
													<label style="display:block; color: #79858C;">'.utf8_encode($result1->Opcion).'</label>
												</td>
					        	                <td bgcolor="#FFF" width="50px" align="center"><label style="display:block; color: #79858C;">'.$result1->Cantidad.'</label></td>
            						            <td bgcolor="#FFF" width="80px" align="center"><label style="display:block; color: #79858C;">'.$result1->Subtotal.'&euro;</label></td>
			        		        	   </tr>';
										   $Subtotal = $Subtotal + $result1->Subtotal;
										   $gastosEnvio = $gastosEnvio + $result1->GastosEnvio;
										}
										echo '
											<tr>
												<td colspan="3" align="right"><label>Subtotal &nbsp;</label></td><td>&rarr;</td>
												<td bgcolor="#fff" align="center"><label>'.$Subtotal.' &euro;</label></td>
											</tr>
											<tr>
												<td colspan="3" align="right"><label>Gastos de Envío &nbsp;</label></td><td>&rarr;</td>
												<td bgcolor="#fff" align="center"><label>'.$gastosEnvio.' &euro;<label></td>
											</tr>
											<tr>
												<td colspan="3" align="right"><label>Total IVA Incluido &nbsp; </label></td><td>&rarr;</td>
												<td bgcolor="#fff" align="center"><label style="color: #F00; font-size: 14px">'.$result1->Total.' &euro;</label></td>
											</tr>
										';
									}
									?>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div id="push"></div>   
</div>

<div id="footer" class="footer"> 
	<?php include('footer.inc.php'); ?>        	
</div>

</html>