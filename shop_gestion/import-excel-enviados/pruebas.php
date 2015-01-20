<?php
	require_once('../conexion/conexion.inc.php');
	$db = DataBase::getInstance();
	
	
	
	$a = '|leonnip@lp.es';
	$b = '12|leonnip@lp.es';
	
	$l1 = explode('|', $a);
	$l2 = explode('|', $b);
	
	echo '-->'.$l1[1].'<br/>';
	echo '-->'.$l2[1].'<br/>';
	
	//OBTENEMOS EL ULTIMOS REGISTRO
	$SQL = "SELECT MAX(Id) AS IdUsuario FROM usuarios";
	$db->setQuery($SQL);
	
	$result = $db->loadObject();
	$idUsuario = $result->IdUsuario + 1;
		
	echo '-->'.$idUsuario;
	
	
	$arr = array (
		array ( "plazo" => "manzana", "definición" => "fruta"),
		array ( "plazo" => "pepino", "definición" => "vegetal"),
		array ( "plazo" => "banana", "definición" => "fruta")
	);
	

	
	$var = array ();
	$var[] = array ( 
		'uno'=>"tema", 
		'dos'=>"otro tema", 
		'tres'=>"otro tema "
		
	);
	
	for ($i=0; $i<=3; $i++) {
	$var [] = array ( 
		'uno'=>"tema", 
		'dos'=>"otro tema", 
		'tres'=>"otro tema ",
		$i=>array(1,2,3,4),
		$i+1=>array(1,2,3,4)
	);
	}
	

	$z[0]= array('uno'=>1, 'dos'=>2, 'tres'=>3);
	for ($i=0; $i<=3; $i++) {
		$var1[] = array(array('a'=>9, 'b'=>8, 'c'=>7));
	}
	
	$z[0][] = array('uno'=>9, 'dos'=>8, 'tres'=>7);
	for ($i=0; $i<=3; $i++) {
		$var1[$i] = array(array('a'=>9, 'b'=>8, 'c'=>7));
	}
	
	$z[0][] = array('uno'=>9, 'dos'=>8, 'tres'=>7);
	$z[0][] = array('uno'=>9, 'dos'=>8, 'tres'=>7);
	
	$z[1] = array('uno'=>9, 'dos'=>8, 'tres'=>7);
	$z[1][] = array('uno'=>9, 'dos'=>8, 'tres'=>7);
	$z[1][] = array('uno'=>9, 'dos'=>8, 'tres'=>7);
	$v[] = array(1,2,3);
	array_push($z[1][], array(9,8,7));
	print_r($z);
	
	$array = array("blue", "red", "green", "blue", "blue");
	$a = array_keys($array, "blue");
	$b = count($a);
	print_r($b);
	print_r(array_keys($array, "blue"));
	
?>