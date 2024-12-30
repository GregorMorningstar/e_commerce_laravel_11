<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
class CartController extends Controller
{
    public function index()
     {
        $items = Cart::instance('cart')->content();
        return view('cart',compact('items'));
     }

    public function add_to_cart(Request $request)
    {

        $quantity_prodact = Product::findOrFail($request->id);

        // Validate the price
        if (!is_numeric($request->price) || $request->price <= 0) {
            throw new \InvalidArgumentException('Please supply a valid price.');
        }
        //dd($quantity_prodact);
        $cart = Cart::instance('cart')
            ->add($request->id, $request->name, $request->quantity, $request->price)
            ->associate('App\Models\Product');
        return redirect()->route('cart.index')->with('success', "Dodano produkt do koszyka");

    }

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
}
    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
    }
}
