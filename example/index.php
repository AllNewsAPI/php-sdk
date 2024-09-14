<?php

require_once 'vendor/autoload.php';

use AllNewsAPI\NewsAPI;

$newsApi = new NewsAPI('21438009-686f-4ebc-988f-146e70c4792b', [
    'baseUrl' => 'http://localhost:8080'
]);

// Simple search
try {
    $results = $newsApi->search([]);
    print_r($results);
} catch (NewsAPIException $e) {
    echo "Error: " . $e->getMessage();
}

echo "\n\n--- Headlines Example ---\n\n";

// Simple headlines
try {
    $headlines = $newsApi->headlines([]);
    print_r($headlines);
} catch (NewsAPIException $e) {
    echo "Error: " . $e->getMessage();
}