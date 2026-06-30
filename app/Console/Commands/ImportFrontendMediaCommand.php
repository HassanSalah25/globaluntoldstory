<?php

namespace App\Console\Commands;

use App\Services\Media\FrontendMediaImporter;
use Illuminate\Console\Command;

class ImportFrontendMediaCommand extends Command
{
    protected $signature = 'media:import-frontend
                            {--force : Re-download images even if they already exist}
                            {--sync-content : Update CMS records that still point at external URLs}';

    protected $description = 'Download frontend seed images into the media library';

    public function handle(FrontendMediaImporter $importer): int
    {
        $this->info('Importing frontend images into the media library...');

        $urls = $importer->import($this->option('force'));

        foreach ($urls as $key => $url) {
            $this->line("  <fg=green>✓</> {$key}: {$url}");
        }

        if ($this->option('sync-content')) {
            $updated = $importer->syncContentReferences();
            $this->info("Updated {$updated} content record(s) to use media library URLs.");
        }

        $this->newLine();
        $this->info('Done. Run with --sync-content to update existing CMS image URLs.');

        return self::SUCCESS;
    }
}
