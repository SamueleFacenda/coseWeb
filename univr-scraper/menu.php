<?php

include_once 'db.php';

$sedi = $conn->query("SELECT DISTINCT sede FROM corsi ORDER BY sede");
$lauree = $conn->query("SELECT DISTINCT laurea FROM corsi ORDER BY laurea");
$anni = $conn->query("SELECT DISTINCT `year` FROM orari ORDER BY `year`");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orari univr aggiornati</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card" style="width: 30rem;">
        <div class="card-body">
            <h5 class="card-title">Scelta orario da visualizzare</h5>
            <form action="generic_renderer.php" method="get">
                <div class="mb-3">
                    <label for="sede" class="form-label">Sede</label>
                    <select class="form-select" name="sede" id="sede">
                        <option value="" selected disabled hidden>Sede</option>
                        <?php while ($sede = $sedi->fetch_assoc()): ?>
                            <option value="<?= $sede['sede'] ?>"><?= $sede['sede'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="laurea" class="form-label">Laurea</label>
                    <select class="form-select" name="laurea" id="laurea">
                        <option value="" selected disabled hidden>Laurea</option>
                        <?php while ($laurea = $lauree->fetch_assoc()): ?>
                            <option value="<?= $laurea['laurea'] ?>"><?= $laurea['laurea'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="anno" class="form-label">Anno</label>
                    <select class="form-select" name="anno" id="anno">
                        <option value="" selected disabled hidden>Anno</option>
                        <?php while ($anno = $anni->fetch_assoc()): ?>
                            <option value="<?= $anno['year'] ?>"><?= $anno['year'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="subscribe" value="true" id="subscribe">
                    <label class="form-check-label" for="subscribe">Ricevi aggiornamenti via mail quando l'orario si aggiorna</label>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">la tua mail (solo se vuoi ricevere notifiche)</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="mario.rossi@gmail.com">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="privacyCheck" required>
                    <label class="form-check-label" for="privacyCheck">
                        Ho letto e accetto la <a href="privacy.html" target="_blank">privacy policy</a>.
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Visualizza</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>