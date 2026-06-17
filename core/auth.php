<?php
class auth
{
    private static $secret = "my_secret_key_123";
    public static function setUser($key, $user, $useCookies = false)
    {
        $_SESSION[$key] = $user;
        if ($useCookies) {
            $user_id = $user['id'];
            // tạo chữ ký (sig)
            $sig = hash_hmac('sha256', $user_id, self::$secret);
            setcookie($key, $user_id, time() + 86400 * 7, '/');
            setcookie($key . '_sig', $sig, time() + 86400 * 7, '/');
        }
    }
    public static function loggedIn($key){
        if(isset($_SESSION[$key])){
            return true;
        }
        if(isset($_COOKIE[$key]) && isset($_COOKIE[$key . '_sig'])){
            $user_id=$_COOKIE[$key];
             $sig = $_COOKIE[$key . '_sig'];
             $valid_sig =hash_hmac('sha256',$user_id,self::$secret);
             if(hash_equals($valid_sig, $sig)){
                return true;
             }
        }

    }
    public static function getUser($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function logout($key)
    {
        unset($_SESSION[$key]);

        // xoá cookie
        setcookie($key, '', time() - 3600, '/');
        setcookie($key . '_sig', '', time() - 3600, '/');
    }

    public static function handleUser()
    {
        $users_id= Auth::getUser('user')['MaTaiKhoan'];
        return $users_id;
    }


}
