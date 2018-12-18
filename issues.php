<?php

ob_start();
session_start();
include('include/config.php');
include('include/defs.php');

if (isset($_POST['mensaje'])){
  $array_issue = array("description" =>$_POST['mensaje'],
                       "stat" => 1,
                       "date_time" => date("Y-m-d H:i:s"),
                       "id_user" => $_SESSION['session']['id']);

  InsertRec("issues_temp", $array_issue);

 }

$rec = GetRecords("SELECT * FROM issues_temp where id_user =".$_SESSION['session']['id']);
foreach ($rec as $key => $value) { ?>
  <input style="width:80%; border: none; background-color: #D3D4C7;" type="text" readonly name="issue_temp[]" value="<?php echo $value['description']; ?>">
  <a data-toggle="modal" data-target="#exampleModalEliminar<?php echo $value['id']; ?>" class="btn"><i class="fa fa-trash"></i></a>
<?php } ?>
