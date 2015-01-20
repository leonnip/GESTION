<?php
	class Import_Pedidos {
		private $OrdenCustomer;
		private  $firstName; 
	    private $lastName; 
        private $dni; 
		private $phone;
		private $email;
		private $sexo;
		private $dateBirth;
		private $pass;
		private $typeStreet;
		private $address;
		private $typeNumber;
		private $number;
		private $floorp;
		private $door;
		private $postCode;
		private $city;
		private $province;
		private $message;
		private $subIndex;
		
		private $idoferta;
		private $talla;
		private $cantidad;
		
		private $addresf;
		
		public function datos($datos, $index) {
			$this->firstName = htmlspecialchars($datos[$index]['nombres']);
			$this->lastName = htmlspecialchars(utf8_encode($datos[$index]['apellidos']));
			$this->dni = htmlspecialchars($datos[$index]['dni']);
			$this->phone = htmlspecialchars($datos[$index]['telefono']);
			$this->email = htmlspecialchars($datos[$index]['email']);
			$this->address = htmlspecialchars($datos[$index]['direccion']);
			$this->postCode = htmlspecialchars($datos[$index]['cp']);
			$this->city = htmlspecialchars($datos[$index]['poblacion']);
			$this->province = htmlspecialchars($datos[$index]['provincia']);
			
			$this->dateBirth = '0000-00-00';
			$this->sexo = 'no especifica';
			$this->pass = base64_decode('elpaisseleccion');
			
			//$direccion = explode(',', $this->address);
			
			//DIRECCION
			if (empty($direccion[0])) { $d0 = '.'; } else { $d0 = $direccion[0]; }
			if (empty($direccion[1])) { $d1 = '.'; } else { $d1 = $direccion[1]; }
			if (empty($direccion[2])) { $d2 = '.'; } else { $d2 = $direccion[2]; }
			if (empty($direccion[3])) { $d3 = '.'; } else { $d3 = $direccion[3]; }
			if (empty($direccion[4])) { $d4 = '.'; } else { $d4 = $direccion[4]; }
			//FIN DIRECCION
			
			$this->typeStreet = 'Calle';		
			$this->addresf = addslashes($this->address);
			$this->typeNumber = '.';
			$this->number = '.';
			$this->floorp = '.';
			$this->door = '.';
			
			$this->message = stripcslashes(addslashes($datos[$index]['mens']));
			
			$count = count($datos[$index]);
			$_subIndex = $count-11;
			$this->subIndex = $_subIndex;
		}
		
		public function pedidos($datos, $index, $fecha, $conn){
			//OBTENEMOS EL ULTIMOS REGISTRO
			$SQL = "SELECT MAX(Id) AS IdUsuario FROM usuarios";
			$conn->setQuery($SQL);
	
			$result = $conn->loadObject();
			$IdUser = $result->IdUsuario + 1;
			
			$EmailUserInsert = $IdUser.'|'.$this->email;
			
			//CREAMOS EL NUEVO CLIENTE
			//$fechaR = date("Y-m-d");
			$fechaR = $fecha;
			$SQL = "INSERT INTO usuarios(Nombres, Apellidos, Dni, Telefono, Email, Contrasena, Genero, FechaNacimiento, FechaRegistro) ";
			$SQL .= "VALUES('$this->firstName', '$this->lastName', '$this->dni', '$this->phone', '$EmailUserInsert', '$this->pass', '$this->sexo', '$this->dateBirth', '$fechaR')";
			$conn->setQuery($SQL);
			if (!$conn->alter())
				throw new Exception("Error al insertar Usuario");
			$cliente = $conn->getInsertID();
			
			//CREAMOS LA NUEVA ORDEN
			//$fecha1 = date("Y-m-d");
			$fecha1 = $fecha;
			$fecha2 = date("Y-m-d", (mktime(0, 0, 0,date("m"),date("d")+3, date("Y"))));
			$hora = date("g:i a");
			$SQL = "INSERT INTO ordenes (IdCliente, FechaOrden, FechaEnvio, Hora, WebCompra, IpRemota) ";
			$SQL .= "VALUES ('$cliente', '$fecha1', '$fecha2', '$hora', 'elpaisseleccion', '192.168.1.109')";
			$conn->setQuery($SQL);
			if (!$conn->alter())
				throw new Exception("No se ha podido crear la nueva orden.");
			$orden = $conn->getInsertID();
			$conn->freeResults();
			
			//AÑADIRMOS LA DIRECCIÓN DE ENVÍO
			$SQL = "INSERT INTO direcciones (D_IdCliente, D_Nombres, D_Apellidos, Sexo, TipoVia, Direccion, TipoNumero, Numero, Piso, Puerta, Telefono, Cp, Poblacion, Provincia, Fecha, Comentarios) ";
			$SQL .= "VALUES('$cliente', '$this->firstName', '$this->lastName', '$this->sexo', '$this->typeStreet', '$this->addresf', '$this->typeNumber', '$this->number', '$this->floorp', '$this->door', '$this->phone', '$this->postCode', '$this->city', '$this->province', '$fecha1', '$this->message')";
			$conn->setQuery($SQL);
			if (!$conn->alter())
				throw new Exception("No se ha podido ingresar la direccion de entrega.");
			$_id_direcc = $conn->getInsertID();
				
			$SQL = "INSERT INTO relordendireccion (IdCliente, IdOrden, IdDireccion) VALUES('$cliente', '$orden', '$_id_direcc')";
			$conn->setQuery($SQL);
			if (!$conn->alter())
				throw new Exception("No se pudo relacionar la orden con la direccion.");
					
			$conn->freeResults();
			
			$this->idoferta = $datos[$index][0]['idoferta'];
			$this->cantidad = $datos[$index][0]['unidades'];
			$this->talla = $datos[$index][0]['tipo'];
			
			//CREAMOS EL CARRITO DE LA COMPRA IMPORT
			for($subI=0; $subI<=$this->subIndex; $subI++) {
				$SQL = "INSERT INTO carritocompra(IdCarrito, IdProducto, Cantidad, Talla, FechaRegistro) VALUES('$cliente', '".$datos[$index][$subI]['idoferta']."', '".$datos[$index][$subI]['unidades']."', '".$datos[$index][$subI]['tipo']."', '$fecha1')";
				$conn->setQuery($SQL);						
				if (!$conn->alter())
					throw new Exception('Imposible a&ntilde;adir al carrito de la compra.');
			}
			
			
			//AÑADIR LAS LINEAS DE LA ORDEN
			$SQL = "INSERT INTO lineasorden (IdOrden, IdProducto, Cantidad, Talla, GastosEnvio, Subtotal, EstadoPedido) ";
			$SQL .= "SELECT $orden, carritocompra.IdProducto, carritocompra.Cantidad, carritocompra.Talla, (opcionesoferta.GastosEnvio * carritocompra.Cantidad) as gastosEnvio, (opcionesoferta.Precio * carritocompra.Cantidad) as subTotal, 'Enviado' FROM productos ";
			$SQL .= "INNER JOIN carritocompra ON productos.IdOferta = carritocompra.IdProducto ";
			$SQL .= "INNER JOIN opcionesoferta ON carritocompra.Talla = opcionesoferta.Id ";
			$SQL .= "WHERE carritocompra.IdCarrito = '$cliente'";
			$conn->setQuery($SQL);
			if (!$conn->alter())
				throw new Exception("Error al crear las líneas de la orden.");
			$conn->freeResults();
			
			//ACTUALIZAMOS EL TOTAL DE LA ORDEN
			$SQL = "SELECT SUM(Subtotal + GastosEnvio) AS totalOrden FROM lineasorden WHERE IdOrden = '$orden'";
			$conn->setQuery($SQL);
			$result = $conn->loadObject();
			$totalOrden = $result->totalOrden;
			
			$SQL = "UPDATE ordenes SET Total = ".$totalOrden.", FormaPago = 'contra-rembolso', EstadoPago = 'ok', Tramitado = 'call-center', EstadoPedido = 'Enviado' WHERE IdOrden = '$orden'";  //TENEMOS QUE CAMBIAR EL EstadoPedido
			$conn->setQuery($SQL);
			if (!$conn->alter())
				throw new Exception("No se ha podido actualizar el total de la Orden");
			$conn->freeResults();			
			
		}
		
		public function show() {
			echo 'Nombres:=> ' . $this->firstName .'<br/>';
			echo 'Apellidos:=> ' . $this->lastName .'<br/>';
			echo 'Dni:=> ' . $this->dni .'<br/>';
			echo 'Telefono:=> ' . $this->phone .'<br/>';
			echo 'Email:=> ' . $this->email .'<br/>';
			//echo 'Fecha Nac.'.$this->dateBirth .'<br/>';
			echo 'Tipo Via:=> ',$this->typeStreet.'<br/>';
			echo 'direccion:=> '.$this->address .'<br/>';
			echo 'Numero:=> '.$this->number .'<br/>';
			echo 'Piso:=> '.$this->floorp .'<br/>';
			echo 'Puerta:=> '.$this->door .'<br/>';
			echo 'Cod postal:=> '.$this->postCode .'<br/>';
			echo 'Ciudad:=> '.$this->city .'<br/>';
			echo 'Provincia:=> '.$this->province .'<br/>';
			echo 'Mensaje:=> '.$this->message .'<br/>';
			echo 'Sub Indice:=>'.$this->subIndex.'<br/><br/>';
			echo 'IdOferta:=>'.$this->idoferta.'<br/>';
			echo 'Talla:=>'.$this->talla.'<br/>';
			echo 'cantidad:=>'.$this->cantidad.'<br/><br/>';
		}
		
	}
?>