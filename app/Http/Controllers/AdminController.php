<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index'); // Dla AdminController
    }
    public function brands()
    {
        $brands = Brand::paginate(10);
        return view('admin.brands', compact('brands'));
    }
    public function brand_add()
    {
        return view('admin.add_brand');
    }
    public function brand_store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . "." . $file_extension;

            $image->storeAs('uploads/brand', $file_name, 'public');
            //dd($image->storeAs('uploads/brand', $file_name, 'public'));  // Sprawdź, czy nie zwraca błędu

            $brand->image = $file_name;
        }

        $brand->save();

        return redirect()->route('admin.brands');
    }
    public function brand_edit($id)
    {

        $brand = Brand::find($id);

        return view('admin.edit_brand', compact('brand'));
    }
    public function brand_update(Request $request)
    {
        // Walidacja danych
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:brands,slug,' . $request->id,
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Znalezienie istniejącego rekordu
        $brand = Brand::findOrFail($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        // Obsługa przesłanego obrazu
        if ($request->hasFile('image')) {
            // Usunięcie starego obrazu
            if (File::exists(public_path('uploads/brand/' . $brand->image))) {
                File::delete(public_path('uploads/brand/' . $brand->image));
            }

            // Przechowywanie nowego obrazu
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;

            $image->storeAs('uploads/brand', $file_name, 'public');
            $brand->image = $file_name;
        }

        // Zapisanie zmian w bazie danych
        $brand->save();

        // Przekierowanie do listy marek z komunikatem
        return redirect()->route('admin.brands')->with('success', 'Marka została zaktualizowana pomyślnie.');
    }
    public function brand_delete($id)
    {
        $brand = Brand::findOrFail($id);
        //usuwanie pliku przy delete marki
        try {
            if (File::exists(public_path('uploads/brand/' . $brand->image))) {
                File::delete(public_path('uploads/brand/' . $brand->image));
            }
            $brand->delete();
            return redirect()->route('admin.brands')->with('success', 'Marka została usunięta.');
        } catch (\Exception $e) {
            return redirect()->route('admin.brands')->with('error', 'Wystąpił błąd: ' . $e->getMessage());
        }

    }
    //CATEGORY
    public function categories()
    {
        $categories = Category::paginate(10);
     //   dd($categories);
        return view('admin.categories',compact('categories'));
    }
    public function category_add(){
        return view('admin.add_category');
    }
    public function category_store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . "." . $file_extension;

            $image->storeAs('uploads/category', $file_name, 'public');
            //dd($image->storeAs('uploads/brand', $file_name, 'public'));  // Sprawdź, czy nie zwraca błędu

            $category->image = $file_name;
        }
//dd($category);
        $category->save();

        return redirect()->route('admin.categories');
    }
    public function category_edit($id)
    {

        $category = Category::findOrFail($id);

        return view('admin.edit_category', compact('category'));
    }
    public function category_update(Request $request)
    {
        // Walidacja danych
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug,' . $request->id,
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Znalezienie istniejącego rekordu
        $category = Category::findOrFail($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        // Obsługa przesłanego obrazu
        if ($request->hasFile('image')) {
            // Usunięcie starego obrazu
            if (File::exists(public_path('uploads/category/' . $category->image))) {
                File::delete(public_path('uploads/category/' . $category->image));
            }

            // Przechowywanie nowego obrazu
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;

            $image->storeAs('uploads/category', $file_name, 'public');
            $category->image = $file_name;
        }
//dd($category);
        // Zapisanie zmian w bazie danych
        $category->save();

        // Przekierowanie do listy marek z komunikatem
        return redirect()->route('admin.categories')->with('success', 'Kategoria została zaktualizowana pomyślnie.');
    }
    public function category_delete($id)
    {
        $category = Category::findOrFail($id);
        //usuwanie pliku przy delete marki
        try {
            if (File::exists(public_path('uploads/category/' . $category->image))) {
                File::delete(public_path('uploads/category/' . $category->image));
            }
            $category->delete();
            return redirect()->route('admin.categories')->with('success', 'kategoria została usunięta.');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories')->with('error', 'Wystąpił błąd: ' . $e->getMessage());
        }

    }
//produkty
    public function products()
    {

        $products = Product::paginate(10);
        return view('admin.products',compact('products'));
}
    public function product_store(Request $request)
    {
        // Walidacja danych
        $test = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'regular_price' => 'required|numeric|min:0|max:999999.99',
            'sale_price' => 'nullable|numeric|min:0|max:999999.99|lt:regular_price',
            'SKU' => 'required',
            'stock_status' => 'required|in:instock,outofstock',
            'featured' => 'required|boolean',
            'quantity' => 'nullable|integer|min:0',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
        ]);
        // Tworzenie nowego produktu
        $product = new Product();
        $product->fill($request->except(['image', 'images'])); // Upewnij się, że tylko dozwolone pola są masowo przypisywane
//dd($product); literowka w fileable i dlatego mi nie zaposywalo
        // Obsługa przesłanego głównego obrazu
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('uploads/products', $fileName, 'public');
            $product->image = $path;
        }

        // Obsługa galerii obrazów
        if ($request->hasFile('images')) {
            $galleryPaths = [];
            foreach ($request->file('images') as $image) {
                $fileName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('uploads/products/gallery', $fileName, 'public');
                $galleryPaths[] = $path;
            }
            $product->images = json_encode($galleryPaths); // Zapisujemy jako JSON
        }
        // Zapis do bazy
        $product->save();

        // Przekierowanie z komunikatem o sukcesie
        return redirect()->route('admin.products')->with('success', 'Produkt został zapisany!');
    }
    public function product_add()
    {
        $categories = Category::all(); // Or any other logic to get categories
        $brands = Brand::all(); // If you want to pass brands as well
        return view('admin.add_products',compact('categories', 'brands'));
}

    public function product_edit($id)
    {
        $categories = Category::all(); // Or any other logic to get categories
        $brands = Brand::all(); // If you want to pass brands as well
        $product_edit = Product::findOrFail($id);
return view('admin.edit_products',compact('product_edit','categories', 'brands'));
    }

    public function products_update(Request $request)
    {

        // Walidacja danych
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'regular_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'SKU' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'stock_status' => 'required|string',
            'featured' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Walidacja obrazu
            'images' => 'nullable|array', // Tablica z dodatkowymi obrazkami
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Walidacja obrazków w galerii
        ]);

$id = $request->input('id');
        // Znalezienie produktu w bazie
        $product = Product::findOrFail($id);
//dd($product."<br>".$id);
        // Aktualizacja danych produktu
        $product->name = $validatedData['name'];
        $product->slug = $validatedData['slug'];
        $product->category_id = $validatedData['category_id'];
        $product->brand_id = $validatedData['brand_id'];
        $product->short_description = $validatedData['short_description'];
        $product->description = $validatedData['description'];
        $product->regular_price = $validatedData['regular_price'];
        $product->sale_price = $validatedData['sale_price'];
        $product->SKU = $validatedData['SKU'];
        $product->quantity = $validatedData['quantity'];
        $product->stock_status = $validatedData['stock_status'];
        $product->featured = $validatedData['featured'];

        // Obsługa głównego obrazu produktu
        if ($request->hasFile('image')) {
            // Usuń stary obrazek (jeśli istnieje)
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }

            // Zapisz nowy obrazek
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        // Obsługa obrazków w galerii
        if ($request->hasFile('images')) {
            // Usuń stare obrazki z galerii
            if ($product->images) {
                $oldImages = json_decode($product->images);
                foreach ($oldImages as $image) {
                    Storage::delete('public/' . $image);
                }
            }

            // Zapisz nowe obrazki
            $galleryImages = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products/gallery', 'public');
                $galleryImages[] = $imagePath;
            }
            $product->images = json_encode($galleryImages); // Zapisać obrazy jako tablicę JSON
        }

        // Zapisz zaktualizowany produkt
        $product->save();

        // Przekierowanie z komunikatem o sukcesie
        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function products_delete($id)
    {
        $delete_products = Product::findOrFail($id);
        //usuwanie pliku przy delete marki
        try {
            if (File::exists(public_path('uploads/products/' . $delete_products->image))) {
                File::delete(public_path('uploads/products/' . $delete_products->image));
            }
            else if (File::exists(public_path('uploads/products/gallery' . $delete_products->image))) {
                File::delete(public_path('uploads/products//gallery' . $delete_products->image));
            }


            $delete_products->delete();
            return redirect()->route('admin.products')->with('success', 'Produkt została usunięta.');
        } catch (\Exception $e) {
            return redirect()->route('admin.products')->with('error', 'Wystąpił błąd: ' . $e->getMessage());
        }
    }

}
