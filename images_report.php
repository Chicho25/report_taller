<?php
include('include/config.php');
include('include/defs.php');
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Imagenes Reportes Taller</title>
  </head>
  <body>
    <?php
    echo '<b>Reporte Numero#:</b> '.$_GET['id_report'].'<br>';
    $rec = GetRecords("SELECT * FROM image_issue WHERE id_report =".$_GET['id_report']);
    foreach ($rec as $key => $value) { ?>
        <!--<img width="400" src="https://ofertadeviaje.com/tallerramen/<?php echo $value['rute']?>"> <br>';-->
        <img width="400" src="<?php echo str_replace("_thumb","",$value['rute']);?>"> <br>
    <?php } ?>
  </body>
</html>
