<!DOCTYPE html>
<html lang="en">
<?php
// import head
include_once '../static/head.php';
echo '<body>';
include_once '../utils/jwt.php';
include_once '../utils/username.php';
include_once '../static/navbar.php';
?>
<section class="text-center">
    <h1>About</h1>
    <p>
        This is a school project made by Samuele Facenda for the Web Programming course.
    </p>
    <p>
        You can find the source code on <a href="https://github.com/SamueleFacenda/coseWeb">GitHub</a>.
    </p>
    <p>
        Made at <a href="https://www.buonarroti.tn.it/">ITT Buonarroti</a>.
    </p>
</section>
<?php
include_once '../static/footer.php';
?>
</body>
</html>