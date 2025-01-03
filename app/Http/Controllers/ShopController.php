<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->query('size', 12);
        $order = (int) $request->query('order', -1);

        $o_column = 'id';
        $o_order = 'DESC';
        $f_brands = $request->query('brands');
        switch ($order) {
            case 1:
                $o_column = 'created_at';
                $o_order = 'DESC';
                break;
            case 2:
                $o_column = 'created_at';
                $o_order = 'ASC';
                break;
            case 3:
                $o_column = 'sale_price';
                $o_order = 'DESC';
                break;
            case 4:
                $o_column = 'sale_price';
                $o_order = 'ASC';
                break;
        }

        $brands = Brand::orderBy('name','ASC')->get();
        $categories = Category::orderBy('name','ASC')->get();
        $products = Product::where(function ($query) use ($f_brands) {
            //jezeli nie dodamy nic do filtra nie wykona sie
            if (!empty($f_brands)) {
                // Rozdzielenie f_brands na tablicę i sprawdzenie wartości
                $brandsArray = explode(',', $f_brands);

                // Filtracja produktów tylko dla wybranych marek
                $query->whereIn('brand_id', $brandsArray);
            }
        })
            ->orderBy($o_column, $o_order)
            ->paginate($size)
            ->appends(['size' => $size, 'order' => $order]);

        return view('shop.index', compact('products', 'size', 'order','brands','f_brands','categories'));
    }


    public function details_product($id)
    {
        $quantity = Product::findOrFail($id);
        $product = Product::with(['category', 'brand'])->findOrFail($id);
        //dd($product);
        return view('shop.details_product',compact('product','quantity'));
    }
}
