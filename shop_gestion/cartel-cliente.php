<?php
	require_once('config.inc.php');
	require_once('conexion/conexion.inc.php');
	$db = DataBase::getInstance();
	
	$orden = $_GET["orden"];	
	//$lineaorden = $_GET['lineaorden'];
	
	
	$SQL = "SELECT ordenes.*, productos.Nombre, productos.Nombre_Producto, opcionesoferta.Opcion, opcionesoferta.OptActiva, opcionesoferta.Precio, opcionesoferta.OptActiva, direcciones.*  FROM ordenes ";
	$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
	$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
	$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
	$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	$SQL .= "INNER JOIN relordendireccion ON usuarios.Id = relordendireccion.IdCliente ";
	$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion ";
	$SQL .= "LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1 ";
	$SQL .= "WHERE ordenes.IdOrden = '$orden'";
	$db->setQuery($SQL);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Cartel | Cliente</title>
        <style type="text/css">
			label {
				font-size: 18px;
				}
			#Empresa {
				border: 0px;
				font-size: 20px;
				text-transform: uppercase;
				max-width: 530px;
				font-weight: bold;
				font-family: "Times New Roman", Times, serif;
				text-align:left;
				}
			#Producto {
				border: 0px;
				text-transform: uppercase;
				outline: none;
				max-width: 530px;
				font-weight: bold;
				}
			input[type=text] {
				border: 0px;
				width: 250px;
				height: 30px;
				text-transform: uppercase;
				font-size: 20px;
				font-weight: bold;
				color: #000;
				font-family: "Times New Roman", Times, serif;
				}
			input[type=text]:focus { background: #FFC; outline: none; }
			textarea {
				font-family: "Times New Roman", Times, serif;
				font-size: 13px;
				width: 530px;
				}
			textarea:focus {  background: #FFC; outline: none; }
		</style>
        <script type="text/javascript" src="../elpais_seleccion/Js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript">
			$(document).ready(function(e) {
                $('#Print').click(function(){
					window.print();
				});
            });
		</script>
	</head>
<body>
<div style="width: 530px; margin: 0 auto; margin-top: 50px;">
	<?php 
		$cont = 0;
		$array = array();
		$row = $db->loadObjectList();
		foreach($row as $row1) { 
			$date = date('d-m-Y');
			$array[$cont] = array(
				'Nombres'=>utf8_encode($row1->D_Nombres), 
				'Apellidos'=>utf8_encode($row1->D_Apellidos),
				'Direccion'=>utf8_encode($row1->Direccion).' '.utf8_encode($row1->Numero).' '.utf8_encode($row1->Piso).' '.utf8_encode($row1->Puerta),
				'Poblacion'=>utf8_encode($row1->Poblacion),
				'Provincia'=>utf8_encode($row1->Provincia),
				'CodPostal'=>$row1->Cp,
				'Telefono'=>$row1->Telefono,
				'Producto'=>utf8_encode($row1->Nombre_Producto),
				'OpcionId'=>$row1->OptActiva,
				'Opcion'=>utf8_encode($row1->Opcion)
				);				
			$cont++;
		}
	?>
    
		<table width="530" border="0" cellpadding="0" cellspacing="0">
    		<tr>
        		<td colspan="2" align="left"><strong style="font-size: 30px"><?php echo $nombre; ?></strong></td>
	        </tr>
    	    <tr>
	    		<td>&nbsp;</td>
	    		<td>&nbsp;</td>
	        </tr>
		  <tr>
	    	<td width="270">
            	<input type="text" style="font-size: 30px" value="<?php echo $empresaTransporte .' - '. $codSeur; ?>" />
            </td>
		    <td width="260"><div align="right"><p><strong style="font-size:25px"><u>FECHA: <?php echo $date; ?></u></strong></p></div></td>
    	  </tr>
		  <tr>
		    <td colspan="2" align="left">
          	<strong style="font-size:24px"><u>PORTES PAGADOS EN ORIGEN &ldquo;RMT. </u>
            <!--<strong style="font-size:24px"><u> </u></strong><strong style="font-size:22px"><u>UNITED DEALS AND BRANDS 2011 S.L.&rdquo;</u></strong> -->
            <textarea id="Empresa" rows="2" cols="60" style="font-size: 27px" ><?php echo $empresa; ?></textarea>
            </td>	   
	      </tr>
		 
		  <tr>
		    <td><p><u><label>ENTREGAR EN:</label></u></p></td>
	    	<td>&nbsp;</td>
	      </tr>
		  <tr>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
	      </tr>
		  <tr>
		    <td colspan="2">
            	<input type="text" id="Nombres" name="Nombres" style="width: 500px" value="<?php echo $array[0]['Nombres'] . " " . $array[0]['Apellidos']; ?>" />
             </td>
    	  </tr>
		  <tr>
		    <td colspan="2">
            	<input type="text" id="Direccion" name="Direccion" style="width: 500px" value="<?php echo $array[0]['Direccion']; ?>" />
             </td>
	      </tr>
		  <tr>
	    	<td colspan="2">
            	<input type="text" id="Cp" name="Cp" style="width:500px" value="<?php echo $array[0]['CodPostal'] . " - " . $array[0]['Poblacion']; ?>" />
            </td>
	      </tr>
		  <tr>
		    <td colspan="2">
            	<input type="text" id="Provincia" name="Provincia" value="<?php echo $array[0]['Provincia']; ?>" />
            </td>
    	  </tr>
		  <tr>
		    <td>
            	<input type="text" id="Telefono" name="Telefono" value="<?php echo $array[0]['Telefono']; ?>" />
            </td>
		    <td>&nbsp;</td>
    	  </tr>
		  <tr>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
    	  </tr>
		  <tr>
		    <td colspan="2">
            	<table>
                	<tr>
                        <td>
		                    <textarea id="Producto" name="Producto" rows="6" style="overflow: hidden; font-size: 18px; text-align: left">
								<?php 
									for ($a=0; $a <= $cont; $a++) {
										echo $array[$a]['Producto'];
										if ($array[$a]['OpcionId'] == 1) {	                    		    	
    	                    				echo " - " . $array[$a]['Opcion'] . "\n";
										} else {
											echo "\n";
										}
									}
								?>
        		            </textarea>
                        </td>
                    </tr>
                </table>
            </td>
	      </tr>
          
		  <tr>
		    <td colspan="2"></td>		    
	      </tr>
          
          <tr>
          	<td colspan="2" align="right">
            	<button type="button" id="Print" onclick="print()"><span>Print</span></button>
            </td>
          </tr>
		</table>
</div>
</body>
</html>