<?php

class Logout
{
    public function destroy_all()
    {
        $past = time() - 3600;
        foreach ($_COOKIE as $key => $value) {
            setcookie($key, $value, $past, '/');
        }
    }
}
