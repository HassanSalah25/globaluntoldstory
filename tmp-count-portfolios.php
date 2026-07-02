<?php

foreach (['commercial-advertising', 'documentary', 'industry', 'tv-show-live'] as $slug) {
    $path = __DIR__ . "/database/structured_content/content/portfolios/{$slug}/en.json";
    $data = json_decode(file_get_contents($path), true);
    echo $slug . ': ' . count($data['items']) . PHP_EOL;
}