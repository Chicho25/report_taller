<?php
ob_start();
ini_set("session.cookie_lifetime","7200");
ini_set("session.gc_maxlifetime","7200");
session_start();

include('include/config.php');
include('include/defs.php');
$mensaje = "";

if (!isset($_SESSION['disponible'])) {
    header('Location: index.php');
}

if (isset($_POST['eliminar_issues'])) {
    DeleteRec("issues_temp", "id=".$_POST['eliminar_issues']);
}

if (isset($_POST['registro'])) {

   $reg_reporte = array("id_register" => $_SESSION['id_user'],
                        "fecha" => date("Y-m-d H:i:s"),
                        "stat" => 1,
                        "id_equipo" => $_POST['equipo'],
                        "ubicacion" => $_POST['ubicacion'],
                        "colaborador" => $_POST['colaborador']);

   $insert = InsertRec("report", $reg_reporte);

   foreach ($_POST['issue_temp'] as $key => $value) {

     $array_issues = array("description" => $value,
                           "id_report" => $insert,
                           "date_time" => date("Y-m-d H:i:s"),
                           "stat" => 1,
                           "id_user" => $_SESSION['id_user']);

     InsertRec("issues", $array_issues);

   }

   ################## images the report ################

if(isset($_FILES['images']) && $_FILES['images']["name"] != ''){

  $cuenta_imagen = 0;

  foreach ($_FILES['images']['tmp_name'] as $key => $image_fiel) {

       $target_dir = "images/";
       $target_file = $target_dir . basename($_FILES['images']["name"][$key]);
       $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
       $filename = $target_dir . $insert.$cuenta_imagen.".".$imageFileType;
       $filenameThumbR = $target_dir . $insert.$cuenta_imagen."_thumb.".$imageFileType;
       if (move_uploaded_file($_FILES['images']["tmp_name"][$key], $filename)){
           //makeThumbnailsWithGivenWidthHeight($target_dir, $imageFileType, $nId, 600, 400);
           InsertRec("image_issue", array("rute" => $filenameThumbR,
                                          "id_report" => $insert,
                                          "date_time" => date("Y-m-d H:i:s")));

           $cuenta_imagen++;

      }
   }
}

   DeleteRec("issues_temp", "(1=1)");

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
                      <label for="equipo"><b>Reporte Numero#: '.$insert.'</b></label><br>
                      <label for="equipo"><b>Fecha: '.date("Y-m-d H:i:s").'</b></label><br>
                      <label for="equipo"><b>Equipo: </b>';
                      $equipos = GetRecords("SELECT * FROM vehicle WHERE id =".$_POST['equipo']);
                      foreach ($equipos as $key => $value) {
                        $quipo = $value['name'].' || '.$value['license_plate'];
                      }
             $html .=' '.$quipo.'</label>
                    </div>
                    <div class="form-group">
                      <label for="descripcion"><b>Issues:</b> </label><br>';
                      $rec = GetRecords("SELECT * FROM issues WHERE id_report =".$insert);
                      foreach ($rec as $key => $value) {
                          $html .='<label> - '.$value['description'].'</label><br>';
                       }
                    $html .='
                    </div>
                    <div class="form-group">
                      <label for="descripcion"><b>Ubicación: </b> '.$_POST['ubicacion'].'</label>
                    </div>
                    <div class="form-group">
                      <label for="equipo"><b>Quien reporta:</b> ';
                         $empleado = GetRecords("SELECT * FROM employee WHERE id =".$_POST['colaborador']);
                         foreach ($empleado as $key => $value) {
                           $colaborador = $value['firstname'].' '.$value['lastname'];
                         }
                    $html .=' '.$colaborador.'
                      </label><br>';
                    if (isset($cuenta_imagen) && $cuenta_imagen > 0) {
                      $html .='<label for="descripcion"><b>Imagenes: </b> </label><br>
                      https://www.gruasshl.com/report_taller/images_report.php?id_report='.$insert.'';
                    }


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

      $subject = 'Reporte Ramen Taller';
      //$fileName = $nomrbe_archivo.'.pdf';
      //$email_ventas = "tayron.arrieta@gruasshl.com";
      //$personas = "tayronperez17@gmail.com taller@gruasshl.com";
      $email_ventas = "taller@gruasshl.com";
      $personas = "call.center@gruasshl.com";
      $to = $email_ventas.' ,'.$personas;
      $repEmail = (isset($email_ventas) && $email_ventas != "") ? $email_ventas : '';
      $conpania_nombre = 'SHL';
      $repName = 'Taller SHL';
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
    <!-- Images file css
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">-->
    <script>
    function preview_images()
    {
     var total_file=document.getElementById("images").files.length;
     for(var i=0;i<total_file;i++)
     {
      $('#image_preview').append("<div class='col-md-2'><img class='img-responsive' width='200' height='200' src='"+URL.createObjectURL(event.target.files[i])+"'></div>");
     }
    }
    </script>
    <link rel="stylesheet" href="css/images_file.css">
  </head>
  <body>
    <?php include('menu.php'); ?>
      <main class="container">
        <form class="" action="" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-12">
              <?php echo $mensaje; ?>
              <h3 class="text-center">Registro de Reporte</h3>
              <div class="form-group">
                <label for="equipo">Equipo</label>
                <select name="equipo" class="selectpicker" data-live-search="true">
                  <option value="">Seleccionar</option>
                  <?php $equipos = GetRecords("SELECT * FROM vehicle where stat = 1"); ?>
                  <?php foreach ($equipos as $key => $value) { ?>
                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name'].' || '.$value['license_plate']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label for="descripcion">Descripción</label>
                <!--<textarea rows="10" cols="50" name="descripcion" class="form-control"></textarea>-->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Agregar ISSUE</button>
                <div id="issues">

                </div>
              </div>
              <div class="form-group">
                <label for="descripcion">Ubicación</label>
                <textarea name="ubicacion" class="form-control"></textarea>
              </div>
              <div class="form-group">
                <label for="equipo">Quien reporta?</label>
                <select name="colaborador" class="selectpicker" data-live-search="true">
                  <option value="">Seleccionar</option>
                  <?php $empleado = GetRecords("SELECT * FROM employee where stat = 1"); ?>
                  <?php foreach ($empleado as $key => $value) { ?>
                    <option value="<?php echo $value['id']; ?>"><?php echo $value['firstname'].' '.$value['lastname']; ?></option>
                  <?php } ?>
                </select>
              </div>
               <div class="col-md-6">
                   <input type="file" class="form-control" id="images" name="images[]" onchange="preview_images();" multiple/>
               </div>
               <div class="col-md-6">
               </div>
              <div class="row" id="image_preview"></div>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="registro">Registrar</button>
            </div>
          </div>
        </form>
      </main>

      <!-- ########### Modal Agregar #################-->
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Registrar issue</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="form-group">
                  <label for="message-text" class="col-form-label">Descripción:</label>
                  <textarea class="form-control" id="message-text" autofocus></textarea>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="button" id="send_mensaje" class="btn btn-primary" data-dismiss="modal">Guardar</button>
            </div>
          </div>
        </div>
      </div>
      <!-- #################  Modal Eliminar  ###################-->
      <?php
      $rec = GetRecords("SELECT * FROM issues_temp where id_user =".$_SESSION['id_user']);
      foreach ($rec as $key => $value) { ?>
      <div class="modal fade" id="exampleModalEliminar<?php echo $value['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Eliminar Issue</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <h6 style="color:red;">Esta seguro que desea eliminar el Issue</h6>
            </div>
            <div class="modal-footer">
              <br>
              <form class="" action="" method="post">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <input type="hidden" name="eliminar_issues" value="<?php echo $value['id']; ?>">
              </form>
            </div>
          </div>
        </div>
      </div>


    <?php } ?>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $.post("issues.php", { todo: 1 }, function(data){
            $("#issues").html(data);
        });
      });
    </script>
    <script language="javascript">
      $(document).ready(function(){
          $("#send_mensaje").click(function () {
              $("#message-text").each(function () {
                  mensaje=$(this).val();
                  $.post("issues.php", { mensaje: mensaje }, function(data){
                      $("#issues").html(data);
                  });
                  $(this).val('');
              });
         });
      });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>-->
    <script type="text/javascript" src="http://www.expertphp.in/js/jquery.form.js"></script>
    <script type="text/javascript">
    $('#add_more').click(function() {
       "use strict";
       $(this).before($("<div/>", {
         id: 'filediv'
       }).fadeIn('slow').append(
         $("<input/>", {
           name: 'file[]',
           type: 'file',
           id: 'file',
           multiple: 'multiple',
           accept: 'image/*'
         })
       ));
     });

     $('#upload').click(function(e) {
       "use strict";
       e.preventDefault();

       if (window.filesToUpload.length === 0 || typeof window.filesToUpload === "undefined") {
         alert("No files are selected.");
         return false;
       }

       // Now, upload the files below...
       // https://developer.mozilla.org/en-US/docs/Using_files_from_web_applications#Handling_the_upload_process_for_a_file.2C_asynchronously
     });

     deletePreview = function (ele, i) {
       "use strict";
       try {
         $(ele).parent().remove();
         window.filesToUpload.splice(i, 1);
       } catch (e) {
         console.log(e.message);
       }
     }

     $("#file").on('change', function() {
       "use strict";

       // create an empty array for the files to reside.
       window.filesToUpload = [];

       if (this.files.length >= 1) {
         $("[id^=previewImg]").remove();
         $.each(this.files, function(i, img) {
           var reader = new FileReader(),
             newElement = $("<div id='previewImg" + i + "' class='previewBox'><img /></div>"),
             deleteBtn = $("<span class='delete' onClick='deletePreview(this, " + i + ")'>X</span>").prependTo(newElement),
             preview = newElement.find("img");

           reader.onloadend = function() {
             preview.attr("src", reader.result);
             preview.attr("alt", img.name);
           };

           try {
             window.filesToUpload.push(document.getElementById("file").files[i]);
           } catch (e) {
             console.log(e.message);
           }

           if (img) {
             reader.readAsDataURL(img);
           } else {
             preview.src = "";
           }

           newElement.appendTo("#filediv");
         });
       }
     });
    </script>
  </body>
</html>
