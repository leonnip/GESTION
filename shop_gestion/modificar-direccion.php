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
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
		<title>ADMIN EL PAIS SELECCION</title>
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
    		    	<li class="active"><a href="#tab1"><span>Modificar Dirección</span></a></li>
		   			<!--<li><a href="#tab2">Anular Pedidos</a></li>-->
   		  	        <!--<li><a href="#tab3">Productos</a></li>-->                
		  	    </ul>
        		
                <div class="tab_container">                	                    
                    <!-- MODIFICAR EL PEDIDO DEL CLIENTE -->
                    <div id="tab1" class="tab_content">
                    	<table class="modific_1">
                        	<tr>
                            	<td><span>Orden N&uacute;mero</span>&nbsp;&nbsp;<strong><?php echo base64_decode($_GET['orden']); ?></strong></td>
                            </tr>  
                            <!--                                                 
                            <tr>
                            	<td height="45px"><span>Dirección Actual del Pedido</span></td>
                            </tr>
                            -->
                            <?php
							if ($_GET['response'] == 1) {
								echo '
	                            <tr>
    	                        	<td>
        	                        	<h4 class="opt"><img src="images/ok.png" style="float: left" />&nbsp;&nbsp;&nbspSu dirección ha sido modificada correctamente.</h4>
            	                    </td>
                	            </tr>';
							}
							?>                            
						</table>
                        
                        <?php
						$SQL = "SELECT * FROM relordendireccion ";
						$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion ";
						$SQL .= "WHERE relordendireccion.IdOrden = '".base64_decode($_GET['orden'])."'";
						$db->setQuery($SQL);
						$result = $db->loadObject();
						$coments = $result->Comentarios;
						?>
                        
                        <form name="formInsertOrder" method="post" action="modificar-direccion.inc.php?idbd=" . <?php echo base64_decode($_GET['idbd']); ?> accept-charset="utf-8" enctype="application/x-www-form-urlencoded">
                        	<input type="hidden" name="id_direccion" value="<?php echo $result->IdDireccion; ?>" />
                            
		                   	<div style="width: 740px; margin: 0 auto; background: #F5F5F5; padding: 30px; border: 1px solid #D9DCDE">
        		            	<fieldset>                                         
		                                <table id="address">
    		                            	<tr>
        		                            	<td align="left">
			    	                            	<label for="nombres">Nombres :<strong class="obligado">*</strong></label><br />
        				                            <input type="text" name="name_payment" style="width: 200px" value="<?php echo utf8_encode(ucwords(strtolower($result->D_Nombres))); ?>" required="required" autocomplete="off" />
                    		                    </td>
                        		                <td>
                            		            	<label for="apellidos">Apellidos :</label><strong class="obligado">*</strong><br />
                                		            <input type="text" id="last_name_payment" name="last_name_payment" style="width: 200px" value="<?php echo utf8_encode(ucwords(strtolower($result->D_Apellidos))); ?>" required="required" autocomplete="off" />
                                    		    </td>
	                                    	</tr>
                                            <tr>                                            	
                                                <td>
                                                	<label for="telefono">Tel&eacute;fono :</label><strong class="obligado">*</strong><br />
                                                    <input type="text" id="phone_payment" name="phone_payment" style="width: 200px;" value="<?php echo $result->Telefono; ?>" required="required" autocomplete="off" />
                                                </td>
                                                <td></td>
                                            </tr>                                           
                                            <tr>
                                            	<td colspan="2" align="left" style="height:30px">                                                	                                                	
                                                    <label for="sexo">Sexo :</label><strong class="obligado">*</strong>&nbsp;&nbsp;                                                    
                                                    <input type="radio" id="hombre" name="sexo_payment" <?php if ($result->Sexo == 'Hombre') echo "checked='checked'"; ?> value="Hombre" required="required" data-message="Obligatorio" />&nbsp;&nbsp;<label>Hombre</label>&nbsp;&nbsp;
                                                    <input type="radio" id="mujer" name="sexo_payment" <?php if ($result->Sexo == 'Mujer') echo "checked='checked'"; ?> value="Mujer" required="required"/>&nbsp;&nbsp;<label>Mujer</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="height:30px">      
                                                	<div style="position: relative; width: 400px; height: 30px">                                             
	                                                	<div style="float: left; width: 100px; line-height: 30px">
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
			                                                    <input type="text" id="address_payment" name="address_payment" style="width: 300px" value="<?php echo utf8_encode(ucwords(strtolower($result->Direccion))); ?>" required="required" autocomplete="off" />
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
        		                                            	<input type="text" id="number_payment" name="number_payment" style="width: 76px" value="<?php echo utf8_encode($result->Numero); ?>" required="required" autocomplete="off" />
                                                            </td>
                                                            <td>
		               		                                    <label for="piso">Piso :</label><strong class="obligado">*</strong><br />
        	                		                            <input type="text" id="piso_payment" name="piso_payment" style="width: 76px" value="<?php echo utf8_encode($result->Piso); ?>" required="required" autocomplete="off" />
                                                            </td>
                                                            <td>
	                                		                    <label for="puerta">Puerta :</label><strong class="obligado">*</strong><br />
    	                                    		            <input type="text" id="door_payment" name="door_payment" style="width: 76px" value="<?php echo utf8_encode($result->Puerta); ?>" required="required" autocomplete="off" />
                                                            </td>
                                                    	</tr>
                                                    </table>
                                                </td>                                                
                                            </tr>
                                            <tr>
                                            	<td colspan="2">
                                                	<label for="codPostal">C&oacute;digo Postal :</label><strong class="obligado">*</strong><br />
                                                    <input type="text" id="cp_payment" name="cp_payment" style="width: 150px" value="<?php echo $result->Cp; ?>" required="required" autocomplete="off"/>
                                                </td>
                                            </tr>
                                            <tr>
                                            	<td style="width:220px">
                                                	<label for="poblacion">Poblaci&oacute;n :</label><strong class="obligado">*</strong><br />
                                                    <input type="text" id="city_payment" name="city_payment" style="width: 200px" value="<?php echo utf8_encode(ucwords(strtolower($result->Poblacion))); ?>" required="required" autocomplete="off" />
                                                </td>
                                            	<td style="vertical-align: baseline">                                                	                                                   
                                                    <div style="position: static; z-index: 9999;">
                                                    	<label for="provincia">Provincia :</label><strong class="obligado">*</strong>
	                                                    <div id="select" style="width:200px; position: absolute; z-index: 999">
	                                                       	<input type="text" id="province_payment" name="province_payment" placeholder="Provincia" value="<?php echo utf8_encode(ucwords(strtolower($result->Provincia))); ?>" style="width: 200px" />
                                                        </div>                      
                                                    </div>                             
                                                </td>
                                            </tr>
                                            <tr>
                                            	<td colspan="2">
                                                	<label for="mensaje">Mensaje :</label><br />
                                                	<input type="text" id="message_payment" name="message_payment" style="width: 447px" value="<?php echo $coments; ?>" autocomplete="off" />
                                                </td>
                                            </tr>                                            
                                        </table>
                        		</fieldset>                    
                        	</div>
                    	                       
                        	<table>
                        		<tr>
                            		<td>&nbsp;</td>
                           	    </tr>
                                <tr>
                            		<td align="center">
                                		<?php
											if ($estadoPedido == 'Anulado' || $estadoPedido == 'Enviado') {
												echo '<label style="color: red">Este pedido no se puede modificar.</label>';
											} else { ?>
    			                            	<input type="submit" id="" name="" value="CAMBIAR DIRECCION" style="width:300px" />
        	                             <?php
            	                            }
                	                    ?>                                   
                    	            </td>
                        	    </tr>
                                <tr height="50px">
                            		<td align="center"><input type="button" value="Cerrar" onclick="javascript:window.close()" /></td>
	                            </tr>
	                        </table>
                        </form>
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