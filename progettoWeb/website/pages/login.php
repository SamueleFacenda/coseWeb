<?php
require_once '../utils/jwt.php';

$servername = "127.0.0.1";
$username = "facenda5inc2022";
$password = "";
$dbname = "my_facenda5inc2022";
$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}


// get secret from env
$secret = getenv('JWT_SECRET');
if ($secret == null){
    $secret = 'segretissimo!!';
}
$wrong_credentials = false;

// if request is post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get data from post
    $username = $_POST['username'];
    $password = $_POST['password'];

    // check if username and password are not empty
    if ($username != null && $password != null) {
        // get user from db
        // $user = getUser($username);
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // check if user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user_password = $user['password'];
            var_dump($user_password);
            var_dump($password);
            // check if password is correct
            if (!password_verify($password, $user_password)) {
                // wrong password
                $wrong_credentials = true;
            }else{
                // create jwt
                $jwt = createJwt((object) ['username' => $username, 'admin' => $user['is_admin'] ? 1:0], $secret);
                // set jwt cookie for one month
                setcookie('jwt', $jwt, time() + 3600 * 24 * 30 , '/');
                // redirect to home
                header('location: /index.php');
                exit;
            }
        }else{
            // username not found
            $wrong_credentials = true;
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
    <form action="/pages/login.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <input type="submit" value="Login">
    </form>

    <?php if ($wrong_credentials): ?>
        <h2>Wrong credentials</h2>
    <?php endif; ?>

    <h2> You're not registered yet? <a href="/pages/register.php">Sign up here</a> </h2>

</body>
</html>