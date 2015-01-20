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
        <!--<script type="text/javascript" src="js/jquery.tools.min.js"></script>-->
        <script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery-class.js"></script>   
          
	</head>
<body>
</body>
<div id="wrapper" class="wrapper">

    <div id="TopBar" class="iluminacion">	
        <?php 
			include('menu.inc.php'); 
			include('config.inc.php');
		?>
    </div> 
    
    <div id="content-body">
    	<div id="general" class="content">
        	<div id="Contenido" style="padding-top: 20px; overflow: auto; overflow-x:hidden">   
            	<ul class="tabs">
    		    	<li><a href="#tab1"><span>Informaci&oacute;n de Producto</span></a></li>
		   			<!--<li><a href="#tab2">Buscar Cliente</a></li>
   		  	        <li><a href="#tab3">Productos</a></li>-->                
		  	    </ul>
        		
                <div class="tab_container">                	                    
                    <!-- BUSQUEDA DE CLIENTE QUE YA COMPRARON CON OPCIONES DE MODIFICACION -->
                    <div id="tab1" class="tab_content">
                    	<?php
						$getArticle = $_GET['idoferta'];
						$SQL = "SELECT productos.IdOferta, productos.Activo, productos.Promo, productos.Nombre, productos.Nombre_Producto, opcionesoferta.Valor, opcionesoferta.Ahorro,
							 	opcionesoferta.Precio, opcionesoferta.GastosEnvio, productos.Ficha, productos.Texto1, imagenes.BaseUrl, imagenes.Imagen,
								opcionesoferta.Id, opcionesoferta.OptActiva 
								FROM productos 								
								INNER JOIN opcionesoferta ON productos.IdOferta = opcionesoferta.IdOpcion
								LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1
								WHERE productos.IdOferta = '$getArticle'";
						$db->setQuery($SQL);
						$row = $db->execute();
						if (mysqli_num_rows($row) > 0) {
							$OBJ = $db->loadObject();
							echo '
								<table>
        		                	<tr>
                		            	<td><span style="font-variant: small-caps; font-family: Trebuchet MS, Arial, Helvetica, sans-serif; font-size: 20px; color: #0877BF">'.utf8_encode($OBJ->Nombre_Producto).'</span></td>
                        		    </tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
		                            <tr>
        		                    	<td><img src="'.$OBJ->BaseUrl.'/'.$OBJ->Imagen.'" width="50%" /></td>
                		            </tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
                        		    <tr>
                            			<td><span style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif; font-size: 18px; color: #484848; font-variant: small-caps">Caracter&iacute;sticas del Producto</span></td>
		                            </tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td align="left">';
										$file = $OBJ->Ficha;																				
										$archivo = ucfirst($file); 
										$archivo = nl2br($archivo);
										echo "<span style='color: #484848; font-size: 13px; width: 700px; margin: 0 auto; display: block'" . html_entity_decode(utf8_encode($archivo)) . "</span>";
										echo '
										</td>
									</tr>
									<tr>
										<form>
											<td align="center"><input id="back" name="back" type="button" value="VOLVER" onclick="history.back()" /></td>
										</form>
									</tr>
	    	                    </table>
							';
						}
						?>                    	
                    </div>
                    <!-- REGISTRO DE NUEVOS PEDIDOS
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