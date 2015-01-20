<html>
	<head>
    	<META httpequiv="ContentType" content="text/html; charset=UTF-8">  
    </head>
    <body>
<?php
	$date = date('Y-m-d');
	header("Content-type: application/vnd.ms-excel,");
    header("Content-Disposition: attachment; filename=pedidos_tarjeta_elpaisseleccion_".$date.".xls");
	
	require_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
	
	include("config.inc.php");
	
	if (isset($_POST['estados'])) {
		$estadoOrder = $_POST['estadoOrder'];
		$tipoPedido = $_POST['tipoPedido'];
		$estado = $_POST['estados'];
		$n		= count($estado);
		$i		= 0;
		
		/*
		//-PRODUCTOS 		
			$sql = "SELECT productos.Nombre_Producto, opcionesoferta.Precio, paisesenvio.TotalGastos FROM productos ";
			$sql .= "INNER JOIN lineasorden ON productos.IdOferta = lineasorden.IdProducto ";
			$sql .= "INNER JOIN ordenes ON ordenes.IdOrden = lineasorden.Idorden ";
			$sql .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
			$sql .= "INNER JOIN paisesenvio ON paisesenvio.IdPais = '$paisDefecto' ";
			$sql .= "WHERE opcionesoferta.Peso > paisesenvio.PesoIn AND opcionesoferta.Peso <= paisesenvio.PesoOut GROUP BY productos.IdOferta ASC";
										
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
			echo '</table>';
			echo '<hr />';
		//-FIN PRODUCTOS*/
		
		# HEADER
		# ======================================================================
		echo '<hr />';
		echo '
			<table>
				<tr>
					<td style="font-weight: bold; font-size: 20px">Pedidos '.$_SESSION['NAME_TIENDA'].'</td>
				</tr>
			</table>
		';	
		echo '<hr />';
		# ======================================================================
		
		//-ORDENES
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
					<td bgcolor="yellow" valign="middle" style="text-align:center; width:100px;font-weight: bold; font-size:13px;">ORDEN CLIENTE (REF.)</td>
					<td bgcolor="yellow" valign="middle" style="text-align:center; width:100px;font-weight: bold; font-size:13px;">REMITENTE</td>
				</tr>
		    ';
		echo utf8_decode($thead);
		
		while ($i < $n) {			
			 $orden = explode("|", $estado[$i]);
			
			$SQL = "SELECT *, lineasorden.*, lineasorden.GastosEnvio as GEnvios FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden AND ordenes.IdOrden = '".$orden[0]."' AND lineasorden.Id = '".$orden[1]."' ";
			$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
		 	$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
			$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
			$SQL .= "INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden ";
			$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion AND usuarios.Id = direcciones.D_IdCliente ";
			$SQL .= "WHERE (ordenes.FormaPago = 'tarjeta' OR ordenes.FormaPago = 'paypal') AND ordenes.EstadoPago = 'ok' AND lineasorden.EstadoPedido = 'Transito'";
			$db->setQuery($SQL);
			$row = $db->execute();
		
			if (mysqli_num_rows($row) > 0) {
				$result1 = $db->loadObject();
				echo '
					<tr>
						<td style="font-size:12px;text-align:left">'.utf8_encode($result1->Nombres).' '.utf8_encode($result1->Apellidos).'</td>
						<td style="font-size:12px;text-align:left">'.$result1->Dni.'</td>
						<td style="font-size:12px;text-align:left">'.$result1->TipoVia.' '.utf8_encode($result1->Direccion).', '.utf8_encode($result1->Numero).', '.utf8_encode($result1->Piso).', '.utf8_encode($result1->Puerta).'</td>
						<td style="font-size:12px;text-align:left">'.$result1->Cp.'</td>
						<td style="font-size:12px;text-align:left">'.utf8_encode($result1->Poblacion).'</td>
						<td style="font-size:12px;text-align:left">'.utf8_encode($result1->Provincia).'</td>
						<td style="font-size:12px;text-align:left">'.$result1->Telefono.'</td>
						<td style="font-size:12px;text-align:left">'.$result1->Email.'</td>
					
						<td style="font-size:12px;text-align:left">'.utf8_encode($result1->Nombre_Producto).'</td>
						<td style="font-size:12px;text-align:center">'.utf8_encode($result1->Opcion).'</td>
						<td style="font-size:12px;text-align:center; font-weight: bold;">'.$result1->Precio.'</td>
						<td style="font-size:12px;text-align:center; font-weight: bold;">'.($result1->GEnvios)/($result1->Cantidad).'&euro;</td>
						<td style="font-size:12px;text-align:center; font-weight: bold;">'.$result1->Cantidad.'</td>
						<td style="font-size:12px;text-align:center; font-weight: bold;">'.$result1->PesoReal.' Kg.</td>
						<td style="font-size:12px;text-align:center; font-weight: bold;">'.(($result1->Precio * $result1->Cantidad) + $result1->GEnvios).'&euro;</td>
						<td style="font-size:12px;text-align:center; font-weight: bold;">0&euro;</td>
						<td style="font-size:12px;text-align:center; font-weight: bold;">'.$result1->Referencia.'</td>
						<td style="font-size:12px;text-align:center">'.utf8_encode($result1->Comentarios).'</td>
						<td style="font-size:12px;text-align:center; font-weight: bold;">'.$result1->IdOrden.$_SESSION['TPV_ORDEN'].'</td>
						<td style="font-size:12px;text-align:center; font-weight: bold;">'.$_SESSION['NAME_TIENDA'].'</td>
					</tr>
				';
			 }
			$i++;
		}
		 echo "</table>";
		 
		# FOOTER
		# ======================================================================
		echo '<hr />';
		 echo '
			<table>
				<tr>
					<td style="font-weight: bold; font-size: 13px">'.$dias[date("w")].', '.date("d").' de '.$meses[date("n")-1]. ' del '.date("Y").' - '.date('g:i a').'</td>
				</tr>
			</table>
		 ';
		 echo '<hr />';
		# ======================================================================	
	}
?>
	</body>
</html>