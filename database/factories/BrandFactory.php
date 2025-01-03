<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Licznik globalny dla generatora (można też przenieść do konstruktora klasy)
        static $counter = 1;

        // Generowanie nazwy "brand1", "brand2", itd.
        $brand_name = "brand" . $counter++;

        // Generowanie nazwy pliku dla obrazu
        $file_name = Carbon::now()->timestamp . '_' . Str::slug($brand_name) . '.jpg';

        // Ścieżka do zapisu obrazu
        $image_path = 'uploads/brand/' . $file_name;

        // Tworzenie fikcyjnego obrazu (opcjonalnie)
        $fakeImageContent = $this->generateFakeImage();
        Storage::disk('public')->put($image_path, $fakeImageContent);

        return [
            'name' => $brand_name, // Zmieniona nazwa na "brandX"
            'slug' => Str::slug($brand_name),
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
        imagestring($image, 5, 200, 220, 'Brand Image', $textColor); // Dodanie tekstu

        ob_start(); // Rozpoczęcie bufora wyjścia
        imagejpeg($image); // Zapis obrazu do bufora
        $imageData = ob_get_clean(); // Pobranie danych z bufora
        imagedestroy($image); // Zniszczenie obrazu

        return $imageData; // Zwrócenie danych obrazu jako ciągu znaków
    }
}
