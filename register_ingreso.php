<?php
ob_start();
ini_set("session.cookie_lifetime","7200");
ini_set("session.gc_maxlifetime","7200");
session_start();

include('include/config_mega_ramen.php');
include('include/defs.php');

if (!isset($_SESSION['id_user'])) {
    header('Location: index.php');
}

$messageBody = "";

if (isset($_POST['reg_cust_cont'])) {

  $id_insert_customer = array("trade_name"=>$_POST['custmer_name'],
                              "legal_name"=>$_POST['custmer_name'],
                              "stat" => 1);

  $nId_customer = InsertRec("crm_customers", $id_insert_customer);

  $id_insert_customer = array("id_customer"=>$nId_customer,
                              "name_contact"=>$_POST['contac_name'],
                              "stat" => 1);

  $nId_contact = InsertRec("crm_contact", $id_insert_customer);

  $_SESSION['id_cliente'] = $nId_customer;
  $_SESSION['id_contact'] = $nId_contact;

  $messageBody = '<div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Cliente y contacto registrados!</strong>
                </div>';

}

if (isset($_POST['registro'])) {

 $array_entry = array("new_customer"=>$_POST['cliente_nuevo'],
                       "term"=>$_POST['term'],
                       "id_type_media"=>$_POST['media'],
                       "id_country"=>$_POST['country'],
                       "date_form"=>$_POST['date_time'],
                       "id_customer"=>$_POST['customer'],
                       "id_contact"=>$_POST['contact'],
                       "proyect_name"=>$_POST['proyect_name'],
                       "work_do"=>$_POST['work_do'],
                       "proyect_locate"=>$_POST['ubicacion'],
                       "id_type_work"=>$_POST['id_type_work'],
                       "inspection"=>$_POST['inspection'],
                       "stat" => 3,
                       "log_user_register" => $_SESSION['id_user'],
                       "log_time" => date("Y-m-d H:i:s"),
                       "exterior_sys" =>1);

  $nId = InsertRec("crm_entry", $array_entry);

  $number_ticket = date("Y").str_pad($nId, 6, "0", STR_PAD_LEFT);

  $array_number_ticket = array("number_tickets" => $number_ticket);

  UpdateRec("crm_entry", "id=".$nId, $array_number_ticket);

  $html = '
  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="Tayron">
      <meta charset="utf-8">
      <title>Mega Rame</title>
    </head>
    <body>
      <header>
        <h3 class="text-center">Registro de Ingreso</h3>
      </header>
      <main class="container">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <p>Se ha Registrado un nuevo ingreso desde el call center.</p>
                  <p>Este ingreso esta disponible en el sistema Mega Ramen. </p>
                  <p>Los datos del ingreso son los siguientes:</p>
                  <label for="equipo"><b>Numero#: '.$number_ticket.'</b></label><br>
                  <label for="equipo"><b>Fecha: '.$_POST['date_time'].'</b></label><br>
                  <label for="equipo"><b>Cliente: </b>';
                  $equipos = GetRecords("SELECT * FROM crm_customers WHERE id =".$_POST['customer']);
                  foreach ($equipos as $key => $value) {
                    $quipo = $value['legal_name'];
                  }
         $html .=' '.$quipo.'</label>
                </div>
                <div class="form-group">
                  <label for="descripcion"><b>Contacto:</b> </label><br>';
                  $rec = GetRecords("SELECT * FROM crm_contact WHERE id =".$_POST['contact']);
                  foreach ($rec as $key => $value) {
                      $html .='<label> - '.$value['name_contact'].'</label><br>';
                   }
                $html .='
                </div>
                <div class="form-group">
                  <label for="descripcion"><b>Ubicación: </b> '.$_POST['ubicacion'].'</label>
                </div>
                <div class="form-group">
                  <label for="equipo"><b>Medio de Comunicacion:</b> ';
                     $empleado = GetRecords("SELECT * FROM crm_type_media WHERE id =".$_POST['media']);
                     foreach ($empleado as $key => $value) {
                       $colaborador = $value['description'];
                     }

                     if ($_POST['cliente_nuevo'] = 1) {
                       $nuevo_cliente = "Si";
                     }else{
                       $nuevo_cliente = "No";
                     }
                     if ($_POST['term'] = 1) {
                       $term = "Largo Termino";
                     }else{
                       $term = "Taxi";
                     }
                     if ($_POST['id_type_work'] = 1) {
                       $tipo_trabajo = "Por Hora";
                     }else{
                       $tipo_trabajo = "Por Proyecto";
                     }
                     if ($_POST['inspection'] = 1) {
                       $inspeccion = "Si";
                     }else{
                       $inspeccion = "No";
                     }

                $html .=' '.$colaborador.'
                  </label><br>
                <label for="descripcion"><b>Nombre del Proyecto: '.$_POST['proyect_name'].'</b> </label><br>
                <label for="descripcion"><b>Trabajo a Realizar: '.$_POST['work_do'].'</b> </label><br>
                <label for="descripcion"><b>Cliente Nuevo: '.$nuevo_cliente.'</b> </label><br>
                <label for="descripcion"><b>Termino: '.$term.'</b> </label><br>
                <label for="descripcion"><b>Tipo de Trabajo: '.$tipo_trabajo.'</b> </label><br>
                <label for="descripcion"><b>Se puede inspeccionar: '.$inspeccion.'</b> </label><br>';

              $html .='
                </div>
              </div>
            </div>
        </main>
      </body>
  </html>';

  include("pdf/mpdf.php");
  $mpdf=new mPDF();
  $mpdf->WriteHTML($html);
  //$mpdf->AddPage();

  $subject = 'Nuevo Ingreso -> Call Center -> '.$number_ticket.'';
  //$fileName = $nomrbe_archivo.'.pdf';
  //$email_ventas = "tayron.arrieta@gruasshl.com";
  //$personas = "tayronperez17@gmail.com";
  $email_ventas = "ventas@gruasshl.com";
  $personas = "callcenter@gruasshl.com";
  $to = $email_ventas.' ,'.$personas;
  $repEmail = (isset($email_ventas) && $email_ventas != "") ? $email_ventas : '';
  $conpania_nombre = 'SHL';
  $repName = 'Call Center';
  //$fileatt = $mpdf->Output($fileName, 'S');
  //$attachment = chunk_split(base64_encode($fileatt));
  $eol = PHP_EOL;
  $separator = md5(time());
  $headers = 'From: '.$repName.' <'.$repEmail.'>'.$eol;
  $headers .= 'MIME-Version: 1.0' .$eol;
  $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";
  $message = "--".$separator.$eol;
  $message .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
  $message .= "Se ha Registrado un Reporte." .$eol;
  $message .= "--".$separator.$eol;
  $message .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
  $message .= $html;
  $message .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
  $message .= "--".$separator.$eol;
  //$message .= "Content-Type: application/pdf; name=\"".$fileName."\"".$eol;
  $message .= "Content-Transfer-Encoding: base64".$eol;
  $message .= "Content-Disposition: attachment".$eol.$eol;
  //$message .= $attachment.$eol;
  $message .= "--".$separator."--";

  mail($to, $subject, $message, $headers);


  if($nId > 0)
  {
      $messageBody = '<div class="alert alert-success">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Entrada Registrada</strong>
                  </div>';
  }

}

 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tayron">
    <meta charset="utf-8">
    <title>Ramen Taller</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
  </head>
  <body>
    <?php include('menu.php'); ?>
    <main class="container">
      <form class="" action="" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="col-12">
            <?php echo $messageBody; ?>
            <h3 class="text-center">Registro de Ingreso</h3>
            <div class="form-group">
              <label for="equipo">Cliente</label>
              <select name="customer" class="selectpicker" data-live-search="true" id="customer">
                <option value="">Seleccionar</option>
                <?php $equipos = GetRecords("SELECT * FROM crm_customers where stat = 1"); ?>
                <?php foreach ($equipos as $key => $value) { ?>
                  <option value="<?php echo $value['id']; ?>" <?php if(isset($_SESSION['id_cliente']) && $_SESSION['id_cliente'] == $value['id']){ echo 'selected'; } ?> ><?php echo $value['legal_name']; ?></option>
                <?php } ?>
              </select>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Agregar Cliente</button>
            </div>
            <div class="form-group">
              <label for="equipo">Contacto</label>
              <select name="contact" class="form-control" id="contact">
                <option value="">Seleccionar</option>
                <?php if(isset($_SESSION['id_contact'])){ ?>
                  <?php $equipos = GetRecords("SELECT * FROM crm_contact where stat = 1"); ?>
                  <?php foreach ($equipos as $key => $value) { ?>
                    <option value="<?php echo $value['id']; ?>" <?php if(isset($_SESSION['id_contact']) && $_SESSION['id_contact'] == $value['id']){ echo 'selected'; } ?> ><?php echo $value['name_contact']; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>

            </div>
            <div class="form-group">
              <div id="date_contact">
              </div>
            </div>
            <div class="form-group">
              <label for="equipo">Cliente Nuevo ?</label>
              <input type="radio" name="cliente_nuevo" value="1"> Si
              <input type="radio" name="cliente_nuevo" value="2"> No
            </div>
            <div class="form-group">
              <label for="equipo">Termino ?</label>
              <input type="radio" name="term" value="1"> Largo Termino
              <input type="radio" name="term" value="2"> Taxi
            </div>
            <div class="form-group">
              <label for="equipo">Medio de Comunicación</label>
              <select name="media" class="selectpicker" data-live-search="true">
                <option value="">Seleccionar</option>
                <?php $equipos = GetRecords("SELECT * FROM crm_type_media where stat = 1"); ?>
                <?php foreach ($equipos as $key => $value) { ?>
                  <option value="<?php echo $value['id']; ?>"><?php echo $value['description']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="equipo">País</label>
              <select name="country" class="selectpicker" data-live-search="true">
                <option value="">Seleccionar</option>
                <?php $equipos = GetRecords("SELECT * FROM country where stat = 1"); ?>
                <?php foreach ($equipos as $key => $value) { ?>
                  <option value="<?php echo $value['id']; ?>"><?php echo $value['description']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="descripcion">Fecha</label>
              <input name="date_time" class="form-control" readonly value="<?php echo date('d-m-Y H:i:s'); ?>">
            </div>
            <div class="form-group">
              <label for="descripcion">Nombre del Proyecto</label>
              <input name="proyect_name" class="form-control" value="">
            </div>
            <div class="form-group">
              <label for="descripcion">Trabajo a Realizar</label>
              <textarea name="work_do" class="form-control"></textarea>
            </div>
            <div class="form-group">
              <label for="descripcion">Ubicacion del Proyecto</label>
              <textarea name="ubicacion" class="form-control"></textarea>
            </div>
            <div class="form-group">
              <label for="equipo">Tipo de trabajo</label>
              <input type="radio" name="id_type_work" value="1"> Por hora
              <input type="radio" name="id_type_work" value="2"> Proyecto
            </div>
            <div class="form-group">
              <label for="equipo">Se puede Inspeccionar?</label>
              <input type="radio" name="inspection" value="1"> Si
              <input type="radio" name="inspection" value="2"> No
            </div>
             <div class="col-md-6">
             </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="registro">Registrar</button>
          </div>
        </div>
      </form>
    </main>

    <!-- Modal Registro de clientes -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Registrar Cliente/Contacto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="post">
          <div class="modal-body">
              <div class="form-group">
                <label for="message-text" class="col-form-label">Nombre del Cliente:</label>
                <textarea class="form-control" autofocus required name="custmer_name"></textarea>
              </div>
              <div class="form-group">
                <label for="message-text" class="col-form-label" >Nombre del Contacto:</label>
                <textarea class="form-control" required name="contac_name"></textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" name="reg_cust_cont" >Guardar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <!-- fin -->



    <script language="javascript" src="https://code.jquery.com/jquery-1.2.6.min.js"></script>
    <script language="javascript">
    $(document).ready(function(){
       $("#customer").change(function () {
               $("#customer option:selected").each(function () {
                customer_elegido=$(this).val();
                $.post("select_dependent.php?metodo=1", { customer_elegido: customer_elegido }, function(data){
                $("#contact").html(data);
                });
            });
       })

       $("#contact").change(function () {
               $("#contact option:selected").each(function () {
                contact_elegido=$(this).val();
                $.post("select_dependent.php?metodo=2", { contact_elegido: contact_elegido }, function(data){
                $("#date_contact").html(data);
                });
            });
       })

    });
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
  <?php
  if (isset($_SESSION['id_cliente'], $_SESSION['id_contact'])) {
      unset($_SESSION['id_cliente'], $_SESSION['id_contact']);
  } ?>
  </body>
</html>
