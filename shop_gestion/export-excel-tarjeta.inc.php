<html>
	<head>
    	<META httpequiv="ContentType" content="text/html; charset=UTF-8">  
    </head>
    <body>
<?php
	$date = date('Y-m-d');
	define('CHARSET', 'UTF-8');
	header("Content-type: application/vnd.ms-excel; charset='".CHARSET."'");
    header("Content-Disposition: attachment; filename=T_ordenes_".$date.".xls");
    
    include("conexion/conexion.inc.php");
    $db = DataBase::getInstance();

	require_once("config.inc.php");

	function array_recibe($url_array) { 
    	$tmp = stripslashes($url_array); 
    	$tmp = urldecode($tmp); 
    	$tmp = unserialize($tmp); 
   		return $tmp; 
	} 
	
	$pedidos = array_recibe($_POST['datosExcel']);
	$cont = count($pedidos);
	
	//-PRODUCTOS 
	/*
	$sql = "SELECT productos.Nombre_Producto, opcionesoferta.Precio, paisesenvio.TotalGastos FROM productos ";
	$sql .= "INNER JOIN lineasorden ON productos.IdOferta = lineasorden.IdProducto ";
	$sql .= "INNER JOIN ordenes ON ordenes.IdOrden = lineasorden.Idorden ";
	$sql .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	$sql .= "INNER JOIN paisesenvio ON paisesenvio.IdPais = '$paisDefecto' ";
	$sql .= "WHERE lineasorden.EstadoPedido = 'Transito' AND opcionesoferta.Peso > paisesenvio.PesoIn AND opcionesoferta.Peso <= paisesenvio.PesoOut GROUP BY productos.IdOferta ASC";
										
	$db->setQuery($sql);
	$result = $db->loadObjectList();
	echo '
		<table border="1" bordercolor="#CCCCCC">
			<tr height="60px">
				<td></td><td></td>
				<td bgcolor="yellow" valign="middle" style="text-align:center; width:450px; font-weight: bold; font-size:13px;">NOMBRE DEL PRODUCTO</td>
				<td bgcolor="yellow" valign="middle" style="text-align:center; width:150px; font-weight: bold; font-size:13px;">DPTO</td>
				<td bgcolor="yellow" valign="middle" style="text-align:center; width:150px; font-weight: bold; font-size:13px;">PRECIO</td>
				<td bgcolor="yellow" valign="middle" style="text-align:center; width:150px; font-weight: bold; font-size:13px;">GASTOS ENVIO</td>
			</tr>
	';
	foreach($result as $result1) {
		$Nombre = $result1->Nombre_Producto;
		$GastosEnvio = $result1->TotalGastos;
		$Precio1 = $result1->Precio;
		echo '
		   	<tr class="color" height="40px">
			<td></td><td></td>
    		<td width="450px" style="text-align:left; width:450px; font-weight: bold; font-size:14px;">'.utf8_encode(strtoupper($Nombre)).'</td>
            <td style="text-align:center; width:150px; font-weight: bold; font-size:14px;">'.utf8_encode($Departamento).'</td>
			<td style="text-align:right; width:150px; font-weight: bold; font-size:14px;">'.$Precio1.'&euro;</td>
			<td style="text-align:right; width:150px; font-weight: bold; font-size:14px;">'.$GastosEnvio.'&euro;</td>
            </tr>';
	}
	echo '</table>';*/
	//-FIN PRODUCTOS
	
	# CABECERA EXCEL
	# =============================
	echo '<hr />';
	echo '
		<table>
			<tr>
				<td style="font-weight: bold; font-size: 20px">Pedidos '.$_SESSION['NAME_TIENDA'].'</td>
			</tr>
		</table>
	';
	echo '<hr />';
	# ==============================
	
	# -ORDENES
	$thead = '<table border="1" bordercolor="#CCCCCC" cellpadding="10" cellspacing="10">
   		<tr height="30px">
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:450px; font-weight: bold; font-size:13px;">NOMBRES DESTINATARIO</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:100px;font-weight: bold; font-size:13px;">DNI / CIF</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:400px;font-weight: bold; font-size:13px;">DIRECCION</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:100px;font-weight: bold; font-size:13px;">CP</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:200px;font-weight: bold; font-size:13px;">POBLACION</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:200px;font-weight: bold; font-size:13px;">PROVINCIA</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:150px;font-weight: bold; font-size:13px;">TEL. DE CONTACTO</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:150px;font-weight: bold; font-size:13px;">EMAIL</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:500px;font-weight: bold; font-size:13px;">DESCRIPCION</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:300px;font-weight: bold; font-size:13px;">TIPO</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:100px;font-weight: bold; font-size:13px;">IMPORTE UNIDAD</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:100px;font-weight: bold; font-size:13px;">GASTO ENVÍO / UNIDAD</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:100px;font-weight: bold; font-size:13px;">UNIDADES</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:100px;font-weight: bold; font-size:13px;">PESO UNIDAD</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:100px;font-weight: bold; font-size:13px;">TOTAL A FACTURAR</td>
			<td bgcolor="red" valign="middle" style="text-align:center; width:100px;font-weight: bold; font-size:13px;">TOTAL COBRAR CLIENTE</td>
			
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:200px;font-weight: bold; font-size:13px;">REFERENCIAS INCLUIDAS</td>
			
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:300px;">OBSERVACIONES</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:100px;font-weight: bold; font-size:13px;">NUMERO DE ORDEN (REF.)</td>
			<td bgcolor="yellow" valign="middle" style="text-align:center; width:100px;font-weight: bold; font-size:13px;">REMITENTE</td>
    ';
    echo utf8_decode($thead);
	
	for ($index=0; $index <= $cont-1; $index++) {
		$Total = (($pedidos[$index]['cantidad']) * ($pedidos[$index]['precio'])) + ($pedidos[$index]['gastosenvio']);
		echo '
			<tr>
				<td style="font-size:12px;text-align:left">'.utf8_encode($pedidos[$index]['nombres']).'</td>
				<td style="font-size:12px;text-align:left">'.utf8_encode($pedidos[$index]['dni']).'</td>
				<td style="font-size:12px;text-align:left">'.utf8_encode($pedidos[$index]['direccion']).'</td>
				<td style="font-size:12px;text-align:left">'.utf8_encode($pedidos[$index]['cp']).'</td>
				<td style="font-size:12px;text-align:left">'.utf8_encode($pedidos[$index]['poblacion']).'</td>
				<td style="font-size:12px;text-align:left">'.utf8_encode($pedidos[$index]['provincia']).'</td>
				<td style="font-size:12px;text-align:left">'.utf8_encode($pedidos[$index]['telefono']).'</td>
				<td style="font-size:12px;text-align:left">'.utf8_encode($pedidos[$index]['email']).'</td>
					
				<td style="font-size:12px;text-align:left">'.utf8_encode($pedidos[$index]['producto']).'</td>
				<td style="font-size:12px;text-align:center">'.utf8_encode($pedidos[$index]['tipo']).'</td>
				<td style="font-size:12px;text-align:center; font-weight: bold;">'.$pedidos[$index]['precio'].'</td>
				<td style="font-size:12px;text-align:center; font-weight: bold;">'.($pedidos[$index]['gastosenvio'])/($pedidos[$index]['cantidad']).'&euro;</td>
				<td style="font-size:12px;text-align:center; font-weight: bold;">'.$pedidos[$index]['cantidad'].'</td>
				<td style="font-size:12px;text-align:center; font-weight: bold;">'.$pedidos[$index]['pesoreal'].' Kg.</td>
				<td style="font-size:12px;text-align:center; font-weight: bold;">'.$Total.'&euro;</td>
				<td style="font-size:12px;text-align:center; font-weight: bold;">0&euro;</td>
				<td style="font-size:12px;text-align:center; font-weight: bold;">'.$pedidos[$index]['referencias'].'</td>
				<td style="font-size:12px;text-align:center">'.utf8_encode($pedidos[$index]['mensaje']).'</td>
				<td style="font-size:12px;text-align:center; font-weight: bold;">'.$pedidos[$index]['idorden'].$_SESSION['TPV_ORDEN'].'</td>
				<td style="font-size:12px;text-align:center; font-weight: bold;">'.$_SESSION['NAME_TIENDA'].'</td>
			</tr>
		';
	 }
	 echo "</table>";
	 # MOSTRAMOS LA FECHA DE EXPORTACION
	 # ==========================================
	 echo '<hr />';
	 echo '
		<table>
			<tr>
				<td style="font-weight: bold; font-size: 13px">'.$dias[date("w")].', '.date("d").' de '.$meses[date("n")-1]. ' del '.date("Y").' - '.date('g:i a').'</td>
			</tr>
		</table>
	 ';
	 echo '<hr />';
	 # ==========================================
?>
	</body>
</html>
