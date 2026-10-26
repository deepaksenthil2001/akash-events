<?php
namespace App\Core;

class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public static function token(): string
    {
        Session::start();
        $token = Session::get(self::SESSION_KEY);
        if (!$token || !is_string($token)) {
            $token = bin2hex(random_bytes(32));
            Session::set(self::SESSION_KEY, $token);
        }
        return $token;
    }

    public static function field(): string
    {
        $token = self::token();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function validate(?string $submittedToken): bool
    {
        Session::start();
        $known = Session::get(self::SESSION_KEY);
        if (!$known || !$submittedToken) {
            return false;
        }
        return hash_equals($known, $submittedToken);
    }
}
