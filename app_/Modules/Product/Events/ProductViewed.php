<?php

namespace Modules\Product\Events;

use Illuminate\Queue\SerializesModels;

class ProductViewed
{
    use SerializesModels;

    /**
     * The product entity.
     *
     * @var \Modules\Product\Entities\Product
     */
    public $product;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($product)
    {
        $this->product = $product;


        if(!empty(auth()->user())){
            $user_id = auth()->user()->id;
            $pending_c = \DB::table('user_coupons')
            ->join('coupons', 'coupons.id', '=', 'user_coupons.coupon_id')
            ->where([['user_coupons.user_id',$user_id],['coupons.used',0]])
            ->select('*')->first();
            if($pending_c != null){
                session()->flash('success',"You have won Coupon! `$pending_c->code`");
            }
        }




        if(auth()->user() == null){

            $pid = $product->id;
            $key = 'uih';
            $pv = [];

            if(isset($_COOKIE[$key])){
                $pv = explode(',',$_COOKIE[$key]);
            }

            if(in_array($pid,$pv)  == false)
            {
                array_push($pv,$pid);
                setcookie($key,implode(',',$pv),time()+30*24*2600,'/');
            }


        }



    }
}
