<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark sticky-top" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Notes</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/pages/notes.php">Notes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/pages/shared.php">Shared</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link disabled" href="/pages/about.php">About</a>
                </li>
                <?php
                global $username;
                if(isset($username)){
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/register.php">Register</a>
                    </li>
                    <?php
                } else {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/logout.php">Logout</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/profile.php">
                            <?= $username ?>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>

        </div>
    </div>
</nav>