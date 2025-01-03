<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Licznik globalny dla generatora kategorii
        static $counter = 1;

        // Generowanie nazwy "category1", "category2", itd.
        $category_name = "category" . $counter++;

        // Generowanie nazwy pliku dla obrazu
        $file_name = Carbon::now()->timestamp . '_' . Str::slug($category_name) . '.jpg';

        // Ścieżka do zapisu obrazu
        $image_path = 'uploads/category/' . $file_name;

        // Tworzenie fikcyjnego obrazu
        $fakeImageContent = $this->generateFakeImage();
        Storage::disk('public')->put($image_path, $fakeImageContent);

        return [
            'name' => $category_name, // Zmieniona nazwa na "categoryX"
            'slug' => Str::slug($category_name),
            'image' => $file_name, // Tylko nazwa pliku bez ścieżki
        ];
    }

    /**
     * Generowanie fikcyjnego obrazu
     */
    private function generateFakeImage(): string
    {
        $image = imagecreate(640, 480); // Tworzenie obrazu o wymiarach 640x480
        $backgroundColor = imagecolorallocate($image, 255, 255, 255); // Białe tło
        $textColor = imagecolorallocate($image, 0, 0, 0); // Czarny tekst
        imagestring($image, 5, 200, 220, 'Category Image', $textColor); // Dodanie tekstu

        ob_start(); // Rozpoczęcie bufora wyjścia
        imagejpeg($image); // Zapis obrazu do bufora
        $imageData = ob_get_clean(); // Pobranie danych z bufora
        imagedestroy($image); // Zniszczenie obrazu

        return $imageData; // Zwrócenie danych obrazu jako ciągu znaków
    }
}
