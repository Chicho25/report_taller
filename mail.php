<?php
$nomrbe_archivo = 'Registro de Clientes';
$html = '';
include("pdf/mpdf.php");
$mpdf=new mPDF();
$mpdf->WriteHTML($html);
$mpdf->AddPage();

$subject = 'Cliente Registrado';
$fileName = $nomrbe_archivo.'.pdf';
$email_ventas = "cobros@gruasshl.com";
$to = $email.','.$email_ventas.', tayron.arrieta@gruasshl.com';
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

mail($to, $subject, $message, $headers); ?>
