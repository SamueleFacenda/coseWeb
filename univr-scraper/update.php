<?php
//suppress warnings for DOMDocument
libxml_use_internal_errors(true);

$url = 'https://www.corsi.univr.it/?ent=cs&id=474&menu=studiare&tab=orario-lezioni&lang=it';
$match = 'CALENDARIO 2°';
$xquery = "//a[contains(.,'$match')]";

// Create DOMdocument from URL
$html = new DOMDocument();
$html->loadHTMLFile($url);

// find the a tag with the xpath
$xpath = new DOMXPath($html);
$href = $xpath->query($xquery);
// get the href attribute
$href = $href->item(0)->getAttribute('href');
$href = "https://www.corsi.univr.it$href";


# previous one
// read the href from the file
$fp = fopen('href.txt', 'r');
$last_time = fgets($fp);
$prev_href = fgets($fp);
fclose($fp);
if(strcmp($prev_href, $href) !== 0) {
  # send notification mail
  $email = 'placeholder@gmail.com';
  $subject = 'Nuovo orario infermieristica';
  $message = '<html><body>';
  $message .= '<head><title>Orario infermieristica univr aggiornato!</title></head>';
  $message .= '<h1>È uscito il nuovo orario</h1>';
  $message .= '<p>Guardalo <a href="https://pastapizza.altervista.org/univr/renderer.php">qui</a></p>';
  $message .= '</body></html>';
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
  $headers .= 'From: "Samuele Facenda" <pastapizza@altervista.org>' . "\r\n";
  $res = mail($email, $subject,$message, $headers); 
}

// save value to file
$fp = fopen('href.txt', 'w');
fwrite($fp, time());
fwrite($fp, "\n");
fwrite($fp, $href);
fclose($fp);

$file = $_SERVER['REQUEST_URI'];
// get the file name
$file = substr($file, strrpos($file, '/') + 1);
if ($file == "update.php") {
    echo "<h1>Aggiornato</h1>";
}
?>
