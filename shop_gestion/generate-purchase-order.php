<?php
	@session_start();
	ini_set('display_errors', 'On');
	
	function redireccionar ($res) {
		@header('Location: http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/transaccion-error.php?error='.base64_encode($res));
		}
	
	if (!isset($_SESSION['Logged']) && !isset($_SESSION['IdCustomer'])) {
		@header ("Location: http://" . $_SERVER['HTTP_HOST']);
	} else {
		include_once("conexion/conexion.inc.php");
		$db = DataBase::getInstance();
		@$_SESSION['DAT'] = $_POST;
		//LOG DE OPERACIONES
		@$fp = fopen('log.html','a');
		if (!$fp) {
			echo '<tr><td>ERROR: No ha sido posible abrir el archivo. Revisa su nombre y sus permisos.</td></tr>'; exit;
		} else {				
			@fwrite($fp, PHP_EOL . 
				$_SESSION['User'].'=>'.
				$_SESSION['DAT']['name_payment'].'-'.$_SESSION['DAT']['last_name_payment'].'-'.$_SESSION['DAT']['dni_payment'].'-'.$_SESSION['DAT']['phone_payment'].'-'.$_SESSION['DAT']['email_payment'].'.'.$_SESSION['DAT']['address_payment'].
				'-'.$_SESSION['DAT']['number_payment'].'-'.$_SESSION['DAT']['piso_payment'].'-'.$_SESSION['DAT']['door_payment'].'-'.$_SESSION['DAT']['cod_payment'].'-'.$_SESSION['DAT']['city_payment'].'-'.$_SESSION['DAT']['province_payment'].'<br/>');				
		}				
		@fclose($fp);
?>
<html>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<head>
		
	</head>
<body OnLoad= "submitForm();">
<body>
	<?php
		include_once("classes/class.generate.pucharse.order.inc.php");
		include_once("config.inc.php");
		$orderT = new Generate_Pucharse_Order;
		
		//$cliente = $_SESSION['IdCustomer'];
		$payment = $_POST['visa-paypal-contra'];
		$cupon = base64_decode(@$_SESSION['Cupon']);
		$ipremota = $_SERVER['SERVER_ADDR'];
		$cliente_def = $_POST['ordencliente'];
		
		try {
			$db->AutoCommit();
			
			if (isset($_POST['_direc_'])) {
				$orderT->input_dir($db, $_POST['_direc_']);				
				$_direcc = $_POST['_direc_'];
			} else {
				$orderT->input();	
				$_direcc = '0';	
			}		
						
			$orderT->Order_Transaction($db, $cupon, $_direcc, $ipremota, $cliente_def, $payment, $tienda);
			$datos = $orderT->Datos_Transaction($db);	
			//print_r($datos);
			
			if($db->Commit()) {
				//if ($payment == 'visa') $payment_post = 'https://sis-t.redsys.es:25443/sis/realizarPago';
				if ($payment == 'visa') @$payment_post = $tpv_url;
				elseif ($payment == 'paypal') $payment_post = 'https://www.paypal.com/cgi-bin/webscr';
				elseif ($payment == 'contra') @$payment_post = 'transaccion-result.php';
				
				/*DESTRUIMOS LA COOKIE QUE YA NO HACE FALTA*/
				unset($_SESSION['DAT']);
				setcookie('usuarioAdmin', '', time()-36000, '/');
				
				/*DATOS QUE NOS SIRVEN PARA ENVIAR LA CONFIRMACION DE ORDEN*/
				@$_SESSION['__Orden__'] = $datos->IdOrden.$tpv_orden;
				$server = $_SERVER['HTTP_HOST'];
				$key = $tpv_key;
				$key_tar_ok = 'tar_ok'; $key_tar_ko = 'tar_ko';
				$key_paypal_ok = "paypal_ok"; $key_palpal_ko = "paypal_ko";
				
				/*RETURN URL OK - KO VISA MASTERCARD*/
				$URL_TAR_OK = "https://".$server.dirname($_SERVER['PHP_SELF'])."/transaccion-result.php?visa=".sha1($key_tar_ok.$key)."&ok_ko=".base64_encode('tar_ok');
				$URL_TAR_KO = "https://".$server.dirname($_SERVER['PHP_SELF'])."/transaccion-result.php?visa=".sha1($key_tar_ko.$key)."&ok_ko=".base64_encode('tar_ko');
				
				/*RETURN ULS OK - KO PAYPAL*/
				$URL_PAYPAL_OK = "https://".$server."/transaction-successfully.php?paypal=".sha1($key_paypal_ok.$key)."&ok_ko=".base64_encode('paypal_ok');
				$URL_PAYPAL_KO = "https://".$server."/transaction-successfully.php?paypal=".sha1($key_palpal_ko.$key)."&ok_ko=".base64_encode('paypal_ko');
				
				$Comercio = $tpv_comercio;
				$Monto =  $datos->Total - base64_decode(@$_SESSION['valorCupon']);
				//$Clave = 'qwertyasdf0123456789';
				$Clave = $tpv_clave;
				//$Clave = '';
				$Amount = $Monto*100;
				$Currency = $tpv_currency;
				$Order = $datos->IdOrden.$tpv_orden;
				$ProductDescription = $datos->detalleProd;
				$Titular = utf8_encode($datos->Nombres).' '.utf8_encode($datos->Apellidos);
				$MerchantCode = $tpv_merchanCode;
				$MerchantURL = 'http://'.$server.dirname($_SERVER['PHP_SELF']).'/visa-paypal/checkout-process-visa.inc.php';
				$MerchantName = $tpv_merchanName;
				$ConsumerLanguage = $tpv_merchanLanguage;
				//$Message = $Amount.$Order.$MerchantCode.$Currency.$Clave;
				$Terminal = $tpv_terminal;
				$TransactionType = $tpv_transactionType;
				$Message = $Amount.$Order.$MerchantCode.$Currency.$Clave;
				$MerchantSignature = sha1($Message);
				
				echo '
					<form id="FormPago" name="FormPago" method="POST" action="'.$payment_post.'" accept-charset="utf-8" enctype="application/x-www-form-urlencoded">
						<input type="hidden" name="Ds_Merchant_Amount" value="'.$Amount.'">
						<input type="hidden" name="Ds_Merchant_Currency" value="'.$Currency.'">
						<input type="hidden" name="Ds_Merchant_Order" value="'.$Order.'">
						<input type="hidden" name="Ds_Merchant_ProductDescription" value="'.$ProductDescription.'">
						<input type="hidden" name="Ds_Merchant_Titular" value="'.$Titular.'">									
						<input type="hidden" name="Ds_Merchant_MerchantCode" value="'.$MerchantCode.'">
						<input type="hidden" name="Ds_Merchant_MerchantURL" value="'.$MerchantURL.'">
						<input type="hidden" name="Ds_Merchant_UrlOK" value="'.$URL_TAR_OK.'">
						<input type="hidden" name="Ds_Merchant_UrlKO" value="'.$URL_TAR_KO.'">
						<input type="hidden" name="Ds_Merchant_MerchantName" value="'.$MerchantName.'">
						<input type="hidden" name="Ds_Merchant_ConsumerLanguage" value="'.$ConsumerLanguage.'">
						<input type="hidden" name="Ds_Merchant_MerchantSignature" value="'.$MerchantSignature.'">
						<input type="hidden" name="Ds_Merchant_Terminal" value="'.$Terminal.'">
						<input type="hidden" name="Ds_Merchant_TransactionType" value="'.$TransactionType.'">	
						<input type="hidden" name="Ds_Merchant_MerchantData" value="'.$Titular.'">
						<input type="hidden" name="Ds_Merchant_TPago" value="'.$payment.'">
						
						<!-- PAYPAL -->
						<input type="hidden" name="cmd" value="_xclick">
					    <input type="hidden" name="business" value="paypal-facilitator@elpaisseleccion.com">
					    <input type="hidden" name="item_name" value="ORDER ELPAIS SELECCION">
					    <input type="hidden" name="item_number" value="'.$Order.'">
					    <input type="hidden" name="amount" value="'.$Monto.'">
						<input type="hidden" name="first_name" value="'.$datos->Nombres.'">  
						 <input type="hidden" name="last_name" value="'.$datos->Apellidos.'">
					    <input type="hidden" name="no_shipping" value="0">
				    	<input type="hidden" name="no_note" value="1">
					    <input type="hidden" name="currency_code" value="EUR">
					    <input type="hidden" name="bn" value="PP-BuyNowBF">
						<input type="hidden" name="notify_url" value="http://'.$server.'/visa-paypal/checkout-process-paypal.inc.php">
					    <input type="hidden" name="return" value="'.$URL_PAYPAL_OK.'">
					    <input type="hidden" name="cancel_return" value="'.$URL_PAYPAL_KO.'">
					</form>
				';
			}
		} catch(Exception $e) {
			$res = $e->getMessage();
			$db->Rollback();	
			echo $res;		
			@header('Location: https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/transaccion-error.php?error='.base64_encode($res));
		}
	?>
    <script type="text/javascript">
			function submitForm() { 
				//document.FormPago.action = "https://sis-t.redsys.es:25443/sis/realizarPago";
				//document.FormPago.action = "https://sis.redsys.es/sis/realizarPago";
				document.FormPago.submit();	    	
			}
		</script>
</body>
</html>
<?php } ?>