

<!DOCTYPE html>
<html lang="en">
<?php
    // import head
    include_once 'static/head.php';
?>
<body>
<?php
    // import navbar
    include_once 'static/navbar.php';
?>
    <main role="main" class="container">
        <section class="text-center">
            <div class="container">
                <h1 class="display-3">Take Notes, Start Here</h1>
                <p class="lead text-muted">
                    Take note of what you do, analyze them. Keeping your life organized is easier when you know what
                    you have done!
                </p>
                <p>
                    <a href="index.php?show=register" class="btn btn-primary my-2">Sign up!</a>
                    <a href="index.php?show=login" class="btn btn-secondary my-2">Sign in</a>
                </p>
            </div>
        </section>
    </main>

<?php
if (isset($_POST['toast'])){ ?>
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img src="..." class="rounded mr-2" alt="...">
            <strong class="mr-auto">Notes By Samuele</strong>
            <small class="text-muted">11 mins ago</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            New user registered
        </div>
    </div>
    <?php } ?>

</body>
</html>