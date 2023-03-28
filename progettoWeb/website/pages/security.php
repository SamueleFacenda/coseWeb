<!DOCTYPE html>
<html lang="en">
<?php
// import head
include_once '../static/head.php';
echo '<body class="d-flex flex-column min-vh-100">';
include_once '../utils/jwt.php';
include_once '../utils/username.php';
include_once '../static/navbar.php';
?>
<section class="text-center">
    <h1 class="display-4">Please repeat this action</h1>
    <p class="lead">
        For security reasons, you have to repeat the action that you were performing.
    </p>
    <p class="lead">
        If this happened without your consent(e.g. you clicked a link and you ended up here),
        please contact the site support and report everithing(e.g. the link you clicked, he sender...).
    </p>
    <p class="lead">
        Thanks for you patience!
    </p>
</section>
<?php
include_once '../static/footer.php';
?>
</body>
</html>