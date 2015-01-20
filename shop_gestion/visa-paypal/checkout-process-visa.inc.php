<?php
	//DATOS QUE PASA EL BANCO
	require_once("../conexion/conexion.inc.php");
	$db = DataBase::getInstance();
	try
	{
		/* por si hay algun fallo se captura las excepciones */
		if (isset($_POST['Ds_Signature']))
		{
			/* creamos las variables para usar */
			$Ds_ErrorCode = $_POST['Ds_ErrorCode'];
			$Ds_Card_Country = $_POST['Ds_Card_Country'];
			$Ds_Date = $_POST['Ds_Date'];
			$Ds_SecurePayment = $_POST['Ds_Secure_Payment'];
			$Ds_Signature = $_POST['Ds_Signature']; //firma hecha por el banco
			$Ds_Order = $_POST['Ds_Order']; //numero de orden
			$Ds_Hora = $_POST['Ds_Hora'];
			$Ds_Response = $_POST['Ds_Response']; //codigo de respuesta
			$Ds_AuthorisationCode = $_POST['Ds_AuthorisationCode'];
			$Ds_Currency = $_POST['Ds_Currency']; //moneda
			$Ds_MerchantCode = $_POST['Ds_MerchantCode']; //codigo de comercio			
			$Ds_Amount = $_POST['Ds_Amount']; //monto de la orden			
			//$CLAVE = 'qwertyasdf0123456789'; //nuestra clave secreta proporcionada por el banco Desarrollo		
			$CLAVE = '15975325846QWERTYUI'; // nuestra clave del banco para producción real
			/* creamos la firma para comparar */
			//$Message = $Ds_Amount.$Ds_Order.$Ds_MerchantCode.$Ds_Currency.$Ds_Response.$CLAVE;
			$Ds_TransactionType = 0;
			//$Ds_Merchant_URL = 'http://www.15encasa.com/checkout-pago-t.php';
			$Message = $Ds_Amount.$Ds_Order.$Ds_MerchantCode.$Ds_Currency.$Ds_Response.$CLAVE;
			$firma = strtoupper(sha1($Message));
			
			$Ds_Response += 0; //convertimos la respuenta en un numero concreto.

			if ($firma == $Ds_Signature) {
				if ($Ds_Response >= 0 && $Ds_Response <= 099) {
					$FormaPago = 'tarjeta';		
					$EstadoPago = 'ok';		
					$SQL = "UPDATE ordenes SET FormaPago = '$FormaPago', EstadoPago = '$EstadoPago', Code_Authorisation = '$Ds_AuthorisationCode' WHERE IdOrden = '$Ds_Order'";
					$db->setQuery($SQL);
					$db->execute();
				} else {
					$FormaPago = '--';		
					$EstadoPago = 'Ds_ErrorCode='.$Ds_ErrorCode.'-||-Ds_Response='.$Ds_Response;		
					$SQL = "UPDATE ordenes SET FormaPago = '$FormaPago', EstadoPago = '$EstadoPago', Code_Authorisation = '$Ds_ErrorCode' WHERE IdOrden = '$Ds_Order'";
					$db->setQuery($SQL);
					$db->execute();	
				}
			} 
		} 
	} catch (Exception $e)
		{
			$para = 'informatica@vip4vip.com';
			$titulo = 'NOTIFICACIONES PAGO TARJETA ERROR EL PAIS';
			$mensaje = $e->message();
			$cabeceras = 'From: informatica@vip4vip.com' . "\r\n" . 'Reply-To: informatica@vip4vip.com' . "\r\n" .'X-Mailer: PHP/' . phpversion();
			mail($para, $titulo, $mensaje, $cabeceras);
		}
	//

?>