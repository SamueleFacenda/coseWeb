<?php

include_once 'db.php';

$sedi = $conn->query("SELECT DISTINCT sede FROM corsi");
$lauree = $conn->query("SELECT DISTINCT laurea FROM corsi");
$anni = $conn->query("SELECT DISTINCT `year` FROM orari");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orari univr aggiornati</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }
        table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
        }
    </style>
</head>
<body>
    <form action="generic_renderer.php" method="get">
        <select name="sede">
            <option value="">Sede</option>
            <?php while ($sede = $sedi->fetch_assoc()): ?>
                <option value="<?= $sede['sede'] ?>"><?= $sede['sede'] ?></option>
            <?php endwhile; ?>
        </select>
        <select name="laurea">
            <option value="">Laurea</option>
            <?php while ($laurea = $lauree->fetch_assoc()): ?>
                <option value="<?= $laurea['laurea'] ?>"><?= $laurea['laurea'] ?></option>
            <?php endwhile; ?>
        </select>
        <select name="anno">
            <option value="">Anno</option>
            <?php while ($anno = $anni->fetch_assoc()): ?>
                <option value="<?= $anno['year'] ?>"><?= $anno['year'] ?></option>
            <?php endwhile; ?>
        </select>
        <input type="submit" value="Invia">
    </form>
</body>
</html>