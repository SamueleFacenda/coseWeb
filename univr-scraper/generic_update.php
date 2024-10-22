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
    // get the one that has the first number equals to the yearù
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
        ;
    }
    
}
