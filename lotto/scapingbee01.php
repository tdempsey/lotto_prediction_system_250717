<?php

// Get cURL resource
$ch = curl_init();

// Set base url & API key
$BASE_URL = "https://app.scrapingbee.com/api/v1/?";
$API_KEY = "6H0GO7FZWBYSRTMGKIJKTELCHGPEXILAKWP5O40STAOSUW50209WGICH34VNQE5HCZ0FS9SYLWD91QFG";

// Set parameters
$parameters = array(
    'api_key' => $API_KEY,
    'url' => 'YOUR-URL' // The URL to scrape
);
// Building the URL query
$query = http_build_query($parameters);

// Set the URL for cURL
curl_setopt($ch, CURLOPT_URL, $BASE_URL.$query);

// Set method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

// Return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Send the request and save response to $response
$response = curl_exec($ch);

// Stop if fails
if (!$response) {
    die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
}

// Do what you want with the response here

// Close curl resource to free up system resources
curl_close($ch);
?>