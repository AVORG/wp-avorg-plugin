<?php

define('WP_USE_THEMES', false);

$wpBasePath = dirname(dirname(dirname(__DIR__)));

require( "$wpBasePath/wp-blog-header.php" );

http_response_code(200);

$endpointId = get_query_var("endpoint_id");

if (!$endpointId) throw new Exception("Can't access endpoint id");

/** @var \Avorg\EndpointFactory $endpointFactory */
$endpointFactory = $factory->secure("Avorg\\EndpointFactory");

/** @var \Avorg\Endpoint $endpoint */
$endpoint = $endpointFactory->getEndpointById($endpointId);

$output = $endpoint->getOutput();

echo $output;
