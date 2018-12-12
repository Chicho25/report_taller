<?php
ob_start();
session_start();
include('include/config.php');
include('include/defs.php');
$mensaje = "";

if (!isset($_SESSION['session'])) {
    header('Location: index.php');
}

if (isset($_POST['registro'])) {

   $reg_reporte = array("id_register" => $_SESSION['session']['id'],
                        "fecha" => date("Y-m-d H:i:s"),
                        "stat" => 1,
                        "id_equipo" => $_POST['equipo'],
                        "description" => $_POST['descripcion'],
                        "ubicacion" => $_POST['ubicacion'],
                        "colaborador" => $_POST['colaborador']);

   $insert = InsertRec("report", $reg_reporte);

   if($reg_reporte){

     $mensaje = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                   <strong>Registro Realizado</strong> El registro fue realizado con Exito.
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                   </button>
                 </div>';
      }

      $nomrbe_archivo = 'ReporteRamenTaller';
      $html = '
      <!DOCTYPE html>
      <html lang="en" dir="ltr">
        <head>
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
          <meta name="description" content="">
          <meta name="author" content="Tayron">
          <meta charset="utf-8">
          <title>Ramen Taller</title>
        </head>
        <body>
          <header>
            <h3 class="text-center">Registro de Reporte</h3>
          </header>
          <main class="container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="equipo"><b>Fecha: '.date("Y-m-d H:i:s").'</b></label><br>
                      <label for="equipo"><b>Equipo: </b>';
                      $equipos = GetRecords("SELECT * FROM crm_craner WHERE id =".$_POST['equipo']);
                      foreach ($equipos as $key => $value) {
                        $quipo = $value['name_craner'];
                      }
             $html .=' '.$quipo.'</label>
                    </div>
                    <div class="form-group">
                      <label for="descripcion"><b>Descripción:</b> '.$_POST['descripcion'].' </label>
                    </div>
                    <div class="form-group">
                      <label for="descripcion"><b>Ubicación: </b> '.$_POST['ubicacion'].'</label>
                    </div>
                    <div class="form-group">
                      <label for="equipo"><b>Quien reporta:</b> ';
                         $empleado = GetRecords("SELECT * FROM collaborator WHERE id =".$_POST['colaborador']);
                         foreach ($empleado as $key => $value) {
                           $colaborador = $value['firs_name'].' '.$value['last_name'];
                         }
                    $html .=' '.$colaborador.'
                      </label>
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

      $subject = 'Reporte Ramen Taller';
      $fileName = $nomrbe_archivo.'.pdf';
      $email_ventas = "taller@gruasshl.com";
      $personas = "tayron.arrieta@gruasshl.com";
      $to = $email_ventas.' ,'.$personas;
      $repEmail = (isset($email_ventas) && $email_ventas != "") ? $email_ventas : '';
      $conpania_nombre = 'SHL';
      $repName = 'Taller SHL';
      $fileatt = $mpdf->Output($fileName, 'S');
      $attachment = chunk_split(base64_encode($fileatt));
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
      $message .= "Content-Type: application/pdf; name=\"".$fileName."\"".$eol;
      $message .= "Content-Transfer-Encoding: base64".$eol;
      $message .= "Content-Disposition: attachment".$eol.$eol;
      $message .= $attachment.$eol;
      $message .= "--".$separator."--";

      mail($to, $subject, $message, $headers);

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
  </head>
  <body>
    <header>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <a class="navbar-brand" href="#">Ramen Taller</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Reportes
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="#">Registrar Reporte</a>
                  <a class="dropdown-item" href="#">Ver Reportes</a>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Mantenimiento
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="#">Registrar Equipo</a>
                  <a class="dropdown-item" href="#">Ver Equipos</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Registrar Colaborador</a>
                  <a class="dropdown-item" href="#">Ver Colaboradores</a>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="salir.php">Salir</a>
              </li>
            </ul>
            <!--<form class="form-inline my-2 my-lg-0">
              <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>-->
          </div>
        </nav>
      </header>
      <main class="container">
        <form class="" action="" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-12">
              <?php echo $mensaje; ?>
              <h3 class="text-center">Registro de Reporte</h3>
              <div class="form-group">
                <label for="equipo">Equipo</label>
                <select name="equipo" class="form-control">
                  <option value="">Seleccionar</option>
                  <?php $equipos = GetRecords("SELECT * FROM crm_craner"); ?>
                  <?php foreach ($equipos as $key => $value) { ?>
                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name_craner']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label for="descripcion">Descripción</label>
                <!--<textarea rows="10" cols="50" name="descripcion" class="form-control"></textarea>-->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Agregar ISSUE</button>
              </div>
              <div class="form-group">
                <label for="descripcion">Ubicación</label>
                <textarea name="ubicacion" class="form-control"></textarea>
              </div>
              <div class="form-group">
                <label for="equipo">Quien reporta?</label>
                <select name="colaborador" class="form-control">
                  <option value="">Seleccionar</option>
                  <?php $empleado = GetRecords("SELECT * FROM collaborator"); ?>
                  <?php foreach ($empleado as $key => $value) { ?>
                    <option value="<?php echo $value['id']; ?>"><?php echo $value['firs_name'].' '.$value['last_name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="registro">Registrar</button>
            </div>
          </div>
        </form>
      </main>

      <!-- ########### Modal #################-->
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">New message</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">Recipient:</label>
                  <input type="text" class="form-control" id="recipient-name">
                </div>
                <div class="form-group">
                  <label for="message-text" class="col-form-label">Message:</label>
                  <textarea class="form-control" id="message-text"></textarea>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Send message</button>
            </div>
          </div>
        </div>
      </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
