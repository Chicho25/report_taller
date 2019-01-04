<?php
ob_start();
ini_set("session.cookie_lifetime","7200");
ini_set("session.gc_maxlifetime","7200");
session_start();
include('include/config.php');
include('include/defs.php');
$mensaje = "";

if (isset($_SESSION['session'])) {
    header('Location: main.php');
}

if (isset($_POST['user'], $_POST['pass'])) {

  $username = $_POST['user'];
  $password = encryptIt($_POST['pass']);
  $username = strip_tags(trim($username));

    $user = GetRecords("SELECT
                        *,
                        count(*) as contar
                        FROM
                        users
                        WHERE
                        user like '%".$username."%'
                        and
                        password like '%".$password."%'");

      foreach ($user as $key => $value) {
              $contar = $value['contar'];
      }

      if ($contar > 0) {
          session_start();
          $sessiones = array('id' => $user[0]['id']);
          $_SESSION['session'] = $sessiones;
          header('Location: main.php');
      }else{
        $mensaje = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>Usuario no valido</strong> el usuario no es valido
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';

      }

}

 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tayron">
    <title>Call Center</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  </head>
  <body class="text-center">
    <main class="container h-100">
      <?php echo $mensaje; ?>
      <div class="row h-100 justify-content-center align-items-center">
        <form class="form" method="post">
          <img class="mb-4" src="shl_logo.png" alt="">
          <h1 class="h3 mb-3 font-weight-normal">Por favor Ingrese</h1>
          <div class="form-group">
            <label for="inputEmail" class="sr-only">Usuario</label>
            <input name="user" type="text" id="" class="form-control" placeholder="Usuario" required autofocus autocomplete="off">
          </div>
          <div class="form-group">
            <label for="inputPassword" class="sr-only">Contraseña</label>
            <input name="pass" type="password" id="inputPassword" class="form-control" placeholder="Contraseña" required>
          </div>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Ingresar</button>
          <p class="mt-5 mb-3 text-muted">&copy; Gruas SHL </p>
        </form>
      </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
