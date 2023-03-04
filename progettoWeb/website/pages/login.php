<?php
require_once '../utils/jwt.php';

require_once '../utils/connection.php';

// get secret from env
$secret = getenv('JWT_SECRET');
if ($secret == null){
    $secret = 'segretissimo!!';
}
$wrong_credentials = false;

// if request is post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get data from post
    $email = $_POST['email'];
    $password = $_POST['password'];

    // check if username and password are not empty
    if (!empty($email) && !empty($password)){
        // get user from db
        connect();

        $user = getUser($email);
        if ($user == null) {
            // username not found
            $wrong_credentials = true;
        }else{

            $user_password = $user['password'];
            // check if password is correct
            if (!password_verify($password, $user_password)) {
                // wrong password
                $wrong_credentials = true;
            }else{
                // create jwt
                $jwt = createJwt((object) ['username' => $user['username'], 'email' => $user['email'], 'admin' => $user['is_admin'] ? 1:0], $secret);
                // set jwt cookie for one month
                setcookie('jwt', $jwt, time() + 3600 * 24 * 30 , '/');
                // redirect to home
                header('location: /index.php?toast=""');
                exit;
            }
        }
    }
}
?>


<?php
    require_once '../static/head.php';
    echo '<body>';
    include_once '../utils/jwt.php';
    include_once '../utils/username.php';
    require_once '../static/navbar.php';
?>
    <form action="/pages/login.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <input type="submit" value="Login">
    </form>

    <?php if ($wrong_credentials): ?>
        <h2>Wrong credentials</h2>
    <?php endif; ?>

    <h2> You're not registered yet? <a href="register.php">Sign up here</a> </h2>

<?php
    require_once '../static/footer.php';
?>
</body>
</html>