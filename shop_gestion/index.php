<?php
session_start();
if ((!isset($_SESSION['Logged'])) && (!isset($_SESSION['UserIdAdmin']))) {
	header('Location: https://'.$_SERVER['HTTP_HOST']);
} else {
	include_once("config.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="css/stylos-pag.css" type="text/css" rel="stylesheet" />
        <link href="css/select-styles.css" type="text/css" rel="stylesheet" />
        <link href="css/smartpaginator.css" type="text/css" rel="stylesheet" />
        <link href="css/icons.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
		<title><?php echo $nombre; ?></title>
        <!--<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>-->
        <script type="text/javascript" src="js/jquery.tools.min.js"></script>
        <script type="text/javascript" src="js/smartpaginator.js"></script>
        
        <script type="text/javascript" src="js/jquery.simplePagination.js"></script>
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.jcookie.min.js"></script>
        <script type="text/javascript" src="js/jquery.validate.js"></script>
        <script type="text/javascript" src="js/jquery-class.js"></script>   
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
			$active1 = 1;
			include_once 'menu.inc.php';
		 ?>
    </div>    
    
    <!-- EFECTO TOOLTIP -->
    <div class="tooltip_customer"></div>
    <!-- FIN -->
    
    <!-- GADGET MENU LATERAL -->
    <?php require("gadget.inc.php"); ?>
    <!-- FIN GADGET -->
        
    <div id="content-body">
    	<div id="general" class="content">
        	<div id="Contenido" style="padding-top: 20px; overflow: auto; overflow-x:hidden">  
            	<div style="position: absolute; top: 0px; left: 990px; z-index:9999999"><img src="images/archivo.png" /></div>  
            	<ul class="tabs">
    		    	<li class="active"><a href="#tab1"><span>Formulario Pedido</span></a></li>
		   			<li><a href="#tab2">Buscar Cliente</a></li>
   		  	        <li><a href="#tab3">Productos</a></li>			                
		  	    </ul>
        		
                <div class="tab_container">
                	<!-- REGISTRO DE NUEVOS PEDIDOS -->
                	<div id="tab1" class="tab_content" style="display: block">
						<div id="NuevoProducto">
                        	<div class="fieldset">
                            	<h2 class="legend">Productos Activos</h2>
                                <div>
                                	<table id="tableSelectProduct">
                                    	<form id="formSelectProduct" name="formSelectProduct" method="post" action="add-to-car.inc.php?idbd=" . <?php echo base64_decode($_GET['idbd']); ?> enctype="application/x-www-form-urlencoded">
	                                    	<tr>
    	                                    	<td>        	                                    	
                                                    <div style="width: 500px; margin: 0 auto">                                              
	                                                    <div id="select" style="margin-bottom: 5px">
	            	                                    <select id="selectProduct" name="selectProduct" data-placeholder="Listado de Productos..." class="chosen-select" style="width: 500px; text-transform: capitalize;">
    	            	                                	<option value=""></option>
        	            	                                <?php
															$sql = "SELECT productos.IdOferta, productos.Nombre_Producto, opcionesoferta.Id FROM productos ";
															$sql .= "INNER JOIN opcionesoferta ON productos.IdOferta = opcionesoferta.IdOpcion ";
															$sql .= "AND productos.Gestion = 1 GROUP BY opcionesoferta.IdOpcion ORDER BY IdOferta DESC";
															$db->setQuery($sql);
															$result = $db->loadObjectList();
															foreach($result as $result1) {
																echo '<option value="'.$result1->IdOferta.'|'.$result1->Id.'">'.$result1->IdOferta. ' &nbsp;&rarr;&nbsp; ' .utf8_encode(($result1->Nombre_Producto)).'</option>';
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
                            	            	<td align="center">
                                	            	<input type="submit" id="insertProduct" name="insertProduct" value="A&ntilde;adir Producto" alt="0" />
                                    	        </td>
                                        	</tr>
                                        </form>
                                    </table>
                                </div>
                            </div>
                            
                        	<div>                            	
                                <div class="adress-information">
    	                           	<?php
									if (isset($_GET['idcustomer'])) {
										$sql = "SELECT * FROM usuarios WHERE Id = '".htmlspecialchars($_GET['idcustomer'])."' LIMIT 1";
										$db->setQuery($sql);
										$row = $db->execute();
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
												$_SESSION['CUSTOMER'] = $_GET['idcustomer'];
												$nombres = utf8_encode($result1->Nombres);
												$apellidos = utf8_encode($result1->Apellidos);
												$dni = utf8_encode($result1->Dni);
												$telefono = $result1->Telefono;		
												$email = $result1->Email;
												$direccion = utf8_encode($result1->Direccion);
												$numero = utf8_encode($result1->Numero);
												$piso = utf8_encode($result1->Piso);
												$puerta = utf8_encode($result1->Puerta);
												$cp = $result1->Cp;
												$poblacion = utf8_encode($result1->Ciudad);	
												$provincia = utf8_encode($result1->Provincia);
												$sexo = $result1->Genero;
												$fecha = explode('-', $result1->FechaNacimiento);
												$anyo = $fecha[0];									
											}
											echo '
											<div id="importantePedido">
                                    			<div style="height:20px">
		                                        	<img id="import" src="images/import.png" />
        		                                    <label id="text">Importante Desplegable</label>
                		                        </div>
                        		                <div class="caption-control">
													<span class="caption-control-wrap">
														<i></i>
													</span>
												</div> 
                		                        <div class="_importante">
                        		                	<div class="_content">
                                		            	<ul>
                                        		    		<li>
                                                		    	<img src="images/info.png" />&nbsp;&nbsp;
																<label style="color: #0062A2;">Este Pedidos se Registrar&aacute; en el siguiente IdCliente = <strong style="font-size: 16px">'.$_GET['idcustomer'].'</strong></label>
		                                                    </li>
        		                                            <li>
                		                                    	<img src="images/info.png" />&nbsp;&nbsp;<label style="color: #0062A2;">Un IdCliente corresponde a un cliente que ya consta en nuestro sistema.</label>
                        		                            </li>
                                		                    <li>
                                        		            	<img src="images/info.png" />&nbsp;&nbsp;<label style="color: #0062A2;">Este mensaje aparece porque has seleccionado un cliente.</label>
                                                		    </li>
		                                                    <li>
        		                                            	<img src="images/info.png" />&nbsp;&nbsp;<label style="color: #0062A2;">Elimina todos los cambios Registrando Nuevo Pedido.</label>
                		                                    </li>
                        		                            <li>
                                		                    	<img src="images/info.png" />&nbsp;&nbsp;<label style="color: #0062A2;">Nuevo pedido ( Archivo - Registrar Nuevo pedido )</label>
                                        		            </li>
		                                                </ul>                                            	
        		                                    </div>
                		                        </div>                                      
                        		            </div>
											';
										}
									} /*else if (isset($_SESSION['DAT'])){
										$nombres = $_SESSION['DAT']['name_payment'];
										$apellidos = $_SESSION['DAT']['last_name_payment'];
										$dni = $_SESSION['DAT']['dni_payment'];
										$telefono = $_SESSION['DAT']['phone_payment'];	
										$email = $_SESSION['DAT']['email_payment'];
										$direccion = $_SESSION['DAT']['address_payment'];
										$numero = $_SESSION['DAT']['number_payment'];
										$piso = $_SESSION['DAT']['piso_payment'];
										$puerta = $_SESSION['DAT']['door_payment'];
										$cp = $_SESSION['DAT']['cod_payment'];
										$poblacion = $_SESSION['DAT']['city_payment'];	
										$mensaje = $_SESSION['DAT']['message_payment'];
									}*/ else {
										$nombres = $_COOKIE['name_payment'];
										$apellidos = $_COOKIE['last_name_payment'];
										$dni = $_COOKIE['dni_payment'];
										$telefono = $_COOKIE['phone_payment'];	
										$email = $_COOKIE['email_payment'];
										$direccion = $_COOKIE['address_payment'];
										$numero = $_COOKIE['number_payment'];
										$piso = $_COOKIE['piso_payment'];
										$puerta = $_COOKIE['door_payment'];
										$cp = $_COOKIE['cp_payment'];
										$poblacion = $_COOKIE['city_payment'];	
										$mensaje = $_COOKIE['message_payment'];
									}
									?>
                                   
                                    
                                    <form id="formInsertOrder" name="formInsertOrder" method="post" action="generate-purchase-order.php?idbd=" . <?php echo base64_decode($_GET['idbd']); ?> class="formularios" accept-charset="utf-8" enctype="application/x-www-form-urlencoded" onSubmit="return validarRadio(this);">                                 	
                                	<h3><img src="images/dat.png" style="float: left;" />&nbsp;Datos Personales</h3>    
                                    <input type="hidden" name="ordencliente" value="<?php if(isset($_GET['idcustomer'])) { echo $_GET['idcustomer']; } else { echo 0; } ?>" />                                
                                    <fieldset>                                         
		                                <table id="address">
    		                            	<tr>
        		                            	<td align="left">
			    	                            	<label for="nombres">Nombres :<strong class="obligado">*</strong></label><br />
        				                            <p><input type="text" id="name_payment" name="name_payment" style="width: 200px" value="<?php echo $nombres; ?>" autocomplete="off" /></p>
                    		                    </td>
                        		                <td>
                            		            	<label for="apellidos">Apellidos :</label><strong class="obligado">*</strong><br />
                                		            <p><input type="text" id="last_name_payment" name="last_name_payment" style="width: 200px" value="<?php echo $apellidos; ?>" required="required" autocomplete="off" /></p>
                                    		    </td>
	                                    	</tr>
                                            <tr>
                                            	<td>
                                                	<label for="dni">Dni :</label><strong class="obligado">*</strong><br />
                                                    <p><input type="text" id="dni_payment" name="dni_payment" style="width: 200px" value="<?php echo $dni; ?>" required="required" autocomplete="off" /></p>
                                                </td>
                                                <td>
                                                	<label for="telefono">Tel&eacute;fono :</label><strong class="obligado">*</strong><br />
                                                    <p><input type="text" id="phone_payment" name="phone_payment" style="width: 200px;" value="<?php echo $telefono; ?>" required="required" autocomplete="off" /></p>
                                                </td>
                                            </tr>
                                            <tr>
                                            	<td colspan="2">
                                                	<p style="width: 420px">
                                                    	<label for="email">Correo Electr&oacute;nico :</label><strong class="obligado">*</strong>
                                                   		<input type="email" id="email_payment" name="email_payment" style="width: 250px" value="<?php echo $email; ?>" required="required" autocomplete="off" />
                                                    </p>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                            	<td colspan="2" align="left" style="height:30px">                                                	
                                                	<p style="float:left; padding: 7px 0px; width: 90px"><label for="no_email">Sin Correo :</label>&nbsp;&nbsp;</p>
                                                    <p style="float:left; padding: 7px 0px; width: 50px"><input type="checkbox" id="sinemail" name="sinemail" />  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</p>
                                                    <p style="width: 50px; float: left; padding: 7px 0px"><label for="sexo">Sexo :</label><strong class="obligado">*</strong>&nbsp;&nbsp;</p>
                                                    <p style="float:left; width: 90px; padding: 7px 0px">
                                                    	<input type="radio" id="hombre" name="sexo_payment" <?php if ($sexo == 'Hombre') echo "checked='checked'"; ?> value="Hombre" onclick="marcado=true" required="required" />
                                                        &nbsp;&nbsp;<label>Hombre</label>&nbsp;&nbsp;
                                                    </p>
                                                    <p style="float:left; width: 120px; padding: 7px 0px">
                                                    	<input type="radio" id="mujer" name="sexo_payment" <?php if ($sexo == 'Mujer') echo "checked='checked'"; ?> value="Mujer" onclick="marcado=true" required="required"/>
                                                    	&nbsp;&nbsp;<label>Mujer</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="height:20px">      
                                                	<div style="position: relative; width: 400px; height: 20px">                                             
	                                                	<div style="float: left; width: 100px; line-height: 20px">
    	                                                	<label for="edad">A&ntilde;o Nacimiento: </label>&nbsp;&nbsp;
        	                                            </div>
	                                                   <div style="position: relative; z-index: 9999999; float:left">
		                                                    <select id="anyo_nacimiento" name="anyo_nacimiento" data-placeholder="Año Nacimiento" class="chosen-select" style="width: 150px;">
        		                                            	<option value="0000"></option>
                		                                        <?php
																if (!isset($_GET['idcustomer'])) {
																	for ($anyo=1920; $anyo <= 1995; $anyo++) {
    	                                		                		echo '<option value="'.$anyo.'">'.$anyo.'</option>';
																	}
																} else {
																	echo '<option value="'.$anyo.'" selected="selected">'.$anyo.'</option>';
																}
																?>
                        		                            </select>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
	    	                            </table>
                                    </fieldset>
                                    <h3><img src="images/direcc.png" style="float: left;" />&nbsp;Dirección de Entrega</h3>
                                    <fieldset>
                                    	<table>
                                        	<?php
												$SQL = "SELECT * FROM direcciones WHERE D_IdCliente = '".$_GET['idcustomer']."' AND Activo = '1'";
												$db->setQuery($SQL);
												$row = $db->execute();
												if (mysqli_num_rows($row) > 0) {	
												echo '
	                                        	<tr>
    	                                        	<td colspan="2" align="left">';                                                	
                                                								
														$result = $db->loadObjectList();
														echo '<div id="_dir">';
														foreach($result as $result1) {
															echo '
															<table id="direcciones" cellpadding="0" cellspacing="0" style="text-align: left; height: 22px">
																<tr>
																	<td>
																		<p style="width:100%">
																			<input type="radio" name="_direc_" value="'.$result1->IdDireccion.'" data-title="'.$result1->D_Pais.'">&nbsp;																		
																				<label style="font-size: 11px; color: #464646; padding-top: 5px; padding-botton: 5px;">
																					'.$result1->TipoVia.' '.utf8_encode($result1->Direccion).' '.utf8_encode($result1->TipoNumero).' 
																					'.utf8_encode($result1->Numero).', '.utf8_encode($result1->Piso).' - '.utf8_encode($result1->Puerta).' 
																					( '.utf8_encode($result1->Poblacion).' - '.utf8_encode($result1->Provincia).' ) <img src="images/'.$result1->D_Pais.'.png" style="padding: 0px 5px 0px 0px; float: left"" />
																				</label>
																		</p><br/>
																	</td>
																</tr>
															</table>';
														}					
														echo '</div>';	
													echo '</td>';
												}
												echo '</tr>';
												if(isset($_GET['idcustomer'])) {
												echo '
												<tr height="40px">
                                            		<td>
                                                		<input type="button" id="nuevaDir" name="nuevaDir" value="Nueva Direcci&oacute;n" />
														<input type="button" id="ocultarDir" name="ocultarDir" value="Ocultar Direcci&oacute;n" />
                                                	</td>
                                            	</tr>
												';
												}
											?>                                               
                                            
                                        </table>
                                    </fieldset>
                                    <fieldset>
                                    	<table id="address" class="address"<?php if(isset($_GET['idcustomer'])) echo 'style="display: none"'; ?>>
                                        	<tr>
                                            	<td colspan="2">
                                                	<table>
                                                    	<tr>
                                                        	<td align="left" style="width: 145px; vertical-align:baseline">
			                                                	
                                                                <div style="position: static; z-index: 9999999999">
                                                                	<label for="tipovia">Tipo Vía</label>
				                                                    <div id="select" style="position: absolute; z-index: 99999">
            					                                        <select id="type_via_payment" name="type_via_payment" style="width: 129px" data-placeholder="TipoVia" class="chosen-select">
        	    			    		            			        	<option value="Calle">Calle</option><option value="Avenida">Avenida</option>
																		    <option value="Carretera">Carretera</option><option value="Glorieta">Glorieta</option>
																			<option value="Paseo">Paseo</option><option value="Plaza">Plaza</option>
																			<option value="Plígono">Pol&iacute;gono</option><option value="Via">V&iacute;a</option>
																			<option value="Autovia">Autov&iacute;a</option><option value="Ronda">Ronda</option>
					                	    	            		        <option value="Travesia">Traves&iacute;a</option><option value="Rambla">Rambla</option>
																			<option value="Parque">Parque</option><option value="Camino">Camino</option>
																			<option value="Riera">Riera</option>
																			<option value="Urbanizacion">Urbanizaci&oacute;n</option>
																			<option value="Apartamento">Apartamento</option>
                       						    				        </select>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td align="left">
                                                				<label for="direccion">Direcci&oacute;n :</label><strong class="obligado">*</strong><br />
			                                                    <p style="width: 305px">
                                                                	<input type="text" id="address_payment" name="address_payment" style="width: 300px" value="<?php echo $direccion; ?>" <?php if(isset($_GET['idcustomer'])) echo 'disabled="disabled"'; ?> required="required" autocomplete="off" /></p>
            			                                    </td>
                                                        </tr>
                                                    </table>
                                                </td>                                            	
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                	<table id="address">
                                                    	<tr>
                                                        	<td width="145px">
                                                            	<label for="numero">Tipo N&uacute;mero :</label><strong class="obligado">*</strong><br />    
                                                                <div style="position: static">                                                            
				                                                    <div id="select" style="position: relative; z-index: 9999">
            		                                                    <select id="type_number_payment" name="type_number_payment" class="chosen-select" style="width: 129px">
		            						        	                	<option value="Numero">N&uacute;mero</option>
																			<option value="Bloque">Bloque</option>
																			<option value="Kilómetro">Kil&oacute;metro</option>
																			<option value="Nave">Nave</option>
																			<option value="Casa">Casa</option>            	                        
                        			    				    		    </select>                                                                
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        	<td>
                                                            	<label for="numero">N&uacute;mero :</label><strong class="obligado">*</strong><br />
        		                                            	<p style="width:83px">
                                                                	<input type="text" id="number_payment" name="number_payment" style="width: 76px" value="<?php echo $numero; ?>" <?php if(isset($_GET['idcustomer'])) echo 'disabled="disabled"'; ?> required="required" autocomplete="off" /></p>
                                                            </td>
                                                            <td>
		               		                                    <label for="piso">Piso :</label><strong class="obligado">*</strong><br />
        	                		                            <p style="width:83px">
                                                                	<input type="text" id="piso_payment" name="piso_payment" style="width: 76px" value="<?php echo $piso; ?>" <?php if(isset($_GET['idcustomer'])) echo 'disabled="disabled"'; ?> required="required" autocomplete="off" />
                                                                </p>
                                                            </td>
                                                            <td>
	                                		                    <label for="puerta">Puerta :</label><strong class="obligado">*</strong><br />
                                                                <p style="width:83px">
    	                                    		                <input type="text" id="door_payment" name="door_payment" style="width: 76px" value="<?php echo $puerta; ?>" <?php if(isset($_GET['idcustomer'])) echo 'disabled="disabled"'; ?> required="required" autocomplete="off" />
                                                                </p>
                                                            </td>
                                                    	</tr>
                                                    </table>
                                                </td>                                                
                                            </tr>
                                            <tr>
                                            	<td colspan="1">
                                                	<label for="codPostal">C&oacute;digo Postal :</label><strong class="obligado">*</strong><br />
                                                    <p style="width:157px"><input type="text" id="cp_payment" name="cp_payment" style="width: 150px" value="<?php echo $cp; ?>" <?php if(isset($_GET['idcustomer'])) echo 'disabled="disabled"'; ?> required="required" autocomplete="off"/></p>
                                                </td>
                                                <td>
                                                	<label for="pais">Pa&iacute;s</label><strong class="obligado">*</strong><br />
                                                    <div style="width:200px; position: relative; z-index: 999">
                                                  
                                                    	<div class="divSelect" style="width:180px">
	                                                    	<select id="pais_payment" name="pais_payment" class="" style="width: 200px">                                         
	    	                                                	<?php
																	$PQL = "SELECT * FROM paises";
																	$db->setQuery($PQL);
																	$result = $db->loadObjectList();
																	foreach($result as $result1) {
																		echo '<option value="'.$result1->IdPais.'">'.utf8_encode($result1->Pais).'</option>';
																	}															
																?>                                                    	
                                	    	                </select>
                                                        </div>
                                                    
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                            	<td style="width:220px">
                                                	<label for="poblacion">Poblaci&oacute;n :</label><strong class="obligado">*</strong><br />
                                                    <p><input type="text" id="city_payment" name="city_payment" style="width: 200px" value="<?php echo $poblacion; ?>" required="required" <?php if(isset($_GET['idcustomer'])) echo 'disabled="disabled"'; ?> autocomplete="off" /></p>
                                                </td>
                                            	<td style="vertical-align: baseline">                                                	                                                   
                                                    <div style="position: static; z-index: 9999;">
                                                    	<label for="provincia">Provincia :</label><strong class="obligado">*</strong>	                                              
                                                       	<div style="width:200px; position: relative; z-index: 999">
		                                                    <p><input type="text" id="province_payment" name="province_payment" value="" /></p>    
                                                        </div>
                                                    </div>                             
                                                </td>
                                            </tr>
                                            <tr>
                                            	<td colspan="2">
                                                	<label for="mensaje">Mensaje :</label><br />
                                                	<input type="text" id="message_payment" name="message_payment" style="width: 447px" value="<?php echo $mensaje; ?>" autocomplete="off" />
                                                </td>
                                            </tr>                                            
                                        </table>
                                    </fieldset>
                                    <fieldset>
                                    	<table id="confirmPago">
                                        	<tr>
                                            	<td align="center" style="height:29px" width="230px">
                                                	<img src="images/visa.png" />&nbsp;
                                                	<label for="Tarjeta">Pago Tarjeta</label>&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" id="Tarjeta" name="visa-paypal-contra" value="visa" />
                                                </td>
                                                <td align="center" style="height:29px">
                                                	<img src="images/contra.png" />&nbsp;
                                                	<label for="Contra-rembolso">Pago Contra-rembolso</label>&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" id="Contra" name="visa-paypal-contra" checked="checked" value="contra" />
                                                </td>
                                            </tr>
                                            <tr height="40px">
                                            	<td colspan="2" align="center">
                                                	<input type="submit" id="nuevoPedido" name="nuevoPedido" value="Ingresar Pedido" disabled="disabled" alt="0" />
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                    </form>
                                    <?php if (isset($_GET['idcustomer'])) { ?>
                                    	<script type="text/javascript">
											$(document).ready(function(e) {
												document.formInsertOrder.address_payment.disabled=true;
                                                $('#address_payment').attr('disabled', true); $('#number_payment').attr('disabled', true); $('#door_payment').attr('disabled', true); $('#piso_payment').attr('disabled', true);
												$('#cp_payment').attr('disabled', true); $('#pais_payment').attr('disabled', true); $('#city_payment').attr('disabled', true); $('#province_payment').attr('disabled', true);
                                            });
										</script>
									<?php } ?>
                                </div>
                                
                                <div class="order-information">
                                	<h3><img src="images/products.png" style="float: left;" />&nbsp;Productos en Cesta</h3>
                                    <div>
                                    	<table id="order" cellpadding="0" cellspacing="0">
                                        	<thead>
                                            	<th width="5%"></th>
                                            	<th><label>Nombre de Producto</label></th>
                                                <th width="120px"><label>Tipo/Talla</label></th>
                                                <th><label>Precio</label></th>
                                                <th><label>Cantidad</label></th>
                                                <th align="right"><label>Subtotal</label></th>
                                            </thead>
                                            <tbody>
                                            	<?php
												$carrito = $_COOKIE['usuarioAdmin'];
												$SQL = "SELECT carritocompra.*, productos.IdOferta, productos.Nombre, productos.Nombre_Producto, opcionesoferta.Precio, 
														opcionesoferta.Opcion, opcionesoferta.OptActiva, paisesenvio.TotalGastos, imagenes.BaseUrl, imagenes.Imagen
														FROM carritocompra 
														INNER JOIN productos ON carritocompra.IdProducto = productos.IdOferta 
														INNER JOIN paisesenvio ON carritocompra.PaisEnvio = paisesenvio.IdPais 
														INNER JOIN opcionesoferta ON carritocompra.Talla = opcionesoferta.Id 
														LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.estado = 1
														WHERE carritocompra.IdCarrito = '$carrito' AND opcionesoferta.Peso > paisesenvio.PesoIn AND opcionesoferta.Peso <= paisesenvio.PesoOut";
												$db->setQuery($SQL);
												$consult = $db->execute();
												
												if (mysqli_num_rows($consult) > 0)  {
													$result = $db->loadObjectList();
													foreach($result as $result1) {
														echo '
														<tr>
															<td width="35px" align="left"><a href="update-amount.inc.php?producto='.$result1->IdOferta.'&talla='.$result1->Talla.'&opcion=delete"><img src="images/delete-1.png" title="eliminar" /></a></td>														
                                                			<td align="left" width="170px" style="position: relative">
																<a name="'.$result1->IdOferta.'" style="display:block;">'.utf8_encode($result1->Nombre_Producto).'</a>
																<div id="'.$result1->IdOferta.'" class="tooltip">
																	<img src="'.$result1->BaseUrl.'/'.$result1->Imagen.'" width="242px" height="155px" />																
																</div>
															</td>
		                                                    <td><label>'.utf8_encode($result1->Opcion).'</label></td>
        		                                            <td><label>'.number_format($result1->Precio,2,',','.').'</label></td>
                		                                    <td>
																<a href="update-amount.inc.php?producto='.$result1->IdOferta.'&talla='.$result1->Talla.'&opcion=down"><img src="images/quantity_down.gif" /></a>&nbsp;&nbsp;
																<label>'.$result1->Cantidad.'</label>&nbsp;&nbsp;
																<a href="update-amount.inc.php?producto='.$result1->IdOferta.'&talla='.$result1->Talla.'&opcion=up"><img src="images/quantity_up.gif" /></a>
															</td>
                        		                            <td align="right"><label>'.number_format(($result1->Precio*$result1->Cantidad),2,',','.').'&euro;</label></td>
                                		                </tr>
														';
														$subTotal = $subTotal + ($result1->Precio*$result1->Cantidad);
														$gastosEnvio = $gastosEnvio + ($result1->TotalGastos*$result1->Cantidad);	
														/*echo '
															<div id="'.$result1->IdOferta.'" class="tooltip">
																<img src="http://www.bonocartilla.com/productos/'.$result1->Nombre.'/'.$result1->Imgoferta.'" width="242px" height="155px" />																
															</div>
														';*/													
													}
													$carritoCesta = true;
													$total = $subTotal + $gastosEnvio;
												} else {
													$carritoCesta = false;
													echo "<tr><td height='10px'></td></tr>";
												}
												$db->freeResults();
												
												?>
                                                <!--<div id="tooltip" class="tooltip">leonni</div>-->
                                                <?php if ($carritoCesta == true) { ?>
                                                	<script type="text/javascript">
														$('#nuevoPedido').removeAttr('disabled', true);														
													</script>
                                                <?php } else { ?>
                                                	<script type="text/javascript">
														$('#nuevoPedido').css('background', '#DBDBDB');
														$('#nuevoPedido').css('border-color', '#BDBDBD')
													</script>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot style="background:#fff; color: #666;">
                                            	<tr>
                                                    <td colspan="5" align="right"><label>Subtotal&nbsp;&nbsp;&nbsp;&nbsp;&rarr;</label></td>
                                                    <td align="right"><strong><?php echo number_format($subTotal,2,',','.'); ?>&euro;</strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" align="right"><label>Gastos de Env&iacute;o&nbsp;&nbsp;&nbsp;&nbsp;&rarr;</label></td>
                                                    <td align="right"><strong><?php echo number_format($gastosEnvio,2,',','.'); ?>&euro;</strong></td>
                                                </tr>
                                                <?php
													if (isset($_SESSION['Cupon'])) {
														$descuentoCupo = base64_decode($_SESSION['valorCupon']);
														echo '
			                    			            <tr>
            			                    			    <td colspan="5" align="right"><label class="car" style="color: red">CUP&Oacute;N DESCUENTO</label></td>
						                                    <td align="right"><label class="car" style="color: red">-'.number_format($descuentoCupo,2,',','.').'&euro;</label></td>
            						                    </tr>
														';
													} else {
															$descuentoCupo = 0;
													}
												?>
                                                <tr>                                                	
                                                    <td colspan="5" align="right"><label>Total IVA incluido&nbsp;&nbsp;&nbsp;&nbsp;&rarr;</label></td>
                                                    <td align="right"><strong style="color: #09F; font-size: 1.2em; font-weight: 100"><?php echo number_format(($total - $descuentoCupo),2,',','.'); ?>&euro;</strong></td>
                                                </tr>                                           
                                            </tfoot>
                                        </table>
                                        <table id="descuento">	                                        
    	                                    <tr>
        	                                   	<td colspan="2" align="left" style="height:0px"><label for="cupon">Cup&oacute;n Descuento</label></td>                                                     
                                            </tr>
                                            <tr>
                                               	<td width="230px"><input type="text" id="codDescuento" name="codDescuento" style="width: 200px" autocomplete="off" /></td>
                                                <td id="valido"><img src="images/validate_ok.png" /></td>
                                                <td id="invalido"><img src="images/validate_error.png" /></td>
                        	                </tr>
                            	            <tr>
                                	           	<td colspan="2">
                                    	           	<input type="button" id="validar" name="validar" value="Validar Cup&oacute;n" />
                                                    <input type="button" id="aplicarDescuento" name="aplicarDescuento" value="Aplicar Descuento" />
                                        	    </td>
                                            </tr>                                        	
                                        </table>
                                        <table id="informacion">
                                        	<tr>
                                            	<td>
                                                	<p>
                                                    	> Para registrar un pedido primeramente añade productos a la cesta de compra.<br />
                                                    	> Luego verifica que todos los campos del formulario estén con la información solicitada.<br />
                                                        > Si ya tienes todo esto listo, confirma la compra presionando en el bóton ingresar pedido. <br />
                                                        > Si te aparece algún error es porque algun campo esta mal o no has añadido productos al carrito.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>     
                                   
                    <!-- BUSQUEDA DE CLIENTE QUE YA COMPRARON -->
                    <div id="tab2" class="tab_content" style="display:none">
                    	<div id="busquedaClientes">	
                        	<div class="fieldset">
                            	<h2 class="legend">Información personal</h2>
	                        	<form id="formCustomer" name="formCustomer" method="post" action="search-customer-order.inc.php?idbd=" . <?php echo $_REQUEST['idbd']; ?> accept-charset="utf-8" enctype="multipart/form-data">
                                	<input type="hidden" name="idbd" value="<?php echo $_REQUEST['idbd']; ?>" />
		                        	<table style="width:80%">
    		                        	<thead>
        		                        	<th><label class="cab">Criterio</label></th>
            		                        <th><label class="cab">Filtro a aplicar</label></th>
                		                    <th></th>
                    		            </thead>
                        		        <tbody>
                            		    	<tr>
                                		    	<td>
                                    		    	<select id="criterio" name="criterio" class="chosen-select" data-placeholder="Seleccione Criterio" style="width: 200px;">                                                    
                                            	    	<option value="0">Seleccione</option>
                                                		<option value="idorden">Número de Orden</option>
                                                    	<option value="dni">Identificación</option>
	                                                    <option value="destinatario">Nombres</option>
    	                                                <option value="direccion">Dirección</option>
        	                                            <option value="telefono">Telefono</option>
            	                                    </select>
                	                        	</td>
	                	                        <td>
    	                	                    	<input type="text" id="filtro" name="filtro" style="width: 350px" autocomplete="off" />
        	                	                </td>
                                	            <td>
                                    	        	<input type="submit" id="buscar" name="buscar" value="Buscar Cliente" alt="0" />                                                    
                                        	    </td>
	            	                        </tr>
    	            	                </tbody>
        	            	        </table>
            	                </form>
                            </div>
                        </div>
                        <div id="resultSearch" style="min-height:400px">
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Resultados</h3><br />
                        	<table id="resultSearch1" cellpadding="0" cellspacing="0" style="margin-bottom: 25px">
                            	<thead>
                                	<th width="50px">Orden</th>
                                	<th width="90px">Fecha</th>
                                    <th width="220px">Nombres</th>
                                    <th width="90px">Dni</th>
                                    <th width="300px">Direcci&oacute;n</th>                                   
                                    <th width="90px">Tel&eacute;fono</th>
                                    <th width="200px">Compra</th>
                                    <th width="100px">Estado</th>
                                </thead>
                                <tbody id="dataCustomer">
									<tr><td colspan="8" height="22px"></td></tr>
                                    <tr><td colspan="8" height="22px"></td></tr>
                                    <tr><td colspan="8" height="22px"></td></tr>
                                    <tr><td colspan="8" height="22px"></td></tr>
                                    <tr><td colspan="8" height="22px"></td></tr>
                                    <tr><td colspan="8" height="22px"></td></tr>
                                    <tr><td colspan="8" height="22px"></td></tr>
                                    <tr><td colspan="8" height="22px"></td></tr>
                                    <tr><td colspan="8" height="22px"></td></tr>
                                    <tr><td colspan="8" height="22px"></td></tr>                                    
                                </tbody>
                            </table>
                        </div>
                        
                        <div id="green" style="margin: auto;"></div>
                        
                    </div>
                    
                    <!--MOSTRAMOS LOS PRODUCTOS ACTIVOS -->
                    <div id="tab3" class="tab_content" style="display: none">
                    	<div id="resultSearch" style="min-height: 430px;">
                        
                    	<div class="fieldset">
                        	<h2 class="legend">Productos Activos</h2>
                            <div style="overflow:auto; width: 997px">                            	
	                        	<?php
								$counter = 0;
								$SQL = "SELECT productos.IdOferta, productos.Activo, productos.Gestion, productos.Nombre, productos.Nombre_Producto, opcionesoferta.Precio,
										imagenes.BaseUrl, imagenes.Imagen
										FROM productos 
										INNER JOIN opcionesoferta ON productos.IdOferta = opcionesoferta.IdOpcion 
										LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1
										GROUP BY IdOferta DESC";
								$db->setQuery($SQL);
								$result = $db->loadObjectList();
								echo '<div id="divs">';
								foreach($result as $result1) {	
									$counter = $counter + 1;							
									echo '
										<fieldset>
										<a id="'.$result1->IdOferta.'" href="info-producto.php?idoferta='.$result1->IdOferta.'" rel="#overlay">
						    				<div id="productos">
				    		    				<div id="ImgText">
						            				<table>
						                				<tr>
						                    				<td width="70px" height="50px">
					    	                    				<img id="img" src="'.$result1->BaseUrl.'/'.$result1->Imagen.'" width="70px" height="50px">
															</td>
						            	            		<td>
					                    	    				<label id="Text">'.utf8_encode($result1->Nombre_Producto).'</label>
																<label id="price">'.$result1->Precio.'&euro;</label>
						                        			</td>
    						                			</tr>
	                								</table>
						    	        		</div>';
												
												if ($result1->Activo == 1) { echo '<img class="icom_web" src="images/icon_web.png" title="Web" width="25px" />'; } else { echo '<img class="icom_web" src="images/icon_incative.png" title="Desactivado" width="25px" />'; }
												if ($result1->Gestion == 1) { echo '<img class="icom_gestion" src="images/icon_gestion.png" title="Gestión" width="25px" />'; } else { echo '<img class="icom_web" src="images/icon_incative.png" title="Desactivado" width="25px" />'; }
											echo '
						        			</div>
										</a>
										</fieldset>';
								};
								echo '</div>';
								$db->freeResults(); 
            	               ?>                           	                                 	                           	   
                            </div>                           
                        </div>  
                        
                        </div>
                        
                        <div class="icons" style="margin-bottom: 20px;">                        	
	    	               	<table>
            	               	<tr>
                	               	<td><img src="images/icon_web.png" width="30px" />&nbsp;&nbsp;Activo en Web</td>
                    	            <td><img src="images/icon_gestion.png" width="30px" />&nbsp;&nbsp;Activo en Gestión</td>
                        	         <td><img src="images/icon_incative.png" width="30px" />&nbsp;&nbsp;Inactivo</td>
                            	</tr>
	                        </table>
	                    </div>
    	                <div id="green1" style="margin: auto;"></div>
                        <script type="text/javascript">
	                        $('#green1').smartpaginator({ totalrecords:<?php echo $counter; ?>, recordsperpage: 16, datacontainer: 'divs', dataelement: 'fieldset', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });
						</script>
                        
                    </div>
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