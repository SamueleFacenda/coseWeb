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
    if (isset($_GET['show']))) {
        $whitelist = ['home', 'notes', 'shared', 'logout'];
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
        header('Location: index.php');
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
        <li><a href="?show=home">Home</a></li>
        <li><a href="?show=notes">Notes</a></li>
        <li><a href="?show=shared">Shared</a></li>

        <?php
            if($username == null){
                ?>
                <li style="float: right"><a href="pages/login.php">Login</a></li>
                <li style="float: right"><a href="pages/register.php">Register</a></li>
                <?php
            } else {
                ?>
                <li style="float: right"><a href="?show=logout">Logout</a></li>
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
        // path traversal ovunque
        include_once 'pages/' . $show . '.php';
    ?>

</body>
</html>