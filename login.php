<?php
require 'vendor\autoload.php';
session_start();
if (isset($_SESSION['reg_success'])) {
    $reg_success = true;
    unset($_SESSION['reg_success']);
}

if (isset($_COOKIE['jwt']) && gettype(Auth::isAuthenticated($_COOKIE['jwt'])) !== 'string') {
    header('Location: /');
}

if (
    isset($_POST['email']) &&
    isset($_POST['password'])
) {
    $error_msg = null;
    $login_success = null;
    try {
        $user = User::whereEmail($_POST['email'])->first();
        if (!$user) {
            $error_msg = 'Unable to find account with the credentials you provided!';
            goto exit_try_catch;
        }

        if (password_verify($_POST['password'], $user['password'])) {
            $auth = new Auth($user['id']);
            setcookie('jwt', $auth->jwt);
            header('Location: /');
        }

        $error_msg = 'Unable to find account with the credentials you provided!';

    } catch (Illuminate\Database\QueryException $e) {
        $err_code = $e->getCode();
        $error_msg = "";

        switch ($err_code) {
            case 23000:
                $error_msg = "E-mail already exists!";
                break;
        }
    }
}
exit_try_catch:
?>
<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Login';
include("includes\header.php");
?>

<body>
    <form class="form-login" method="POST" action="">

        <?php if (isset($reg_success)) : ?>
            <div class="alert alert-success mb-4" role="alert">
                <strong>Success:</strong> Successfully registered. You many now login.
            </div>
        <?php endif; ?>
        <?php if (isset($error_msg)) : ?>
            <div class="alert alert-danger mb-4" role="alert">
                <strong>Error:</strong> <?= $error_msg; ?>
            </div>
        <?php endif; ?>

        <div class="text-center mb-4">
            <h1 class="h1 font-weight-normal">✏️ Notes ✏️</h1>
            <h5 class="h5 mt-1 mb-5 font-weight-normal">Login</h1>
        </div>

        <div class="form-label-group">
            <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
            <label for="inputEmail">Email address</label>
        </div>

        <div class="form-label-group">
            <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
            <label for="inputPassword">Password</label>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <a class="btn btn-lg btn-outline-primary btn-block my-2" href="/register.php">
            Register
        </a>
    </form>
</body>

</html>