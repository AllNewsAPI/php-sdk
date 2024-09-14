# AllNewsAPI PHP SDK

[![Packagist Version](https://img.shields.io/packagist/v/allnewsapi/php-sdk.svg?style=flat-square)](https://packagist.org/packages/allnewsapi/php-sdk)
[![License](https://img.shields.io/packagist/l/allnewsapi/php-sdk.svg?style=flat-square)](LICENSE)
[![Build](https://img.shields.io/badge/build-passing-brightgreen?style=flat-square)](https://github.com/AllNewsAPI/php-sdk)

The official **AllNewsAPI SDK** for the PHP programming language. 

Fetch real-time and historical news articles and headlines from multiple sources around the world.

---

## Installation

Install the package via Composer:

```bash
composer require allnewsapi/php-sdk
```

---

## Usage

> AllNewsAPI uses API keys for authentication. To get started, <a href="https://allnewsapi.com/signup" target="_blank">sign up for free</a> to get an API key

Next, import and initialize the SDK with your API key. 

```php
// Loads Composer's autoloader for all libraries
require_once 'vendor/autoload.php';

use AllNewsAPI\NewsAPI;

// Initialize the SDK with your API key
$newsApi = new NewsAPI('your-api-key');

// Simple search
try {
    $results = $newsApi->search(['q' => 'bitcoin']);
    print_r($results);
} catch (NewsAPIException $e) {
    echo "Error: " . $e->getMessage();
}

// Advanced search with multiple parameters
try {
    $results = $newsApi->search([
        'q' => 'AI startups',
        'lang' => ['en', 'fr'],
        'category' => 'technology',
        'max' => 10,
        'sortby' => 'relevance'
    ]);
    print_r($results);
} catch (NewsAPIException $e) {
    echo "Error: " . $e->getMessage();
}

// Get headlines
try {
    $headlines = $newsApi->headlines(['category' => 'technology']);
    print_r($headlines);
} catch (NewsAPIException $e) {
    echo "Error: " . $e->getMessage();
}

// Get headlines with multiple parameters
try {
    $headlines = $newsApi->headlines([
        'country' => 'us',
        'category' => 'business',
        'max' => 5
    ]);
    print_r($headlines);
} catch (NewsAPIException $e) {
    echo "Error: " . $e->getMessage();
}
```

---

## Available Methods

### `search(array $options)`

Search for news articles with various options. 🔗 [See API Documentation](https://allnewsapi.com/docs#search-endpoint)  

#### Parameters:

| Parameter   | Type              | Description |
|-------------|-------------------|-------------|
| `q`         | string             | Keywords to search for |
| `startDate` | string  | Start date (`YYYY-MM-DD` or `YYYY-MM-DD HH:MM:SS`) |
| `endDate`   | string  | End date (`YYYY-MM-DD` or `YYYY-MM-DD HH:MM:SS`) |
| `content`   | boolean            | Whether to include full article content |
| `lang`      | string / array     | Language(s) to filter by |
| `country`   | string / array     | Country/countries to filter by (ISO codes) |
| `region`    | string / array     | Region(s) to filter by |
| `category`  | string / array     | Category/categories to filter by |
| `max`       | int                | Maximum number of results |
| `attributes`| string / array     | Attributes to search in (`title`, `description`, `content`) |
| `page`      | int                | Page number for pagination |
| `sortby`    | string             | Sort by `'publishedAt'` or `'relevance'` |
| `publisher` | string / array     | Filter by publisher(s) |
| `format`    | string             | Response format (`json`, `csv`, `xlsx`) |

#### Returns:

- `array`: The search results.

#### Exceptions:

- Throws `NewsAPIException` on errors.

### `headlines(array $options)`

Get news headlines with various options. 🔗 [See API Documentation](https://allnewsapi.com/docs#headlines-endpoint)

#### Parameters:

| Parameter   | Type              | Description |
|-------------|-------------------|-------------|
| `q`         | string             | Keywords to search for |
| `startDate` | string  | Start date (`YYYY-MM-DD` or `YYYY-MM-DD HH:MM:SS`) |
| `endDate`   | string  | End date (`YYYY-MM-DD` or `YYYY-MM-DD HH:MM:SS`) |
| `content`   | boolean            | Whether to include full article content |
| `lang`      | string / array     | Language(s) to filter by |
| `country`   | string / array     | Country/countries to filter by (ISO codes) |
| `region`    | string / array     | Region(s) to filter by |
| `category`  | string / array     | Category/categories to filter by |
| `max`       | int                | Maximum number of results |
| `attributes`| string / array     | Attributes to search in (`title`, `description`, `content`) |
| `page`      | int                | Page number for pagination |
| `sortby`    | string             | Sort by `'publishedAt'` or `'relevance'` |
| `publisher` | string / array     | Filter by publisher(s) |
| `format`    | string             | Response format (`json`, `csv`, `xlsx`) |

#### Returns:

- `array`: The headlines results.

#### Exceptions:

- Throws `NewsAPIException` on errors.

---

## License

The MIT License (MIT). Please see the [License File](LICENSE) for more information.
