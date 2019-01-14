<?php 

include('include/config.php');
include('include/defs.php');

    $get = GetRecords("SELECT
                       issues.description, 
                       report.id_equipo, 
                       report.ubicacion, 
                       issues.date_time, 
                       report.colaborador
                       FROM issues inner join report on report.id = issues.id_report");

    foreach ($get as $key => $value) {
        
        $array_feet_issues = array("summary" => $value['description'], 
        "description" => $value['ubicacion'],
        "stat" => 1, 
        "id_vehicle" => $value['id_equipo'], 
        "reportedOn" => $value['date_time'], 
        "id_reportedby" => $value['colaborador'], 
        "id_priority" => 1,
        "stat_issue" => 0, 
        "isClosed" => 0, 
        "createdOn"=> $value['date_time'], 
        "id_vehsection"=> 1);

        InsertRec("fleet_issue", $array_feet_issues);
    }

?>