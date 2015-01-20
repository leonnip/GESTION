<?php
	function PedidosProductoContra($orden, $lineaorden, $idoferta, $nombreP, $db, $group_number){
		include('config.inc.php');
		echo '
		<div id="frm">
			<form id="form_'.$group_number.'" name="form-cambiar-estadosC" method="post" data-title="group_'.$group_number.'" action="cambiar-estados.orders.inc.php" enctype="application/x-www-form-urlencoded">
				<input type="hidden" id="listados" name="listados" value="1" />
				<input type="hidden" id="idofertaList" name="idofertaList" value="'.$idoferta.'" />
				<fieldset id="group_'.$group_number.'">
					<table id="resultSearch_Opt" cellpadding="0" cellspacing="0" style="margin-top: 25px; margin-bottom: 25px">
					<thead>
						<th colspan="14" align="center">'.utf8_encode($nombreP).'</th>
						<input type="hidden" name="nombreOfert" value="'.utf8_encode($nombreP).'" />
					</thead>
		        	<thead>
						<th width="30px">User</th>
						<th width="30px">País</th>
			   	      	<th width="60px">Orden</th>
        			    <th width="160px">Fecha</th>
            		    <th width="250px">Nombres</th>
                		<th width="250px">Producto</th>
                    	<th width="80px">Imagen</th>
	                    <th width="20px">#</th>  
                        <th width="100px">Refer</th>                                    
    	                <th width="100px">Talla/Tipo</th>  
						<th width="30px">Agencia</th>                                  
        	            <th width="100px">Estado</th>
            	        <th width="32px">Pago</th>
                	    <th width="30px"></th>                                   
			        </thead>
    				<!-- PEDIDOS CONTRA-REMBOLSO -->
            		<tbody id="dataCustomerOrder">';
          
			//DETECTAMOS LA CANTIDAD DE ORDENES REALIZADAS POR CONTRA-REMBOLSO
			$SQL = "SELECT ordenes.IdOrden, lineasorden.Id ";
			$SQL .= "FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
			$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
			$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
			$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";		
			$SQL .= "INNER JOIN agenciatransportes ON opcionesoferta.IdAgencia = agenciatransportes.IdAgencia ";
			$SQL .= "WHERE TRIM(lineasorden.EstadoPedido) = 'Transito' ";
			$SQL .= "AND TRIM(ordenes.EstadoPago) ='ok' AND TRIM(ordenes.FormaPago) = 'contra-rembolso' AND productos.Listados = 1 AND productos.IdOferta = '$idoferta'";									
			$db->setQuery($SQL);
			$row = $db->execute();
				if (mysqli_num_rows($row) > 0) {
					$result = $db->loadObjectList();
					foreach($result as $result1) {
						$ord[] = array('idorden'=>$result1->IdOrden, 'lineaorden'=>$result1->Id); 
					}
				}
			//FIN
											
			//DATOS PARA PRESENTAR
				$contOrders = count($ord);										
			//FIN
			
			for ($i = 0; $i <= $contOrders; $i++) {
				$SQL = "SELECT ordenes.*, ordenes.Hora AS Clock, lineasorden.Id as lineasOrden, lineasorden.Cantidad, lineasorden.GastosEnvio as GEnvio, lineasorden.EstadoPedido, ";
				$SQL .= "productos.*, opcionesoferta.*, usuarios.*, relordendireccion.*, direcciones.*, agenciatransportes.*, imagenes.BaseUrl, imagenes.Imagen FROM ordenes ";
				$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden AND ordenes.IdOrden = '".$ord[$i]['idorden']."' AND lineasorden.Id = '".$ord[$i]['lineaorden']."' ";
				$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
				$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
				$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
				$SQL .= "INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden ";
				$SQL .= "INNER JOIN agenciatransportes ON opcionesoferta.IdAgencia = agenciatransportes.IdAgencia ";
				$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion AND usuarios.Id = direcciones.D_IdCliente ";
				$SQL .= "LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1 ";
				$SQL .= "WHERE ordenes.FormaPago = 'contra-rembolso' AND ordenes.EstadoPago = 'ok'";
				$db->setQuery($SQL);
												$row = $db->execute();
										
												if (mysqli_num_rows($row) > 0) {
													$result1 = $db->loadObject();
													//foreach($result as $result1) {
														echo '
														<tr>
															<td height="24px">';
																if (trim($result1->Tramitado) == 'usuario-web') { echo '<img src="images/usuario-web.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else if (trim($result1->Tramitado) == 'call-center') { echo '<img src="images/call-center.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else { echo '<img src="images/usuario.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
															echo '
															</td>
															<td height="24px"><img src="images/'.$result1->D_Pais.'.png" width="16px" /></td>
															<td height="24px"><label>'.$result1->IdOrden.'</label></td>
															<td height="24px"><label>'.$result1->FechaOrden.' | '.$result1->Clock.'</label></td>
															<td height="24px"><label>'.utf8_encode(ucwords(strtolower($result1->Nombres))).' ' .utf8_encode(ucwords(strtolower($result1->Apellidos))).'</label></td>
															<td height="24px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
															<td height="24px">
																<img src="'.$result1->BaseUrl . $result1->Imagen.'" width="30px" />
															</td>
															<td height="24px"><label>'.$result1->Cantidad.'</label></td>
															<td height="24px">';
																if ($result1->Referencia == '0')
																	echo '<label style="color: red">'.$result1->Referencia.'</label>';
																else
																	echo '<label>'.$result1->Referencia.'</label>';
															echo '
															</td>
															<td height="24px">';
															if ($result1->OptActiva == 1) echo '<label>'.utf8_encode($result1->Opcion).'</label>';
															echo '
															</td>
															<td>';
																if ($result1->IdAgencia == 1) echo '<label style="color: #0099CC">'.$result1->CodAgencia.'</label>'; else
																if ($result1->IdAgencia == 2) echo '<label style="color: #009900">'.$result1->CodAgencia.'</label>'; else
																echo '<label style="color: #800000">'.$result1->CodAgencia.'</label>';
																echo '
															</td>
															<td height="24px">';															
																echo '<label style="color: red">'.$result1->EstadoPedido.'</label>';																
																echo '
															</td>
															<td><img src="images/contrap.png" title="'.$result1->FormaPago.'" style="cursor: pointer" /></td>
															<td height="24px"><input class="check" type="checkbox" name="estados[]" value="'.$result1->IdOrden.'|'.$result1->lineasOrden.'" /></td>													
														</tr>
														';
														$cantidad = $cantidad + $result1->Cantidad;
														$array_orders_contra[] = array('nombres'=>utf8_encode($result1->D_Nombres).' '.($result1->D_Apellidos), 'dni'=>$result1->Dni, 'email'=>$result1->Email,
																				   'direccion'=>($result1->TipoVia).' '.($result1->Direccion).','.$result1->TipoNumero.','.($result1->Numero).','.($result1->Piso).
																				   ','.($result1->Puerta), 'cp'=>$result1->Cp, 'poblacion'=>($result1->Poblacion), 'provincia'=>($result1->Provincia),
																				   'telefono'=>($result1->Telefono), 'producto'=>($result1->Nombre_Producto), 'tipo'=>($result1->Opcion),
																				   'precio'=>$result1->Precio, 'gastosenvio'=>$result1->GEnvio, 'cantidad'=>$result1->Cantidad, 'total'=>$result1->Total, 'referencias'=>$result1->Referencia,
																				   'mensaje'=>$result1->Comentarios, 'idorden'=>$result1->IdOrden, 'pesoreal'=>$result1->PesoReal); 
													//}
												} 									
					}
					echo '<tr style="background: #1F88A7;" height="25px">
								<td colspan="5"></td>
								<td><strong style="font-size:14px; color:#FFF;">Pedidos</strong></td>
								<td><strong style="font-size: 14px; color: #FFF">'.$cantidad.'</strong></td><td colspan="3"></td>
								<td></td><td></td><td></td><td></td>
							</tr>';
				            echo '            
		                	</tbody>
            			</table>
			
						<table id="updateEstados">
			            	<tr>
            			    	<td align="center"><a rel="group_'.$group_number.'" href="#select_all" class="linkBoton"><img src="images/tick2.png" style="float: left; padding: 4px 0px 2px 7px" />Seleccionar Todos</a></td>
								<td align="center"><a rel="group_'.$group_number.'" href="#select_none" class="linkBoton"><img src="images/tickno.png" style="float: left; padding: 4px 0px 2px 7px" />Deseleccionar Todos</a></td>
								<td align="center"><a rel="group_'.$group_number.'" href="#invert_selection" class="linkBoton"><img src="images/invert.png" style="float: left; padding: 4px 0px 2px 7px" />Invertir Selección</a></td>	
			    	        </tr>
			        	    <tr>
            				    <td colspan="3" align="center" style="background: white">
									<div class="divSelect" style="width:200px">
					                	<select id="estadoOrder" name="estadoOrder" data-placeholder="Seleccione Estado">
					                    	<option value="0"></option>
        	        				        <option value="Enviado">Enviado</option>
					                        <option value="Entregado">Entregado</option>
                					        <option value="Incidencia">Incidencia</option>
				    	                    <option value="Transito">Transito</option>
            					         </select>
									</div>
				             	</td>
			    	        </tr>
        				    <tr>
			            	    <td colspan="3" align="center">
            			            <input type="hidden" id="tipoPedido" name="tipoPedido" value="contra-rembolso" />                                            
			                	    <button type="submit" id="cambiarEstadoC" name="cambiarEstadoC"><img src="images/change-estado.png" style="float: left; padding: 2px 0px 2px 7px" />Cambiar estado</button>
                			    </td>
    			            </tr>
	            		</table>
					</fieldset>
				</form>
			</div>';
			
			echo '		
			<hr />
            <table id="exportExcel">
                <tr>
                  	<td align="center">
                       	<form name="'.$idoferta.'" action="export-excel-contra.inc.php" method="post" enctype="application/x-www-form-urlencoded">
                           	<input type="hidden" id="datosExcel" name="datosExcel" value="'.array_envia($array_orders_contra) .'" />
	                       	<button type="submit" id="exportExcelContra" name="exportExcelContra" ><img src="images/excel.png" style="float: left; padding: 2px 0px 2px 7px" />Exportar a Excel</button>
                        </form>
                    </td>
                </tr>
            </table>';
	}
	
	
	
	function PedidosProductoTarjeta($orden, $lineaorden, $idoferta, $nombreP, $db, $group_number) {
		include('config.inc.php');
		echo '
		<div id="frm">
        	<form id="form_'.$group_number.'" name="form-cambiar-estadosT" method="post" action="cambiar-estados.orders.inc.php" data-title="group_'.$group_number.'" enctype="application/x-www-form-urlencoded">
				<input type="hidden" id="listados" name="listados" value="1" />
				<input type="hidden" id="idofertaList" name="idofertaList" value="'.$idoferta.'" />
            	<fieldset id="group_'.$group_number.'">
                	<table id="resultSearch_Opt" cellpadding="0" cellspacing="0">
						<thead>
							<th colspan="14" align="center">'.utf8_encode($nombreP).'</th>
							<input type="hidden" name="nombreOfert" value="'.utf8_encode($nombreP).'" />
						</thead>
                    	<thead>
							<th width="30px">User</th>
							<th width="30px">País</th>
                           <th width="60px">Orden</th>
	                       <th width="160px">Fecha</th>
    	                   <th width="250px">Nombres</th>
        	               <th width="250px">Producto</th>
            	           <th width="80px">Imagen</th>
                	       <th width="20px">#</th>  
                           <th width="100px">Refer</th>                                     
                    	   <th width="100px">Talla/Tipo</th>  
						   <th width="30px">Agencia</th>                                  
                           <th width="100px">Estado</th>
                           <th width="32px">Pago</th>
                           <th width="30px"></th>                                   
                       </thead>
	                   <!-- PEDIDOS TARJETA PAYPAL -->
    	               <tbody id="dataCustomerOrder">';
        	           
					  		//DETECTAMOS LA CANTIDAD DE ORDENES REALIZADAS POR CONTRA-REMBOLSO
							$SQL = "SELECT ordenes.IdOrden, lineasorden.Id ";
							$SQL .= "FROM ordenes INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
							$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
							$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
							$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";	
							$SQL .= "INNER JOIN agenciatransportes ON opcionesoferta.IdAgencia = agenciatransportes.IdAgencia ";	
							$SQL .= "WHERE TRIM(lineasorden.EstadoPedido) = 'Transito' ";
							$SQL .= "AND ordenes.EstadoPago ='ok' AND (ordenes.FormaPago = 'tarjeta' OR ordenes.FormaPago = 'paypal') AND productos.Listados AND productos.IdOferta = '$idoferta'";									
							$db->setQuery($SQL);
							$row = $db->execute();
								if (mysqli_num_rows($row) > 0) {
									$result = $db->loadObjectList();
									foreach($result as $result1) {
										$ordT[] = array('idorden'=>$result1->IdOrden, 'lineaorden'=>$result1->Id); 
									}
								}
							//FIN
										
							//DATOS PARA PRESENTAR
							$contOrdersT = count($ordT);											
							//FIN
							for ($i = 0; $i <= $contOrdersT; $i++) {
								$SQL = "SELECT ordenes.*, ordenes.Hora AS Clock, lineasorden.Id as lineasOrden, lineasorden.Cantidad, lineasorden.GastosEnvio as GEnvio, lineasorden.EstadoPedido, ";
								$SQL .= "productos.*, opcionesoferta.*, usuarios.*, relordendireccion.*, direcciones.*, agenciatransportes.*, imagenes.BaseUrl, imagenes.Imagen FROM ordenes ";
								$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden AND ordenes.IdOrden = '".$ordT[$i]['idorden']."' AND lineasorden.Id = '".$ordT[$i]['lineaorden']."' ";
								$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
							 	$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	 							$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
	 							$SQL .= "INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden ";
								$SQL .= "INNER JOIN agenciatransportes ON opcionesoferta.IdAgencia = agenciatransportes.IdAgencia ";
								$SQL .= "INNER JOIN direcciones ON relordendireccion.IdDireccion = direcciones.IdDireccion AND usuarios.Id = direcciones.D_IdCliente ";
								$SQL .= "LEFT JOIN imagenes ON productos.IdOferta = imagenes.IdOferta AND imagenes.Estado = 1 ";
								$SQL .= "WHERE (ordenes.FormaPago = 'tarjeta' OR ordenes.FormaPago = 'paypal') AND ordenes.EstadoPago = 'ok'";
								$db->setQuery($SQL);
											$row = $db->execute();
											
											if (mysqli_num_rows($row) > 0) {
												$result1 = $db->loadObject();												
													echo '
													<tr>
														<td height="24px">';
																if (trim($result1->Tramitado) == 'usuario-web') { echo '<img src="images/usuario-web.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else if (trim($result1->Tramitado) == 'call-center') { echo '<img src="images/call-center.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
																else { echo '<img src="images/usuario.png" title="'.utf8_encode($result1->Tramitado).'" />'; }
															echo '
														</td>
														<td height="24px"><img src="images/'.$result1->D_Pais.'.png" width="16px" /></td>
														<td height="24px"><label>'.$result1->IdOrden.'</label></td>
														<td height="24px"><label>'.$result1->FechaOrden.' | '.$result1->Clock.'</label></td>
														<td height="24px"><label>'.utf8_encode(ucwords(strtolower($result1->Nombres))).' ' .utf8_encode(ucwords(strtolower($result1->Apellidos))).'</label></td>
														<td height="24px"><label>'.utf8_encode($result1->Nombre_Producto).'</label></td>
														<td height="24px">
															<img src="'.$result1->BaseUrl . $result1->Imagen.'" width="30px"/>
														</td>
														<td height="24px"><label>'.$result1->Cantidad.'</label></td>
														<td height="24px">';
															if ($result1->Referencia == '0')
																echo '<label style="color: red">'.$result1->Referencia.'</label>';
															else
																echo '<label>'.$result1->Referencia.'</label>';
														echo '
														</td>
														<td height="24px">';
														if ($result1->OptActiva == 1) echo '<label>'.utf8_encode($result1->Opcion).'</label>';
														echo '
														</td>
														<td>';
																if ($result1->IdAgencia == 1) echo '<label style="color: #0099CC">'.$result1->CodAgencia.'</label>'; else
																if ($result1->IdAgencia == 2) echo '<label style="color: #009900">'.$result1->CodAgencia.'</label>'; else
																echo '<label style="color: #800000">'.$result1->CodAgencia.'</label>';
																echo '
														</td>
														<td height="24px">';															
															echo '<label style="color: red">'.$result1->EstadoPedido.'</label>';															
														echo '</td>
														<td>';
															if ($result1->FormaPago == 'tarjeta') { $imagen = 'visap.png'; } else if ($result1->FormaPago == 'paypal') { $imagen = 'paypalp.png'; }															
														echo '
															<img src="images/'.$imagen.'" title="'.$result1->FormaPago.'='.$result1->Code_Authorisation.'" style="cursor: pointer" />
														</td>
														<td height="24px"><input class="check" type="checkbox" name="estados[]" value="'.$result1->IdOrden.'|'.$result1->lineasOrden.'" /></td>														
													</tr>
													';
													$cantidadT = $cantidadT + $result1->Cantidad;	
													$array_orders_tarjeta[] = array('nombres'=>($result1->D_Nombres).' '.($result1->D_Apellidos), 'dni'=>$result1->Dni, 'email'=>$result1->Email, 
																				   'direccion'=>($result1->TipoVia).' '.($result1->Direccion).','.$result1->TipoNumero.','.($result1->Numero).','.($result1->Piso).
																				   ','.($result1->Puerta), 'cp'=>$result1->Cp, 'poblacion'=>($result1->Poblacion), 'provincia'=>($result1->Provincia),
																				   'telefono'=>($result1->Telefono), 'producto'=>($result1->Nombre_Producto), 'tipo'=>($result1->Opcion),
																				   'precio'=>$result1->Precio, 'gastosenvio'=>$result1->GEnvio, 'cantidad'=>$result1->Cantidad, 'total'=>$result1->Total, 'referencias'=>$result1->Referencia,
																				   'mensaje'=>$result1->Comentarios, 'idorden'=>$result1->IdOrden, 'pesoreal'=>$result1->PesoReal); 											
											}											
							}
							echo '<tr style="background: #1F88A7;" height="25px">
								  	  <td colspan="5"></td>
									  <td><strong style="font-size:14px; color:#FFF;">Pedidos</strong></td>
									  <td><strong style="font-size: 14px; color: #FFF">'.$cantidadT.'</strong></td><td colspan="3"></td>
									  <td></td><td></td><td></td><td></td>
								  </tr>										                                 
        	                        </tbody>
            	                </table>
                            </fieldset>
                            <hr />
                            <table id="updateEstados">
                            	<tr>
                                	<td align="center"><a rel="group_'.$group_number.'" href="#select_all" class="linkBoton"><img src="images/tick2.png" style="float: left; padding: 4px 0px 2px 7px" />Seleccionar Todos</a></td>
									<td align="center"><a rel="group_'.$group_number.'" href="#select_none" class="linkBoton"><img src="images/tickno.png" style="float: left; padding: 4px 0px 2px 7px" />Deseleccionar Todos</a></td>
									<td align="center"><a rel="group_'.$group_number.'" href="#invert_selection" class="linkBoton"><img src="images/invert.png" style="float: left; padding: 4px 0px 2px 7px" />Invertir Selección</a></td>	
                                </tr>
                                <tr>
                                	<td colspan="3" align="center" style="background: white">
										<div class="divSelect" style="width:200px">
	                                    	<select id="estadoOrder" name="estadoOrder" data-placeholder="Seleccione Estado">
    	                                    	<option value="0"></option>
        	                                    <option value="Enviado">Enviado</option>
            	                                <option value="Entregado">Entregado</option>
                	                            <option value="Incidencia">Incidencia</option>
                    	                        <option value="Transito">Transito</option>
                        	                </select>
										</div>
                                    </td>
                                </tr>
                                <tr>
                                	<td colspan="3" align="center">     
                                    	<input type="hidden" id="tipoPedido" name="tipoPedido" value="tarjeta" />                                	
                                        <button type="submit" id="cambiarEstadoT" name="cambiarEstadoT"><img src="images/change-estado.png" style="float: left; padding: 2px 0px 2px 7px" />Cambiar estado</button>
                                    </td>
                                </tr>
                            </table>
                            </form>
                            </div>';
							
			echo '		
			<hr />
            <table id="exportExcel">
                <tr>
                  	<td align="center">
                       	<form name="'.$idoferta.'" action="export-excel-tarjeta.inc.php" method="post" enctype="application/x-www-form-urlencoded">
                           	<input type="hidden" id="datosExcel" name="datosExcel" value="'.array_envia($array_orders_tarjeta) .'" />
	                       	<button type="submit" id="exportExcelContra" name="exportExcelContra" ><img src="images/excel.png" style="float: left; padding: 2px 0px 2px 7px" />Exportar a Excel</button>
                        </form>
                    </td>
                </tr>
            </table>';
				
	}
?>