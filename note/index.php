<?php
if (!$_COOKIE['jwt']) {
    header('Location: login.php');
};

require '..\vendor\autoload.php';

$auth = Auth::isAuthenticated($_COOKIE['jwt']);

if (gettype(Auth::isAuthenticated($_COOKIE['jwt'])) == 'string') {
    header('Location: login.php');
}
include strtolower($_SERVER['REQUEST_METHOD']) . '.php';