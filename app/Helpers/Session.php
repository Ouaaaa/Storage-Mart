<?php
class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'use_strict_mode' => true,
                'cookie_samesite' => 'Lax'
            ]);
        }
    }
    public static function regenerate() {
        self::start();
        session_regenerate_id(true);
    }
    public static function destroy() {
        self::start();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }
}
// BUG-26 fix: removed auto-invocation of Session::start() here.
// Callers must call Session::start() explicitly to avoid unintended side effects
// when this file is included before headers have been sent or before
// the calling code is ready to start a session.
