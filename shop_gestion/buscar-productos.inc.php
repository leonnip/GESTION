<?php
	require_once('conexion/conexion.inc.php');
	include('config.inc.php');
	$db = DataBase::getInstance();
	
	$dato = utf8_decode($_POST['dato']);
	$SQL = "SELECT * FROM productos INNER JOIN opcionesoferta ON productos.IdOferta = opcionesoferta.IdOpcion WHERE Nombre_Producto LIKE '%".$dato."%'";
	$db->setQuery($SQL);
	$row = $db->execute();
	
	if (mysqli_num_rows($row) > 0) {
		$result = $db->loadObjectList();
		foreach($result as $result1) {
			echo '
				<tr height="26px">
					<td><label>'.$result1->IdOferta.'</label></td>
					<td><img src="'.$web.'/productos/'.$result1->Nombre.'/'.$result1->Images1.'" width="30px"/></td>
					<td><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
					<td><label>';
					if ($result1->OptActiva == 1) {
						echo utf8_encode($result1->Opcion);
					}
					echo '
					</label></td>
					<td><label>'.number_format($result1->Precio/$result1->Iva, 2,',','.').'</label></td>
					<td><label>'.number_format($result1->Precio,2,',','.').'</label></td>
					<td><label>'.number_format(($result1->PrecioSesion/$result1->Iva),2,',','.').'</label></td>
					<td bgcolor="#71BA00"><label style="color: white; font-family: tahoma">'.number_format($result1->PrecioSesion,2,',','.').'</label></td>
					<td><label>'.$result1->Referencia.'</label></td>
					<td><label>'.$result1->Facturacion.'</label></td>
				</tr>
				';
			$conta = $conta + 1;
		}
		
		$pagination = 10;
		$reg = $conta % $pagination;
		$tReg = $pagination - $reg;
		$totalRegister1 = $conta + $tReg;
							
		for ($i=1; $i <= $tReg; $i++) {
			echo '<tr height="26px"><td colspan="10"></td></tr>';
		}
		
	} else {
		echo '<tr style="background: none; cursor: default" height="115px"><td colspan="9"><img src="images/logs.png" /><br/><label style="font-variant: small-caps; font-size: 16px; color: #484848" >Sin Resultados a Mostrar</labe></td></tr>';
	}
?>
<script type="text/javascript">
	$(document).ready(function(e) {
        $('#greenL').smartpaginator({ totalrecords: <?php echo $totalRegister1; ?>, recordsperpage: 10, datacontainer: 'resultSearchL', dataelement: 'tbody tr', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });
    });
</script>