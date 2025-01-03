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
        //dd($items);
        return view('cart',compact('items'));

     }

    public function add_to_cart(Request $request)
    {
        $quantity_prodact = Product::findOrFail($request->id);
        // Validate the price
        if (!is_numeric($request->price) || $request->price <= 0) {
            throw new \InvalidArgumentException('Please supply a valid price.');
        }

        // Sprawdź dostępność produktu (opcjonalne)
        if ($request->quantity > $quantity_prodact->quantity) {
            return redirect()->back()->with('error', 'Nie można dodać więcej produktów niż jest dostępnych.');
        }

        // Pobierz obraz produktu
        $product = $quantity_prodact; // Już załadowany produkt.
        $image = $quantity_prodact->image; // dodano z product

        // Dodaj do koszyka
        $cart = Cart::instance('cart')
            ->add([
                'id' => $request->id,
                'name' => $request->name,
                'qty' => $request->quantity,
                'price' => $request->price,
                'options' => [
                    'image' => $image, // Przekazanie obrazu produktu.
                ],
            ])->associate('App\Models\Product');

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

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function clear_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();

    }
}
