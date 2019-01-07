<?php

session_start();
ob_start();
include('include/config.php');
include('include/defs.php');

if (isset($_POST['Submit'])) {
    $obtener_registro = GetRecords("select count(*) as contar, stat, id from users where user = 'callcenter' and password = '".$_POST['pwd']."'");
    $comprobar = $obtener_registro[0]['contar'];

    if ($comprobar >= 1) {
       $_SESSION['disponible'] = $obtener_registro[0]['stat'];
       $_SESSION['id_user'] = $obtener_registro[0]['id'];

       header("Location: main.php");
       exit;

    }

}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Call Center</title>
<style type="text/css">
body {
font-size: 100%;
font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif;
/*padding-top: 100px;*/
}
label { display: block; margin-top: 10px; }
#login { width: 300px; height: 400px; margin: 0 auto; border: 1px solid #eee; padding: 25px; }
a { color: #0066CC; text-decoration: none; border-bottom: 1px dotted #0066cc; }
#submit_butt { margin-top: 300px; position: absolute;}
h3 { margin-top: 0; text-align: center;}

.botonTeclado{
	width:60px;
  height:60px;
  background-color:#03800b;
  margin: 5px;
  padding:10px;
  -webkit-border-radius: 50px;
  -moz-border-radius: 50px;
  border-radius: 50px;
  font-size:16px;
  line-height:32px;
  text-transform: uppercase;
  float:left;
	color:white;
}

.botonTeclado:hover{
	opacity: 0.50;
  -moz-opacity: .50;
  filter:alpha (opacity=50);
	color:white;
}

.ingreso{
	width:150px;
  height:80px;
  background-color:#03800b;
  margin: 5px;
	margin-left: 75px;
  padding:10px;
  font-size:16px;
  line-height:32px;
  text-transform: uppercase;
  float:left;
	color:white;

}

.ingreso:hover{
	opacity: 0.50;
  -moz-opacity: .50;
  filter:alpha (opacity=50);
	color:white;
}



</style>

<link href="keyboardstyle.css" type="text/css" rel="stylesheet" />

</head>

<body>
<div class="">
	<img src="image/1.png" alt="" width="200" style="display:block; margin:auto;">
</div>
<div id="login">

<h3>Call Center</h3>
<form action="" method="post" id="loginform">
	<label for="username"></label>
	<input type="hidden" name="username" id="username" value="callcenter" />

	<label for="pwd"></label>
	<input type="hidden" name="pwd" id="pwd" style="position:absolute;"/>

	<input type="submit" name="Submit" id="submit_butt" class="ingreso" value="Ingresar" />
</form>

<div id="keyboard">
	<div id="row0">
		<input name="1" class="botonTeclado" type="button" value="1" />
		<input name="2" class="botonTeclado" type="button" value="2" />
		<input name="3" class="botonTeclado" type="button" value="3" />
		<input name="4" class="botonTeclado" type="button" value="4" />
		<input name="5" class="botonTeclado" type="button" value="5" />
		<input name="6" class="botonTeclado" type="button" value="6" />
		<input name="7" class="botonTeclado" type="button" value="7" />
		<input name="8" class="botonTeclado" type="button" value="8" />
		<input name="9" class="botonTeclado" type="button" value="9" />
		<input name="0" class="botonTeclado" type="button" value="0" />
	</div>

</div>

</div>

<script type="text/javascript" src="jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="jquery-ui-personalized-1.5.2.min.js"></script>
<script type="text/javascript" src="jquery-fieldselection.js"></script>

<script type="text/javascript" src="vkeyboard.js"></script>

</body>

</html>
