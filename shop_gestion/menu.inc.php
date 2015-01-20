<?php
	error_reporting(E_ALL);
	@session_start();
	include("conexion/conexion.inc.php");
	$db = DataBase::getInstance();
?>
<div id="auxiliar" style="width: 100%; height: 50px; display: none"></div>
<div id="Header">
    <!--<img src="images/<?php echo $logo; ?>" style="height: 40px; position: absolute; top: 5px; left: 30px;" />	-->
    <h1 style="height: 50px; line-height: 50px; color: #ffffff; font-size: 28px; padding-left: 40px">
	    <?php echo $_SESSION['NAME_TIENDA']; ?>
    </h1>
    <div id="Logeado">
    	<table>
        	<tr>
            	<td><label class="Floats" style="padding-top: 2px">Conectado como</label><img src="images/user.png" class="Floats" />
                <label class="Floats"><?php echo utf8_encode($_SESSION['UserIdAdmin']); ?></label>
                <img src="images/on-off.png" class="Floats" /><a class="Floats" href="logout.php" style="padding-top: 2px">Cerrar Seci&oacute;n</a></td>
            </tr>
        </table>
    </div>
</div>

<div id="ContentMenu" class="sombra_header">
        	<div id="Menu">
	           	<ul id="Main">
    	           	<li id="Submain" class="Izquierda">
                    	<a class="LinealMenu icon-archivo <?php if ($active1 == 1) { echo 'menuactive'; } ?>" href="#"><span>Archivo</span></a>
                        <div id="SubDivMenu">
                        	<ul class="SubUL">
                            	<li class="sub sup"><a id="Link" href="index.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Registrar Nuevo Pedido</a></li>
                                <li class="sub inf"><a href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/tiendas.php">&raquo;&nbsp;Inicio Local</a></li>
                                <li class="sub inf"><a href="logout.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Salir del Sistema</a></li>
                            </ul>                   			
                        </div>
                        <div id="PartInfMenu"></div>
                    </li>
                    <li id="Submain">
                    	<a class="LinealMenu icon-user <?php if ($active2 == 1) { echo 'menuactive'; } ?>" href="#"><span>Clientes</span></a>
                        <div id="SubDivMenu">
                        	<ul class="SubUL">
                            	<li class="sub sup"><a href="customers.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Operaciones</a></li>
                               <li class="sub sup"><a href="customers-pay.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Registrar Pago</a></li> 
                                <!--<li class="sub"><a href="anotar-incidencia.php">&raquo;&nbsp;Anotar Incidencia</a></li>-->
                            </ul>
                        </div>
                        <div id="PartInfMenu"></div>
                    </li>
                    <li id="Submain">
                    	<a class="LinealMenu icon-modific <?php if ($active3 == 1) { echo 'menuactive'; } ?>" href="#"><span>Facturaci&oacute;n</span></a>
                        <div id="SubDivMenu">
	                        <ul class="SubUL">
                            	<li class="sub inf"><a href="costes-facturacion.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Costes Facturaci&oacute;n</a></li>    	                    	       	                   
                                <li class="sub inf"><a href="referencias.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Referencias Y Stock</a></li>
                                <li class="sub inf"><a href="facturacion-usuario.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Facturación por Usuario</li>
                                <li class="sub inf"><a href="facturas.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Facturas pdf</li>
                                <li class="sub inf"><a href="resumen-facturacion.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Resumen Facturación</li>
                                <li class="sub inf"><a href="resumen-abonos.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Resumen Abonos</li>
                                <li class="sub inf"><a href="resumen-facturacion-agencia.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Facturación Agencias</li>
                                <li class="sub inf"><a href="abono-pedidos-agencia.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Abono Pedidos</li>
            	            </ul>
                        </div>
                        <div id="PartInfMenu"></div>
                    </li>
                    <li id="Submain" class="Derecha">
                    	<a class="LinealMenu icon-order <?php if ($active4 == 1) { echo 'menuactive'; } ?>" href="#"><span>Pedidos</span></a>
                        <div id="SubDivMenu">                        	
	                        <ul class="SubUL">
                            	<li class="sub sup"><a href="productos-listados.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Ordenar Productos</a></li>
    	                    	<li class="sub sup"><a href="orders.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Ordenes Registrados</a></li>
        	                    <li class="sub"><a href="informe-pedidos.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Informe Pedidos</a></li> 
                                <li class="sub"><a href="informe-pedidos-web.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Informe Pedidos Web</a></li> 
                                <li class="sub"><a href="informe-callcenter.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Informe Call-Center</a></li>
                                 <li class="sub"><a href="informe-web.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Informe Web</a></li>   
                                <li class="sub"><a href="informes-pagos.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Informe Forma Pago</a></li>   
                                <li class="sub inf"><a href="informe-ventas.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Informes Ventas Usuario</a></li>
                                <li class="sub inf"><a href="report-draw.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Informe Clientes Sorteo</a></li>
                                <li class="sub inf"><a href="report-provinces.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Informe Ventas Provincias</a></li>
                                <li class="sub"><a href="informe-estadistico.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;Informe Global</a></li>  
                                <li class="sub"><a href="informe-nws.php?idbd=<?php echo $_GET['idbd']; ?>">&raquo;&nbsp;NewsLetters</a></li>  
            	            </ul>
                        </div>
                        <div id="PartInfMenu"></div>
                    </li>
        	    </ul>
            </div>
</div>