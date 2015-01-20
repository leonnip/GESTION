<?php
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=ficheroExcel.xls");
header("Pragma: no-cache");
header("Expires: 0");

header("Content-Type: application/force-download");
header("Content-Transfer-Encoding: binary"); 


if($_POST['values0']) {
	echo urldecode($_POST['values0']);
} else if ($_POST['values1']) {
	echo urldecode($_POST['values1']);
} else if ($_POST['values2']) {
	echo urldecode($_POST['values2']);
} else if ($_POST['values3']) {
	echo urldecode($_POST['values3']);
}
?>