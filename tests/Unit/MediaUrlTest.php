<?php

namespace Tests\Unit;

use App\Support\MediaUrl;
use Tests\TestCase;

class MediaUrlTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.url' => 'https://globaluntoldstory.com/api/public',
            'filesystems.disks.public.url' => 'https://globaluntoldstory.com/api/public/storage',
        ]);
    }

    public function test_to_storage_path_strips_subdirectory_storage_prefix(): void
    {
        $url = 'https://globaluntoldstory.com/api/public/storage/media/2026/07/example.webp';

        $this->assertSame('media/2026/07/example.webp', MediaUrl::toStoragePath($url));
    }

    public function test_to_public_url_does_not_double_subdirectory_prefix(): void
    {
        $stored = 'api/public/storage/media/2026/07/example.webp';

        $this->assertSame(
            'https://globaluntoldstory.com/api/public/storage/media/2026/07/example.webp',
            MediaUrl::toPublicUrl($stored)
        );
    }

    public function test_to_public_url_repairs_already_doubled_paths(): void
    {
        $stored = 'api/public/storage/api/public/storage/media/2026/07/example.webp';

        $this->assertSame(
            'https://globaluntoldstory.com/api/public/storage/media/2026/07/example.webp',
            MediaUrl::toPublicUrl($stored)
        );
    }
}
