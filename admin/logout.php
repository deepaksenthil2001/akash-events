<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Auth;
use App\Helpers\View;

Auth::logout();
header("Location: " . View::url('/admin/login.php'));
exit;
