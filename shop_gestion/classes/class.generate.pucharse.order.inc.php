<?php
	class Generate_Pucharse_Order {
		private $OrdenCustomer;
		private  $firstName; 
	    private $lastName; 
        private $dni; 
		private $phone;
		private $email;
		private $pass;
		private $sexo;
		private $anyo_nacimiento;
		private $typeStreet;
		private $address;
		private $typeNumber;
		private $number;
		private $floorp;
		private $door;
		private $postCode;
		private $pais;
		private $city;
		private $province;
		private $message;
		private $dateBirth;
		private $cliente;
		
		public function input() {
			$this->firstName = utf8_decode($_POST['name_payment']);
			$this->lastName = utf8_decode($_POST['last_name_payment']);
			$this->dni = utf8_decode($_POST['dni_payment']);
			$this->email = utf8_decode($_POST['email_payment']);
			$this->phone = utf8_decode($_POST['phone_payment']);
			$this->sexo = utf8_decode($_POST['sexo_payment']);
			$this->anyo_nacimiento = utf8_decode($_POST['anyo_nacimiento']);
			$this->typeStreet = utf8_decode($_POST['type_via_payment']);
			$this->address = utf8_decode($_POST['address_payment']);
			$this->typeNumber = utf8_decode($_POST['type_number_payment']);
			$this->number = utf8_decode($_POST['number_payment']);
			$this->floorp = utf8_decode($_POST['piso_payment']);
			$this->door = utf8_decode($_POST['door_payment']);
			$this->postCode = utf8_decode($_POST['cp_payment']);
			$this->pais = utf8_decode($_POST['pais_payment']);
			$this->city = utf8_decode($_POST['city_payment']);
			$this->province = utf8_decode($_POST['province_payment']);
			$this->message = utf8_decode($_POST['message_payment']);
			$this->pass = base64_encode('password');
			$this->dateBirth = $this->anyo_nacimiento.'-00-00';
		}
		
		public function input_dir($conn, $dir_entrega) {
			$SQL = "SELECT * FROM  direcciones ";
			$SQL .= "INNER JOIN usuarios ON direcciones.D_IdCliente = usuarios.Id ";
			$SQL .= "WHERE direcciones.D_IdCliente = '".$dir_entrega."'";
			$conn->setQuery($SQL);
			if (!$conn->alter()) {
				throw new Exception('Imposible obtener la direccion de entrega.');
			} else {
				$dir = $conn->loadObject();
				$this->firstName = $dir->D_Nombres;
				$this->lastName = $dir->D_Apellidos;
				$this->dni = $dir->Dni;
				$this->phone = $dir->Telefono;
				$this->email = $dir->Email;
				$this->sexo = $dir->Sexo;
				$this->typeStreet = $dir->TipoVia;
				$this->address = $dir->Direccion;
				$this->typeNumber = $dir->TipoNumero;
				$this->number = $dir->Numero;
				$this->floorp = $dir->Piso;
				$this->door = $dir->Puerta;
				$this->postCode = $dir->Cp;
				$this->city = $dir->Poblacion;
				$this->province = $dir->Provincia;
				$this->message = $dir->Comentarios;
				$this->pass = $dir->Contrasena;
				$this->anyo_nacimiento = $_POST['anyo_nacimiento'];
				$this->dateBirth = $this->anyo_nacimiento.'-00-00';
			}
		}
		
		private function GetIdCarrito() {
			if (isset($_COOKIE['usuarioAdmin']))	
				return $_COOKIE['usuarioAdmin'];
			else
				return false;
		}
		
		private function MigrarCarrito($conn, $carrito, $idCustomer) {
			$SQL = "DELETE FROM carritocompra WHERE IdCarrito = '$idCustomer' ";
			$conn->setQuery($SQL);
			$conn->execute();
			$SQL = "UPDATE carritocompra SET IdCarrito = '$idCustomer' WHERE IdCarrito = '$carrito'";
			$conn->setQuery($SQL);
			if (!$conn->alter()) {
				throw new Exception("No se a Podido Migrar el Carrito de la Compra.");				
			}
			setcookie("usuarioAdmin", 1, time()-3600, "/");
		}
		
		public function Order_Transaction($conn, $cupon, $_direcc, $ipremota, $cliente_def, $payment, $tienda) {
			//OBTENEMOS EL ULTIMOS REGISTRO
			$SQL = "SELECT MAX(Id) AS IdUsuario FROM usuarios";
			$conn->setQuery($SQL);
			if (!$conn->alter()) {
				throw new Exception('Imposible obtener el último id de usuario');
			} else {
				$result = $conn->loadObject();
				$idUsuario = $result->IdUsuario + 1;
			}
			
			//SI EL CLIENTE NO TIENE CORREO ELECTRONICO
			if (empty($this->email))
				$emailf= "|" . base64_encode($idUsuario);
			else
				$emailf = "|" . $this->email;
			
			//CREAMOS EL NUEVO CLIENTE
			if ($_direcc == '0') {
				if ($cliente_def == 0) {
					$fechaR = date("Y-m-d");
					$SQL = "INSERT INTO usuarios(Id, Nombres, Apellidos, Dni, Telefono, Email, Contrasena, Genero, FechaNacimiento, FechaRegistro) ";
					$SQL .= "VALUES('$idUsuario', '$this->firstName', '$this->lastName', '$this->dni', '$this->phone', '$emailf', '$this->pass', '$this->sexo', '$this->dateBirth', '$fechaR')";
					$conn->setQuery($SQL);
					if (!$conn->alter()) {
						throw new Exception("Error al insertar Usuario");
					} else {
						$cliente = $conn->getInsertID();
						$this->cliente = $cliente;
						$carrito = $this->GetIdCarrito();
						$this->MigrarCarrito($conn, $carrito, $cliente);
					}
				} else {
					$cliente = $cliente_def;
					$this->cliente = $cliente;
					$carrito = $this->GetIdCarrito();
					$this->MigrarCarrito($conn, $carrito, $cliente);
				}
			} else {
				$cliente = $cliente_def;
				$this->cliente = $cliente;
				$carrito = $this->GetIdCarrito();
				$this->MigrarCarrito($conn, $carrito, $cliente);
			}
	
			//CREAMOS LA NUEVA ORDEN
			$fecha1 = date("Y-m-d");
			$fecha2 = date("Y-m-d", (mktime(0, 0, 0,date("m"),date("d")+3, date("Y"))));
			$hora = date("g:i a");
			$userAdmin = $_SESSION['User'];			
			
			
			$SQL = "INSERT INTO ordenes (IdCliente, FechaOrden, FechaEnvio, Hora, WebCompra, Tramitado, IpRemota) ";
			$SQL .= "VALUES ('$cliente', '$fecha1', '$fecha2', '$hora', '$tienda', '$userAdmin', '$ipremota')";
			$conn->setQuery($SQL);
			if (!$conn->alter())
				throw new Exception("No se ha podido crear la nueva orden.");
			$orden = $conn->getInsertID();
			$this->OrdenCustomer = $orden;
			$conn->freeResults();
			
			//INSERTAMOS LA DIRECCION DE ENTREGA DEL PEDIDO
			if ($_direcc == '0') {
				$SQL = "INSERT INTO direcciones (D_IdCliente, D_Nombres, D_Apellidos, Sexo, TipoVia, Direccion, TipoNumero, Numero, Piso, Puerta, Telefono, Cp, Poblacion, Provincia, Fecha, Comentarios,D_Pais) ";
				$SQL .= "VALUES('$cliente', '$this->firstName', '$this->lastName', '$this->sexo', '$this->typeStreet', '$this->address', '$this->typeNumber', '$this->number', '$this->floorp', '$this->door', '$this->phone', '$this->postCode', '$this->city', '$this->province', '$fecha1', '$this->message', '$this->pais')";
				$conn->setQuery($SQL);
				if (!$conn->alter())
					throw new Exception("No se ha podido ingresar la direccion de entrega.");
				$_id_direcc = $conn->getInsertID();
				
				$SQL = "INSERT INTO relordendireccion (IdCliente, IdOrden, IdDireccion) VALUES('$cliente', '$orden', '$_id_direcc')";
				$conn->setQuery($SQL);
				if (!$conn->alter())
					throw new Exception("No se pudo relacionar la orden con la direccion 0.");
					
				$conn->freeResults();
			} else {
				$SQL = "INSERT INTO relordendireccion (IdCliente, IdOrden, IdDireccion) VALUES('$cliente', '$orden', '$_direcc')";
				$conn->setQuery($SQL);
				if (!$conn->alter())
					throw new Exception("No se pudo relacionar la orden con la direccion.");
				$conn->freeResults();
			}
			
			
			//AÑADIR LAS LINEAS DE LA ORDEN
			$SQL = "INSERT INTO lineasorden (IdOrden, IdProducto, Cantidad, Talla, GastosEnvio, Subtotal) ";
			$SQL .= "SELECT $orden, carritocompra.IdProducto, carritocompra.Cantidad, carritocompra.Talla, (paisesenvio.TotalGastos * carritocompra.Cantidad) as gastosEnvio, (opcionesoferta.Precio * carritocompra.Cantidad) as subTotal FROM productos ";
			$SQL .= "INNER JOIN carritocompra ON productos.IdOferta = carritocompra.IdProducto ";
			$SQL .= "INNER JOIN paisesenvio ON carritocompra.PaisEnvio = paisesenvio.IdPais ";
			$SQL .= "INNER JOIN opcionesoferta ON carritocompra.Talla = opcionesoferta.Id ";
			$SQL .= "WHERE carritocompra.IdCarrito = '$cliente' AND opcionesoferta.Peso > paisesenvio.PesoIn AND opcionesoferta.Peso <= paisesenvio.PesoOut";
			$conn->setQuery($SQL);
			if (!$conn->alter())
				throw new Exception("Error al crear las líneas de la orden.");
			$conn->freeResults();
			
			//ACTUALIZAMOS EL TOTAL DE LA ORDEN
			$SQL = "SELECT SUM(Subtotal + GastosEnvio) AS totalOrden FROM lineasorden WHERE IdOrden = '$orden'";
			$conn->setQuery($SQL);
			$result = $conn->loadObject();
			$totalOrden = $result->totalOrden;
		
			if ($payment == 'contra') {
				$SQL = "UPDATE ordenes SET Total = ".$totalOrden.", FormaPago = 'contra-rembolso', EstadoPago = 'ok' WHERE IdOrden = '$orden'";
				$conn->setQuery($SQL);
				if (!$conn->alter())
					throw new Exception("No se ha podido actualizar el total de la Orden");
				$conn->freeResults();
			} else if ($payment == 'visa') {
				$SQL = "UPDATE ordenes SET Total = ".$totalOrden." WHERE IdOrden = '$orden'";
				$conn->setQuery($SQL);
				if (!$conn->alter())
					throw new Exception("No se ha podido actualizar el total de la Orden Visa");
				$conn->freeResults();
			}
			
			//ELIMANOS EL CARRITO DE LA COMPRA
			$SQL = "DELETE FROM carritocompra WHERE IdCarrito = '$cliente'";
			$conn->setQuery($SQL);
			if (!$conn->alter())
				throw new Exception("No se ha podido eliminar el carrito de la compra una vez generada la orden.");	
			$conn->freeResults();
			
			//ACTUALIZAMOS LA TABLA CUPON SI EXISTE DESCUENTO
			if (isset($_SESSION['Cupon'])) {
				$SQL = "UPDATE cupones SET IdOrder = '$orden', IdCliente = '$cliente', Estado = '1' WHERE CodCupon = '$cupon' AND Activo = '1'";
				$conn->setQuery($SQL);
				if (!$conn->alter())
					throw new Exception("No hemos podido actualizar la tabla Cupones");
			}
			
		}
		
		public function Datos_Transaction($conn) {
			$SQL = "SELECT usuarios.Nombres, usuarios.Apellidos, ordenes.IdOrden, ordenes.Total, SUM(lineasorden.Cantidad) AS totalProd, SUM(lineasorden.Subtotal + lineasorden.GastosEnvio) as totalCompra, GROUP_CONCAT(CONCAT_WS('|',productos.Nombre_Producto, opcionesoferta.Opcion)) AS detalleProd FROM usuarios ";
			$SQL .= "INNER JOIN ordenes ON usuarios.Id = ordenes.IdCliente ";
			$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
			$SQL .= "INNER JOIN productos ON productos.IdOferta = lineasorden.IdProducto ";
			$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.IdOpcion ";
			$SQL .= "WHERE ordenes.IdOrden = '$this->OrdenCustomer' AND usuarios.Id = '$this->cliente'";
			$conn->setQuery($SQL);
			$objShop = $conn->loadObject();
			$conn->execute();
			return $objShop;
		}
		
		public function show() {
			echo 'Nombres:' . $this->firstName .'<br/>';
			echo 'Apellidos:' . $this->lastName .'<br/>';
			echo 'Dni:' . $this->dni .'<br/>';
			echo 'Telefono:' . $this->phone .'<br/>';
			echo 'Sexo:' . $this->sexo .'<br/>';
			echo 'Fecha Nac.'.$this->dateBirth .'<br/>';
			echo 'direccion'.$this->address .'<br/>';
			echo 'Numero'.$this->number .'<br/>';
			echo 'Piso'.$this->floorp .'<br/>';
			echo 'Puerta'.$this->door .'<br/>';
			echo 'Cod postal'.$this->postCode .'<br/>';
			echo 'Pais'.$this->pais.'<br/>';
			echo 'Ciudad'.$this->city .'<br/>';
			echo 'Provincia'.$this->province .'<br/>';
			echo 'Mensaje'.$this->message .'<br/>';
		}
		
	}
?>