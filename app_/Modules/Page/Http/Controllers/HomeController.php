<?php

namespace Modules\Page\Http\Controllers;

class HomeController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

        return view('public.home.index');
    }
}
