<?php

/*

Metodos por post
1 = cliente -> contacto
2 = contacto -> datos del contacto

*/

include('include/config_mega_ramen.php');
include("include/defs.php");

$options="";
if ($_GET["metodo"]==1) {

  $arrContact = GetRecords("Select * from crm_contact where stat = 1 and id_customer = '".$_POST["customer_elegido"]."'");
  echo '<option value="">Seleccionar</option>';
  foreach ($arrContact as $key => $value) {
    $kinId = $value['id'];
    $name_contact = $value['name_contact'];
    $last_name = $value['last_name'];
  ?>
  <option value="<?php echo $kinId?>"><?php echo $name_contact.' '.$last_name?></option>
  <?php
  }

}
if ($_GET["metodo"]==2) {
    $arrContact = GetRecords("Select * from crm_contact where stat = 1 and id = '".$_POST["contact_elegido"]."'");

    foreach ($arrContact as $key => $value) {
      $kinId = $value['id'];
      $name_contact = $value['name_contact'];
      $last_name = $value['last_name'];
      $phone1 = $value['phone_1'];
      $phone2 = $value['phone_2'];
      $email = $value['email'];
    } ?>

    <div class="form-group required">
      <label class="col-lg-4 text-left control-label font-bold">Nombre</label>
      <div class="col-lg-4">
        <input type="text" class="form-control" placeholder="Nombre" name="feather" value="<?php echo $name_contact;?>" readonly>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-lg-4 text-left control-label font-bold">Apellido</label>
      <div class="col-lg-4">
        <input type="text" class="form-control" placeholder="Precio Por Hora" name="price_hour" value="<?php echo $last_name;?>" readonly>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-lg-4 text-left control-label font-bold">Celular</label>
      <div class="col-lg-4">
        <input type="text" class="form-control" placeholder="Precio Por Dia" name="price_day" value="<?php echo $phone1;?>" readonly>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-lg-4 text-left control-label font-bold">Telefono</label>
      <div class="col-lg-4">
        <input type="text" class="form-control" placeholder="Telefono" name="price_week" value="<?php echo $phone2;?>" readonly>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-lg-4 text-left control-label font-bold">Email</label>
      <div class="col-lg-4">
        <input type="email" class="form-control" placeholder="Precio por Mes" name="price_mon" value="<?php echo $email;?>" readonly>
      </div>
    </div>
    <?php
  }

?>
