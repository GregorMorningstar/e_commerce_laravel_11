<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; // Do wysyłania zapytań HTTP

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Tworzenie losowego obrazu
        $image = $this->generateRandomImage();

        // Przechowywanie obrazu w odpowiednim katalogu
        $fileName = time() . '.jpg';
        $path = 'uploads/products/' . $fileName;
        Storage::disk('public')->put($path, $image);
        // Losowanie kategorii i marki
        $category = Category::inRandomOrder()->first();  // Losowanie kategorii
        $brand = Brand::inRandomOrder()->first();        // Losowanie marki
        return [
            'name' => $this->faker->word(),
            'slug' => Str::slug($this->faker->word()),
            'short_description' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'regular_price' => $this->faker->numberBetween(10, 100),
            'sale_price' => $this->faker->numberBetween(5, 90),
            'SKU' => $this->faker->unique()->word(),
            'stock_status' => $this->faker->randomElement(['instock', 'outstock']),
            'featured' => $this->faker->boolean(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'image' => $path,  // Ścieżka do pliku
            'category_id' => $category->id,  // Losowo przypisanie category_id
            'brand_id' => $brand->id,        // Losowo przypisanie brand_id
        ];
    }

    /**
     * Pobiera losowy obraz z Unsplash i zapisuje go na dysku
     *
     * @return string
     */
    public function generateRandomImage()
    {
        $accessKey = 'vGd6ixWS-pbgFBxxMvPSivZpkErSQJHcoNt3x88N3UU';  // Zastąp swoim kluczem API Unsplash
        $response = Http::get("https://api.unsplash.com/photos/random?client_id={$accessKey}&count=1");

        // Jeśli odpowiedź jest poprawna, pobieramy obraz
        if ($response->successful()) {
            $imageUrl = $response->json()[0]['urls']['regular'];  // Pobieramy URL obrazu
            $imageContent = Http::get($imageUrl)->body();  // Pobieramy zawartość obrazu

            return $imageContent;  // Zwraca zawartość obrazu
        }

        // W przypadku błędu, zwróć pustą odpowiedź (możesz dodać logowanie błędów)
        return null;
    }
}
