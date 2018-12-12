<?php

// clave del raspberry Gamble7910

function conexion(){
  $enlace = mysqli_connect("localhost", "root", "", "formularios");
  return $enlace;
}

function FixString($strString){
  $strString = str_replace("'", "''", $strString);
  $strString = str_replace("\'", "'", $strString);
  return $strString;
}

function InsertRec($strTable, $arrValue){
  $strQuery = "	insert into $strTable (";
  reset($arrValue);
  while(list ($strKey, $strVal) = each($arrValue)){
    $strQuery .= $strKey . ",";
  }
  $strQuery = substr($strQuery, 0, strlen($strQuery) - 1);
  $strQuery .= ") values (";
  reset($arrValue);
  while(list ($strKey, $strVal) = each($arrValue)){
    $strQuery .= "'" . FixString($strVal) . "',";
  }
  $strQuery = substr($strQuery, 0, strlen($strQuery) - 1);
  $strQuery .= ");";
  $insercion = conexion() -> query($strQuery);
  return $insercion->insert_id;
}

function GetRecords($sql){
  $check= conexion() -> query($sql);
  $return=array();
  $i=0;
  while ($row = $check -> fetch_array()){
    $return[$i]=$row;
    $i++;
  }
  return $return;
}

 ?>
