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
        <?php include('menu.inc.php'); ?>
    </div> 
    
    <div id="content-body">
    	<div id="general" class="content">
        	<div id="transaccion">
            	<table>
                	<tr>
                    	<td><label id="title">Resultado de Transacci&oacute;n</label></td>
                    </tr>
                	<tr>
                    	<td><img src="images/error.png" /></td>
                    </tr>
                    <tr>
                    	<td><strong>Error al Realizar la Operaci&oacute;n</strong></td>
                    </tr>
                    <tr>
                    	<td style="background: #f2f4f7;">
                        	<table id="detalles">
                            	<tr>
                                	<td><strong>Detalle de Error</strong></td>
                                </tr>
                            	<tr>
                                	<td align="center"><label class="font" style="text-align:center; color: #F00"><?php echo base64_decode($_GET['error']) ?></label></td>
                                </tr>
                                <tr>
                                	<td><label class="font" style="text-align:center">Regrese al formulario de pedido y verifique que todos los datos este correctos</label></td>
                                </tr>
                                <tr style="height: 50px">
                                	<td align="center">
                                    	<form>
                                        	<input type="button" id="Back2" name="Back2" value="VOLVER" onclick="history.back()" />
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>                    
                </table>
            </div>
        </div>
    </div>
    <div id="push"></div>   
</div>

<div id="footer" class="footer"> 
	<?php include('footer.inc.php'); ?>        	
</div>

</html>