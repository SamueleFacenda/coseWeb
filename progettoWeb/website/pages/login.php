<?php
require_once __DIR__ . '/../utils/jwt.php';

// get secret from env
$secret = getenv('JWT_SECRET');
if ($secret == null){
    $secret = 'segretissimo!!';
}

// if request is post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get data from post
    $username = $_POST['username'];
    $password = $_POST['password'];

    // check if username and password are not empty
    if ($username != null && $password != null) {
        // get user from db
        // $user = getUser($username);
        $user = array();
        $user['username'] = 'samuele';
        $user['password'] = 'ciao';

        // check if user exists
        if ($user != null) {
            // check if password is correct
            //if (password_verify($password, $user['password'])) {
            if($password == $user['password']){
                // create jwt
                $jwt = createJwt((object) ['username' => $username], $secret);
                // set jwt cookie for one month
                setcookie('jwt', $jwt, time() + 3600 * 24 * 30 , '/');
                // redirect to home
                header('Location: /#home');
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
    <form action="login.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <input type="submit" value="Login">
    </form>

</body>
</html>