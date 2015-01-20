<?php
session_start();
if ((!isset($_SESSION['Logged'])) && (!isset($_SESSION['UserIdAdmin']))) {
	header('Location: https://'.$_SERVER['HTTP_HOST']);
} else {
	include("config.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="css/stylos-pag.css" type="text/css" rel="stylesheet" />
        <link href="css/select-styles.css" type="text/css" rel="stylesheet" />
        <link href="css/smartpaginator.css" type="text/css" rel="stylesheet" />
        <link href="css/icons.css" type="text/css" rel="stylesheet" />
        <!--<link href="css/tinytips.css" type="text/css" rel="stylesheet" media="screen" />-->
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
		<title><?php echo $nombre; ?></title>
        <script type="text/javascript" src="js/jquery.tools.min.js"></script>
        <script type="text/javascript" src="js/smartpaginator.js"></script>
        <script type="text/javascript" src="js/jquery.simplePagination.js"></script>
        <!--<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>-->
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.jcookie.min.js"></script>
         <script type="text/javascript" src="js/jquery.validate.js"></script>
        <script type="text/javascript" src="js/jquery-class.js"></script>   
          
	</head>
<body>
</body>

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
			$active2 = 1;
			include('config.inc.php');
			include('menu.inc.php'); 
		?>
    </div> 
    
    <!-- Para el tooltip del searh operation y estado -->
    <div class="tooltip_customer"></div>
    <!-- Fin tooltip -->
    
    <!-- GADGET MENU LATERAL -->
    <?php require("gadget.inc.php"); ?>
    <!-- FIN GADGET -->
    
    <div id="content-body">
    	<div id="general" class="content">
        	<div id="Contenido" style="padding-top: 20px; overflow: auto; overflow-x:hidden">   
            	<div style="position: absolute; top: 0px; left: 990px; z-index:9999999"><img src="images/clientes.png" /></div> 
            	<ul class="tabs">
    		    	<li class="active"><a href="#tab1"><span>Registrar Pagos</span></a></li>		   			
   		  	        <!--<li><a href="#tab3">Productos</a></li>-->                
		  	    </ul>
        		
                <div class="tab_container">                	                    
                    <!-- BUSQUEDA DE CLIENTE QUE YA COMPRARON CON OPCIONES DE MODIFICACION -->
                    <div id="tab1" class="tab_content">
                    	<div id="busquedaClientes">	
                        	<div class="fieldset">
                            	<h2 class="legend">Información personal</h2>                                
	                        	<form id="formCustomerPay" name="formCustomerPay" method="post" action="customer-pay.inc.php?idbd=" . <?php echo base64_decode($_GET['idbd']); ?> accept-charset="utf-8" enctype="multipart/form-data">
		                        	<table style="width:80%">
    		                        	<thead>
        		                        	<th><label class="cab">Criterio</label></th>
            		                        <th><label class="cab">Filtro a aplicar</label></th>
                		                    <th></th>
                    		            </thead>
                        		        <tbody>
                            		    	<tr>
                                		    	<td>
                                    		    	<select id="criterio" name="criterio" data-placeholder="Seleccione Opción" class="chosen-select" style="width: 200px;">
                                            	    	<option value="0"></option>
                                                		<option value="idorden">Número de Orden</option>
                                                    	<option value="dni">Identificación</option>
	                                                    <option value="destinatario">Nombres</option>
    	                                                <option value="direccion">Dirección</option>
        	                                            <option value="telefono">Telefono</option>
            	                                    </select>
                	                        	</td>
	                	                        <td>
    	                	                    	<input type="text" id="filtro" name="filtro" style="width: 350px" autocomplete="off" />
        	                	                </td>
                                	            <td>
                                    	        	<input type="submit" id="buscar" name="buscar" value="Buscar Cliente" />                                                    
                                        	    </td>
	            	                        </tr>
    	            	                </tbody>
        	            	        </table>
            	                </form>
                            </div>
                        </div>
                        <div id="resultSearch">
                        	<h3><img src="images/result.png" style="float: left;" />&nbsp;Resultados</h3><br />
                        	<table id="resultSearch1" cellpadding="0" cellspacing="0">
                            	<thead>
                                	<th width="40px"></th>            						
						            <th width="60px">IdOrden</th>
						            <th width="90px">Fecha Orden</th>
						            <th width="80px">Dni</th>
						            <th width="270px">Nombres</th>                                    
						            <th>Direcci&oacute;n</th>                                    
						            <th width="40px"></th>
						            <th width="40px"></th>
						            <th width="40px"></th>
                                </thead>
                                <tbody id="dataCustomer">
									<tr><td colspan="9" ></td></tr><tr><td colspan="9" ></td></tr>
                                    <tr><td colspan="9" ></td></tr><tr><td colspan="9" ></td></tr>
                                    <tr><td colspan="9" ></td></tr><tr><td colspan="9" ></td></tr>
                                    <tr><td colspan="9" ></td></tr><tr><td colspan="9" ></td></tr>
                                    <tr><td colspan="9" ></td></tr><tr><td colspan="9" ></td></tr>                                    
                                </tbody>
                            </table>                                                        
                        </div>
                        
                        <table style="position: absolute; bottom: 75px; width: 100%;">
                           	<tr>
                               	<td width="100px"><label class="ver"></label>&nbsp;<label>Ver Pedido</label></td>    
        	                    <td width="100px"><label class="imprimir"></label>&nbsp;<label>Cartel</label></td>
    	                        <td width="100px"><label class="modificar"></label>&nbsp;<label>Modificar</label></td>
	                            <td width="100px"><label class="cambiar"></label>&nbsp;<label>Dirección</label></td>
                                <td width="100px"><label class="transito"></label>&nbsp;<label>Transito</label></td>
                                <td width="100px"><label class="enviado"></label>&nbsp;<label>Enviado</label></td>
                                <td width="100px"><label class="noentregado"></label>&nbsp;<label>No entregado</label></td>
                                <td width="100px"><label class="entregado"></label>&nbsp;<label>Entregado</label></td>
                                <td width="100px"><label class="anulado"></label>&nbsp;<label>Anulado</label></td>
                        	    <td width="100px"><label class="devolucion"></label>&nbsp;<label>Devolución</label></td>                                
                            </tr>
                        </table>
                        <div id="green2" style="margin: auto; position: absolute; bottom: 20px;"></div>
                        
                    </div>
                    <!--
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