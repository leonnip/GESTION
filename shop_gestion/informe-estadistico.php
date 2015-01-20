<?php
session_start();
if ((!isset($_SESSION['Logged'])) && (!isset($_SESSION['UserIdAdmin']))) {
	header('Location: https://'.$_SERVER['HTTP_HOST']);
} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="css/stylos-pag.css" type="text/css" rel="stylesheet" />
        <link href="css/select-styles.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
		<title>ADMIN WEB SERVICE</title>
        <script type="text/javascript" src="js/jquery.tools.min.js"></script>
        <!--<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>-->
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.jcookie.min.js"></script>
         <script type="text/javascript" src="js/jquery.validate.js"></script>
        <script type="text/javascript" src="js/jquery-class.js"></script>   
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
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
			$active4 = 1;
			include('config.inc.php');
			include('menu.inc.php'); 
		?>
    </div> 
    
    <!-- GADGET MENU LATERAL -->
    <?php require("gadget.inc.php"); ?>
    <!-- FIN GADGET -->
    
    <div id="content-body">
    	<div id="general" class="content">
        	<div id="Contenido" style="padding-top: 20px; overflow: auto; overflow-x:hidden"> 
            	<div style="position: absolute; top: 0px; left: 990px; z-index:9999999"><img src="images/pedidos.png" /></div>  
            	<ul class="tabs">
    		    	<li class="active"><a href="#tab1"><span>Informe Ventas Admin.</span></a></li>
		   			<!--<li><a href="#tab2">Cuadro Completo</a></li>
   		  	        <li><a href="#tab3">Cuadro Completo</a></li>-->
		  	    </ul>
        		
                <div class="tab_container">                	                    
                    <!-- INFORME DE PEDIDOS DESDE FECHA INCIO A FECHA FIN -->
                    <div id="tab1" class="tab_content">
                    	<div id="busquedaClientes">	
                        	<div class="fieldset">
                            	<h2 class="legend">Rango de B&uacute;squeda</h2>
                                <span class="tooltip_links">&nbsp;</span>                                
	                        	<form id="formInforme" name="formInforme" method="post" class="loading" action="<?php echo $_SERVER['../elpais_seleccion/PHP_SELF']; ?>" accept-charset="utf-8" enctype="multipart/form-data">
		                        	<table style="width:40%">
    		                        	<thead>
        		                        	<th><label class="cab">Fecha Inicio</label></th>
            		                        <th><label class="cab">Fecha Fin</label></th>
                		                    <th></th>
                    		            </thead>
                        		        <tbody>
                            		    	<tr>
                                		    	<td align="center">
                                    		    	<input type="text" id="Fecha1" name="Fecha1" style="text-align:center; width: 150px" autocomplete="off" />
                	                        	</td>
	                	                        <td align="center">
    	                	                    	<input type="text" id="Fecha2" name="Fecha2" style="text-align: center; width: 150px" autocomplete="off" />
        	                	                </td>                                	            
	            	                        </tr>
                                            <tr>
                                            	<td colspan="2" align="center" height="50px">
                                    	        	<input type="submit" id="consultar" name="consultar" value="Consultar Informe" alt="0" />                                                    
                                        	    </td>
                                            </tr>
    	            	                </tbody>
        	            	        </table>
            	                </form>
                            </div>
                        </div>
                        <div id="resultSearch">
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Informe Ventas Administrador <?php echo $nombre; ?></h3><br />
                            <div id="dvData0">
	                        	<table id="resultSearch_Opt" cellpadding="1" cellspacing="1">
    	                        	<thead>
        	                        	<th colspan="3" align="center">
            	                        	<?php 
												if (isset($_POST['consultar'])) {
													//FUNCIONES PARA SACAR EL DIA SEMANA ESPAÑOL
													function actual_date($anyo, $mes, $dia, $diaSemana)  {  
													    $week_days = array ("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");  
													    $months = array ("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");  
													    //$year_now = date ("Y");  
														$year_now = (int)$anyo;
													    //$month_now = date ("n");  
														$month_now = (int)$mes;
												    	//$day_now = date ("j");  
														$day_now = (int)$dia;
													    //$week_day_now = date ("w");
														$week_day_now = $diaSemana;
													    $date = $week_days[$week_day_now] . ", " . $day_now . " de " . $months[$month_now] . " de " . $year_now;   
													    return $date;    
													}
										
													function diaSemana($ano,$mes,$dia) {
														// 0->domingo	 | 6->sabado
														$dia= date("w",mktime(0, 0, 0, $mes, $dia, $ano));
														return $dia;
													}
													//HASTA AQUI
												
													$f1 = $_POST['Fecha1']; $_f1 = explode('-', $f1); $Y = $_f1[0]; $M = $_f1[1]; $D = $_f1[2]; $diaSemana1 = diaSemana($Y, $M, $D); $fechaTexto1 = actual_date($Y, $M, $D, $diaSemana1);
													$f2 = $_POST['Fecha2']; $_f2 = explode('-', $f2); $Y = $_f2[0]; $M = $_f2[1]; $D = $_f2[2]; $diaSemana2 = diaSemana($Y, $M, $D); $fechaTexto2 = actual_date($Y, $M, $D, $diaSemana2);
										
													if ($_POST['Fecha1'] == $_POST['Fecha2']) { echo 'Fecha de Informe : ' . $fechaTexto1; } else { echo 'Rango Fecha Informe : &nbsp;&nbsp;'.$fechaTexto1. ' &nbsp;-&nbsp; ' .$fechaTexto2; }
												}
											?>
                    	                </th>
                        	        </thead>
                            		<thead>
                                		<th width="80px">#</th>                                    
                                    	<th width="300px">Descripcion</th>
	                                    <th width="100px">Dato</th>   
        	                        </thead>  
                                    <tbody>                              
            	                    <?php
									//Función para verificar un email válido
									function VerificarEmail($email) {
								    $Sintaxis='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
								    if(preg_match($Sintaxis, $email))
								      return true;
								    else
								     return false;
									}
									//Fin función
									
									if (isset($_POST['consultar'])) {									
										
										echo '<tbody id="dataCustomer">';									
										
										$fecha1 = $_POST['Fecha1'];
										$fecha2 = $_POST['Fecha2'];
									
										$fechaInicio=strtotime($fecha1);
										$fechaFin=strtotime($fecha2);
													
										//===TOTALES CLIENTES REGISTRADOS										
										$arrayUssCall = array();
										$arrayUssCall_Email = array();
													
										$SQL = "SELECT * FROM usuarios WHERE FechaRegistro >= '$fecha1' AND FechaRegistro <= '$fecha2'";
										$db->setQuery($SQL);
										$result = $db->loadObjectList();
										foreach($result as $result1) {		
											//Buscamos en el resultado del Email que no exista | para contabilizar los usuarios email, estos son únicos no se repiten									
											$pos = strrpos($result1->Email, '|');
											if ($pos === false) {
												$usuariosWeb = $usuariosWeb + 1;
											} else {												
												// Si existe en Email esta palabra | lo contabilizamos como call y metemos el dni en un arreglo unicos
												$data = explode('|', $result1->Email);
												$email = htmlentities($data[1]);
												if(VerificarEmail($email)) {
													if(!in_array($data[1], $arrayUssCall_Email))
														$arrayUssCall_Email[] = $data[1];
													$usuariosCall_Email = count($arrayUssCall_Email);
												} else {
													if (!in_array($result1->Dni, $arrayUssCall))
														$arrayUssCall[] = $result1->Dni;
													$usuariosCall = count($arrayUssCall);
												}
											}
										}
										$db->freeResults();
										
										echo '
											<table cellpadding="0" cellspacing="0" style="text-align:center">
												<thead>
													<th colspan="3"><h3>CLIENTES TOTALES REGISTRADOS</h3></th>
												</thead>
												<tbody>
													<tr height="25px">
														<td width="165px">1</td><td><label>Usuarios Web con Email</label></td><td width="165px"><label>'.$usuariosWeb.'</label></td>
													</tr>
													<tr height="25px">
														<td>2</td><td><label>Usuarios Call con Emal</label></td><td><label>'.count($arrayUssCall_Email).'</label></td>
													</tr>
													<tr height="25px">
														<td>2</td><td><label>Usuarios Call sin Emal</label></td><td><label>'.$usuariosCall.'</label></td>
													</tr>
													<tr height="25px" style="background:#1F88A7;">
														<td></td><td><label style="color: white">Total Registrados</label></td><td><label style="color: white">'.($usuariosWeb + $usuariosCall + $usuariosCall_Email).'</label></td>
													</tr>
												</tbody>
											</table>
										';
										
										//=== FIN TOTALES CLIENTES
										
										echo '<hr />';
										
										
										//=== CLIENTES QUE COMPRARON 										
										/*								
										$SQL = "SELECT usuarios.Id, usuarios.Dni, usuarios.Nombres, usuarios.Genero, ordenes.FechaOrden, ordenes.EstadoPago, ordenes.FormaPago, COUNT(*) AS Repetido FROM usuarios ";
										$SQL .= "INNER JOIN ordenes ON usuarios.Id = ordenes.IdCliente ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "WHERE FechaOrden >= '$fecha1' AND FechaOrden <= '$fecha2' AND EstadoPago = RTRIM('ok') AND FormaPago != RTRIM('--') AND Tramitado = RTRIM('usuario-web') AND ordenes.EstadoOrden != RTRIM('Anulado') ";
										$SQL .= "GROUP BY usuarios.Id ";
										$db->setQuery($SQL);
										$row = $db->execute();
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
												$clientesWebComp = $clientesWebComp + 1;
											}
										}
										$db->freeResults();
										
										$SQL = "SELECT usuarios.Id, usuarios.Dni, usuarios.Nombres, usuarios.Genero, ordenes.FechaOrden, ordenes.EstadoPago, ordenes.FormaPago, COUNT(*) AS Repetido FROM usuarios ";
										$SQL .= "INNER JOIN ordenes ON usuarios.Id = ordenes.IdCliente ";
										$SQL .= "WHERE FechaOrden >= '$fecha1' AND FechaOrden <= '$fecha2' AND TRIM(EstadoPago) = RTRIM('ok') AND TRIM(FormaPago) <> RTRIM('--') AND TRIM(Tramitado) = RTRIM('call-center') AND TRIM(EstadoOrden) <> 'Anulado' ";
										$SQL .= "GROUP BY usuarios.Dni ";
										$db->setQuery($SQL);
										$row = $db->execute();
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
												$clientesCallComp = $clientesCallComp + 1;
											}
										}
										$db->freeResults();
										*/
										
										//===== CLIENTES QUE COMPRARON =====
										$SQL = "SELECT ";
										$SQL .= "CASE ";
										$SQL .= "WHEN trim(ordenes.Tramitado) = 'call-center' THEN 'callcenter' ";
										$SQL .= "WHEN trim(ordenes.Tramitado) = 'usuario-web' THEN 'web' ";
										$SQL .= "ELSE 'oficina' ";
										$SQL .= "END pedido, count(DISTINCT(ordenes.IdOrden)) as total ";
										$SQL .= "FROM ordenes ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.Idorden ";
										$SQL .= "WHERE ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND lineasorden.EstadoPedido <> 'Anulado' AND TRIM(ordenes.EstadoPago) = 'ok' GROUP BY pedido";
										$db->setQuery($SQL);
										$row = $db->execute();
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();																						
										}
										$db->freeResults();
									
										
										echo '
											<table cellpadding="0" cellspacing="0" style="text-align:center">
												<thead>
													<th colspan="3"><h3>CLIENTES QUE COMPRARON</h3></th>
												</thead>';
												
												for($i = 0; $i < count($result); $i++) {
													echo '
													<tr height="25px">
														<td width="165px">1</td><td><label>Usuarios '.$result[$i]->pedido.'</label></td><td width="165px"><label>'.$result[$i]->total.'</label></td>
													</tr>';
													$totalPedidos = $totalPedidos + $result[$i]->total;
												}												
												
										echo '
												<tr height="25px" style="background:#1F88A7;">
													<td></td><td><label style="color: white">Total que compraron</label></td><td><label style="color: white">'.($totalPedidos).'</label></td>
												</tr>
											</table>
										';
										
										//=== FIN CLIENTES QUE COMPRARON
										
										echo '<hr />';
										
										//=== CLIENTES QUE COMPRARON MAS DE UNA VEZ										
										/*									
										$SQL = "SELECT usuarios.*, ordenes.*, COUNT(*) AS Repetido FROM usuarios ";
										$SQL .= "INNER JOIN ordenes ON usuarios.Id = ordenes.IdCliente ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "WHERE FechaOrden >= '$fecha1' AND FechaOrden <= '$fecha2' AND TRIM(EstadoPago) = RTRIM('ok') AND TRIM(FormaPago) != RTRIM('--') AND TRIM(Tramitado) = RTRIM('usuario-web') AND TRIM(ordenes.EstadoOrden) != RTRIM('Anulado') ";
										$SQL .= "GROUP BY usuarios.Id";
										$db->setQuery($SQL);
										$row = $db->execute();
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
												if($result1->Tramitado == rtrim('usuario-web')) {
													if ($result1->Repetido > 1)
														$clienteWebRep = $clienteWebRep + 1;
												} 
											}
										}
										$db->freeResults();
										
										$SQL = "SELECT usuarios.*, ordenes.*, COUNT(*) AS Repetido FROM usuarios ";
										$SQL .= "INNER JOIN ordenes ON usuarios.Id = ordenes.IdCliente ";
										$SQL .= "WHERE FechaOrden >= '$fecha1' AND FechaOrden <= '$fecha2' AND TRIM(EstadoPago) = RTRIM('ok') AND TRIM(FormaPago) != RTRIM('--') AND TRIM(Tramitado) = RTRIM('call-center') ";
										$SQL .= "GROUP BY usuarios.Dni";
										$db->setQuery($SQL);
										$row = $db->execute();
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
												if(trim($result1->Tramitado) == rtrim('call-center')) {
													if ($result1->Repetido > 1)
														$clienteCallRep = $clienteCallRep + 1;
												} 
											}
										}
										$db->freeResults();
										*/
										
										//===== CLIENTES QUE COMPRAON MAS DE 1 VEZ =====
										$SQL = "SELECT count(repetido) as repetidoWeb ";
										$SQL .= "from ( ";
										$SQL .= "SELECT ordenes.Tramitado, count(ordenes.IdOrden) as repetido FROM ordenes ";
										$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
										$SQL .= "WHERE ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND ordenes.Tramitado = 'usuario-web' ";
										$SQL .= "AND ordenes.EstadoPago = 'ok' AND lineasorden.EstadoPedido <> 'Anulado' group by usuarios.Dni HAVING repetido > 1 ) x";
										$db->setQuery($SQL);										
										$result = $db->loadObject();
										$clienteWebRep = $result->repetidoWeb;																						
										$db->freeResults();
																				
										$SQL = "SELECT count(repetido) as repetidoCall ";
										$SQL .= "from ( ";
										$SQL .= "SELECT ordenes.Tramitado, count(ordenes.IdOrden) as repetido FROM ordenes ";
										$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
										$SQL .= "WHERE ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND ordenes.Tramitado = 'call-center' ";
										$SQL .= "AND ordenes.EstadoPago = 'ok' AND lineasorden.EstadoPedido <> 'Anulado' group by usuarios.Dni HAVING repetido > 1 ) y";
										$db->setQuery($SQL);
										$result = $db->loadObject();
										$clienteCallRep = $result->repetidoCall;										
										$db->freeResults();
										
										$SQL = "SELECT count(repetido) as repetidoOfi ";
										$SQL .= "from ( ";
										$SQL .= "SELECT ordenes.Tramitado, count(ordenes.IdOrden) as repetido FROM ordenes ";
										$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
										$SQL .= "WHERE ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND ordenes.Tramitado <> 'call-center' AND ordenes.Tramitado <> 'usuario-web' ";
										$SQL .= "AND ordenes.EstadoPago = 'ok' AND lineasorden.EstadoPedido <> 'Anulado' group by usuarios.Dni HAVING repetido > 1 ) z";
										$db->setQuery($SQL);
										$result = $db->loadObject();
										$clienteOfiRep = $result->repetidoOfi;										
										$db->freeResults();
										
										echo '
											<table cellpadding="0" cellspacing="0" style="text-align:center">
												<thead>
													<th colspan="3"><h3>CLIENTES QUE COMPRARON MÁS DE UNA VEZ</h3></th>
												</thead>
												<tbody>
													<tr height="25px">
														<td width="165px">1</td><td><label>UsuariosWeb </label></td><td width="165px"><label>'.number_format($clienteWebRep).'</label></td>
													</tr>
													<tr height="25px">
														<td>2</td><td><label>UsuariosCall </label></td><td><label>'.number_format($clienteCallRep).'</label></td>
													</tr>
													<tr height="25px">
														<td>3</td><td><label>UsuariosOfi</label></td><td><label>'.number_format($clienteOfiRep).'</label></td>
													</tr>
													<tr height="25px" style="background:#1F88A7;">
														<td></td><td><label style="color: white">Total que compraron mas de una vez</label></td><td><label style="color: white">'.number_format($clienteWebRep + $clienteCallRep + $clienteOfiRep).'</label></td>
													</tr>
												</tbody>
											</table>
										';
										
										//=== FIN
										
										echo '<hr />';
										
										//=== CLIENTES POR SEXO QUE COMPRARON										
										
										$SQL = "SELECT usuarios.Id, usuarios.Dni, usuarios.Nombres, usuarios.Genero, ordenes.FechaOrden, ordenes.EstadoPago, ordenes.FormaPago, COUNT(*) AS Repetido, SUM(lineasorden.Subtotal) AS dinero FROM usuarios ";
										$SQL .= "INNER JOIN ordenes ON usuarios.Id = ordenes.IdCliente ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "WHERE FechaOrden >= '$fecha1' AND FechaOrden <= '$fecha2' AND TRIM(EstadoPago) = RTRIM('ok') AND TRIM(FormaPago) != RTRIM('--') AND TRIM(Tramitado) = RTRIM('usuario-web') AND TRIM(lineasorden.EstadoPedido) <> 'Anulado' ";
										$SQL .= "GROUP BY usuarios.Id";
										$db->setQuery($SQL);
										$row = $db->execute();
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
												if (trim($result1->Genero) == rtrim('Hombre')) {
													$clienteHom = $clienteHom + 1;
													$totalDinHom = $totalDinHom + $result1->dinero;
												}
												else if (trim($result1->Genero) == rtrim('Mujer')) 	{
													$clienteMuj = $clienteMuj + 1;
													$totalDinMuj = $totalDinMuj + $result1->dinero;
												}
											}
										}
										$db->freeResults();
																				
										echo '
											<table cellpadding="0" cellspacing="0" style="text-align:center">
												<thead>
													<th colspan="4"><h3>CLIENTES POR SEXO QUE COMPRARON</h3></th>
												</thead>
												<tbody>
													<tr height="25px">
														<td width="165px">1</td><td><label>Usuarios Masculinos que compraron</label></td>
														<td width="165px"><label>'.$clienteHom.'</label></td>
														<td><label>'.number_format($totalDinHom,2,',','.').'</label></td>
													</tr>
													<tr height="25px">
														<td>2</td><td><label>Usuarios Femeninos que compraron</label></td>
														<td><label>'.$clienteMuj.'</label></td>
														<td><label>'.number_format($totalDinMuj,2,',','.').'</label></td>
													</tr>
													<tr height="25px" style="background:#1F88A7;">
														<td></td><td><label style="color: white">Total usuarios</label></td>
														<td><label style="color: white">'.($clienteHom + $clienteMuj).'</label></td>
														<td width="150px"><label style="color: white">'.number_format(($totalDinHom + $totalDinMuj),2,',','.').'</label></td>
													</tr>
												</body>
											</table>
										';
										
										//=== FIN
										
										echo '<hr />';
										
										//=== CLIENTES QUE COMPRARON POR EDAD										
										
										function calcular_edad($fecha){
											$dias = explode("-", $fecha, 3);
											$dias = mktime(0,0,0,$dias[1],$dias[2],$dias[0]);
											$edad = (int)((time()-$dias)/31556926 );
											return $edad;
										}
										
										$SQL = "SELECT usuarios.Id, usuarios.Dni, usuarios.Nombres, usuarios.FechaNacimiento, ordenes.FechaOrden, ordenes.EstadoPago, ordenes.FormaPago, COUNT(usuarios.Id) AS Repetido, SUM(lineasorden.Subtotal) as dinero FROM usuarios ";
										$SQL .= "INNER JOIN ordenes ON usuarios.Id = ordenes.IdCliente ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "WHERE FechaOrden >= '$fecha1' AND FechaOrden <= '$fecha2' AND TRIM(EstadoPago) = RTRIM('ok') AND TRIM(FormaPago) != RTRIM('--') AND TRIM(Tramitado) = RTRIM('usuario-web') AND TRIM(lineasorden.EstadoPedido) <> 'Anulado' ";
										$SQL .= "GROUP BY usuarios.Id";
										$db->setQuery($SQL);
										$row = $db->execute();
										if(mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
												$edad = calcular_edad($result1->FechaNacimiento);
												$dinero = $result1->dinero;
											
												if ($edad >= 15 && $edad <= 20) { $array0[] = $edad; $totalDin0 = $totalDin0 + $dinero; }
												if ($edad >= 21 && $edad <= 25) { $array1[] = $edad; $totalDin1 = $totalDin1 + $dinero; }
												if ($edad >= 26 && $edad <= 30) { $array2[] = $edad; $totalDin2 = $totalDin2 + $dinero; }
												if ($edad >= 31 && $edad <= 35) { $array3[] = $edad; $totalDin3 = $totalDin3 + $dinero; }
												if ($edad >= 36 && $edad <= 40) { $array4[] = $edad; $totalDin4 = $totalDin4 + $dinero; }
												if ($edad >= 41 && $edad <= 45) { $array5[] = $edad; $totalDin5 = $totalDin5 + $dinero; }
												if ($edad >= 46 && $edad <= 50) { $array6[] = $edad; $totalDin6 = $totalDin6 + $dinero; }
												if ($edad >= 51 && $edad <= 55) { $array7[] = $edad; $totalDin7 = $totalDin7 + $dinero; }
												if ($edad >= 56 && $edad <= 60) { $array8[] = $edad; $totalDin8 = $totalDin8 + $dinero; }
												if ($edad >= 61 && $edad <= 65) { $array9[] = $edad; $totalDin9 = $totalDin9 + $dinero; }
												if ($edad >= 66 && $edad <= 70) { $array10[] = $edad; $totalDin10 = $totalDin10 + $dinero; }
												if ($edad >= 71 && $edad <= 75) { $array11[] = $edad; $totalDin11 = $totalDin11 + $dinero; }
												if ($edad >= 76 && $edad <= 80) { $array12[] = $edad; $totalDin12 = $totalDin12 + $dinero; }
												if ($edad >= 81 && $edad <= 85) { $array13[] = $edad; $totalDin13 = $totalDin13 + $dinero; }
												
												$totalDinero = $totalDinero + $dinero;
											}
											
											/*
											if (count($array0) > 0) { echo 'Edad entre 15 - 20 = '.count($array0).'<br/>'; }
											if (count($array1) > 0) { echo 'Edad entre 20 - 25 = '.count($array1).'<br/>'; } if (count($array2) > 0) { echo 'Edad entre 25 - 30 = '.count($array2).'<br/>'; }
											if (count($array3) > 0) { echo 'Edad entre 30 - 35 = '.count($array3).'<br/>'; } if (count($array4) > 0) { echo 'Edad entre 35 - 40 = '.count($array4).'<br/>'; }
											if (count($array5) > 0) { echo 'Edad entre 40 - 45 = '.count($array5).'<br/>'; } if (count($array6) > 0) { echo 'Edad entre 45 - 50 = '.count($array6).'<br/>'; }
											if (count($array7) > 0) { echo 'Edad entre 50 - 55 = '.count($array7).'<br/>'; } if (count($array8) > 0) { echo 'Edad entre 55 - 60 = '.count($array8).'<br/>'; }
											if (count($array9) > 0) { echo 'Edad entre 60 - 65 = '.count($array9).'<br/>'; } if (count($array10) > 0) { echo 'Edad entre 65 - 70 = '.count($array10).'<br/>'; }
											if (count($array11) > 0) { echo 'Edad entre 70 - 75 = '.count($array11).'<br/>'; } if (count($array12) > 0) { echo 'Edad entre 75 - 80 = '.count($array12).'<br/>'; }
											if (count($array13) > 0) { echo 'Edad entre 80 - 85 = '.count($array13).'<br/>'; }
											*/
											$TotalArray = ((count($array0))+(count($array1))+(count($array2))+(count($array3))+(count($array4))+(count($array5))+(count($array6))
											+(count($array7))+(count($array8))+(count($array9))+(count($array10))+(count($array11))+(count($array12))+(count($array13))); 											
											
										}
										$db->freeResults();										
										
										
										echo '
											<table cellpadding="0" cellspacing="0" style="text-align:center">
												<thead>
													<th colspan="4"><h3>CLIENTES QUE COMPRARON POR EDAD</h3></th>
												</thead>
												<tbody>
													<tr height="25px"><td width="165px">1</td><td><label>Edad entre 15 - 20</label></td><td width="165px"><label>'.count($array0).'</label></td><td><label>'.number_format($totalDin0,2,',','.').'</label></td></tr>
													<tr height="25px"><td width="165px">2</td><td><label>Edad entre 20 - 25</label></td><td><label>'.count($array1).'</label></td><td><label>'.number_format($totalDin1,2,',','.').'</label></td></tr>
													<tr height="25px"><td width="165px">3</td><td><label>Edad entre 25 - 30</label></td><td><label>'.count($array2).'</label></td><td><label>'.number_format($totalDin2,2,',','.').'</label></td></tr>
													<tr height="25px"><td width="165px">4</td><td><label>Edad entre 30 - 35</label></td><td><label>'.count($array3).'</label></td><td><label>'.number_format($totalDin3,2,',','.').'</label></td></tr>
													<tr height="25px"><td width="165px">5</td><td><label>Edad entre 35 - 40</label></td><td><label>'.count($array4).'</label></td><td><label>'.number_format($totalDin4,2,',','.').'</label></td></tr>
													<tr height="25px"><td width="165px">6</td><td><label>Edad entre 40 - 45</label></td><td><label>'.count($array5).'</label></td><td><label>'.number_format($totalDin5,2,',','.').'</label></td></tr>
													<tr height="25px"><td width="165px">7</td><td><label>Edad entre 45 - 50</label></td><td><label>'.count($array6).'</label></td><td><label>'.number_format($totalDin6,2,',','.').'</label></td></tr>
													<tr height="25px"><td width="165px">8</td><td><label>Edad entre 50 - 55</label></td><td><label>'.count($array7).'</label></td><td><label>'.number_format($totalDin7,2,',','.').'</label></td></tr>
													<tr height="25px"><td width="165px">9</td><td><label>Edad entre 55 - 60</label></td><td><label>'.count($array8).'</label></td><td><label>'.number_format($totalDin8,2,',','.').'</label></td></tr>
													<tr height="25px"><td width="165px">10</td><td><label>Edad entre 60 - 65</label></td><td><label>'.count($array9).'</label></td><td><label>'.number_format($totalDin9,2,',','.').'</label></td></tr>
													<tr height="25px"><td width="165px">11</td><td><label>Edad entre 65 - 70</label></td><td><label>'.count($array10).'</label></td><td><label>'.number_format($totalDin10,2,',','.').'</label></td></tr>												
													<tr height="25px"><td width="165px">12</td><td><label>Edad entre 70 - 75</label></td><td><label>'.count($array11).'</label></td><td><label>'.number_format($totalDin11,2,',','.').'</label></td></tr>
													<tr height="25px"><td width="165px">13</td><td><label>Edad entre 75 - 80</label></td><td><label>'.count($array12).'</label></td><td><label>'.number_format($totalDin12,2,',','.').'</label></td></tr>
													<tr height="25px"><td width="165px">14</td><td><label>Edad entre 80 - 85</label></td><td><label>'.count($array13).'</label></td><td><label>'.number_format($totalDin13,2,',','.').'</label></td></tr>
												</tbody>
												<tfoot>
													<tr><td></td><td><label style="color: white">Total usuarios</label></td><td><label style="color: white">'.$TotalArray.'</label></td><td width="150px"><label style="color: white">'.$totalDinero.'</label></td></tr>
												</foot>
											</table>
										';
										//=== FIN
										
										echo '<hr />';
										
										//=== PEDIDO MEDIO
										//echo '<h3>Pedido Medio</h3>';
										/*
										$SQL = "SELECT productos.IdOferta, opcionesoferta.Id, SUM(lineasorden.Cantidad) as Unidades, productos.Nombre_Producto, opcionesoferta.Precio, ordenes.Tramitado ";
										$SQL .= "FROM ordenes ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
										$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
										$SQL .= "WHERE ordenes.EstadoPago = 'ok' AND ordenes.EstadoOrden != 'Anulado' AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND Tramitado = RTRIM('usuario-web') ";
										$SQL .= "GROUP BY opcionesoferta.Id ORDER BY productos.IdOferta ASC";
										$db->setQuery($SQL);
										$row = $db->execute();
										if(mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
												$total = $result1->Precio * $result1->Unidades;
												$factTotal = $factTotal + $total;
												$unidadesVendidasWeb = ($unidadesVendidasWeb) + ($result1->Unidades);												
											}
											$pedidoMedioWeb = ($factTotal/$unidadesVendidasWeb);
										}
										$db->freeResults();
										
										$SQL = "SELECT productos.IdOferta, opcionesoferta.Id, SUM(lineasorden.Cantidad) as Unidades, productos.Nombre_Producto, opcionesoferta.Precio, ordenes.Tramitado ";
										$SQL .= "FROM ordenes ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
										$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
										$SQL .= "WHERE ordenes.EstadoPago = 'ok' AND ordenes.EstadoOrden <> 'Anulado' AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND Tramitado = RTRIM('call-center') ";
										$SQL .= "GROUP BY opcionesoferta.Id ORDER BY productos.IdOferta ASC";
										$db->setQuery($SQL);
										$row = $db->execute();
										if(mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
												$total = $result1->Precio * $result1->Unidades;
												$factTotalC = $factTotalC + $total;
												$unidadesVendidasCall = ($unidadesVendidasCall) + ($result1->Unidades);												
											}
											$pedidoMedioCall = ($factTotalC/$unidadesVendidasCall);
										}
										$db->freeResults();
										*/
										
										$SQL = "SELECT ";
										$SQL .= "CASE ";
										$SQL .= "WHEN ordenes.Tramitado = 'call-center' THEN 'callcenter' ";
										$SQL .= "WHEN ordenes.Tramitado = 'usuario-web' THEN 'web' ";
										$SQL .= "ELSE 'oficina' ";
										$SQL .= "END pedido, SUM(lineasorden.Subtotal) as total, SUM(lineasorden.Cantidad) AS productos ";
										$SQL .= "FROM ordenes ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "WHERE ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND ordenes.EstadoPago = 'ok' AND lineasorden.EstadoPedido <> 'Anulado' GROUP BY pedido";
										$db->setQuery($SQL);
										$result = $db->loadObjectList();
										
										echo '
											<table cellpadding="0" cellspacing="0" style="text-align:center">
												<thead>
													<th colspan="4"><h3>PROMEDIO PEDIDO MEDIO</h3></th>
												</thead>
												<tbody>';
													for($i = 0; $i < count($result); $i++) {
														echo '
														<tr height="25px">
															<td width="165px">1</td><td><label>Pedido Medio '.$result[$i]->pedido.'</label></td>
															<td width="165px"><label>'. $result[$i]->total . ' / ' . $result[$i]->productos . ' = ' .'</label></td>
															<td width="150px"><label>'.number_format($result[$i]->total/$result[$i]->productos,2,',','.').'</label></td>
														</tr>';
														$pedidoMedioT = $pedidoMedioT + $result[$i]->total/$result[$i]->productos;
														
													}
														
													echo '
												</tbody>
												<tfoot>
													<tr height="25px">
														<td></td><td><label style="color: white">Pedido Medio General</label></td><td></td><td><label style="color: white">'.number_format(($pedidoMedioT/count($result)),2,',','.').'</label></td>
													</tr>
												</tfoot>
											</table>
										';
										
										//=== FIN
										
										echo '<hr />';
										
										//=== TOTAL DEL PEDIDOS
										
										$SQL = "SELECT administrador.IdAdmin, administrador.Usuario, administrador.Nombres, administrador.Apellidos, COUNT(DISTINCT(ordenes.IdOrden)) as totalOrdenes, IFNULL(SUM(lineasorden.Cantidad),0) as totalProductos FROM ordenes ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "INNER JOIN administrador ON ordenes.Tramitado = administrador.Usuario ";
										$SQL .= "WHERE ordenes.EstadoPedido <> 'Anulado' AND ordenes.EstadoPago = 'ok' AND FormaPago != RTRIM('--') AND ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' GROUP BY ordenes.Tramitado";
										$db->setQuery($SQL);
										$result = $db->loadObjectList();
										echo '<table cellpadding="0" cellspacing="0">';
										echo '<thead><th colspan="3"><h3>PRODUCTOS VENDIDOS</h3></th></thead>';
										echo '<tbody>';
										foreach($result as $result1) {
											echo  '<tr height="25px"><td><label>'.utf8_encode($result1->Nombres) . '</label></td>';
											echo '<td><label>Ordenes &nbsp;' . $result1->totalOrdenes . '</label></td>';
											echo '<td><label>Productos &nbsp;' . $result1->totalProductos . '</label></td></tr>';
											$totalPP = $totalPP + $result1->totalProductos;
											$totalOO = $totalOO + $result1->totalOrdenes;
										}
										
										/*
										echo 'Total Ordenes = '. $totalOO . '<br/>';
										echo 'Total Productos = '. $totalPP . '<br/>';
										*/
										
										echo'
											</tbody>
											<tfoot>
												<tr height="25px" style="background:#1F88A7;">
													<td><label style="color:white">Totales</label></td><td><label style="color:white">'.$totalOO.'</label></td><td width="165px"><label style="color:white">'.$totalPP.'</label></td>
												</tr>
											</tfoot>';
										echo '</table>';
										//=== FIN
										
										echo '<hr />';
										
										//=== CLIENTES Q COMPRARON MAS DE UN PRODUCTO
										/*
										$SQL = "SELECT usuarios.Id, usuarios.Dni, usuarios.Nombres, ordenes.IdOrden, ordenes.FechaOrden, ordenes.EstadoPago, ordenes.FormaPago, COUNT(usuarios.Id) as Repetido FROM usuarios ";
										$SQL .= "INNER JOIN ordenes ON usuarios.Id = ordenes.IdCliente ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "WHERE FechaOrden >= '$fecha1' AND FechaOrden <= '$fecha2' AND EstadoPago = RTRIM('ok') AND FormaPago != RTRIM('--') AND Tramitado = RTRIM('usuario-web') ";
										$SQL .= "GROUP BY ordenes.IdOrden";
										$db->setQuery($SQL);
										$row = $db->execute();
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
												if ($result1->Repetido > 1) 
													$clientesCompraronVar = $clientesCompraronVar + 1;
											}
										}
										$db->freeResults();
										
										$SQL = "SELECT usuarios.Id, usuarios.Dni, usuarios.Nombres, ordenes.IdOrden, ordenes.FechaOrden, ordenes.EstadoPago, ordenes.FormaPago, COUNT(usuarios.Id) as Repetido FROM usuarios ";
										$SQL .= "INNER JOIN ordenes ON usuarios.Id = ordenes.IdCliente ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "WHERE FechaOrden >= '$fecha1' AND FechaOrden <= '$fecha2' AND EstadoPago = RTRIM('ok') AND FormaPago != RTRIM('--') AND Tramitado = RTRIM('call-center') ";
										$SQL .= "GROUP BY ordenes.IdOrden";
										$db->setQuery($SQL);
										$row = $db->execute();
										if (mysqli_num_rows($row) > 0) {
											$result = $db->loadObjectList();
											foreach($result as $result1) {
												if ($result1->Repetido > 1) 
													$clientesCallCompraronVar = $clientesCallCompraronVar + 1;
											}
										}
										$db->freeResults();
										*/
										
										$SQL = "SELECT ";
										$SQL .= "CASE "; 
										$SQL .= "WHEN ordenes.Tramitado = 'call-center' THEN 'callcenter' ";
										$SQL .= "WHEN ordenes.Tramitado = 'usuario-web' THEN 'web' ";
										$SQL .= "ELSE 'oficina' ";
										$SQL .= "END pedido, SUM(lineasorden.Cantidad) AS productos ";
										$SQL .= "FROM ordenes ";
										$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
										$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
										$SQL .= "WHERE ordenes.FechaOrden >= '$fecha1' AND ordenes.FechaOrden <= '$fecha2' AND ordenes.EstadoPago = 'ok' AND lineasorden.EstadoPedido <> 'Anulado' GROUP BY usuarios.Id HAVING productos > 1";
										$db->setQuery($SQL);
										$result = $db->loadObjectList();
										foreach($result as $result1) {											
											if($result1->pedido == 'callcenter')
												$clientesCall = $clientesCall + 1;
											if($result1->pedido == 'web')
												$clientesWeb = $clientesWeb + 1;
											if($result1->pedido == 'oficina')
												$clientesOfiT = $clientesOfiT + 1;
										}

										
										$graph[] = array("usuario"=>$result1->Usuario, "ordenes"=>$result1->totalOrdenes, "productos"=>$result1->totalProductos);	
										
										
										echo '
											<table cellpadding="0" cellspacing="0" style="text-align:center">
												<thead>
													<th colspan="3"><h3>CLIENTES QUE COMPRARON MÁS DE UN PRODUCTO</h3></th>
												</thead>
												<tbody>
													<tr height="25px">
														<td width="165px">1</td><td><label>ClientesWeb </label></td><td width="165px"><label>'.number_format($clientesWeb).'</label></td>
													</tr>
													<tr height="25px">
														<td>2</td><td><label>ClientesCall </label></td><td><label>'.number_format($clientesCall).'</label></td>
													</tr>
													<tr height="25px">
														<td>3</td><td><label>ClientesOfi </label></td><td><label>'.number_format($clientesOfiT).'</label></td>
													</tr>
												</tbody>
												<tfoot>
													<tr height="25px" style="background:#1F88A7;">
														<td></td><td><label style="color: white">Total usuarios</label></td><td><label style="color: white">'.($clientesCall + $clientesWeb + $clientesOfiT).'</label></td>
													</tr>
												</tfoot>
											</table>
										';
										//== FIN
																			
									
										echo '</tbody>';											
									
									} else {
										echo '
										<tbody id="dataCustomer">
											<tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
	                        		        <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
    	                        	        <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
        	                       		    <tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>
            	                       		<tr><td colspan="10" height="24px"></td></tr><tr><td colspan="10" height="24px"></td></tr>                	                    
		                               	</tbody>
										';	
									}
									?>  
                               
                	            </table>
                    		</div>
                                    
                            <div class="excel">
                            	<form id="excel0" name="excel0" method="post" action="ficheroExcel.php">
                                	<input type="hidden" id="values0" name="values0" />
                                    <input type="submit" id="btnExport" value="Exportar Excel" alt="0" />
                                </form>                               
                            </div>
                            
                            <!-- GRAFICA DE PORCENTAJES -->	
	                        <?php
									if (isset($_POST['consultar'])) {
										if (empty($subtotal)) { $subtotal = 0; $comision = 0; }
								?>
    	        	            <script type="text/javascript">
     								google.load("visualization", "1", {packages:["corechart"]});
								    google.setOnLoadCallback(drawChart);
								    function drawChart() {
							    	    var data = google.visualization.arrayToDataTable([
							    	      ['Informe', 'Ordenes', 'Productos'],	
										  ['totales', <?php echo $totalOO; ?>, <?php echo $totalPP; ?>],
										   <?php for($i=0; $i<=$totalGraph-1; $i++) {
											echo "['".$graph[$i]['usuario']."',".$graph[$i]['ordenes'].",".$graph[$i]['productos']."],";
										   } ?>										 	
						        		]);

							        var options = {
						        	  title: 'Resumen de ventas',
						    	      hAxis: {title: 'Ventas', titleTextStyle: {color: 'red'}}
							        };

							        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
							        chart.draw(data, options);
						    	  }
							    </script>
    	                        <div id="chart_div" style="width: 900px; height: 300px; margin: 0 auto; margin-bottom: 20px"></div> 
        	                <?php } ?>
            	            <!-- FIN GRAFICA RENDIMIENTO DE PRODUCTO -->
                            
                        </div>
                    </div>                    
                    
                    <!--MOSTRAMOS EL CUADRO COMPLETO -->
                    <div id="tab2" class="tab_content">                    	                        
                    	                 
                    </div>                    
                </div>
                
                <!-- CAMBIAR ESTADO DE LOS PEDIDOS-->
                	<div id="tab3" class="tab_content">                 	
                        						                       
                    </div>
                <!-- FIN TAB -->
                
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