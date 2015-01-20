<?php
	require_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
	
	$pais = $_POST['pais'];
	$carrito = $_COOKIE['usuarioAdmin'];
	
	$SQL = "UPDATE carritocompra SET PaisEnvio = '$pais' WHERE IdCarrito = '$carrito'";
	$db->setQuery($SQL);
	$db->execute();
	$db->freeResults();
	
	echo '
		<table id="order" cellpadding="0" cellspacing="0">
        	<thead>
            	<th width="5%"></th>
                 	<th><label>Nombre de Producto</label></th>
                    <th width="120px"><label>Tipo/Talla</label></th>
                    <th><label>Precio</label></th>
                    <th><label>Cantidad</label></th>
                    <th align="right"><label>Subtotal</label></th>
                 </thead>
                 <tbody>';
                 	$SQL = "SELECT carritocompra.*, productos.IdOferta, productos.Nombre, productos.Nombre_Producto, opcionesoferta.Precio, opcionesoferta.Opcion, opcionesoferta.OptActiva, paisesenvio.TotalGastos, imagenes.BaseUrl, imagenes.Imagen 
							FROM carritocompra 
							INNER JOIN productos ON carritocompra.IdProducto = productos.IdOferta 
							INNER JOIN paisesenvio ON carritocompra.PaisEnvio = paisesenvio.IdPais 
							INNER JOIN opcionesoferta ON carritocompra.Talla = opcionesoferta.Id 
							LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1
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
        		                <td><label>'.$result1->Precio.'</label></td>
                		       	<td>
									<a href="update-amount.inc.php?producto='.$result1->IdOferta.'&talla='.$result1->Talla.'&opcion=down"><img src="images/quantity_down.gif" /></a>&nbsp;&nbsp;
									<label>'.$result1->Cantidad.'</label>&nbsp;&nbsp;
									<a href="update-amount.inc.php?producto='.$result1->IdOferta.'&talla='.$result1->Talla.'&opcion=up"><img src="images/quantity_up.gif" /></a>
								</td>
                        		<td align="right"><label>'.$result1->Precio*$result1->Cantidad.'&euro;</label></td>
                             </tr>';
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
                        <td align="right"><strong><?php echo $subTotal; ?>&euro;</strong></td>
                    </tr>
                    <tr>
                       <td colspan="5" align="right"><label>Gastos de Env&iacute;o&nbsp;&nbsp;&nbsp;&nbsp;&rarr;</label></td>
                       <td align="right"><strong><?php echo $gastosEnvio; ?>&euro;</strong></td>
                    </tr>
                    <?php
					if (isset($_SESSION['Cupon'])) {
						$descuentoCupo = base64_decode($_SESSION['valorCupon']);
						echo '
			                <tr>
            				    <td colspan="5" align="right"><label class="car" style="color: red">CUP&Oacute;N DESCUENTO</label></td>
						        <td align="right"><label class="car" style="color: red">-'.$descuentoCupo.'&euro;</label></td>
            				</tr>
						';
					} else {
						$descuentoCupo = 0;
					}
					?>
                    <tr>                                                	
                       <td colspan="5" align="right"><label>Total IVA incluido&nbsp;&nbsp;&nbsp;&nbsp;&rarr;</label></td>
                       <td align="right"><strong style="color: #09F; font-size: 1.3em"><?php echo $total - $descuentoCupo; ?>&euro;</strong></td>
                    </tr>                                           
                 </tfoot>
             </table>