<?php
session_start();
require 'vendor\autoload.php';
if (
    isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['confirm-password'])
) {
    $result = null;
    $error_msg = null;
    $registration_success = null;
    try {
        $result = User::insert([
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'created_at' =>  date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
    } catch (Illuminate\Database\QueryException $e) {
        $err_code = $e->getCode();
        $error_msg = "";

        switch ($err_code) {
            case 23000:
                $error_msg = "E-mail already exists!";
                break;
        }
    }

    if ($result && !$error_msg) {
        $_SESSION['reg_success'] = true;
        header("Location: /login.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Login';
include("includes\header.php");
?>

<body>
    <form id="register_form" method="post" action="" class="form-login">

        <?php if (isset($error_msg)) : ?>
            <div class="alert alert-danger mb-4" role="alert">
                <strong>Error:</strong> <?= $error_msg; ?>
            </div>
        <?php endif; ?>

        <div class="text-center mb-4">
            <h1 class="h1 font-weight-normal">✏️ Notes ✏️</h1>
            <h5 class="h5 mt-1 mb-5 font-weight-normal">Register</h1>
        </div>

        <div class="form-label-group">
            <input name="email" type="email" id="input-email" class="form-control" placeholder="Email address" required autofocus>
            <label for="input-email">Email address</label>
        </div>

        <div class="form-label-group">
            <input name="password" type="password" id="input-password" class="form-control" placeholder="Password" required>
            <label for="input-password">Password</label>
        </div>

        <div class="form-label-group">
            <input name="confirm-password" type="password" id="input-confirm-password" class="form-control" placeholder="Confirm Password" required>
            <label for="input-confirm-password">Confirm Password</label>
        </div>


        <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
        <a class="btn btn-lg btn-outline-primary btn-block my-2" href="/login.php">
            Back to Login
        </a>
    </form>

    <script>
        $('#register_form').submit(function() {
            const email = $('#input-password').val().trim();
            const password = $('#input-password').val().trim();
            const confirm_password = $('#input-confirm-password').val().trim();

            if (!email || !password || !confirm_password) {
                alert('Please fill in all the forms');
                return false;
            }

            if (password !== confirm_password) {
                alert('Password has to match');
                return false;
            }

            return true;
        });
    </script>

</body>

</html>