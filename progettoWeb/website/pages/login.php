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

        $user = get_user($email);
        var_dump($user);
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
                if(!$user['is_email_verified']){
                    // redirect to email verification page
                    header('location: /pages/email.php?email=' . urlencode($email));
                    exit;
                }

                // create jwt
                $jwt = createJwt((object) ['username' => $user['username'], 'email' => $user['email'], 'admin' => $user['is_admin'] ? 1:0], $secret);
                // set jwt cookie for one month
                setcookie('jwt', $jwt, time() + 3600 * 24 * 30 , '/', httponly: true);
                // redirect to home
                header('location: /index.php?toast=logged');
                exit;
            }
        }
    }
}
?>


<?php
    require_once '../static/head.php';
    echo '<body class="d-flex flex-column min-vh-100">';
    include_once '../utils/username.php';
    require_once '../static/navbar.php';
?>

<section class="py-5" style="background-color: #eee;">
    <div class="container h-auto">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-10 col-lg-8 col-xl-6">
                <div class="card text-black" style="border-radius: 25px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-11 col-lg-10 col-xl-8">
                                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Log in</p>
                                <form class="mx-1 mx-md-4" action="/pages/login.php" method="post">
                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input name="email" type="email" id="form3Example3c" class="form-control" required
                                            value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email']) ?>" />
                                            <label class="form-label" for="form3Example3c">Your Email</label>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input name="password" type="password" id="form3Example4c"
                                                   class="form-control <?php if($wrong_credentials) echo 'is-invalid' ?> " required/>
                                            <label class="form-label" for="form3Example4c">Password</label>
                                            <div class="invalid-feedback">Wrong credentials!</div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <button type="submit" class="btn btn-primary btn-lg">Log in</button>
                                    </div>
                                    <div class="text-center">
                                        <p>Not registered yet? <a href="/pages/register.php">Sign up</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
    require_once '../static/footer.php';
?>
</body>
</html>