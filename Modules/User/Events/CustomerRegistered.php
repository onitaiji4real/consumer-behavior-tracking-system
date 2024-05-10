<?php

namespace Modules\User\Events;

use Modules\User\Entities\User;
use Illuminate\Queue\SerializesModels;

class CustomerRegistered
{
    use SerializesModels;

    /**
     * The instance of user.
     *
     * @var \Modules\User\Entities\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param \Modules\User\Entities\User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $default_coupon = \DB::table('coupons')->where('code','DISCOUNT_NEW_MEMBERS')->first();
        if($default_coupon->is_active == 1)
        {
            unset($default_coupon->id);
            $default_coupon->code =  substr( strtoupper(md5(uniqid())),0,10) ;
            $default_coupon->used = 0;

            $r = [];
            foreach ($default_coupon as $cl => $v) {
                $r[$cl] = $v;
            }
            $coupon_id = \DB::table('coupons')->insertGetId($r);

            \DB::table('user_coupons')->insert([
                'user_id' => $user->id,
                'coupon_id' => $coupon_id,
            ]);
        }


    }
}
