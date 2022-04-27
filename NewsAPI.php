<?php
/**
 * FreeNewsAPI SDK - A simple PHP wrapper for the Free News API
 * 
 * @author FreeNewsAPI
 * @version 1.0.0
 */

namespace FreeNewsAPI;

class NewsAPI
{
    /**
     * @var string The API key for authentication
     */
    private $apiKey;
    
    /**
     * @var string The base URL for the API
     */
    private $baseUrl;
    
    /**
     * @var string The search endpoint URL
     */
    private $searchEndpoint;

    /**
     * Create a new instance of the NewsAPI client
     * 
     * @param string $apiKey Your Free News API key
     * @param array $config Optional configuration options
     */
    public function __construct(string $apiKey, array $config = [])
    {
        if (empty($apiKey)) {
            throw new NewsAPIException("API key is required", 400);
        }

        $this->apiKey = $apiKey;
        $this->baseUrl = $config['baseUrl'] ?? "https://api.freenewsapi.com";
        $this->searchEndpoint = "{$this->baseUrl}/v1/search";
    }

    /**
     * Build the URL with query parameters for the API request
     * 
     * @param array $params Query parameters for the search
     * @return string The complete URL for the API request
     */
    private function buildUrl(array $params = []): string
    {
        // Add API key to parameters
        $params['apikey'] = $this->apiKey;
        
        // Handle array values by joining them with commas
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $params[$key] = implode(',', $value);
            }
        }
        
        // Build query string
        $queryString = http_build_query($params);
        
        return "{$this->searchEndpoint}?{$queryString}";
    }

    /**
     * Make a request to the API
     * 
     * @param array $params Query parameters for the request
     * @return mixed The API response
     * @throws NewsAPIException If the API request fails
     */
    private function makeRequest(array $params = [])
    {
        $url = $this->buildUrl($params);
        
        // Initialize cURL session
        $curl = curl_init();
        
        // Set cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_TIMEOUT => 30
        ]);
        
        // Execute the request
        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        
        // Close cURL session
        curl_close($curl);
        
        // Handle errors
        if ($error) {
            throw new NewsAPIException("cURL Error: {$error}", 500);
        }
        
        if ($statusCode >= 400) {
            $errorMessage = $this->getErrorMessage($statusCode);
            
            // Try to get more detailed error message from response
            if (!empty($response)) {
                $decodedResponse = json_decode($response, true);
                if (isset($decodedResponse['detail']) && isset($decodedResponse['detail']['message'])) {
                    $errorMessage = $decodedResponse['detail']['message'];
                }
                if (isset($decodedResponse['message'])) {
                    $errorMessage = $decodedResponse['message'];
                }
            
            }
            
            throw new NewsAPIException($errorMessage, $statusCode);
        }
        
        // Handle different formats
        $format = $params['format'] ?? 'json';
        
        if ($format === 'json') {
            $decodedResponse = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decodedResponse;
            }
            throw new NewsAPIException("Invalid JSON response", 500);
        }
        
        // For other formats (csv, xlsx), return raw response
        return $response;
    }

    /**
     * Get an error message based on status code
     * 
     * @param int $statusCode HTTP status code
     * @return string Error message
     */
    private function getErrorMessage(int $statusCode): string
    {
        $errorMessages = [
            400 => "Bad Request - Your request is invalid",
            401 => "Unauthorized - Invalid API Key or Account status is inactive",
            403 => "Forbidden - Your account is not authorized to make that request",
            429 => "Too Many Requests - You have reached your daily request limit. The next reset is at 00:00 UTC",
            500 => "Internal Server Error - We had a problem with our server. Please try again later",
            503 => "Service Unavailable - We're temporarily offline for maintenance. Please try again later"
        ];
        
        return $errorMessages[$statusCode] ?? "Unknown error occurred with status code: {$statusCode}";
    }

    /**
     * Format date objects to string if needed
     * 
     * @param mixed $date Date value
     * @return string|null Formatted date string or null
     */
    private function formatDate($date)
    {
        if ($date instanceof \DateTime) {
            return $date->format('Y-m-d\TH:i:s\Z');
        }
        
        return $date;
    }

    /**
     * Search for news articles
     * 
     * @param array $options Search options
     * @return mixed Search results
     */
    public function search(array $options = [])
    {
        // Format dates if provided
        if (isset($options['startDate'])) {
            $options['startDate'] = $this->formatDate($options['startDate']);
        }
        
        if (isset($options['endDate'])) {
            $options['endDate'] = $this->formatDate($options['endDate']);
        }
        
        return $this->makeRequest($options);
    }
}

/**
 * Custom exception class for NewsAPI errors
 */
class NewsAPIException extends \Exception
{
    /**
     * Create a new NewsAPIException
     * 
     * @param string $message Error message
     * @param int $code HTTP status code
     */
    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}

