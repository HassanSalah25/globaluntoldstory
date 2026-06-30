<?php

namespace Database\Seeders;

use App\Services\Media\FrontendMediaImporter;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $importer = app(FrontendMediaImporter::class);
        $urls = $importer->import();

        $this->command?->info('Imported '.count($urls).' frontend image(s) into the media library.');
    }
}
