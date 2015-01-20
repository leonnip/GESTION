<?php
	require_once("conexion/conexion.inc.php");
	$db = DataBase::getInstance();

	$producto = $_GET['idproducto'];
	$productOption = explode('|', $producto);
	$idproducto = $productOption[0];
	$opcion = $productOption[1];	
	
	$sql1 = "SELECT * FROM opcionesoferta  WHERE IdOpcion = '$idproducto' AND OptActiva = '1'";
	$db->setQuery($sql1);
	$Rows = $db->execute();
	if (mysqli_num_rows($Rows) > 0) {
		$resultOpt = $db->loadObjectList();
		echo '<div class="divSelect" style="width: 250px; margin: 0 auto;">';
		echo '<select id="opcion" name="opcion" data-placeholder="Seleccione Opción..." class="chosen-select" style="width: 250px;">';
		echo '<option value="0">Seleccione Opción</option>';
		foreach($resultOpt as $ResultOpt1)
		echo '<option value="'.$ResultOpt1->Id.'">'.utf8_encode($ResultOpt1->Opcion).'</option>';
		echo '</select>';
		echo '</div>';
	} else {
		echo '<input type="hidden" id="opcion" name="opcion" value="'.$opcion.'" />';
	}
?>
 <script type="text/javascript"> 
	/*$(document).ready(function(e) {
    	$(".chosen-select").chosen(); 
		$(".chosen-select-deselect").chosen({allow_single_deselect:true}); 
    });*/ 
</script> 