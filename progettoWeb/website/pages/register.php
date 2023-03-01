<?php
require_once '../utils/jwt.php';

$servername = "127.0.0.1";
$username = "facenda5inc2022";
$password = "";
$dbname = "my_facenda5inc2022";
/*
$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
*/

// get secret from env
$secret = getenv('JWT_SECRET');
if ($secret == null){
    $secret = 'segretissimo!!';
}
$password_not_match = false;
$username_already_exists = false;

// if request is post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get data from post
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $email = $_POST['email'];

    if ($password != $password2) {
        $password_not_match = true;
    }


    // check if username and password are not empty
    if (!empty($username) && !empty($password) && !$password_not_match && !empty($email)){
        // get user from db
        // $user = getUser($username);
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
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
    <title>register</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
<body>
    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>
                                    <form class="mx-1 mx-md-4" action="/pages/register.php" method="post">
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input name="username" type="text" id="form3Example1c"
                                                       class="form-control <?php if($username_already_exists) echo 'is-invalid'; ?>" required/>
                                                <label class="form-label" for="form3Example1c">Your Name</label>
                                                <div class="invalid-feedback">Username already exists</div>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input name="email" type="email" id="form3Example3c" class="form-control" required/>
                                                <label class="form-label" for="form3Example3c">Your Email</label>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input name="password" type="password" id="form3Example4c"
                                                       class="form-control <?php if($password_not_match) echo 'is-invalid'; ?>" required/>
                                                <label class="form-label" for="form3Example4c">Password</label>
                                                <div class="invalid-feedback">Passwords does not match</div>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input name="password2" type="password" id="form3Example4cd"
                                                       class="form-control <?php if($password_not_match) echo 'is-invalid'; ?>" required/>
                                                <label class="form-label" for="form3Example4cd">Repeat your password</label>
                                            </div>
                                        </div>
                                        <div class="form-check d-flex justify-content-center mb-5">
                                            <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c" />
                                            <label class="form-check-label" for="form2Example3">
                                                I agree all statements in <a href="#!">Terms of service</a>
                                            </label>
                                        </div>
                                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                            <button type="submit" class="btn btn-primary btn-lg">Register</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                                    <img src="https://clickup.com/blog/wp-content/uploads/2020/01/note-taking.png" class="img-fluid" alt="Sample image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>