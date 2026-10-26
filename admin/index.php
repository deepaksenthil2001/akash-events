<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Auth;
use App\Helpers\View;

if (Auth::check()) {
    header("Location: " . View::url('/admin/dashboard.php'));
} else {
    header("Location: " . View::url('/admin/login.php'));
}
exit;
