<?php

include_once '../utils/jwt.php';
include_once '../utils/username.php';
include_once '../utils/connection.php';

if(isset($email)){
    connect();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $label = $_POST['label'];
        $text = $_POST['text'];
        $action = $_POST['action'];
        if(empty($text)){
            $text = null;
        }

        if(!empty($label) && !empty($action)){
            switch($action){
                case 'create':
                    add_note($email, $label, $text);
                    $toast_text = 'Note with title'.htmlspecialchars($label).' added successfully!';
                    break;
                case 'edit':
                    $id = $_POST['note_id'];
                    edit_note($email, $id, $label, $text);
                    $toast_text = 'Note with title'.htmlspecialchars($label).' edited successfully!';
                    break;
                case 'delete':
                    $id = $_POST['note_id'];
                    delete_note($email, $id);
                    $toast_text = 'Note with title'.htmlspecialchars($label).' deleted successfully!';
                    break;
            }
        }
    }

    if(isset($_GET['query'])){
        $query = $_GET['query'];
    }

    if(isset($_GET['query'])){
        $query = $_GET['query'];
        $notes = get_notes_containing($email, $query);
    }else{
        $notes = get_notes($email);
    }
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
    <div class="row align-items-center justify-content-center">
        <div class="col-sm-2 offset-sm-5">
            <h1 class="display-4 w-auto">Notes</h1>
        </div>
        <div class="col-sm-4 offset-sm-1 text-center">
            <div class="mx-sm-2 px-4 px-sm-0">
                <!-- research -->
                <form class="d-flex" role="search" method="get" action="notes.php">
                    <input class="form-control me-2 w-75" type="search" placeholder="Search" aria-label="Search" name="query"
                    value="<?=$query?>">
                    <button class="btn btn-outline-success w-25" type="submit">
                        <!-- search icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </div>
    <?php if(!isset($email)): ?>
        <p>Log in to see your notes</p>
    <?php else:
        if($notes == null && empty($query)){
            echo "<p>You don't have any notes, click on 'add note' to create one!</p>";
        }
        if($notes == null && !empty($query)){
            echo "<p>No search result!</p>";
        }
        ?>

        <!-- Button trigger modal -->
        <button class="z-3 btn btn-primary position-fixed bottom-0 end-0 m-md-5 m-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                <!-- + icon -->
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
            Add note
        </button>

        <!-- Notes -->
        <div class="container mt-3">
            <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
                <?php foreach($notes as $note): ?>
                    <div class="col">
                        <div class="card h-100" onclick="editModal(<?=$note['note_id']?>)">
                            <div class="card-body" id="<?=$note['note_id']?>">
                                <h5 class="card-title">
                                    <?=htmlspecialchars($note['label'])?>
                                </h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <?=$note['date']?>
                                </h6>
                                <p class="card-text">
                                    <?=nl2br(htmlspecialchars($note['text']))?>
                                </p>
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

<!-- Modal Add -->
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
                    <button type="submit" name="action" value="create" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit note</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="notes.php" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="labelEdit" class="form-label">Label</label>
                        <input type="text" class="form-control" id="labelEdit" name="label" required>
                    </div>
                    <div class="mb-3">
                        <label for="textEdit" class="form-label">Text</label>
                        <textarea class="form-control animated" id="textEdit" name="text" rows="1" onfocus="updateTextArea()" onkeyup="updateTextArea()" style="overflow:hidden"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" class="btn btn-danger me-auto" value="delete">Delete</button>
                    <button type="button" class="btn btn-success" data-bs-target="#modalToggleShare" data-bs-toggle="modal">Share</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="action" class="btn btn-primary" value="edit">Save</button>
                </div>
                <input type="hidden" name="note_id">
            </form>
        </div>
    </div>
</div>


<!-- Share Modal -->
<div class="modal fade" id="modalToggleShare" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Share note</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="shared.php" method="post" autocomplete="off">
                <div class="modal-body">
                    <div class="position-relative">
                        <div class="input-group mb-3">
                            <input id="search" name="dest" class="form-control d-inline-block" type="search" placeholder="Search user" aria-label="Search" onkeyup="updateSearch()">
                            <button class="btn btn-outline-success" type="submit">
                                <!-- share icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-share" viewBox="0 0 16 16">
                                    <path d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5zm-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="list-group position-absolute top-100 start-0 end-0 m-0" id="searchList">
                            <!-- suggestion items -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                <input type="hidden" name="note_id">
            </form>
        </div>
    </div>
</div>



<!-- Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img src="/img/icon.png" height="30" width="30" class="rounded me-2" alt="Notes">
            <strong class="me-auto">Notes</strong>
            <small>now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?=$toast_text?>
        </div>
    </div>
</div>

<?php
include_once '../static/footer.php';
if (isset($toast_text)){ ?>
    <script>
        // Show toast
        const toastLiveExample = document.getElementById('liveToast')
        const toast = new bootstrap.Toast(toastLiveExample)
        toast.show()
    </script>
<?php }
?>
<script src="../js/notes.js"></script>
</body>
</html>