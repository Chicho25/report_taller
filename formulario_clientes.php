<?php include('include/config.php'); ?>
<?php include('include/defs.php'); ?>
<?php $mensaje = "";

  if (isset($_POST['button_send'])) {
    $insert_form = array("fecha_insert" => date("Y-m-d H:i:s"),
                         "razon_social" => $_POST['razon_social'],
                         "razon_comercial" => $_POST['razon_comercial'],
                         "ruc" => $_POST['ruc'],
                         "dv" => $_POST['dv'],
                          "actividad_principal" => $_POST['actividad_principal'],
                          "pais" => $_POST['pais'],
                          "ciudad" => $_POST['ciudad'],
                          "edificio" => $_POST['edificio'],
                          "provincia" => $_POST['provincia'],
                          "calle" => $_POST['calle'],
                          "piso" => $_POST['piso'],
                          "otros" => $_POST['otros'],
                          "telef_prin" => $_POST['telef_prin'],
                          "fax" => $_POST['fax'],
                          "representante_legal" => $_POST['representante_legal'],
                          "numero_identificacion" => $_POST['numero_identificacion'],
                          "operaciones_1" => $_POST['operaciones_1'],
                          "op_tel_1" => $_POST['op_tel_1'],
                          "op_email_1" => $_POST['op_email_1'],
                          "op_charge_1" => $_POST['op_charge_1'],
                          "operaciones_2" => $_POST['operaciones_2'],
                          "op_tel_2" => $_POST['op_tel_2'],
                          "op_email_2" => $_POST['op_email_2'],
                          "op_charge_2" => $_POST['op_charge_2'],
                          "cuentas_pagar" => $_POST['cuentas_pagar'],
                          "cp_tel_1" => $_POST['cp_tel_1'],
                          "cp_email_1" => $_POST['cp_email_1'],
                          "cp_charge_1" => $_POST['cp_charge_1'],
                          "compras" => $_POST['compras'],
                          "c_tel_1" => $_POST['c_tel_1'],
                          "c_email_1" => $_POST['c_email_1'],
                          "c_charge_1" => $_POST['c_charge_1'],
                          "contab_finanzas" => $_POST['contab_finanzas'],
                          "cf_tel_1" => $_POST['cf_tel_1'],
                          "cf_email_1" => $_POST['cf_email_1'],
                          "cf_charge_1" => $_POST['cf_charge_1'],
                          "otro_1" => $_POST['otro_1'],
                          "nombre_otro_1" => $_POST['nombre_otro_1'],
                          "otro_tel_1" => $_POST['otro_tel_1'],
                          "otro_email_1" => $_POST['otro_email_1'],
                          "otro_charge_1" => $_POST['otro_charge_1'],
                          "otro_2" => $_POST['otro_2'],
                          "nombre_otro_2" => $_POST['nombre_otro_2'],
                          "otro_tel_2" => $_POST['otro_tel_2'],
                          "otro_email_2" => $_POST['otro_email_2'],
                          "otro_charge_2" => $_POST['otro_charge_2'],
                          "direccion_entrega" => $_POST['direccion_entrega'],
                          "pagos_via" => $_POST['pagos_via'],
                          "horario_entrega" => $_POST['horario_entrega'],
                          "info_adicional" => $_POST['info_adicional']);

                          $insert = InsertRec("clientes_formulario", $insert_form);

                          // Aviso de operación / Registro Publico

                          if(isset($_FILES['aviso_operacion']) && $_FILES['aviso_operacion']['tmp_name'] != "")
                          {
                              $target_dir = "aviso_registro/";
                              $target_file = $target_dir . basename($_FILES["aviso_operacion"]["name"]);
                              $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                              $filename = $target_dir . $insert.".".$imageFileType;
                              $filenameThumb = $target_dir . $insert."_thumb.".$imageFileType;
                              if (move_uploaded_file($_FILES["aviso_operacion"]["tmp_name"], $filename))
                              {
                                  //makeThumbnailsWithGivenWidthHeight($target_dir, $imageFileType, $nId, 600, 400);

                                  UpdateRec("clientes_formulario", "id = ".$insert, array("aviso_operacion" => $filenameThumb));
                              }
                          }

                          //Identificación del Representante legal

                          if(isset($_FILES['identificacion_representante_legal']) && $_FILES['identificacion_representante_legal']['tmp_name'] != "")
                          {
                              $target_dir = "identificacion/";
                              $target_file = $target_dir . basename($_FILES["identificacion_representante_legal"]["name"]);
                              $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                              $filename = $target_dir . $insert.".".$imageFileType;
                              $filenameThumbR = $target_dir . $insert."_thumb.".$imageFileType;
                              if (move_uploaded_file($_FILES["identificacion_representante_legal"]["tmp_name"], $filename))
                              {
                                  //makeThumbnailsWithGivenWidthHeight($target_dir, $imageFileType, $nId, 600, 400);

                                  UpdateRec("clientes_formulario", "id = ".$insert, array("identificacion_representante_legal" => $filenameThumbR));
                              }
                          }

                          if ($insert) {
                            $mensaje = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                          <strong>Registro Realizado</strong> El registro fue realizado con Exito.
                                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>';
                                      }

                          $nomrbe_archivo = 'Registro de Clientes';
                          $html = '

                          <!DOCTYPE html>
                          <html lang="en" dir="ltr">
                            <head>
                              <meta charset="utf-8">
                              <title>Formulario de Clientes</title>
                              <meta name="viewport" content="width=device-width, user-scalable=no">
                            <style media="screen">
                                html, body {
                                  height: 100%;
                                  background-color: #fcca0b;
                                }
                              </style>
                            </head>
                            <body>
                              <header>
                                  <figure>
                                    <img src="shl_logo.png" class="rounded mx-auto d-block" alt="">
                                  </figure>
                                  <h2 class="text-center">REGISTRO DE CLIENTES - GRUAS SHL</h2>
                              </header>
                              <main class="container">
                                <form class="" action="" method="post" enctype="multipart/form-data">
                                  <div class="row">
                                    <div class="col-12">
                                      <h4>Datos Principales</h4>
                                      <div class="form-group">
                                        <label for=""><b>Razón Social: </b></label>
                                        '.$_POST['razon_social'].'
                                      </div>
                                      <div class="form-group">
                                        <label for=""><b>Razón Comercial: </b></label>
                                        '.$_POST['razon_comercial'].'
                                      </div>
                                    </div>
                                    <div class="col-8">
                                      <div class="form-group">
                                        <label for=""><b>RUC: </b></label>
                                        '.$_POST['ruc'].'
                                      </div>
                                    </div>
                                    <div class="col-4">
                                      <div class="form-group">
                                        <label for=""><b>DV: </b></label>
                                        '.$_POST['dv'].'
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="form-group">
                                        <label for=""><b>Actividad Principal: </b></label>
                                        '.$_POST['actividad_principal'].'
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="form-group">
                                        <label for=""><b>Aviso de operación / Registro Publico (No mayor a 3 meses): </b></label>
                                        https://ofertadeviaje.com/formularios/'.str_replace("_thumb","", $filenameThumb).'
                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                  <h4><b>Dirección: </b></h4>
                                  <div class="row">
                                    <div class="col-6">
                                      <div class="form-group">
                                        <label for=""><b>Pais: </b></label>';

                                        $pais = GetRecords("SELECT * FROM pais WHERE id=".$_POST['pais']);
                                                foreach ($pais as $key => $value):
                                                $pais = utf8_encode($value['nombre']);
                                                endforeach;

                                      $html .='
                                      '.$pais.'
                                      </div>
                                      <div class="form-group">
                                        <label for=""><b>Ciudad: </b></label>
                                        '.$_POST['ciudad'].'
                                      </div>
                                      <div class="form-group">
                                        <label for=""><b>Edificio: </b></label>
                                        '.$_POST['edificio'].'
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div class="form-group">
                                        <label for=""><b>Provincia: </b></label>';

                                        $paispro = GetRecords("SELECT * FROM provincias WHERE id=".$_POST['provincia']);
                                                foreach ($paispro as $key => $value):
                                                $provincia = utf8_encode($value['nombre']);
                                                endforeach;

                                      $html .='
                                      '.$provincia.'
                                      </div>
                                      <div class="form-group">
                                        <label for=""><b>Calle o Ave: </b></label>
                                        '.$_POST['calle'].'
                                        </div>
                                      <div class="form-group">
                                        <label for=""><b>Piso/Oficina: </b></label>
                                        '.$_POST['piso'].'
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="form-group">
                                        <label for=""><b>Otros: </b></label>
                                        '.$_POST['otros'].'
                                      </div>
                                    </div>

                                    <div class="col-6">
                                      <div class="form-group">
                                        <label for=""><b>Telefono Principal: </b></label>
                                        '.$_POST['telef_prin'].'
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div class="form-group">
                                        <label for=""><b>Fax: </b></label>
                                        '.$_POST['fax'].'
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="form-group">
                                        <label for=""><b>Representante Legal: </b></label>
                                        '.$_POST['representante_legal'].'
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="form-group">
                                        <label for=""><b>Identificación del Representante legal: </b></label>
                                        <div class="custom-file">
                                          https://ofertadeviaje.com/formularios/'.str_replace("_thumb","", $filenameThumbR).'
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="form-group">
                                        <label for=""><b>Numero de Identidicación Representante Legal: </b></label>
                                        '.$_POST['numero_identificacion'].'
                                      </div>
                                    </div>
                                  </div>
                                  <h4>Informacion de Contactos</h4>
                                  <div class="row">
                                    <div class="col-12">
                                      <table border="1">
                                        <thead>
                                          <tr>
                                            <th scope="col">Area</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Teléfono</th>
                                            <th scope="col">Correo</th>
                                            <th scope="col">Cargo</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <th scope="row">Operaciones</th>
                                            <td>'.$_POST['operaciones_1'].'</td>
                                            <td>'.$_POST['op_tel_1'].'</td>
                                            <td>'.$_POST['op_email_1'].'</td>
                                            <td>'.$_POST['op_charge_1'].'</td>
                                          </tr>
                                          <tr>
                                            <th scope="row">Operaciones</th>
                                            <td>'.$_POST['operaciones_2'].'</td>
                                            <td>'.$_POST['op_tel_2'].'</td>
                                            <td>'.$_POST['op_email_2'].'</td>
                                            <td>'.$_POST['op_charge_2'].'</td>
                                          </tr>
                                          <tr>
                                            <th scope="row">Ctas. x Pagar</th>
                                            <td>'.$_POST['cuentas_pagar'].'</td>
                                            <td>'.$_POST['cp_tel_1'].'</td>
                                            <td>'.$_POST['cp_email_1'].'</td>
                                            <td>'.$_POST['cp_charge_1'].'</td>
                                          </tr>
                                          <tr>
                                            <th scope="row">Compras</th>
                                            <td>'.$_POST['compras'].'</td>
                                            <td>'.$_POST['c_tel_1'].'</td>
                                            <td>'.$_POST['c_email_1'].'</td>
                                            <td>'.$_POST['c_charge_1'].'</td>
                                          </tr>
                                          <tr>
                                            <th scope="row">Contab./Finanzas</th>
                                            <td>'.$_POST['contab_finanzas'].'</td>
                                            <td>'.$_POST['cf_tel_1'].'</td>
                                            <td>'.$_POST['cf_email_1'].'</td>
                                            <td>'.$_POST['cf_charge_1'].'</td>
                                          </tr>
                                          <tr>
                                            <td>'.$_POST['otro_1'].'</td>
                                            <td>'.$_POST['nombre_otro_1'].'</td>
                                            <td>'.$_POST['otro_tel_1'].'</td>
                                            <td>'.$_POST['otro_email_1'].'</td>
                                            <td>'.$_POST['otro_charge_1'].'</td>
                                          </tr>
                                          <tr>
                                            <td>'.$_POST['otro_2'].'</td>
                                            <td>'.$_POST['nombre_otro_2'].'</td>
                                            <td>'.$_POST['otro_tel_2'].'</td>
                                            <td>'.$_POST['otro_email_2'].'</td>
                                            <td>'.$_POST['otro_charge_2'].'</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                  <h4>Información de pagos</h4>
                                  <div class="row">
                                    <div class="col-12">
                                      <div class="form-group">
                                        <label for=""><b>Dirección para entrega de facturas: </b></label>
                                        '.$_POST['direccion_entrega'].'
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div class="form-group">
                                        <label for=""><b>Pagos Vía: </b></label>
                                        ';

                                        $via = "";
                                        if($_POST['pagos_via'] == 1){
                                          $via = "ACH";
                                        }elseif($_POST['pagos_via'] == 2){
                                          $via = "Cheque";
                                        }elseif($_POST['pagos_via'] == 3){
                                          $via = "Tarj. Credito";
                                        }

                                    $html .= ' '.$via.'</div>
                                    </div>
                                    <div class="col-6">
                                      <div class="form-group">
                                        <label for=""><b>Horario de Entrega de Cheques:</b></label>
                                        '.$_POST['horario_entrega'].'
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="form-group">
                                        <label for=""><b>Información Adicional: </b></label>
                                        '.$_POST['info_adicional'].'
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-12">
                                    </div>
                                  </div>
                                </form>
                              </main>
                              <footer class="text-center">
                                info@gruasshl.com <br> Grúas SHL. All rights reserved ®
                              </footer>
                            </body>
                          </html>';
                          include("pdf/mpdf.php");
                          $mpdf=new mPDF();
                          $mpdf->WriteHTML($html);
                          $mpdf->AddPage();

                          $subject = 'Cliente Registrado';
                          $fileName = $nomrbe_archivo.'.pdf';
                          $email_ventas = "cobros@gruasshl.com";
                          $personas = "yenika.gonzalez@gruasshl.com";
                          $to = $email_ventas.' ,'.$personas;
                          $repEmail = (isset($email_ventas) && $email_ventas != "") ? $email_ventas : '';
                          $conpania_nombre = 'SHL';
                          $repName = 'Cobros SHL';
                          $fileatt = $mpdf->Output($fileName, 'S');
                          $attachment = chunk_split(base64_encode($fileatt));
                          $eol = PHP_EOL;
                          $separator = md5(time());
                          $headers = 'From: '.$repName.' <'.$repEmail.'>'.$eol;
                          $headers .= 'MIME-Version: 1.0' .$eol;
                          $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";
                          $message = "--".$separator.$eol;
                          $message .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
                          $message .= "Se ha Registrado un cliente." .$eol;
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
    <meta charset="utf-8">
    <title>Formulario de Clientes</title>
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style media="screen">
      html, body {
        height: 100%;
        background-color: #fcca0b;
      }
    </style>
  </head>
  <body>
    <header>
        <?php echo $mensaje; ?>
        <figure>
          <img src="shl_logo.png" class="rounded mx-auto d-block" alt="">
        </figure>
        <h2 class="text-center">REGISTRO DE CLIENTES - GRUAS SHL</h2>
    </header>
    <main class="container">
      <form class="" action="" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="col-12">
            <h4>Datos Principales</h4>
            <div class="form-group">
              <label for="">Razón Social</label>
              <input type="text" class="form-control" placeholder="Razon Social" name="razon_social" value="" required>
            </div>
            <div class="form-group">
              <label for="">Razón Comercial</label>
              <input type="text" class="form-control" placeholder="Razón Comercial" name="razon_comercial" value="">
            </div>
          </div>
          <div class="col-8">
            <div class="form-group">
              <label for="">RUC</label>
              <input type="text" class="form-control" placeholder="RUC" name="ruc" value="" required>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">DV</label>
              <input type="text" class="form-control" placeholder="DV" name="dv" value="" required>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="">Actividad Principal</label>
              <input type="text" class="form-control" placeholder="Actividad Principal" name="actividad_principal" value="" required>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="">Aviso de operación / Registro Publico (No mayor a 3 meses).</label>
              <div class="custom-file">
                <input name="aviso_operacion" type="file" class="custom-file-input" id="validatedCustomFile" required>
                <label class="custom-file-label" for="validatedCustomFile">Selecione un Archivo...</label>
                <div class="invalid-feedback">Seleccione un Archivo</div>
              </div>
            </div>
          </div>
        </div>

        <h4>Dirección</h4>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="">Pais</label>
              <select class="form-control" id="pais" name="pais" required>
                <option value="">Seleccionar Pais</option>
                <?php $pais = GetRecords("SELECT * FROM pais"); ?>
                <?php foreach ($pais as $key => $value): ?>
                  <option value="<?php echo $value['id']; ?>"><?php echo utf8_encode($value['nombre']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="">Ciudad</label>
              <input type="text" class="form-control" placeholder="Ciudad" name="ciudad" value="" required>
            </div>
            <div class="form-group">
              <label for="">Edificio</label>
              <input type="text" class="form-control" placeholder="Edificio" name="edificio" value="" required>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Provincia</label>
              <select class="form-control" id="provincia" name="provincia" required>
                <option value="">Seleccionar Provincia</option>
              </select>
            </div>
            <div class="form-group">
              <label for="">Calle o Ave</label>
              <input type="text" class="form-control" placeholder="Calle o Ave" name="calle" value="" required>
            </div>
            <div class="form-group">
              <label for="">Piso/Oficina</label>
              <input type="text" class="form-control" placeholder="Piso/Oficina" name="piso" value="" required>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="">Otros</label>
              <input type="text" class="form-control" placeholder="Otros" name="otros" value="">
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label for="">Telefono Principal</label>
              <input type="text" class="form-control" placeholder="Telefono Principal" name="telef_prin" value="" required>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Fax</label>
              <input type="text" class="form-control" placeholder="Fax" name="fax" value="">
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="">Representante Legal</label>
              <input type="text" class="form-control" placeholder="Representante Legal" name="representante_legal" value="" required>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="">Identificación del Representante legal</label>
              <div class="custom-file">
                <input name="identificacion_representante_legal" type="file" class="custom-file-input" id="validatedCustomFile" required>
                <label class="custom-file-label" for="validatedCustomFile">Selecione un Archivo...</label>
                <div class="invalid-feedback">Seleccione un Archivo</div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="">Numero de Identidicación Representante Legal</label>
              <input type="text" class="form-control" placeholder="Numero de Identidicación Representante Legal" name="numero_identificacion" value="" required>
            </div>
          </div>
        </div>
        <h4>Informacion de Contactos</h4>
        <div class="row">
          <div class="col-12">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Area</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Teléfono</th>
                  <th scope="col">Correo</th>
                  <th scope="col">Cargo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">Operaciones</th>
                  <td><input type="text" class="form-control" placeholder="Nombre" name="operaciones_1" required></td>
                  <td><input type="text" class="form-control" placeholder="Telefono" name="op_tel_1" required></td>
                  <td><input type="text" class="form-control" placeholder="Correo" name="op_email_1" required></td>
                  <td><input type="text" class="form-control" placeholder="Cargo" name="op_charge_1"></td>
                </tr>
                <tr>
                  <th scope="row">Operaciones</th>
                  <td><input type="text" class="form-control" placeholder="Nombre" name="operaciones_2"></td>
                  <td><input type="text" class="form-control" placeholder="Telefono" name="op_tel_2"></td>
                  <td><input type="text" class="form-control" placeholder="Correo" name="op_email_2"></td>
                  <td><input type="text" class="form-control" placeholder="Cargo" name="op_charge_2"></td>
                </tr>
                <tr>
                  <th scope="row">Ctas. x Pagar</th>
                  <td><input type="text" class="form-control" placeholder="Nombre" name="cuentas_pagar" required></td>
                  <td><input type="text" class="form-control" placeholder="Telefono" name="cp_tel_1" required></td>
                  <td><input type="text" class="form-control" placeholder="Correo" name="cp_email_1" required></td>
                  <td><input type="text" class="form-control" placeholder="Cargo" name="cp_charge_1"></td>
                </tr>
                <tr>
                  <th scope="row">Compras</th>
                  <td><input type="text" class="form-control" placeholder="Nombre" name="compras" required></td>
                  <td><input type="text" class="form-control" placeholder="Telefono" name="c_tel_1" required></td>
                  <td><input type="text" class="form-control" placeholder="Correo" name="c_email_1" required></td>
                  <td><input type="text" class="form-control" placeholder="Cargo" name="c_charge_1"></td>
                </tr>
                <tr>
                  <th scope="row">Contab./Finanzas</th>
                  <td><input type="text" class="form-control" placeholder="Nombre" name="contab_finanzas" required></td>
                  <td><input type="text" class="form-control" placeholder="Telefono" name="cf_tel_1" required></td>
                  <td><input type="text" class="form-control" placeholder="Correo" name="cf_email_1" required></td>
                  <td><input type="text" class="form-control" placeholder="Cargo" name="cf_charge_1"></td>
                </tr>
                <tr>
                  <th><input type="text" class="form-control" placeholder="Area" name="otro_1"></th>
                  <td><input type="text" class="form-control" placeholder="Nombre" name="nombre_otro_1"></td>
                  <td><input type="text" class="form-control" placeholder="Telefono" name="otro_tel_1"></td>
                  <td><input type="text" class="form-control" placeholder="Correo" name="otro_email_1"></td>
                  <td><input type="text" class="form-control" placeholder="Cargo" name="otro_charge_1"></td>
                </tr>
                <tr>
                  <th><input type="text" class="form-control" placeholder="Area" name="otro_2"></th>
                  <td><input type="text" class="form-control" placeholder="Nombre" name="nombre_otro_2"></td>
                  <td><input type="text" class="form-control" placeholder="Telefono" name="otro_tel_2"></td>
                  <td><input type="text" class="form-control" placeholder="Correo" name="otro_email_2"></td>
                  <td><input type="text" class="form-control" placeholder="Cargo" name="otro_charge_2"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <h4>Información de pagos</h4>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="">Dirección para entrega de facturas</label>
              <input type="text" class="form-control" placeholder="Dirección para entrega de facturas" name="direccion_entrega" value="" required>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Pagos Vía</label>
              <select class="form-control" name="pagos_via" required>
                <option value="">Seleccionar</option>
                <option value="1">ACH</option>
                <option value="2">Cheque</option>
                <option value="3">Tarj. Crédito</option>
              </select>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Horario de Entrega de Cheques</label>
              <input type="text" class="form-control" placeholder="Horario de Entrega de Cheques" name="horario_entrega" value="" required>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="">Información Adicional</label>
              <input type="text" class="form-control" placeholder="Información Adicional" name="info_adicional" value="">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-lg btn-block" name="button_send">Guardar</button>
          </div>
        </div>
      </form>
    </main>
    <footer class="text-center">
      info@gruasshl.com <br> Grúas SHL. All rights reserved ®
    </footer>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script language="javascript">
    $(document).ready(function(){
        $("#pais").on('change', function () {
            $("#pais option:selected").each(function () {
                elegido=$(this).val();
                $.post("provincias_stados.php", { elegido: elegido }, function(data){
                    $("#provincia").html(data);
                });
            });
       });
    });
    </script>
  </body>
</html>
