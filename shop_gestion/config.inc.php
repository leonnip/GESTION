<?php
	//@session_start();
	# CON ESTO NOS CONECTAMOS A LA BASE DE DATOS BD_SERVICE
	# =====================================================================
		date_default_timezone_set('Europe/Berlin');
		# SEPARADOR DE ENLACES
		define('DS', DIRECTORY_SEPARATOR);			
		# RUTA DE ARCHIVOS
		define('ROOT', realpath(dirname(__FILE__)) . DS);
	
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		
		global $tpv_orden;
		
		$mysqli = new mysqli("databaseserver.cplbepow19k0.eu-central-1.rds.amazonaws.com", "leonnip", "Jamon2011", "bd_service");
		if(mysqli_connect_errno()) {
			printf("Conexión fallida", mysqli_connect_errno());
		}
		$query = "SELECT * FROM basedatos WHERE IdBase = '".base64_decode($_REQUEST['idbd'])."'";
		$resultSession = $mysqli->query($query);
		while($rows = $resultSession->fetch_assoc()) {	
			$_SESSION['NAME_TIENDA'] = $rows['NombreTienda'];
			$_SESSION['NAME_BD'] = $rows['NameBd'];
			$_SESSION['PASS_BD'] = $rows['PassBd'];
			$_SESSION['USSER_BD'] = $rows['UsserBd'];
			
			$nombre = $rows['NombreTienda'];
			$empresa = $rows['Empresa'];
			$web = $rows['UrlTienda'];
			$tienda = $rows['NombreTienda'];
			$logo = $rows['Logo'];
			$comisionPrensa = $rows['Comision'];
			$tpv_orden = $rows['Codigo'];
			
			$empresaFact = $rows['Empresa'];
			$cifFact = $rows['Cif'];
			$direccFact = $rows['Direccion'];
			$telefonoFact = $rows['Telefono'];
			$emailFact = $rows['Email'];
			$descript = $rows['Descripcion'];
			
			define('EMPRESA_FACT', $rows['Empresa']);
			define('CIF_FACT', $rows['Cif']);
			define('DIRECCION_FACT', $rows['Direccion']);
			define('TELEFONO_FACT', $rows['Telefono']);
			define('EMAIL_FACT', $rows['Email']);
			define('DESCRIP_FACT', utf8_encode($rows['Descripcion']));
			
			$_SESSION['TPV_ORDEN'] = $rows['Codigo'];
			
		}
		mysqli_free_result($resultSession);
		mysqli_close($mysqli);
	
	# =====================================================================
	
	$codSeur = "44015";
	$precioCallcenter = 3;
	
	$host = "http://" . $_SERVER['HTTP_HOST'];
	$hostDirname = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	$paisDefecto = 1;   //PONEMOS EL PAIS POR DEFECTO PARA REGISTRAR LOS PEDIDOS
	$empresaTransporte = 'TIPSA';
	
	//$comisionPrensa = 0.20;
	
	//=== REDSIS PAGOS TPV ===
	$tpv_url = "https://sis.redsys.es/sis/realizarPago";
	# $tpv_orden = "|CTP";
	$tpv_key = "|elpaisseleccion_";
	$tpv_comercio = "ELPAISSELECCION";
	//$tpv_clave = 'qwertyasdf0123456789';
	$tpv_clave = "15975325846QWERTYUI";
	$tpv_currency = "978";
	$tpv_merchanCode = "322992710";
	$tpv_merchanName = "elpaisseleccion.com";
	$tpv_merchanLanguage = "001";
	$tpv_terminal = 1;
	$tpv_transactionType = 0;
	
	//=== FIN TPV ===
	
	
	
?>