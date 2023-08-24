<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Services\UserFileService;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $max = 127;
        for ($idx=0; $idx<$max; $idx++) {
            $file_name = fake()->regexify('[A-Za-z]{24}');
            $is_text_file = random_int(0, 1);
            $uploaded_file = $is_text_file
                ? UploadedFile::fake()->createWithContent("{$file_name}.txt", fake()->realText(128))
                : UploadedFile::fake()->image("{$file_name}.jpg");

            UserFileService::uploadFile($uploaded_file);
        }
    }
}
