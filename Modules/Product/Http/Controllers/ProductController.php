<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Review\Entities\Review;
use Modules\Product\Entities\Product;
use Modules\Product\Events\ProductViewed;
use Modules\Product\Filters\ProductFilter;
use Modules\Product\Http\Middleware\SetProductSortOption;
use DB;

class ProductController extends Controller
{
    use ProductSearch;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(SetProductSortOption::class)->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Modules\Product\Entities\Product $model
     * @param \Modules\Product\Filters\ProductFilter $productFilter
     * @return \Illuminate\Http\Response
     */
    public function index(Product $model, ProductFilter $productFilter)
    {
        
        if (request()->expectsJson()) {
            return $this->searchProducts($model, $productFilter);
        }

        return view('public.products.index');
    }

    /**
     * Show the specified resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {

        $product = Product::findBySlug($slug);
       
        $relatedProducts = $product->relatedProducts()->forCard()->get();
        $upSellProducts = $product->upSellProducts()->forCard()->get();        $review = $this->getReviewData($product);

        event(new ProductViewed($product));
        if(auth()->user()){

            $this->saveProductsViews($product);
        }
        
        return view('public.products.show', compact('product', 'relatedProducts', 'upSellProducts', 'review'));
    }

    public function saveProductsViews($product){
       
        $productData = DB::table('user_product_views')->where([['product_id' ,'=' ,$product->id],['user_id' ,'=' ,auth()->user()->id]])->first();

        if($productData !== null){
            $productData->views =  $productData->views + 1;
            $productData = DB::table('user_product_views')->where([['product_id' ,'=' ,$product->id],['user_id' ,'=' ,auth()->user()->id]])->update(array('views'=> $productData->views));
        }else{
             DB::table('user_product_views')->insert([
                'user_id' => auth()->user()->id,
                'product_id' => $product->id,
                'views' => 1
            ]);
        }

    }
    private function getReviewData(Product $product)
    {
        if (! setting('reviews_enabled')) {
            return;
        }

        return Review::countAndAvgRating($product);
    }

    
}
