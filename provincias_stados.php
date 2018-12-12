<?php include('include/config.php'); ?>
<?php include('include/defs.php'); 
$html = "";

$provincia = GetRecords("SELECT * FROM provincias WHERE id_pais = '".$_POST["elegido"]."'");
foreach ($provincia as $key => $value): ?>
<?php $html .='<option value="'.$value['id'].'">'.utf8_encode($value['nombre']).'</option>';
endforeach;

echo $html;
?>
