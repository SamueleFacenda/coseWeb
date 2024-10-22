<?php

//suppress warnings for DOMDocument
libxml_use_internal_errors(true);

$url = 'https://www.corsi.univr.it/?ent=cs&id=474&menu=studiare&tab=orario-lezioni&lang=it';

// https://www.corsi.univr.it/?ent=cs&id=1179&menu=studiare&tab=orario-lezioni&lang=en

$xquery = "//div[@id='tab-orario-lezioni']//a[contains(.,'SEM') or contains(.,'sem')]";

include_once 'db.php';

$urls = $conn->query("SELECT * FROM corsi LEFT JOIN orari ON corsi.id = orari.corso");
$urls = $urls->fetch_all(MYSQLI_ASSOC);

$update_stmt = $conn->prepare("UPDATE orari SET lasturl = ? WHERE id = ?");

foreach ($urls as $url) {

    $id = parse_url($url['url'])['query'];
    $id = explode('=', $id)[2];
    $orario_url = "https://www.corsi.univr.it/?ent=cs&id=$id&menu=studiare&tab=orario-lezioni&lang=it";

    
    // Create DOMdocument from URL
    $html = new DOMDocument();
    $html->loadHTMLFile($orario_url);

    // find the a tag with the xpath
    $xpath = new DOMXPath($html);
    $href = $xpath->query($xquery);
    // get the one that has the first number equals to the year
    $minPos = 1000;
    $node = null;
    foreach ($href as $h) {
        $pos = strpos($h->nodeValue, $url['year']);
        if ($pos !== false && $pos < $minPos) {
            $minPos = $pos;
            $node = $h;
        }
    }

    if ($node === null) {
        echo $url['laurea'] . ' ' . $url['year'] . " " .$url["sede"] . " anno not found </br>";
        continue;
    }

    $href = $node->getAttribute('href');


    $href = "https://www.corsi.univr.it$href";

    // check is is the same as lasturl
    if (strcmp($url['lasturl'], $href) !== 0) {
        echo $url['laurea'] . ' ' . $url['year'] . " " .$url["sede"] . " updated </br>";
        $update_stmt->bind_param("si", $href, $url['id']);
        $update_stmt->execute();
        // send notification mail

        $orario_id = $url['id'];
        $subscriptions = $conn->query("SELECT email FROM subscriptions WHERE orario = $orario_id");
        foreach($subscriptions as $email) {
            $email = $email['email'];
            $escaped_laurea = urlencode($url['laurea']);
            $subject = 'Nuovo orario ' . $url['laurea'] . ' ' . $url['year'] . ' ' . $url['sede'];
            $message = '<html><body>';
            $message .= '<head><title>Orario ' . $url['laurea'] . ' ' . $url['year'] . ' ' . $url['sede'] . ' univr aggiornato!</title></head>';
            $message .= '<h1>È uscito il nuovo orario</h1>';
            $message .= '<p>Guardalo <a href="https://pastapizza.altervista.org/univr/generic_renderer.php?laurea='.$escaped_laurea.'&anno='.$url['year'].'&sede='.$url['sede'].'">qui</a></p>';
            $message .= '<p>Unsubscribe <a href="https://pastapizza.altervista.org/univr/unsubscribe.php?email='.$email.'&orario='.$orario_id.'">here</a></p>';
            $message .= '</body></html>';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
            $headers .= 'From: "Samuele Facenda" <pastapizza@altervista.org>' . "\r\n";
            $res = mail($email, $subject,$message, $headers); 
        }
    }
    
}


$update_stmt->close();
$conn->close();
