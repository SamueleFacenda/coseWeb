<?php

$anno = $_GET['anno'];
$anno = intval($anno);
$laurea = $_GET['laurea'];
$sede = $_GET['sede'];

$subscribe = $_GET['subscribe'];
$email = $_GET['email'];


# query the database for the orario
include_once 'db.php';
$stmt = $conn->prepare("SELECT * FROM corsi INNER JOIN orari ON orari.corso = corsi.id WHERE `year` = ? AND laurea = ? AND sede = ?");
$stmt->bind_param("iss", $anno, $laurea, $sede);
$stmt->execute();
$result = $stmt->get_result();
$orario = $result->fetch_assoc();


if($subscribe == "true") {
    $orario_id = $orario['id'];
    $stmt = $conn->prepare("INSERT IGNORE INTO subscriptions (email, orario) VALUES (?, ?)");
    $stmt->bind_param("si", $email, $orario_id);
    $stmt->execute();

    $stmt->close();
    $conn->close();
    $escaped_laurea = urlencode($laurea);
    header("Location: generic_renderer.php?laurea=$escaped_laurea&anno=$anno&sede=$sede");
}

$stmt->close();
$conn->close();

if ($orario == null) {
    echo "Orario not found";
    die();
}

$href = $orario['lasturl'];

$href = urldecode($href);
// dumb cors security bypass
$url = "../../proxy.php?url=$href";
$url = urlencode($url);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orario univr aggiornato</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }
    </style>
</head>
<body>
    <!-- preview the pdf in browser viewer -->
    <object data="<?php echo $href; ?>" type="application/pdf" width="100%" height="100%">
        
        <!-- fallback 1, pdfjs view -->
        <iframe title="PDF" src="pdfjs/web/viewer.html?file=<?= $url ?>" width="100%" height="100%">
            
            <!-- fallback 2, drive pdf viewer, very buggy -->
            <iframe src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?= $href ?>" 
                id="iframepdf" onload="iframeLoaded()" onerror="updateIframe()"
                width="100%" height="100%" frameborder="0">
                <h1>click <a href="<?php echo $href; ?>">here</a> to view the file</h1>
            </iframe>
        </iframe>
    </object>
</body>
</html>