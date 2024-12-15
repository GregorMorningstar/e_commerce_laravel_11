<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
}
