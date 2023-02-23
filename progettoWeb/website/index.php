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

    $url = $_SERVER['REQUEST_URI'];
    // set fragment, if any, else at home
    $fragment = parse_url($url, PHP_URL_FRAGMENT);
    if ($fragment == null || $fragment == "") {
        $fragment = 'home';
    }

    // check if fragment is logout
    if ($fragment === 'logout') {
        // delete jwt
        setcookie('jwt', '', time() - 3600, '/');
        // redirect to home
        header('Location: /#home');
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
    <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- https://material-components.github.io/material-components-web-catalog/#/-->
</head>
<body>
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#notes">Notes</a></li>
        <li><a href="#shared">Shared</a></li>

        <?php
            if($username == null){
                ?>
                <li style="float: right"><a href="pages/login.php">Login</a></li>
                <li style="float: right"><a href="pages/register.php">Register</a></li>
                <?php
            } else {
                ?>
                <li style="float: right"><a href="#logout">Logout</a></li>
                <li style="float: right">
                    <a href="pages/profile.php">
                    <!-- google icons person -->
                    <i class="material-icons">person</i>
                    <?php echo $username; ?>
                    </a>
                </li>
                <?php    
            }
        ?>
        <li style="float:right"><a class="active" href="#about">About</a></li>
      </ul>
    <!-- import the right page -->
    <?php
        include_once 'pages/' . $fragment . '.php';
    ?>

</body>
</html>