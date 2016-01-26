<?php

Class Coupon extends AppModel
{
    public $name = "Coupon";

    public function generate_code($length = 8)
    {
        $c = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $cl = strlen($c);
        $check = true;

        while ($check) {
            $code = '';

            for ($i = 0; $i < $length; $i++) {
                $code .= $c[rand(0, $cl - 1)];
            }

            $check = $this->is_code_used($code);
        }

        return $code;
    }

    public function is_code_used($code)
    {
        $searches = $this->find('first', ['conditions' => ['code' => $code]]);
        if (count($searches) == 0) {
            return false;
        }
        return true;
    }

    public function count_used_code($id)
    {
        App::import('model','BookingCoupon');
        $booking_coupon = new BookingCoupon();
        $count = $booking_coupon->find('count', ['conditions' => ['coupon_id' => $id]]);
        return $count;
    }

    public function is_code_valid($code)
    {
        $searches = $this->find('first', ['conditions' => ['code' => $code, 'is_active' => 1]]);
        if (count($searches) == 0) {
            return 0;
        }
        $count = $this->count_used_code($searches['Coupon']['id']);
        if ($searches['Coupon']['max_usage'] <= $count) {
            return 1;
        }
        return true;
    }
}

?>
