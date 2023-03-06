

<!DOCTYPE html>
<html lang="en">
<?php
    // import head
    include_once 'static/head.php';
    echo '<body>';
    include_once 'utils/jwt.php';
    include_once 'utils/username.php';
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
                    <a href="/pages/register.php" class="btn btn-primary my-2">Sign up!</a>
                    <a href="/pages/login.php" class="btn btn-secondary my-2">Sign in</a>
                </p>
            </div>
        </section>
    </main>


    <!-- Hidden Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="img/icon.png" height="30" width="30" class="rounded me-2" alt="Notes">
                <strong class="me-auto">Notes</strong>
                <small>now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Hello <?= /** @noinspection PhpUndefinedVariableInspection */$username ?>!,
                <!-- XSS Ocio -->
                User <?= htmlspecialchars($_GET['toast'])?> successfully!
            </div>
        </div>
    </div>
<?php
    include_once 'static/footer.php';
    if (isset($_GET['toast'])){ ?>
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