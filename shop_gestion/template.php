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
        	<!-- AREA DE PRUBAS -->
            	<?php
					$a = 117;
					$b = 10;
					$r = $a % $b;
					echo 'Resultado = '. $r;
					$t = $b - $r;
					echo 'Total = '. $t;
				?>
            <!-- FIN DE AREA -->
        </div>
    </div>
    <div id="push"></div>   
</div>

<div id="footer" class="footer"> 
	<?php include('footer.inc.php'); ?>        	
</div>

</html>