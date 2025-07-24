<?php
// SPARQL endpoint
$endpointUrl = 'https://query.wikidata.org/sparql';

// SPARQL query
$sparqlQuery = "SELECT ?item ?itemLabel WHERE {
  ?item wdt:P31 wd:Q5; # instances of human
        wdt:P136 wd:Q638; # genre comedy
  SERVICE wikibase:label { bd:serviceParam wikibase:language 'en'. }
}
LIMIT 100";

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $endpointUrl . '?query=' . urlencode($sparqlQuery));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/sparql-results+json',
    'User-Agent: MyWikidataQueryBot/1.0 (https://example.com/YourContactEmail)'
));

// Execute the cURL session
$result = curl_exec($ch);

// Check for cURL errors
if ($result === false) {
    echo 'cURL Error: ' . curl_error($ch);
}

// Close cURL session
curl_close($ch);

// Decode the JSON response
$data = json_decode($result, true);

// Check if data was received
if ($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
} else {
    echo "No data received, or data could not be parsed.";
}
?>
