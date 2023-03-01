<?php
    // define jwt lifetime of one month
    $maxAge = 3600 * 24 * 30;
    require_once 'utils/jwt.php';

    // secret get env
    $secret = getenv('JWT_SECRET');
    // default secret
    if ($secret == null){
        $secret = 'segretissimo!!';
    }


    $username = null;
    // set username if jwt is set
    if(isset($_COOKIE['jwt']) && verifyJwt($_COOKIE['jwt'], $secret, $maxAge)){
        $jwt = $_COOKIE['jwt'];
        $payload = getJwtDat($jwt);
        $username = $payload->username;
    }

    $show = 'home';
    // check if show is set
    if (isset($_GET['show'])) {
        $whitelist = ['home', 'notes', 'shared', 'logout', 'about'];
        // strict check if show is in whitelist
        if (in_array($_GET['show'], $whitelist, true)) {
            $show = $_GET['show'];
        }
    }

    // check if fragment is logout
    if ($show === 'logout') {
        // delete jwt
        setcookie('jwt', '', time() - 3600, '/');
        $username = null;
        // redirect to home
        header('location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes by Samuele Facenda</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark fixed-top" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Notes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="?show=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?show=notes">Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?show=shared">Shared</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link disabled" href="?show=about">About</a>
                    </li>
                    <?php
                    if($username == null){
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/register.php">Register</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?show=logout">Logout</a>
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
    <!-- import the right page -->
    <?php
        // path traversal ovunque
        // forse meno con la whitelist
        include_once 'pages/' . $show . '.php';
    ?>
</body>
</html>