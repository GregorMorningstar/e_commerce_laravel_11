<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class ShopController extends Controller
{
    public function index()
    {

        $products = Product::paginate(12);
    //    dd($products);
        return view('shop.index',compact('products'));
    }

    public function details_product($id)
    {
        $quantity = Product::findOrFail($id);
        $product = Product::with(['category', 'brand'])->findOrFail($id);
        //dd($product);
        return view('shop.details_product',compact('product','quantity'));
    }
}
