<?php

include_once '../utils/jwt.php';
include_once '../utils/username.php';
include_once '../utils/connection.php';

$show_toast_added = false;
if(isset($email)){
    connect();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $label = $_POST['label'];
        $text = $_POST['text'];
        if(empty($text)){
            $text = null;
        }

        if(!empty($label)){
            add_note($email, $label, $text);
            $show_toast_added = true;
        }
    }

    $notes = get_notes($email);
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
// import head
include_once '../static/head.php';
echo '<body>';
include_once '../static/navbar.php';
?>
<section class="text-center">
    <h1>Notes</h1>
    <?php if(!isset($username)): ?>
        <p>Log in to see your notes</p>
    <?php else:
        if($notes == null){
            echo "<p>You don't have any notes, click on 'add note' to create one!</p>";
        }
        ?>

        <!-- Button trigger modal -->
        <button class="z-3 btn btn-primary position-fixed bottom-0 end-0 m-md-5 m-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
            Add note
        </button>

        <!-- Notes -->
        <div class="container text-center">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
                <?php foreach($notes as $note): ?>
                    <div class="col col-sm-6 mb-3 h-100">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?=htmlspecialchars($note['label'])?> </h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?=$note['date']?></h6>
                                <p class="card-text"><?=htmlspecialchars($note['text'])?></p>
                                <!--
                                <a href="#" class="card-link">Card link</a>
                                -->
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add note</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="notes.php" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="label" class="form-label">Label</label>
                        <input type="text" class="form-control" id="label" name="label" required>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label">Text</label>
                        <textarea class="form-control" id="text" name="text" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <!--
            <img src="..." class="rounded me-2" alt="...">
            -->
            <strong class="me-auto">Notes</strong>
            <small>now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Note with title '<?= htmlspecialchars($_POST['label'])?>' added successfully!
        </div>
    </div>
</div>

<?php
include_once '../static/footer.php';
if ($show_toast_added){ ?>
    <script>
        // Show toast
        const toastLiveExample = document.getElementById('liveToast')
        const toast = new bootstrap.Toast(toastLiveExample)
        toast.show()

    </script>
<?php }
?>
</body>
</html>