<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            'slug' => 'required|string|unique:brands,slug|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
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

        return redirect()->route('admin.brands')->with('status', 'Brand successfully added.');
    }
}
