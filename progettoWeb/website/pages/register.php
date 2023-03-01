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

// if request is post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get data from post
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $email = $_POST['email'];
    $password_not_match = false;
    if ($password != $password2) {
        $password_not_match = true;
    }
    $username_already_exists = false;

    // check if username and password are not empty
    if (!empty($username) && !empty($password) && !$password_not_match && !empty($email)){
        // get user from db
        // $user = getUser($username);
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $password = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        // check if user exists
        if ($result->num_rows > 0) {
            $username_already_exists = true;
        }else{
            // create user
            $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $password = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bind_param("sss", $username, $password, $email);
            $stmt->execute();

            // create jwt
            $jwt = createJwt((object) ['username' => $username, 'admin' => 0], $secret);
            // set jwt cookie for one month
            setcookie('jwt', $jwt, time() + 3600 * 24 * 30 , '/');
            // redirect to home
            header('location: /index.php');
            exit;
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
    <form action="/pages/register.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username">
        <label for="email">email</label>
         <input type="text" name="email" id="email">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <label for="password2">Repeat password</label>
        <input type="password" name="password2" id="password2">
        <input type="submit" value="Register">
    </form>

    <?php
    if ($password_not_match) {
        echo '<p>passwords do not match</p>';
    }
    if ($username_already_exists) {
        echo '<p>username already exists</p>';
    }
    ?>

    <h2>ALready registered? <a href="/pages/login.php">log in there</a> </h2>

</body>
</html>