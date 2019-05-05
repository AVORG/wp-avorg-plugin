<?php

define('WP_USE_THEMES', false);

$wpBasePath = dirname(dirname(dirname(__DIR__)));

require( "$wpBasePath/wp-blog-header.php" );

$endpointId = get_query_var("endpoint_id");

if (!$endpointId) throw new Exception("Can't access endpoint id");

/** @var \Avorg\EndpointFactory $endpointFactory */
$endpointFactory = $factory->secure("EndpointFactory");

/** @var \Avorg\Endpoint $endpoint */
$endpoint = $endpointFactory->getEndpoint($endpointId);

$output = $endpoint->getOutput();

echo $output;
