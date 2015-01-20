<?php
require_once('../config.inc.php');
include('fpdf17/fpdf.php');
include('class/CCliente.php');
include('class/CFactura.php');

class CPdf extends FPDF
{
    //El código ascci del €
    private $_AsciiEuro;
     
    //r: Ruta. La ruta de la imágen a insertar en el PDF
    private $_rImagen;
    private $_sEmpresaEmisora;
    private $_sCif;
	private $_sDireccion;
	private $_sTeleFax;
	private $_sMail;
	private $_sTextoEmpresa;
     
    private $_oCliente;
    private $_oFactura;
 
     
    public function __construct(/*CCliente $oCliente, CFactura $oFactura,*/ $sEmpresaEmisora="", $sCif="", $sDireccion="", $sTeleFax="", $sMail="", $sTextoEmpresa, $rImagen="mr.jpg", $cOrientacion="P", $sUnidDistancia="mm", $sTamanoFolio="A4") 
    {
        parent::__construct($cOrientacion, $sUnidDistancia, $sTamanoFolio);
        $this->_AsciiEuro = chr(128);
                 
        $this->_sEmpresaEmisora = $sEmpresaEmisora;
        $this->_sCif = $sCif;
        $this->_rImagen = $rImagen;
		$this->_sDireccion = $sDireccion;
		$this->_sTeleFax = $sTeleFax;
		$this->_sMail = $sMail;
		$this->_sTextoEmpresa = $sTextoEmpresa;
         
       /* $this->_oCliente = $oCliente;
        $this->_oFactura = $oFactura;*/
    }
	
	function Rotate($angle,$x=-1,$y=-1) {
	    if($x==-1)
    	    $x=$this->x;
	    if($y==-1)
    	    $y=$this->y;
	    if($this->angle!=0)
    	    $this->_out('Q');
	    $this->angle=$angle;
    	if($angle!=0)
    	{	
        	$angle*=M_PI/180;
	        $c=cos($angle);
    	    $s=sin($angle);
        	$cx=$x*$this->k;
	        $cy=($this->h-$y)*$this->k;
    	    $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	    }
	} 
     
	function Header() {
	    //Arial bold 15
    	$this->SetFont("Arial","",8);
	    //Move to the right
    	$this->Cell(80);
	    //Title
    	$this->Cell(30,10, EMPRESA_FACT ,0,0,'C');
	    //Line break
    	$this->Ln(18);
		
		//Texto girado de la izquierda del pdf
		$this->SetFont("helvetica","",8);
		$this->Rotate(90, 6, 220);
		$this->Text(6,220,utf8_decode($this->_sTextoEmpresa),45);
		$this->Rotate(0);
	}
	
	//Page footer
	function Footer() {
    	//Position at 1.5 cm from bottom
	    $this->SetY(-25);
    	//Arial italic 8
	    $this->SetFont('Arial','I',8);
    	//Page number
		$this->Cell(0,10, utf8_decode($this->_sTextoEmpresa),0,0,"C");
		$this->SetY(-19);
		$this->SetTextColor(200,200,200);
	    $this->Cell(0,10,utf8_decode("Página ").$this->PageNo().'/{nb}',0,0,'C');
		$this->SetTextColor(0,0,0);
	}
 
    //Cabecera de página, Titulo y Logo
    private function set_config_header()
    {
        //Arial bold 15
        $iTamanoFuente = 15;
        $this->SetFont("Arial","B",$iTamanoFuente);
 
        //Configuración del Logo de la empresa
        //ruta a la imágen
        $rImagen = $this->_rImagen;
        $iMargenX = 10;
        $iMargenY = 18;
        $iAnchoImagen = 74;
        $this->Image($rImagen, $iMargenX, $iMargenY, $iAnchoImagen);
        //Movernos a la derecha, 1: dejamos una celda por la izquierda
        $this->Cell(110);
         
        //Configuracion del títutlo de la cabecera:
        $iAncho = 10;
        $iAltura = 0;
        //Los caracteres castellanos hay que pasarlos a ISO-8859-1 que es
        //el juego con el que trabaja FPDF
        $sTitulo = utf8_decode("Factura nº: ");
         
        //Sin borde
        $iAnchoBorde = 0;
        //Indico que no haga un salto de linea despues de pintar el título sino haría
        //algo como: Factura nº: (salta una linea)
        // 232 y quiero que sea:  Factura nº: 232 (en una sola linea)
        $iSaltosLinea = 0;
        //Alineado a la izquierda
        $cAlineacion = "L";
        $this->Cell($iAncho,$iAltura,$sTitulo,$iAnchoBorde,$iSaltosLinea,$cAlineacion);
    }
  
    //Muestra el Nº al lado de "FACTURA nº:"
    private function set_numero_factura()
    {
        $oFactura = $this->_oFactura;
        $iNumFactura = $oFactura->get_numero();
        //Arial bold 15
        $this->SetFont("Arial","B",15);
        $this->Cell(23);
        //Título
        $this->Cell(10,0,$iNumFactura,0,0,"L");
        //Salto de línea
        $this->Ln(10);
    }
  
    private function set_datos_empresa_emisora()
    {
        $sNombreEmpresa = $this->_sEmpresaEmisora;
        $sCif = $this->_sCif;
		$sDireccion = $this->_sDireccion;
		$sTeleFax = $this->_sTeleFax;
		$sMail = $this->_sMail;
         
        $oFactura = $this->_oFactura;
        $dFecha = $oFactura->get_fecha();
        //Convierto 2010-01-01 a 1 de Enero de 2010
        //$sFechaLarga = CUtils::fFechaLarga($dFecha);
         
        //Esto me ayudara a dejar un espacio fijo desde el margen izquierdo
        //de modo que se vea tabulado algo como:
        //          Fecha
        //..115..   Empresa (emisora)
        //          Cif (cif empresa emisora)
        $iAnchoTabular = 1;
 
        $this->SetFont("Arial","B",12);
        $this->Cell($iAnchoTabular);
        //$this->Cell(1,7,"FECHA:",20,0,"L");
        $this->Ln(10);
        //$this->SetFont("Arial","B",12);
        //$this->Cell($iAnchoTabular);
        //$this->Cell(1,7,$dFecha,20,0,"L");
        //$this->Ln();
		
        $this->Cell($iAnchoTabular);
        $this->Cell(1,6,$sNombreEmpresa,20,0,"L");
        $this->Ln();
		
		$this->Cell($iAnchoTabular);
        $this->Cell(1,6, $sDireccion,20,0,"L");
        $this->Ln();
		
        $this->Cell($iAnchoTabular);
        $this->Cell(1,6,"CIF - $sCif",20,0,"L");
		$this->Ln();
		
		$this->SetFont("Arial","",10);
		$this->Cell($iAnchoTabular);
        $this->Cell(1,6,$sTeleFax,20,0,"L");
        $this->Ln();
		
		$this->Cell($iAnchoTabular);
        $this->Cell(1,6,$sMail,20,0,"L");
        $this->Ln(-45);		
    }
  
    private function set_datos_cliente()
    {
        $oCliente = $this->_oCliente;
        $sNombreEmpresa = $oCliente->get_empresa();
        $sCif = $oCliente->get_cifnif();
        $sDireccion = $oCliente->get_direccion();
        $sCodigoPostal = $oCliente->get_codigo_postal();
        $sCiudad = $oCliente->get_ciudad();
		$sTelefono = $oCliente->get_telefono();
		$sFechaOrden = $oCliente->get_fechaOrden();
         
        $this->SetFont("Arial","B",10);
		$this->Cell(90);
        $this->Cell(0,6,"RAZON SOCIAL:",1,0,"L");
        //$this->Ln();
        $this->SetFont("Arial","B",10);
		$this->Cell(-60);
        $this->Cell(0,6,utf8_decode($sNombreEmpresa),1,0,"L");
        $this->Ln();
		
		$this->Cell(90);
		$this->SetFont("Arial","B",10);
		$this->Cell(0,6,"DIRECCION:",1,0,"L");
		
        $this->SetFont("Arial","",10);
		$this->Cell(-60);
        $this->Cell(0,6,utf8_decode($sDireccion),1,0,"L");
        $this->Ln();
		
		$this->Cell(90);
		$this->SetFont("Arial","B",10);
		$this->Cell(0,6,"COD. POSTAL:",1,0,"L");
		
        $this->SetFont("Arial","",10);
		$this->Cell(-60);
        $this->Cell(0,6,str_pad($sCodigoPostal,5,"0",STR_PAD_LEFT),1,0,"L"); 
        $this->Ln();
		
		$this->Cell(90);
		$this->SetFont("Arial","B",10);
		$this->Cell(0,6,"POBLACION:",1,0,"L");
		
        $this->SetFont("Arial","",10);
		$this->Cell(-60);
        $this->Cell(0,6,utf8_decode($sCiudad),1,0,"L");
		$this->Ln();
		
		$this->Cell(90);
		$this->SetFont("Arial","B",10);
		$this->Cell(0,6,"TELEFONO:",1,0,"L");
		
        $this->SetFont("Arial","",10);
		$this->Cell(-60);
        $this->Cell(0,6,$sTelefono,1,0,"L");
        $this->Ln();
		
		$this->Cell(90);
		$this->SetFont("Arial","B",10);
		$this->Cell(0,6,"FECHA",1,0,"L");
		
		$this->Cell(-60);
		$this->SetFont("Arial","B",10);
		$this->Cell(0,6,"CIF:",1,0,"C");
		$this->Ln();
		
		$this->Cell(90);
		$this->SetFont("Arial","B",10);
		$this->Cell(0,6,$sFechaOrden,1,0,"L");
		
		$this->Cell(-60);
		$this->SetFont("Arial","B",10);
		$this->Cell(0,6,$sCif,1,0,"C");
		
		$this->Ln(20);
    }
  
    private function set_datos_detalle()
    {
        //Símbolo del euro
        $cEuro = $this->_AsciiEuro;
        $arDetalles = $this->_oFactura->get_detalle();
         
        $this->SetFont("Arial","B",10);
		$this->SetFillColor(242,242,242);//Fondo verde de celda
        //$this->SetTextColor(240, 255, 240); //Letra color blanco
         
        //Esto formara la cabecera de la tabla de detalle algo como:
        // |            Concepto            |   Cantidad    |
        $arLabelsCabeceras = array("Codigo","Descripcion","Und", "Precio", "Envio", "Importe");
        //Anchuras de las columnas, coindicen con la posición de las cabeceras
        $arAnchoColumna = array(20,90,15,20,20,25);        
 
        $iAltura = 12;
        $iAnchoBorde = 1;
        $iSaltosLinea = 0;
        //Centrado
        $cAlineacion = "C";
         
        //Dibujo las cabeceras
        foreach($arLabelsCabeceras as $iIndice => $sTituloCabecera)
        {
            $iAnchoColumna = $arAnchoColumna[$iIndice];
            $this->Cell($iAnchoColumna,$iAltura,$sTituloCabecera,$iAnchoBorde,$iSaltosLinea,$cAlineacion, true);
        }
        //Despues de dibujar la linea con las cabeceras se hace un salto
        $this->Ln();
 
        //Reconfiguro la fuente para que no sea en negrita
        $this->SetFont("Arial","",10);
         
        $iAltura = 6;
        //Dibujo el detalle de la factura $arDetalles es del tipo n x ("CONCEPTO"=>"..","CANTIDAD"=>"")
		$conta = 0;
		$x = 16;
		$y = 26;
        foreach($arDetalles as $arDetalle)
        {
			$conta = $conta + 1;
			$contArray = count($arDetalles);
			//Columna Codigo	
			/*		
			$codigo = 1;
			$iAnchoColumna = $arAnchoColumna[0];
            $this->Cell($iAnchoColumna, $iAltura, $codigo,"LR",0,"C");
			*/
			
            //Columna concepto
			/*
            $sConcepto = $arDetalle["CONCEPTO"];
            $iAnchoColumna = $arAnchoColumna[1];
			*/
			
			/*
			$this->SetXY(10,100);
			$this->Cell(25, 6, "hola",0, 0, "C");
			$this->SetXY(35,97); 
			$this->MultiCell(85,6,"adios como estas amor hola esta o que mor de mi via quiero verte", 0, 0, "L");
			$this->SetXY(120,100);
			$this->MultiCell(25,6, "9",0,0,"C");
			$this->SetXY(150, 100);
			$this->Cell(25,6,"11",0,0,"C");
			$this->SetXY(175,100);
			$this->Cell(25,6,"11,90",0,0,"C");
			*/
			
			if ($conta%2 == 0) { $color = 1; } else { $color = 0; }
			
			$x = $this->x;
			$y = $this->y;
			$push_right = 0;

			$this->MultiCell($w = 20,6, $arDetalle['codigo']."\n ","LR",'C',$color);
	
			$push_right += $w;
			$this->SetXY($x + $push_right, $y);

			$this->MultiCell($w = 90,6, $arDetalle['descripcion']."\n".$arDetalle['opcion'],"LR",'L',$color);

			$push_right += $w;
			$this->SetXY($x + $push_right, $y);

			$this->MultiCell($w = 15,6, $arDetalle['cantidad']."\n ","LR",'C',$color);
			
			$push_right += $w;
			$this->SetXY($x + $push_right, $y);

			$this->MultiCell($w = 20,6, number_format($arDetalle['precio'],2,',','.')."\n ","LR",'C',$color);
			
			$push_right += $w;
			$this->SetXY($x + $push_right, $y);

			$this->MultiCell($w = 20,6, number_format($arDetalle['gastos'],2,',','.')."\n ","LR",'C',$color);
			
			$push_right += $w;
			$this->SetXY($x + $push_right, $y);

			$this->MultiCell($w = 25,6, number_format($arDetalle['subtotal'],2,',','.')."\n ","LR",'C',$color);
			
			/*
			$this->SetFont("Arial","",10);
			$this->MultiCell($iAnchoColumna, $iAltura, "Colchon viscoelastico newlux con tejido aloe ","L","L",0);
			$this->SetXY($x+90,$y);
			*/

            //$this->Cell($iAnchoColumna, $iAltura, $sConcepto,"LR");
			
            //Columna cantidad
			/*
            $iAnchoColumna = $arAnchoColumna[2];
            $fCantidad = $arDetalle["CANTIDAD"];
            $sMonto = number_format($fCantidad,2,",","");
            $sMonto = "$sMonto $cEuro";
            $this->Cell($iAnchoColumna, $iAltura, $sMonto,"LR",0,"C");
			*/
			
			//Columna Precio
			/*
			$precio = '12,5';
			$iAnchoColumna = $arAnchoColumna[3];
			$this->Cell($iAnchoColumna, $iAltura, $precio,"LR",0,"C");
			*/
			
			//Columna Importe
			/*
			$importe = '15,5';
			$iAnchoColumna = $arAnchoColumna[4];
			$importe = number_format(12.60,2,',','.');
			$importe = "$importe $cEuro";
			$this->Cell($iAnchoColumna, $iAltura, $importe,"LR",0,"C");
            */ 
			 
            //$this->Ln();
			
			if ($conta == $contArray) {
				while($conta <= 11) {	
					$x = $this->x;
					$y = $this->y;				
					$push_right = 0;
					$conta = $conta + 1;
					
					$this->MultiCell($w = 20,6," \n ","LR",'C',0);
					$push_right += $w;
					$this->SetXY($x + $push_right, $y);
					$this->MultiCell($w = 90,6," \n ","LR",'L',0);
					$push_right += $w;
					$this->SetXY($x + $push_right, $y);
					$this->MultiCell($w = 15,6," \n ","LR",'C',0);
					$push_right += $w;
					$this->SetXY($x + $push_right, $y);
					$this->MultiCell($w = 20,6," \n ","LR",'C',0);
					$push_right += $w;
					$this->SetXY($x + $push_right, $y);
					$this->MultiCell($w = 20,6," \n ","LR",'C',0);
					$push_right += $w;
					$this->SetXY($x + $push_right, $y);
					$this->MultiCell($w = 25,6," \n ","LR",'C',0);
					//$this->Ln();					
				}
			}
			
        }
 
        //Línea de cierre
        $iAnchoTotal = array_sum($arAnchoColumna);
        $this->Cell($iAnchoTotal,0,"","T");
        $this->Ln();
    }
 
    //TODO se puede mejorar con un bucle
    private function set_resumen_totales()
    {
        //Símbolo del euro
        $cEuro = $this->_AsciiEuro;
        $oFactura = $this->_oFactura;
         
        $this->SetFont("Arial","B",10);
  
        $arLabels = array
        (
            "subtotal"=>"Subtotal: ",
            "iva"=>"Iva: ",
            "total"=>"Total IVA Incluido: "
        );
  
        //Anchuras de las columnas
        $arAnchoColumna = array("concepto"=>165,"cantidad"=>25);
        $iAltoCelda = 8;
        foreach ($arLabels as $sIndice =>$sLabel)
        {
            switch ($sIndice) 
            {
                case "subtotal":
                    $sEuros = number_format($oFactura->get_subtotal(),2,",","");
                break;
                case "iva":
                    $sEuros = number_format($oFactura->get_iva(),2,",","");
                break;
                case "total":
                    $sEuros = number_format($oFactura->get_total(),2,",","");
                break;
                default:
                    $sEuros = "0,00";
                break;
            }
            $sEuros = "$sEuros $cEuro";
            //Label
            $this->Cell($arAnchoColumna["concepto"],$iAltoCelda, $sLabel, "LR",0,"R");
            //Cantidad
            $this->Cell($arAnchoColumna["cantidad"],$iAltoCelda, $sEuros, "LR",0,"C");
            $this->Ln();            
        }
        //Línea de cierre
        $iAnchoTotal = array_sum($arAnchoColumna);
        $this->Cell($iAnchoTotal,0,"","T");
    }
     
    //Pie de página
	/*
    private function set_config_footer()
    {
        $iNumPagina = $this->PageNo();
        //Posición: a 15mm (1,5) cm del final
        $this->SetY(-35);
        //Arial Bold italic 8
        $this->SetFont("Arial","",8);
        //Número de página
		$this->SetTextColor(201, 201, 201);
        $sTextoCelda = "Página $iNumPagina/{nb}";
        $sTextoCelda = utf8_decode($sTextoCelda);
        $cAlineacion = "C";
        //Ver la configuracion de los parametros en la linea 49
        $this->Cell(0,10, $sTextoCelda, 0,0, $cAlineacion);
    }
	*/
     
    /**
     * IMPORTANTE: Este metodo se encarga del rasterizado del contenido
     * pasado a formato PDF.  
     * No se puede llamar a este metodo despues de haber enviado cabeceras al
     * navegador. No porque sea mi metodo, es que la clase FPDF tiene esa restricción
     * El error provocado es este:
     * FPDF error: Some data has already been output, can't send PDF file (output started at ...
     */
    public function generar_factura()
    {
        //Iniciamos el objeto Página sobre el que se dibujará el contenido del
        //PDF
        $this->AddPage();
        //Activamos la opción para que muestre el número de páginas
        $this->AliasNbPages();		
         
        //LOS METODOS EXTENDIDOS:
        //Posiciona el logotipo de la hoja, en este caso para emular un papel membretado
        //el logo es una imagen .jpg de tamaño A4
        $this->set_config_header();
        //Asignamos el número de factura a: Factura nº:, creado en el paso anterior
        $this->set_numero_factura();        
        //Dibujamos la fecha de la factura y los datos de nuestra empresa
        //Lleva un salto de linea de 10
        $this->set_datos_empresa_emisora();
        //Saltamos 10 lineas extra, un pequeño parche
        $this->Ln(8);
        //Dubujamos los datos del cliente: Nombre, Nif, Domicilio
        //lleva un salto de línea de 20
        $this->set_datos_cliente();
        //Dibujamos el detalle desglosado de la factura: Concepto, Cantidad
        $this->set_datos_detalle();
        //Dibujamos el resumen de: subtotal, iva y total
        $this->set_resumen_totales();
        //Configuramos lo que mostrará en el pie de página: Página i/n
        //$this->set_config_footer();
        //En este punto todo el documento está generado, con este metodo
        //lo imprimimos en PDF
		//enviamos cabezales http para no tener problemas
        //$this->Output("Factura.pdf", "D");
    }
     
	public function print_pdf() {
		/*header("Content-Transfer-Encoding", "binary");
		header('Cache-Control: maxage=3600'); 
		header('Pragma: public');*/
		$oFactura = $this->_oFactura;
        $iNumFactura = $oFactura->get_numero();
		$this->Output("Factura-".$iNumFactura.".pdf", "I");
	}
	
    public function set_imagen($rImagen)
    {
        $this->_rImagen = $rImagen;
    } 
	public function set_cliente($cliente) {
		$this->_oCliente = $cliente;
	}
	public function set_factura($factura) {
		$this->_oFactura = $factura;
	}
  
}


//OPERAMOS CON EL NUMERO DE ORDEN
require "../conexion/conexion.inc.php";
$db = DataBase::getInstance();

//RECIBIMOS EL ARRAY
function array_recibe($url_array) { 
   	$tmp = stripslashes($url_array); 
	$tmp = urldecode($tmp); 
    $tmp = unserialize($tmp); 
	return $tmp; 
}

function dataCliente($orden, $db) {
	/*$SQL = "SELECT ordenes.*, lineasorden.*, productos.*, opcionesoferta.Opcion, opcionesoferta.OptActiva, opcionesoferta.Precio, opcionesoferta.Iva, usuarios.*, direcciones.* FROM ordenes ";
	$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
	$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
	$SQL .= "INNER JOIN direcciones ON ordenes.IdCliente = direcciones.D_IdCliente ";
	$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
	$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	$SQL .= "WHERE ordenes.IdOrden = '$orden'";*/	
	
	$SQL = "SELECT ordenes.*, lineasorden.*, productos.*, opcionesoferta.Opcion, opcionesoferta.OptActiva, opcionesoferta.Precio, opcionesoferta.Iva, usuarios.*, direcciones.* FROM ordenes ";
	$SQL .= "INNER JOIN lineasorden ON ordenes.IdOrden = lineasorden.IdOrden ";
	$SQL .= "INNER JOIN usuarios ON ordenes.IdCliente = usuarios.Id ";
	$SQL .= "INNER JOIN relordendireccion ON ordenes.IdOrden = relordendireccion.IdOrden ";
	$SQL .= "INNER JOIN direcciones ON direcciones.IdDireccion = relordendireccion.IdDireccion ";
	$SQL .= "INNER JOIN productos ON lineasorden.IdProducto = productos.IdOferta ";
	$SQL .= "INNER JOIN opcionesoferta ON lineasorden.Talla = opcionesoferta.Id ";
	$SQL .= "WHERE ordenes.IdOrden = '$orden'";
	
	
	$db->setQuery($SQL);
	$row = $db->execute();
	if (mysqli_num_rows($row) > 0) {
		$result = $db->loadObjectList();
		foreach($result as $result1) {
			if ($result1->OptActiva == 1) { $opt = $result1->Opcion; } else { $opt = " ";}
			$pedidos[] = array('codigo'=>$result1->IdOferta, 
						   'descripcion'=>$result1->Nombre_Producto, 
						   'opcion'=>$opt, 
						   'cantidad'=>$result1->Cantidad, 
						   'precio'=>($result1->Precio/$result1->Iva), 
						   'gastos'=>($result1->GastosEnvio/1.21), 
						   'subtotal'=>(($result1->Subtotal/$result1->Iva) + ($result1->GastosEnvio/1.21)));
			$fechaOrden = $result1->FechaOrden;
			$total = $total + ($result1->Subtotal + $result1->GastosEnvio);
			$subtotal = $subtotal + ($result1->Subtotal/$result1->Iva);
			$ivaSubtotal = $ivaSubtotal + ($result1->Precio - ($result1->Precio/$result1->Iva));
			$gastos = $gastos + ($result1->GastosEnvio/1.21);		
			$ivaGastos = $ivaGastos + ($result1->GastosEnvio - ($result1->GastosEnvio/1.21));
		
			//DATOS TRANSACCION CLIENTE
			$nombes = utf8_encode($result1->D_Nombres) . " " . utf8_encode($result1->D_Apellidos);
			$dni = $result1->Dni;
			$direccion = utf8_encode($result1->Direccion)." ".utf8_encode($result1->Numero)." ".utf8_encode(strtoupper($result1->Piso))." ".utf8_encode(strtoupper($result1->Puerta));
			$poblacionPro = utf8_encode($result1->Poblacion) ." - ".utf8_encode($result1->Provincia);
			$cp = $result1->Cp;
			$telefono = $result1->Telefono;	
			$arrayData1 = array('pedidos'=>$pedidos, 'fechaOrden'=>$fechaOrden, 'total'=>$total, 'subtotal'=>$subtotal, 'ivaSubtotal'=>$ivaSubtotal,
								'gastos'=>$gastos, 'ivaGastos'=>$ivaGastos, 'nombres'=>$nombes, 'dni'=>$dni, 'direccion'=>$direccion, 'poblacionPro'=>$poblacionPro,
								'cp'=>$cp, 'telefono'=>$telefono);
		}
		$iva = $ivaGastos + $ivaSubtotal;
		$subtotalTT = $subtotal + $gastos;
		$arrayData2 = array('iva'=>$iva, 'subtotalTT'=>$subtotalTT);
		$arrayData = array_merge($arrayData1, $arrayData2);
	}
	return $arrayData;
}
//FIN DE OPERACION

//RECIBIMOS EL ARRAY DE IDORDENES
$array = $_REQUEST['orden'];
if (count($array) > 1)
	$array = $_REQUEST['orden'];
else
	$array=array_recibe($array); 

//CREAMOS EL PDF PARA LAS FACTURAS
$oFacturaPdf = new CPdf(EMPRESA_FACT, CIF_FACT, 
						DIRECCION_FACT, TELEFONO_FACT,
						EMAIL_FACT, DESCRIP_FACT);

//RECORREMOS EL ARRAY DE FACTURAS
foreach($array as $indice => $valor) {
	$clienFact = dataCliente($valor, $db);
	//La clase CCliente deberia ser un modelo, aqui estoy emulando el objeto.
	$oCliente = new CCliente($clienFact['nombres'], $clienFact['dni'], $clienFact['direccion'], $clienFact['cp'], $clienFact['poblacionPro'], $clienFact['telefono'], $clienFact['fechaOrden']);
	$oFactura = new CFactura($clienFact['fechaOrden'], $clienFact['total'], $clienFact['subtotalTT'], $clienFact['iva'], $clienFact['gastos'], $clienFact['ivaGastos'], $valor.$tpv_orden, $clienFact['pedidos']);
	
	//$oCliente = new CCliente($nombes, $dni, $direccion, $cp, $poblacionPro, $telefono, $fechaOrden);
	//$oFactura = new CFactura($fechaOrden, $total, $subtotalTT, $iva, $gastos, $ivaGastos, $orden, $pedidos);
	//$rImagen = guarda algo como: protohtt://localhost/proy_anytest/html_images/LogoHoja.jpg
	$rImagen = ROOT . '/images/' . $logo;
	$oFacturaPdf->set_imagen($rImagen);
	$oFacturaPdf->set_cliente($oCliente);
	$oFacturaPdf->set_factura($oFactura);
	$oFacturaPdf->generar_factura();
}
$oFacturaPdf->print_pdf();
?>