<?php
session_start();
require_once('../config.inc.php');

if (!isset($_SESSION['Logged']) && !isset($_SESSION['UserIdAdmin'])) {
} else {
    $nombre_fichero = 'pedidos.csv';
    if (file_exists($nombre_fichero)) {
        # IMPORTAMOS EXCEL UNA VEZ PRESIONADO EL BOTON DE IMPORTACION
        # -----------------------------------------------------------------------------------------------------------------
        if (isset($_POST['import-excel'])) {
	        require_once("../conexion/conexion.inc.php");
    	    $db = DataBase::getInstance();
	
    	    include("class.import.pedidos.inc.php");
        	$pedidos = new Import_Pedidos();
	
	        require_once ('loading.inc.php'); 
        	$divLoader = new loadingDiv;
	
	        //echo "el numero de lineas es ".count(file("pedidos.csv"))."<br/>"; 
    	    $divLoader->loader();

        	$fp = fopen('pedidos.csv','r');
	        if (!$fp) {echo 'ERROR: No ha sido posible abrir el archivo. Revisa su nombre y sus permisos.'; exit;}
	
    	    $loop = 0; // contador de líneas
    	    while (!feof($fp)) { // loop hasta que se llegue al final del archivo
	    	    $line = fgets($fp); // guardamos toda la línea en $line como un string
    	    	// dividimos $line en sus celdas, separadas por el caracter |
	    	    // e incorporamos la línea a la matriz $field
    		    $field[$loop] = explode (';', $line);
        		//$direc = explode(',', $field[$loop][5]);
		
	        	//CAMPO INDEX DNI
		        $dato_dni[] = $field[$loop][2];
		
        		$loop++;
	        	$fp++; // necesitamos llevar el puntero del archivo a la siguiente línea
    	    }
    	    fclose($fp);
		
	        //NUEVA IMPLEMENTACION
    	    function buscar($dat, $limit, $dat_unico) {
	    	    for($j=0; $j<=$limit; $j++) {
		    	    if($dat_unico[$j]==$dat)
                	    return $j;
    		    }
    	    }
	    
    	    $arrayUnique = array_unique($dato_dni);
        	$arrayUniqueOrd = array_values($arrayUnique);
	        $numlineas = count($arrayUnique);
        	$fecha1 = $_POST['fecha'];
	        //$fecha1 = '2013-10-06';
	
    	    for($a=0; $a<=$numlineas-1; $a++) {
	    	    $clave = buscar($arrayUniqueOrd[$a], $loop, $arrayUnique);
    		    $array[] = array(
	    		    'nombres'=>$field[$clave][0], 'apellidos'=>$field[$clave][1], 'dni'=>$field[$clave][2], 'telefono'=>$field[$clave][3], 
    	    		'email'=>$field[$clave][4], 'direccion'=>$field[$clave][5], 'cp'=>$field[$clave][6], 'poblacion'=>$field[$clave][7], 
	    	    	'provincia'=>$field[$clave][8], 'mens'=>$field[$clave][17]);
    	    }
	
	        for ($x=0; $x <= $numlineas-1; $x++) {
		        $pedido[$x] = array(
			        	'nombres'=>$array[$x]['nombres'], 'apellidos'=>$array[$x]['apellidos'], 'dni'=>$array[$x]['dni'], 'telefono'=>$array[$x]['telefono'], 
				        'email'=>$array[$x]['email'], 'direccion'=>$array[$x]['direccion'], 'cp'=>$array[$x]['cp'], 'poblacion'=>$array[$x]['poblacion'], 
    				    'provincia'=>$array[$x]['provincia'], 'mens'=>$array[$x]['mens']);		
		
	        	$claves = array_keys($dato_dni, $arrayUniqueOrd[$x]);
		        $n = count($claves);
    		    for($j = 0; $j <= $n-1; $j++){
	    		    $key = $claves[$j];
    		    	$pedido[$x][] = array('idoferta'=>$field[$key][9], 'tipo'=>$field[$key][10], 'unidades'=>$field[$key][14]);
        		}
	        }
	
    	    $countOrderInsert = 0;
        	if (empty($fecha)) {
	        	try {
		        	$db->AutoCommit();
			        for ($index=0; $index<=$numlineas-1; $index++) {
				        $arry[] = $pedido[$index];
        				$pedidos->datos($arry, $index);
	        			$pedidos->pedidos($pedido, $index, $fecha1, $db);
		        		//$pedidos->show();
			        	$countOrderInsert ++;
				    }		
        		} catch (Exception $e) {
	        		$db->Rollback();
		        	$error = $e->getMessage();
			        echo $countOrderInsert.'=>'.$error;
    			    exit();
    	    		$transaccion = 'false';
	    	    }
    	    	if ($db->Commit()){
	    	    	$transaccion = 'true';
    		    }
            } 
            # AQUI TERMMINAMOS EL PROCESO DE IMPORTACION DEL FICHERO 
        } 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>IMPORTADOR DE PEDIDOS CONSULTA PREPARADA</title>
        <style type="text/css">
			* { margin: 0px; padding: 0px; }
			img { border: 0px; }
			body, html { background: #f5f5f5; }
			#container { width: 500px; height: 400px; margin: 25px auto; text-align:center; font-variant: small-caps; font-size: 18px; font-family: "Trebuchet MS", Arial, Helvetica, sans-serif; }
			h1 { display: block; margin-bottom: 15px;}
			table { width: 100%; }
			table tr td { height: 55px; background: #FFF; }
			hr { display: block; width: 100%; height: 2px; border-top: 1px solid #DBDBDB; border-bottom: 1px solid #FFF; margin-top: 15px; margin-bottom: 15px; }
		</style>
        <link href="stylo-fecha.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="../../js/jquery.tools.min.js"></script>
        <script type="text/javascript">
			$(document).ready(function(e) {
                /*=== SELECCION DE FECHAS ===*/
				$('#fecha').dateinput({ format: "yyyy-mm-dd"});
				$('#import-excel').click(function(){
					if (!$('#fecha').val()) { $('#fecha').focus(); return false; }	
					var r = confirm('Seguro que desea importar el fichero...?');				
					if (r == true) 
						return true;
					else
						return false;
				});
            });
		</script>
	</head>
<body>
	<?php
		//abro el archivo para lectura 
		$archivo = fopen ("pedidos.csv", "r"); 

		//inicializo una variable para llevar la cuenta de las líneas y los caracteres 
		$num_lineas = 0; 
		$caracteres = 0; 

		//Hago un bucle para recorrer el archivo línea a línea hasta el final del archivo 
		while (!feof ($archivo)) { 
		    //si extraigo una línea del archivo y no es false 
		    if ($linea = fgets($archivo)){ 
		     	//acumulo una en la variable número de líneas 
		       $num_lineas++; 
		      	//acumulo el número de caracteres de esta línea 
		      	$caracteres += strlen($linea); 
		    } 
		} 
		fclose ($archivo);
	?>
	<div id="container">
    	<h1><?php echo $nombre; ?></h1>
        <div class="content">
        	<table cellpadding="1" cellspacing="1">
            	<tr>
                	<td colspan="2" align="center"><h3>Importar Ficheros CSV</h3></td>
                </tr>
                 <tr>
                	<td colspan="2" align="center"><h4 style="color:#F00">&rarr; TRANSITO &larr;</h4>
                </tr>
            	<form id="import" name="import" method="post" action="index.php?idbd=" . <?php echo base64_decode($_GET['idbd']); ?>>
                	<tr>
                    	<td>Archivo pedidos.csv</td>
                        <td><?php echo $num_lineas; ?> lineas a importar.</td>
                    </tr>
                    <tr>
                    	<td>Fecha de Importaci&oacute;n</td>
                        <td><?php echo $fecha1; ?></td>
                    </tr>
                    <tr>
                    	<td colspan="2" align="center">
                        	<span>Fecha de Inserci&oacute;n</span>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2" align="center">
                        	<input type="text" id="fecha" name="fecha" style="width:150px; height:22px; border:1px solid #bdbdbd; outline:none; box-shadow: 1px 2px 10px #dbdbdb; text-align:center; font-size:15px; font-variant: small-caps; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif" />
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2" align="center">
                        	<input type="submit" id="import-excel" name="import-excel" value="Importar Csv" />
                        </td>
                    </tr>
                </form>
            </table>
            <hr />
            <table cellpadding="1" cellspacing="1" style="text-align:center">
            	<tr>
                	<td><span>Resultado</span></td>
                </tr>
                <tr>
                	<td>
                    	<?php
							if ($transaccion == 'false') { echo "<strong style='color:red'>".$error."</strong>"; }
							else if($transaccion == 'true') { echo "<strong style='color: green'>".$countOrderInsert." Ordenes Insertadas Correctamente</strong>"; rename('pedidos.csv', 'folder-csv/pedidos-'.$fecha1.'.csv'); unlink('pedidos.csv'); }
						?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
<?php
} else {
	echo '
		<div style="text-align:center">
			<img src="../images/w-excel.png" />
			<h3>No existe fichero de importacion .cvs</h3><br />
			<a href="'.$_SERVER['HTTP_REFERER'].'" style="color: #09C">VOLVER</a>		
		</div>
	';
}
}
?>