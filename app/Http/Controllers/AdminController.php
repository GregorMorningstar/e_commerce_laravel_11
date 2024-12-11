<?php

namespace App\Http\Controllers;

use App\Models\Brand;
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
}
