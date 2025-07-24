<?php
// SPARQL endpoint
$endpointUrl = 'https://query.wikidata.org/sparql';

// SPARQL query
$sparqlQuery = "SELECT ?item ?itemLabel WHERE {
  ?item wdt:P31 wd:Q5; # instances of human
        wdt:P136 wd:Q638; # genre comedy
  SERVICE wikibase:label { bd:serviceParam wikibase:language 'en'. }
}
LIMIT 10";

// Prepare the HTTP request header
$options = [
    "http" => [
        "header" => "Accept: application/sparql-results+json\r\n",
        "method" => "GET"
    ]
];

// Context for the HTTP request
$context = stream_context_create($options);

// Perform the HTTP request
$result = file_get_contents($endpointUrl . '?query=' . urlencode($sparqlQuery), false, $context);

// Decode the JSON response
$data = json_decode($result, true);

// Print the results
echo "<pre>";
print_r($data);
echo "</pre>";
?>
