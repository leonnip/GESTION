<?php
	error_reporting(1);
	define('DS', DIRECTORY_SEPARATOR);					//SEPRADOR DE LOS ENLCACES 
	define('ROOT', realpath(dirname(__FILE__)) . DS);	//RUTA DE LOS ARCHIVOS
	
    if(isset($_POST['importCsv']) == 1) {
		# IMPORTAMOS CSV PARA SUBIRLO A LA BASE DE DATOS  
        $csv_mimetypes = array(
                'text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt',
        );
        $ruta = ROOT . 'import-excel-transito';            
        try {
            if($_FILES['filecsv']['error'] == 0) {                 
                 $files = $_FILES['filecsv']['name'];                                        
                 # Buscamos si el archivo que subimos tiene el MIME type que permitimos en nuestra subida
                 if(!in_array($_FILES['filecsv']['type'], $csv_mimetypes)) {
                     throw new Exception("Solamente puede subir archivos .csv"); 
                 }              
                 if($_FILES['filecsv']['tmp_name']) {
                     $tmp_name = $_FILES['filecsv']['tmp_name'];
                     $name = $_FILES['filecsv']['name'];
                     $file = $ruta.$name;                    
                     if(!move_uploaded_file($tmp_name, "$name")) 
	                     throw new Exception("Error al cargar fichero.");
                 }
             } else {
                     throw new Exception("No ha selecciona ningun archivo.");
             }
             $upload = TRUE;
        } catch(Exception $e) {
            $upload = $e->getMessage();
        }
		# FIN DE PROCESO DE SUBIR CSV PARA IMPORTAR A LA BD			
     } else if($_POST['importPedidos'] == 1) {
		 # PROCESO DE IMPORTAR FICHERO CSV A LA BASE DE DATOS
		 # --------------------------------------------------------------------------------------------------
		 if(isset($_POST['valid']) && !empty($_POST['fecha'])) {
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
			# -----------------------------------------------------------------------------------------------
            # AQUI TERMMINAMOS EL PROCESO DE IMPORTACION DEL FICHERO 
		 }
		 # FIN DE IMPORTAR A LA CSV
	 }
?>
<!doctype html>
<html>
    <head>
    <meta charset="UTF-8">
        <title>IMPORTAR FICHERO DE PEDIDOS</title>
        <style type="text/css">
            body, html { font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, sans-serif; font-size: 11px; font-weight: normal; letter-spacing: 0.04em; word-spacing: 0.08em; color: #81868f;  background: #04364f; }
            .import { padding: 15px; background: #fff; overflow: auto; margin: 30px; }
            fieldset { border: 1px solid #E9E9E9; margin: 1%; width: 46%; float: left; background: #fff; }
            legend { margin-left: 15px; }
            h3 { display: block; padding: 5px; font-size: 15px; text-align: center; color: #F00; }
            .btn { -moz-user-select: none; min-width: 80px; background: linear-gradient(#f9f9f9 40%, #e3e3e3 70%) repeat scroll 0 0 rgba(0, 0, 0, 0); border: 1px solid #999; border-radius: 3px; cursor: pointer;
                    display: inline-block; font-size: 10pt; font-weight: 100; outline: medium none; padding: 3px 8px;
                    text-shadow: 1px 1px #fff;
                    white-space: nowrap;
                	color: #000;
                	transition: none 0s ease 0s ;
                	font-family: Lucida Grande;
                	text-align: center;
                	margin-left: 5px;
            }
            .btn:active { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3) inset;  }
			input[type=file] { width: 300px; }
			span { font-weight: bold; line-height: 15px; color: #000;  }
			label { color: #F30; display: block; padding: 10px; }
			strong { display: block; color: #093; padding: 10px; width: 100%; float: left; }
			input[type=text] { outline: none; height: 22px; width: 130px; text-align: center; box-shadow: 1px 2px 23px #CCC; }
        </style>
        <link href="stylo-fecha.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="../js/jquery.tools.min.js"></script>
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
        <div class="import">
            <h3>Fichero CSV <?php echo $_SESSION['NAME_TIENDA']; ?></h3>
            <fieldset class="basic">
                <legend>Importarte para el fichero</legend>
                <form action="" method="post" enctype="multipart/form-data">
	                <table>
    	                <thead>
        	                <th>Fichero a Importar</th>
            	        </thead>
                	    <tbody>
                   	        <tr>
                   	            <td>
                   	                - El fichero debe estar en formato .csv<br>
                                    - El número de líneas que se muestre debe ser igual a numero de líneas del fichero.<br>
	                                - El campo DNI es obligatorio, si no lo tiene solicite al cliente.<br>
    	                            - Si el archivo presenta errores modifiquelo y vuelvalo a subir.<br>
        	                        - El fichero se tendrá que llamar pedidos.csv<br>
            	                </td>
                	        </tr>
                   		    <tr height="50px">
                                <td><input type="file" name="filecsv" /></td>
                        	</tr>
                    	</tbody>
                    	<tfoot>
                        	<tr height="50px">
                           		 <td><button type="submit" name="importCsv" class="btn" value="1">Importar Csv</button></td>
                        	</tr>
                    	</tfoot>
                	</table>
                </form>
                <?php
				if($upload == 'TRUE') {
					echo "<strong>Fichero subido correctamente.</strong>";
				} else
					echo "<label>" . $upload . "</label>";
				?>
                
                <a class="btn" style="text-decoration: none" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/shop_gestion/?idbd=' . $_REQUEST['idbd']; ?>" title="Ir a Tienda">Ir a Tienda</a>
            </fieldset>
           
            
            <?php
				# Leemos el archivo  de pedidos
				$ruta = ROOT;   
				$fp = fopen($ruta.'pedidos.csv','r');
            	$nombreFichero = 'pedidos.csv';
            
            	if(is_readable($nombreFichero)) {
    				if (!$fp) {
	    				$upload = 'ERROR: No ha sido posible abrir el archivo. Revisa su nombre y sus permisos.'; 
			    	}  else {
				    	$loop = 0; // contador de líneas
					    while (!feof($fp)) { // loop hasta que se llegue al final del archivo
						    $line = fgets($fp); // guardamos toda la línea en $line como un string
    						$field[$loop] = explode (';', $line);
	    					$dato_dni[] = $field[$loop][2];		
		    				$loop++;
				    		$fp++; // necesitamos llevar el puntero del archivo a la siguiente línea
					    }
    					fclose($fp);
	    			}
				}
			?>
            
            <fieldset>
                <legend>Fichero cargado</legend>
                <form action="" method="post" accept-charset="utf-8" enctype="application/x-www-form-urlencoded">
	                <table>
    	                <thead>
        	                <th>Datos del Fichero</th>
            	        </thead>
                	    <tbody>
                   	        <tr>
                   	            <td>       
                                	<?php 
										$fichero = 'pedidos.csv';
										if(file_exists($fichero)) {
											$numColum = explode(';', $line);
											$nameFile = "pedidos.csv";
											$numLine = count(file($nombreFichero));
											$numColum = count($numColum);
											$size = filesize($ruta.'pedidos.csv');
										}
									?>
                                		- Nombre: <span><?php echo $nameFile; ?></span><br>
                                		- Numero de líneas. <span><?php echo $numLine; ?></span><br>
                                		- Número de columnas. <span><?php echo $numColum; ?></span><br>
                                		- Tamaño del fichero. <span><?php echo $size; ?>&nbsp;bytes</span><br><br>
                                       Fecha Importación<br><br>
                                       <input type="text" id="fecha" name="fecha" value="" ><br><br>
                                       <input type="checkbox" name="valid" value="1" />&nbsp;Confirma fichero y subirlo.<br>   
                            	</td>
	                        </tr>
    	                </tbody>
        	            <tfoot>
            	            <tr height="50px">
                	            <td><button id="import-excel" type="submit" name="importPedidos" class="btn" value="1">Importar Pedidos</button></td>
                   	        </tr>
                   	    </tfoot>
                    </table>
                </form>
                <?php
					if($transaccion == 'true') { 
						echo "<strong style='color: green'>".$countOrderInsert." Ordenes Insertadas Correctamente</strong>"; 
						unlink('pedidos.csv'); 
					}
				?>
            </fieldset>            
        </div>
    </body>
</html>