<?php
include_once '../utils/jwt.php';
include_once '../utils/username.php';

if(isset($email) && isset($_POST['note_id']) && isset($_POST['query'])){
    $dest = $_POST['query'];
    $note_id = $_POST['note_id'];


}



?>
<!DOCTYPE html>
<html lang="en">
<?php
// import head
include_once '../static/head.php';
echo '<body class="d-flex flex-column min-vh-100">';
include_once '../static/navbar.php';
?>
<section class="text-center">
    <h1 class="display-4">Shared</h1>
</section>
<?php
include_once '../static/footer.php';
?>
</body>
</html>