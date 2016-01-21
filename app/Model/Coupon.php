<?php

Class Coupon extends AppModel
{
    public $name = "Coupon";

    public static function generate_code($length = 8)
    {
        $c = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $cl = strlen($c);
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $c[rand(0, $cl - 1)];
        }
        return $code;
    }

}

?>
